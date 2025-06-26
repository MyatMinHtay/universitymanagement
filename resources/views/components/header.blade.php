 <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="/" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.webp" alt=""> -->
        <i class="bi bi-buildings"></i>
        <h1 class="sitename">University Of Mandalay</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="/" class="active">Home</a></li>
          <li><a href="/departments">Departments</a></li>
          <li><a href="/teachers">Teachers</a></li>
          <li><a href="/students">Students</a></li>
          
          @auth
               <li class="dropdown"><a href="#"><span>{{Auth::user()->username}}</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="/profile">Profile</a></li>
              <li><a href="/admin/users">Dashboard</a></li>
              <li><a href="/logout">Logout</a></li>
            </ul>
          </li>
             @else
          <li><a href="/login">Login</a></li>
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
