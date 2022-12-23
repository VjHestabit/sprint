<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Helpers\CustomHelper;
use App\Models\TeacherSubject;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    public function userRegister(Request $request)
    {
        $aVals = $request->all();
        // echo "<pre>";
        // print_r($aVals);
        // die;
        Validator::make($aVals, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ])->validate();

        $userData['role_id'] = isset($aVals['role_id']) ? $aVals['role_id'] : '';

        if($userData['role_id'] == CustomHelper::TEACHER){
            Validator::make($aVals, [
                'exp' => ['required','integer'],
                'subject_name' => ['required','array']
            ])->validate();
        }

        $userData['first_name'] = isset($aVals['first_name']) ? $aVals['first_name'] : '';
        $userData['last_name'] = isset($aVals['last_name']) ? $aVals['last_name'] : '';
        $userData['email'] = isset($aVals['email']) ? $aVals['email'] : '';
        $userData['password'] = isset($aVals['password']) ? Hash::make($aVals['password']) : '';
        if($request->hasFile('profile_picture')){
            $file= $request->file('profile_picture');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $userData['profile_picture'] = $filename;
        }
        $userData['status'] = CustomHelper::NOTAPPROVE;
        $user = User::create($userData);

        $uDetails['user_id'] = $user->id;
        $uDetails['address'] = isset($aVals['address']) ? $aVals['address'] : '';
        $uDetails['current_school'] = isset($aVals['current_school']) ? $aVals['current_school'] : '';
        $uDetails['previous_school'] = isset($aVals['previous_school']) ? $aVals['previous_school'] : '';
        $uDetails['exp'] = isset($aVals['exp']) ? $aVals['exp'] : '';
        $uDetails['father_name'] = isset($aVals['father_name']) ? $aVals['father_name'] : '';
        $uDetails['mother_name'] = isset($aVals['mother_name']) ? $aVals['mother_name'] : '';
        $uDetails['assigned_status'] = CustomHelper::UNASSIGNED;

        $userDetail = UserDetail::create($uDetails);

        if(isset($aVals['subject_name']) && count($aVals['subject_name']) > 0){
            $total_subs = count($aVals['subject_name']);
            foreach($aVals['subject_name'] as $key =>  $sub){
                $tSubject['user_id'] = $user->id;
                $tSubject['subject_name'] = $sub;
                TeacherSubject::create($tSubject);
            }
        }

        return redirect()->route('login');
    }
}
