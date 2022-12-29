@extends('layouts.admin')
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
                                        @if ($aRow->status == CustomHelper::NOTAPPROVE)
                                            <a href="{{ URL('approve/teacher') }}/{{ $aRow->id }}/1" class="btn {{ CustomHelper::$getUserBtnStatus[$aRow->status] }}" onclick="return confirm('Are you sure you want to change the status');">
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
        <div class="modal-dialog" role="document">

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
@endsection
