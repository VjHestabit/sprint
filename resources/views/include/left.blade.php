<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="mx-3 sidebar-brand-text">Sprint <sup></sup></div>
    </a>

    <!-- Divider -->
    <hr class="my-0 sidebar-divider">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    @if (Auth::user()->role_id == CustomHelper::ADMIN || Auth::user()->role_id == CustomHelper::TEACHER)
    <!-- Heading -->
    <div class="sidebar-heading">
        User Management
    </div>
          <!-- Nav Item - Charts -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('students.index') }}">
                <i class="fas fa-users"></i>
                <span>Student</span></a>
        </li>
    @endif
    @if (Auth::user()->role_id == CustomHelper::ADMIN)
        <!-- Nav Item - Tables -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('teachers.index') }}">
                <i class="fas fa-users"></i>
                <span>Teacher</span></a>
        </li>
    @endif
</ul>
