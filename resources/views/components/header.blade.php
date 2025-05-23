 <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="/home" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.webp" alt=""> -->
        <i class="bi bi-buildings"></i>
        <h1 class="sitename">MDY University</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="/home" class="active">Home</a></li>
          <li class="dropdown"><a href="/about"><span>About</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="/about">About Us</a></li>
              <li><a href="/admissions">Admissions</a></li>
              <li><a href="/academics">Academics</a></li>
              <li><a href="/faculty-staff">Faculty &amp; Staff</a></li>
              <li><a href="/campus-facilities">Campus &amp; Facilities</a></li>
            </ul>
          </li>

          <li><a href="/students-life">Students Life</a></li>
          <li><a href="/news">News</a></li>
          <li><a href="/events">Events</a></li>
          <li><a href="/alumni">Alumni</a></li>
          <li class="dropdown"><a href="#"><span>More Pages</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="/news-details">News Details</a></li>
              <li><a href="/event-details">Event Details</a></li>
              <li><a href="/privacy">Privacy</a></li>
              <li><a href="/terms-of-service">Terms of Service</a></li>
              <li><a href="/contact">Contact</a></li>
            </ul>
          </li>

          
          {{-- <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#">Dropdown 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="#">Deep Dropdown 1</a></li>
                  <li><a href="#">Deep Dropdown 2</a></li>
                  <li><a href="#">Deep Dropdown 3</a></li>
                  <li><a href="#">Deep Dropdown 4</a></li>
                  <li><a href="#">Deep Dropdown 5</a></li>
                </ul>
              </li>
              <li><a href="#">Dropdown 2</a></li>
              <li><a href="#">Dropdown 3</a></li>
              <li><a href="#">Dropdown 4</a></li>
            </ul>
          </li> --}}
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
