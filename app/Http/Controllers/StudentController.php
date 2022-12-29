<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Models\TeacherSubject;
use App\Models\User;
use App\Models\UserDetail;
use App\Notifications\AssignTeacherNotification;
use App\Notifications\MailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aRows = User::join('user_details','user_details.user_id','users.id')
                ->where('users.role_id','!=',CustomHelper::ADMIN)
                ->where('users.id','!=',Auth::user()->id)
                ->where('users.role_id','!=',CustomHelper::TEACHER);
        if(Auth::user()->role_id == CustomHelper::TEACHER){
            $aRows = $aRows->where('users.role_id',CustomHelper::STUDENT)
                            ->where('user_details.assigned_to',Auth::user()->id);
        }

        $aRows = $aRows->select('users.*','user_details.assigned_status','assigned_to','user_details.address')->get();
        // dd($aRows);
        return view('student.index',compact('aRows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function profileShow(Request $request)
    {
        $user = User::where('id',Auth::user()->id)->first();
        $user_details = UserDetail::where('user_id',Auth::user()->id)->first();
        // echo "<pre>";
        // print_r($user_details);
        // die;
        $subject = TeacherSubject::where('user_id',Auth::user()->id)->select('subject_name')->get();
        $teacherExp = CustomHelper::$getTeacherExp;
        $getSubjectList = CustomHelper::$getSubjectList;
        return view('profile.update',compact('teacherExp','getSubjectList','user','user_details','subject'));
    }

    public function profileUpdate(Request $request)
    {
        $data = $request->all();
        // echo "<pre>";
        // print_r($data);
        // die;
        Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ])->validate();


        $userData['first_name'] = isset($data['first_name']) ? $data['first_name'] : '';
        $userData['last_name'] = isset($data['last_name']) ? $data['last_name'] : '';
        $userData['email'] = isset($data['email']) ? $data['email'] : '';
        if($request->hasFile('profile_picture')){
            $file= $request->file('profile_picture');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $userData['profile_picture'] = $filename;
        }

        $user = User::where('id',$data['id'])->update($userData);

        $uDetails['address'] = isset($data['address']) ? $data['address'] : '';
        $uDetails['current_school'] = isset($data['current_school']) ? $data['current_school'] : '';
        $uDetails['previous_school'] = isset($data['previous_school']) ? $data['previous_school'] : '';
        $uDetails['exp'] = isset($data['exp']) ? $data['exp'] : '';
        $uDetails['father_name'] = isset($data['father_name']) ? $data['father_name'] : '';
        $uDetails['mother_name'] = isset($data['mother_name']) ? $data['mother_name'] : '';

        $userDetail = UserDetail::where('user_id',$data['id'])->update($uDetails);

        if(isset($data['subject_name']) && count($data['subject_name']) > 0){
            $total_subs = count($data['subject_name']);
            foreach($data['subject_name'] as $key =>  $sub){
                $tSubject['subject_name'] = $sub;
                TeacherSubject::where('user_id',$data['id'])->update($tSubject);
            }
        }

        return redirect()->back()->with('success','Profile Updated Successfully');
    }

    public function approveStudent($id,$status)
    {
        $sUpdate = User::where('id',$id)->update(['status' => $status]);
        $user = User::where('id',$id)->first();

        $details = [
            'greeting' => 'Hi'.$user->first_name. ' ' .$user->last_name,
            'body' => 'Your Profile has been approved',
            'thanks' => 'Thank you for using Sprint from Hestabit !',
            'actionText' => 'View My Site',
            'actionURL' => url('/'),
        ];

        Notification::route('mail',$user->email)->notify(new MailNotification($details));

        return redirect()->back()->with(['success','Student Approved Successfully']);
    }

    public function assignedTeacher(Request $request)
    {
        $data = $request->all();
        $student_id = $data['id'];
        $teacherList = User::where('role_id',CustomHelper::TEACHER)->where('status',CustomHelper::APPROVE)->pluck('first_name','id')->toArray();
        return (string) view('student.assign',compact('teacherList','student_id'))->render();
    }

    public function teacherAssign(Request $request)
    {
        $aData = $request->all();

        $uDetails['assigned_status'] = CustomHelper::ASSIGNED;
        $uDetails['assigned_to'] = $aData['assigned_to'];
        UserDetail::where('user_id',$aData['student_id'])->update($uDetails);
        $user = User::where('id',$aData['assigned_to'])->first();
        $student = User::where('id',$aData['student_id'])->first();
        $details = [
            'teacher' => $student->first_name . ' ' . $student->last_name . ' has been assigned to You'
        ];

        Notification::send($user,new AssignTeacherNotification($details));

        return redirect()->back()->with('success','Teacher Assigned Successfully');
    }
}
