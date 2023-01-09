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
        $aRows = User::with('userDetail','teacherData')
                ->where('role_id',CustomHelper::TEACHER)
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
        $user = User::find($id);
        $user->status = $status;
        $user->update();
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
