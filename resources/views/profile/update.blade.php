@extends('layouts.admin')
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    {{-- <h1 class="mb-2 text-gray-800 h3">Student List</h1> --}}

    <!-- DataTales Example -->
    <div class="mb-4 shadow card">
        <div class="py-3 card-header">
            <h6 class="m-0 font-weight-bold text-primary">Update Profile</h6>
        </div>
       <form action="{{ route('profileUpdate') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="form-group row">
                <div class="mb-3 col-sm-6 mb-sm-0">
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <input type="text" class="form-control form-control-user" id="exampleFirstName" name="first_name" value="{{ $user->first_name }}" required>
                </div>
                <div class="col-sm-6">
                    <input type="text" class="form-control form-control-user" id="exampleFirstName" name="last_name" value="{{ $user->last_name }}" requireds>
                </div>
            </div>
            <div class="form-group">
                <input type="email" class="form-control form-control-user" id="exampleInputEmail" name="email" value="{{ $user->email }}">
            </div>
            {{-- <div class="form-group row">
                <div class="mb-3 col-sm-6 mb-sm-0">
                    <input type="password" class="form-control form-control-user" name="password" id="exampleInputPassword" placeholder="Password">
                </div>
                <div class="col-sm-6">
                    <input type="password" class="form-control form-control-user" name="password_confirmation" id="exampleRepeatPassword" placeholder="Repeat Password">
                </div>
            </div> --}}
            <div class="form-group">
                <textarea type="email" class="form-control" name="address" placeholder="Enter Address">{!! (isset($user_details->address)) ? $user_details->address : '' !!}</textarea>
            </div>
            <div class="form-group">
                <input type="text" name="previous_school" id="previous_school" class="form-control" value="{{ (isset($user_details->previous_school)) ? $user_details->previous_school : '' }}" {{ (Auth::user()->role_id == CustomHelper::STUDENT) ? 'required' : '' }}>
            </div>
            <div class="form-group">
                <input type="text" name="current_school" id="current_school" class="form-control" value="{{ (isset($user_details->current_school)) ? $user_details->current_school : '' }}" {{ (Auth::user()->role_id == CustomHelper::STUDENT) ? 'required' : '' }}>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="file"  name="profile_picture">
                    </div>
                </div>
                <div class="col-md-8">
                    @if (isset($user->profile_picture))
                        <img src="{{ asset('uploads/'.$user->profile_picture) }}" class="w-25">
                    @endif
                </div>
            </div>
            @if (Auth::user()->role_id == CustomHelper::STUDENT)
            <hr>
                <h4>Parent Details</h4>
                <hr>
                <div class="form-group">
                    <input type="text" name="father_name" id="father_name" class="form-control" placeholder="Father's Name" required>
                </div>
                <div class="form-group">
                    <input type="text" name="mother_name" id="mother_name" class="form-control" placeholder="Mother's Name" required>
                </div>
            @endif
            @if (Auth::user()->role_id == CustomHelper::TEACHER)
                <div class="form-group">
                    <label>Total Exp</label>
                    {{ Form::select('exp',['' => 'Please Select'] + $teacherExp, isset($user_details->exp) ? $user_details->exp : null,['id' => 'exp','class'=> 'form-control','required' => (Auth::user()->role_id == CustomHelper::TEACHER) ? 'required' : '']) }}
                </div>

            @endif

            <button type="submit" class="btn btn-primary btn-user btn-block">
                Register Account
            </button>
        </div>
       </form>
    </div>
</div>
@endsection
