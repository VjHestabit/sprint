<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\UserDetail;
use App\Notifications\AssignTeacherNotification;
use App\Notifications\MailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aRows = User::with('userDetail.techerDetail')
                ->whereNotIn('role_id',[CustomHelper::ADMIN,CustomHelper::TEACHER])
                ->get();
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
        $user = User::with(['userDetail','teacherData'])->where('id',Auth::user()->id)->first();
        $teacherExp = CustomHelper::$getTeacherExp;
        $getSubjectList = CustomHelper::$getSubjectList;
        return view('profile.update',compact('teacherExp','getSubjectList','user'));
    }

    public function profileUpdate(UserRequest $request)
    {
        $validate = $request->validated();
        $user_data = $request->only(['first_name','last_name','email','id']);
        $user = User::find($request->id)->update($user_data);
        if($request->hasFile('profile_picture')){
            $user->profile_picture = CustomHelper::uploadImage($request->file('profile_picture'));
            $user->update();
        }
        $user_detail_data = $request->except(['first_name','last_name','email','id','_token']);
        $userDetailUpdate = $user->userDetail()->update($user_detail_data);

        if($request->has('subject_name') && count($request->input('subject_name')) > 0){
            foreach($request->input('subject_name') as $key =>  $sub){
                $user->teacherData()->update($sub);
            }
        }
        return redirect()->back()->with('success','Profile Updated Successfully');
    }

    public function approveStudent($id,$status)
    {
        $user = User::find($id)->update(['status'=> $status]);
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
