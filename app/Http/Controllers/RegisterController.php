<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Helpers\CustomHelper;
use App\Http\Requests\UserRequest;
use App\Models\User;


class RegisterController extends Controller
{
    use PasswordValidationRules;

    public function studentView(){
        $id = CustomHelper::STUDENT;
        $teacherExp = array();
        $getSubjectList = array();
        return view('auth.register',compact('id','teacherExp','getSubjectList'));
    }

    public function teacherView(){
        $id = CustomHelper::TEACHER;
        $teacherExp = CustomHelper::$getTeacherExp;
        $getSubjectList = CustomHelper::$getSubjectList;
        return view('auth.register',compact('id','teacherExp','getSubjectList'));
    }

    public function userRegister(UserRequest $request)
    {
        $validate = $request->validated();
        $user_data = $request->only(['role_id','first_name','last_name','email','password']);
        $user = User::create($user_data);
        if($request->hasFile('profile_picture')){
            $user->profile_picture = CustomHelper::uploadImage($request->file('profile_picture'));
            $user->save();
        }
        $user_detail_data = $request->except(['role_id','first_name','last_name','email','password','_token']);
        $user->userDetail()->create();

        if($request->has('subject_name') && count($request->input('subject_name')) > 0){
            foreach($request->input('subject_name') as $key =>  $sub){
                $user->teacherData()->create($sub);
            }
        }

        return redirect()->route('login');
    }
}
