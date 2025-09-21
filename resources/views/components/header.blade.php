<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="{{ route('home') }}" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.webp" alt=""> -->
        <i class="bi bi-buildings"></i>
        <h1 class="sitename">University Of Mandalay</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{ route('home') }}" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
        <li><a href="{{ route('user.departments') }}" class="{{ request()->is('departments') ? 'active' : '' }}">Departments</a></li>
        <li><a href="{{ route('userteachers') }}" class="{{ request()->is('teachers') ? 'active' : '' }}">Teachers</a></li>

        <li><a href="{{ route('userstudents') }}" class="{{ request()->is('students') ? 'active' : '' }}">Students</a></li>
          
          @auth
               <li class="dropdown"><a href="#"><span>{{Auth::user()->username}}</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="{{ route('users') }}">Dashboard</a></li>
            <li><a href="{{ route('logout') }}">Logout</a></li>
            </ul>
          </li>
             @else
          <li><a href="{{ route('login') }}">Login</a></li>
          @endauth
          
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <div class="mt-5 alertbox">
          @if (session('success'))
          <x-alert type='success'>{{session('success')}}</x-alert>
        @endif

        @if (session('warning'))
            <x-alert type='warning'>{{session('warning')}}</x-alert>
        @endif

        @if (session('danger'))
            <x-alert type='danger'>{{session('danger')}}</x-alert>
        @endif

          <x-showerror name="error"></x-showerror>

      </div>
