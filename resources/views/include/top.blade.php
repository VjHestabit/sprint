<nav class="mb-4 bg-white shadow navbar navbar-expand navbar-light topbar static-top">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="mr-3 btn btn-link d-md-none rounded-circle">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->

    <!-- Topbar Navbar -->
    <ul class="ml-auto navbar-nav">

        @if (Auth::user()->role_id == CustomHelper::TEACHER)
              <!-- Nav Item - Alerts -->
              <li class="mx-1 nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                    <!-- Counter - Alerts -->
                    @php
                        $count = CustomHelper::notificationCount();
                        $data = CustomHelper::notificationData();
                    @endphp
                    <span class="badge badge-danger badge-counter">{{ ($count > 0) ? $count : ''}}</span>
                </a>
                <!-- Dropdown - Alerts -->
                <div class="shadow dropdown-list dropdown-menu dropdown-menu-right animated--grow-in"
                    aria-labelledby="alertsDropdown">
                    <h6 class="dropdown-header">
                        Alerts Center
                    </h6>
                    @foreach ($data as $key => $notify)
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="mr-3">
                                <div class="icon-circle bg-primary">
                                    <i class="text-white fas fa-file-alt"></i>
                                </div>
                            </div>
                            <div>
                                <div class="text-gray-500 small">{{ date('M  d, Y',strtotime($notify->created_at))  }}</div>
                                <span class="font-weight-bold">{{ json_decode($notify->data)->data }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </li>
        @endif

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 text-gray-600 d-none d-lg-inline small">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                <img class="img-profile rounded-circle"
                    src="{{ (isset(Auth::user()->profile_picture)) ? asset('uploads/'.Auth::user()->profile_picture) : asset('assets/img/undraw_profile.svg') }}">
            </a>
            <!-- Dropdown - User Information -->
            <div class="shadow dropdown-menu dropdown-menu-right animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('profileShow') }}">
                    <i class="mr-2 text-gray-400 fas fa-user fa-sm fa-fw"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="#">
                    <i class="mr-2 text-gray-400 fas fa-cogs fa-sm fa-fw"></i>
                    Settings
                </a>
                <a class="dropdown-item" href="#">
                    <i class="mr-2 text-gray-400 fas fa-list fa-sm fa-fw"></i>
                    Activity Log
                </a>
                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="{{ route('logout') }}" data-toggle="modal" data-target="#logoutModal" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="mr-2 text-gray-400 fas fa-sign-out-alt fa-sm fa-fw"></i>
                    Logout
                </a>
                <form action="{{ route('logout') }}" method="POST" style="display: none" id="logout-form">@csrf</form>

            </div>
        </li>

    </ul>

</nav>
