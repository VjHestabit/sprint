<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sprint - Register</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="my-5 border-0 shadow-lg card o-hidden">
            <div class="p-0 card-body">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="mb-4 text-gray-900 h4">Create an Account!</h1>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form class="user" action="{{ route('registerUser') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="role_id" value="{{ $id }}">
                                <div class="form-group row">
                                    <div class="mb-3 col-sm-6 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName" name="first_name" placeholder="First Name"  autofocus>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName" name="last_name" placeholder="Last Name"  autofocus>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="exampleInputEmail" name="email" placeholder="Email Address">
                                </div>
                                <div class="form-group row">
                                    <div class="mb-3 col-sm-6 mb-sm-0">
                                        <input type="password" class="form-control form-control-user" name="password" id="exampleInputPassword" placeholder="Password">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user" name="password_confirmation" id="exampleRepeatPassword" placeholder="Repeat Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <textarea type="email" class="form-control" name="address" placeholder="Enter Address"></textarea>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="previous_school" id="previous_school" class="form-control" placeholder="Previous School" {{ ($id == CustomHelper::STUDENT) ? '' : '' }}>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="current_school" id="current_school" class="form-control" placeholder="Current School" {{ ($id == CustomHelper::STUDENT) ? '' : '' }}>
                                </div>
                                <div class="form-group">
                                    <input type="file"  name="profile_picture">
                                </div>
                                @if ($id == CustomHelper::STUDENT)
                                <hr>
                                    <h4>Parent Details</h4>
                                    <hr>
                                    <div class="form-group">
                                        <input type="text" name="father_name" id="father_name" class="form-control" placeholder="Father's Name" >
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="mother_name" id="mother_name" class="form-control" placeholder="Mother's Name" >
                                    </div>
                                @endif
                                @if ($id == CustomHelper::TEACHER)
                                    <div class="form-group">
                                        <label>Total Exp</label>
                                        {{ Form::select('exp',['' => 'Please Select'] + $teacherExp,null,['id' => 'exp','class'=> 'form-control','' => ($id == CustomHelper::TEACHER) ? '' : '']) }}
                                    </div>
                                    <div class="form-group">
                                        <div class="ml-1 row">
                                            {{ Form::select('subject_name[]',['' => 'Please Select'] + $getSubjectList,null,['id' => 'exp','class'=> 'form-control','' => ($id == CustomHelper::TEACHER) ? '' : '','multiple' => 'multiple']) }}
                                        </div>
                                    </div>
                                @endif

                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>
</body>

</html>
