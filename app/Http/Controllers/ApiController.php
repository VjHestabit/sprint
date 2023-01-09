<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Requests\AuthenticateRequest;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    public function register(UserRequest $request)
    {
        $validate = $request->validated();
        $user_data = $request->only(['role_id','first_name','last_name','email','password']);
        $user = User::create($user_data);
        if($request->hasFile('profile_picture')){
            $user->profile_picture = CustomHelper::uploadImage($request->file('profile_picture'));
            $user->save();
        }
        $user_detail_data = $request->except(['role_id','first_name','last_name','email','password']);
        $user->userDetail()->create($user_detail_data);

        if($request->has('subject_name') && count($request->input('subject_name')) > 0){
            foreach($request->input('subject_name') as $key =>  $sub){
                $user->teacherData()->create($sub);
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
        ], Response::HTTP_OK);
    }

    public function authenticate(AuthLoginRequest $request)
    {
        $validate = $request->validated();
        $credentials = $request->only(['email','password']);
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
    	return $credentials;
            return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    public function logout(AuthenticateRequest $request)
    {
        $validate = $request->validated();
        try {
            JWTAuth::invalidate($request->only('token'));
            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get_user(AuthenticateRequest $request)
    {
        $validate = $request->validated();

        $authenticate = JWTAuth::authenticate($request->token);
        $user = User::with(['userDetail.techerDetail'])->where('id',$authenticate->id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $user,
            'message' => "Data Fetched Successfully"
        ],200);
    }

    public function update_user(UserRequest $request){
        $validate = $request->validated();

        $user = JWTAuth::authenticate($request->token);
        $user_data = $request->only(['first_name','last_name','email']);
        $userUpdate = User::find($user->id)->update($user_data);
        if($request->hasFile('profile_picture')){
            $user->profile_picture = CustomHelper::uploadImage($request->file('profile_picture'));
            $user->update();
        }
        $user_detail_data = $request->except(['first_name','last_name','email','token','password','subject_name']);
        $userDetailUpdate = $user->userDetail()->update($user_detail_data);

        if($request->has('subject_name') && count($request->input('subject_name')) > 0){
            foreach($request->input('subject_name') as $key =>  $sub){
                $user->teacherData()->update(['subject_name' => $sub]);
            }
        }
         //User created, return success response
         return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    public function delete_user(AuthenticateRequest $request){

        $validate = $request->validated();
        $user = JWTAuth::authenticate($request->token);
        $user->userDetail()->delete();
        $user->teacherData()->delete();
        $user->delete();

        JWTAuth::invalidate($request->token);

        return response()->json([
            'success' => true,
            'message' => 'Record Deleted Successfully',
        ]);
    }
}
