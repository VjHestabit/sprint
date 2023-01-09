@extends('layouts.admin')
@php
    use App\Models\UserDetail;
@endphp
@section('css')
    <!-- Custom styles for this page -->
    <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
  <!-- Begin Page Content -->
  <div class="container-fluid">

    <!-- Page Heading -->
    {{-- <h1 class="mb-2 text-gray-800 h3">Student List</h1> --}}

    <!-- DataTales Example -->
    <div class="mb-4 shadow card">
        <div class="py-3 card-header">
            <h6 class="m-0 font-weight-bold text-primary">Student List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Teacher Assigned</th>
                            <th>Teacher Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Teacher Assigned</th>
                            <th>Teacher Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @if (count($aRows) > 0)
                            @foreach ($aRows as $aKey => $aRow)
                                <tr>
                                    <td>{{ ++$aKey }}</td>
                                    <td>{{ $aRow->first_name }} {{ $aRow->last_name }}</td>
                                    <td>{{ $aRow->email }}</td>
                                    <td>{{ ($aRow->address) ? $aRow->address : '-------' }}</td>
                                    <td>
                                        @if (Auth::user()->role_id == CustomHelper::ADMIN)
                                            <button class="btn {{ CustomHelper::$studentBtnStatus[$aRow->userDetail['assigned_status']] }}" @if ($aRow->status == CustomHelper::ASSIGNED) onclick="assignTeacher('{{ $aRow->id }}')" @endif>{{ CustomHelper::$studentStatus[$aRow->userDetail['assigned_status']] }}</button>
                                        @else
                                        <button class="btn {{ CustomHelper::$studentBtnStatus[$aRow->userDetail['assigned_status']] }}">{{ CustomHelper::$studentStatus[$aRow->userDetail['assigned_status']] }}</button>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($aRow->userDetail->assigned_status == CustomHelper::ASSIGNED)
                                            {{ $aRow->userDetail->techerDetail->first_name }} {{ $aRow->userDetail->techerDetail->last_name }}
                                        @else
                                        -----------------
                                        @endif
                                    </td>
                                    <td>
                                        @if ($aRow->status == CustomHelper::NOTAPPROVE && Auth::user()->role_id == CustomHelper::ADMIN)
                                            <a href="{{ URL('approve/student') }}/{{ $aRow->id }}/1" class="btn {{ CustomHelper::$getUserBtnStatus[$aRow->status] }}" onclick="return confirm('Are you sure you want to change the status');">
                                                {{ CustomHelper::$getUserStatus[$aRow->status] }}
                                            </a>
                                        @else
                                            <a href="#" class="btn {{ CustomHelper::$getUserBtnStatus[$aRow->status] }}">
                                                {{ CustomHelper::$getUserStatus[$aRow->status] }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" id="approveData">

        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection
@section('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('assets/js/demo/datatables-demo.js') }}"></script>
    <script>
        function assignTeacher(id){
            $.ajax({
                method : 'POST',
                url : '{{ route("assignedTeacher") }}',
                data : {
                    _token : '{{ csrf_token() }}',
                    id : id
                },
                success:function(response){
                    console.log(response);
                    $('#approveData').html(response);

                    $('#exampleModal').modal('show');
                }
            });
        }
    </script>
@endsection
