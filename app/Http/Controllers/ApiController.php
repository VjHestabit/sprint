<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Helpers\CustomHelper;
use App\Models\TeacherSubject;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Rules\Password;

class ApiController extends Controller
{
    public function register(Request $request)
    {
    	//Validate data
        $data = $request->all();
        // echo "<pre>";
        // print_r($data);
        // die;
        $validator = Validator::make($request->all(), [
            'first_name' => 'required', 'string', 'max:255',
            'last_name' => 'required', 'string', 'max:255',
            'email' => 'required', 'string', 'email', 'max:255', 'unique:users',
            'password' => 'required', 'string', new Password, 'confirmed',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator], 200);
        }

        //Request is valid, create new user
        $userData['role_id'] = isset($data['role_id']) ? $data['role_id'] : '';
        $userData['first_name'] = isset($data['first_name']) ? $data['first_name'] : '';
        $userData['last_name'] = isset($data['last_name']) ? $data['last_name'] : '';
        $userData['email'] = isset($data['email']) ? $data['email'] : '';
        $userData['password'] = isset($data['password']) ? Hash::make($data['password']) : '';
        if($request->hasFile('profile_picture')){
            $file= $request->file('profile_picture');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $userData['profile_picture'] = $filename;
        }
        $userData['status'] = CustomHelper::NOTAPPROVE;
        $user = User::create($userData);

        $uDetails['user_id'] = $user->id;
        $uDetails['address'] = isset($data['address']) ? $data['address'] : '';
        $uDetails['current_school'] = isset($data['current_school']) ? $data['current_school'] : '';
        $uDetails['previous_school'] = isset($data['previous_school']) ? $data['previous_school'] : '';
        $uDetails['exp'] = isset($data['exp']) ? $data['exp'] : null;
        $uDetails['father_name'] = isset($data['father_name']) ? $data['father_name'] : '';
        $uDetails['mother_name'] = isset($data['mother_name']) ? $data['mother_name'] : '';
        $uDetails['assigned_status'] = CustomHelper::UNASSIGNED;
        $userDetail = UserDetail::create($uDetails);
        if(isset($data['subject_name']) && count($data['subject_name']) > 0){
            $total_subs = count($data['subject_name']);
            foreach($data['subject_name'] as $key =>  $sub){
                $tSubject['user_id'] = $user->id;
                $tSubject['subject_name'] = $sub;
                TeacherSubject::create($tSubject);
            }
        }
        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator], 200);
        }

        //Request is validated
        //Create token
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

 		//Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator], 200);
        }

		//Request is validated, do logout
        try {
            JWTAuth::invalidate($request->token);

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

    public function get_user(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);
        $userDetails = UserDetail::where('user_id',$user->id)->first();
        $user['user_details'] = $userDetails;
        $user['user_details']['assigned_to'] = ($userDetails->assigned_to != "") ? User::where('id',$userDetails->assigned_to)->first() : '';
        // echo "<pre>";
        // print_r($user);
        // die;

        return response()->json([
            'status' => 'success',
            'data' => $user,
            'message' => "Data Fetched Successfully"
        ],200);
    }

    public function update_user(Request $request){
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'first_name' => 'required', 'string', 'max:255',
            'last_name' => 'required', 'string', 'max:255',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator], 200);
        }
        $user = JWTAuth::authenticate($request->token);
        // echo "<pre>";
        // print_r($data);
        // die;
        $validator2 = Validator::make($request->all(), [
            'email' => 'required', 'string', 'email', 'max:255','unique:users,email,'.$user->id.',id',
        ]);

        if ($validator2->fails()) {
            return response()->json(['error' => $validator], 200);
        }
        $userData['first_name'] = isset($data['first_name']) ? $data['first_name'] : '';
        $userData['last_name'] = isset($data['last_name']) ? $data['last_name'] : '';
        $userData['email'] = isset($data['email']) ? $data['email'] : '';
        $userData['password'] = isset($data['password']) ? Hash::make($data['password']) : '';
        if($request->hasFile('profile_picture')){
            $file= $request->file('profile_picture');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $userData['profile_picture'] = $filename;
        }
        $userData['status'] = CustomHelper::NOTAPPROVE;
        $userUpdate = User::where('id',$user->id)->update($userData);

        $uDetails['address'] = isset($data['address']) ? $data['address'] : '';
        $uDetails['current_school'] = isset($data['current_school']) ? $data['current_school'] : '';
        $uDetails['previous_school'] = isset($data['previous_school']) ? $data['previous_school'] : '';
        $uDetails['exp'] = isset($data['exp']) ? $data['exp'] : null;
        $uDetails['father_name'] = isset($data['father_name']) ? $data['father_name'] : '';
        $uDetails['mother_name'] = isset($data['mother_name']) ? $data['mother_name'] : '';

        $userDetail = UserDetail::where('user_id',$user->id)->update($uDetails);

        if(isset($data['subject_name']) && count($data['subject_name']) > 0){
            $total_subs = count($data['subject_name']);
            foreach($data['subject_name'] as $key =>  $sub){
                $tSubject['user_id'] = $user->id;
                $tSubject['subject_name'] = $sub;
                TeacherSubject::create($tSubject);
            }
        }
         //User created, return success response
         return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    public function delete_user(Request $request){
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator], 200);
        }
        $user = JWTAuth::authenticate($request->token);

        UserDetail::where('user_id',$user->id)->delete();
        TeacherSubject::where('user_id',)->delete();
        User::where('id',$user->id)->delete();
        JWTAuth::invalidate($request->token);

        return response()->json([
            'success' => true,
            'message' => 'Record Deleted Successfully',
        ]);
    }
}
