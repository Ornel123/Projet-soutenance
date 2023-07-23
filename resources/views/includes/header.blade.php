<div class="d-flex align-items-center justify-content-between">
    <a href="{{route('home')}}" class="logo d-flex align-items-center">
        <img src="{{ asset('template/img/logo.png') }}" alt="">
        <span class="d-none d-lg-block">NiceAdmin</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
</div><!-- End Logo -->

@if (auth()->check())
<nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
            <a class="nav-link nav-icon search-bar-toggle " href="#">
                <i class="bi bi-search"></i>
            </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown pe-3">

            <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                <img src="{{ asset('template/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle">
                <span class="d-none d-md-block dropdown-toggle ps-2">{{Auth()->user()->name}}</span>
            </a><!-- End Profile Iamge Icon -->

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                <li class="dropdown-header">
                    <h6>{{Auth()->user()->email}}</h6>
                    <span>{{Auth()->user()->role}}</span>
                </li>
                @if(auth()->user()->role == "Admin")
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="{{route('admin.admincreate')}}">
                        <i class="bi bi-person"></i>
                        <span>Ajouter Admin</span>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>
                    <a class="dropdown-item d-flex align-items-center" href="{{route('admin.profcreate')}}">
                        <i class="bi bi-gear"></i>
                        <span>Ajouter Enseignants</span>
                    </a>
                </li>
                @endif

                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>
                    <a class="dropdown-item d-flex align-items-center" href="#">
                        <i class="bi bi-box-arrow-right"></i>
                        <form action="{{route('logout')}}" method="POST">
                            @CSRF
                            <button type="submit" style="border:none;background-color:transparent"><span>Sign Out</span></button>
                        </form>
                    </a>
                </li>

            </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

    </ul>
</nav><!-- End Icons Navigation -->
@else

@endif
