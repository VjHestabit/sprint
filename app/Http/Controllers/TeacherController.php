<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Models\User;
use App\Notifications\MailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aRows = User::join('user_details','user_details.user_id','users.id')
                ->where('users.id','!=',Auth::user()->id)
                ->where('users.role_id',CustomHelper::TEACHER)
                ->select('users.*','user_details.assigned_status','assigned_to','user_details.address')
                ->get();

        return view('teacher.index',compact('aRows'));
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

    public function approveTeacher($id,$status)
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

        return redirect()->back()->with(['success','Teacher Approved Successfully']);
    }
}
