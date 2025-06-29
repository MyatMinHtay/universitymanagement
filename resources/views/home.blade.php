<x-layout>
    <main class="main">

          <!-- Hero Section -->
          <section id="hero" class="hero section dark-background" style="background-image: url(assets/img/education/showcase-1.webp); background-size: cover; background-position: center center; background-repeat: no-repeat;">

           
        

          <div class="hero-container">
              
              <div class="overlay"></div>
              <div class="container">
              <div class="row align-items-center">
                  <div class="col-lg-7" data-aos="zoom-out" data-aos-delay="100">
                  <div class="hero-content">
                      <h1>Welcome to University Of Mandalay</h1>
                      <p>No other light can complete with the brightess of the light of wisdom</p>
                      <div class="cta-buttons">
                      
                      </div>
                  </div>
                  </div>
                
              </div>
              </div>
          </div>

          <div class="event-ticker">
              <div class="container">
              <div class="row gy-4">
                  <div class="col-md-6 col-xl-4 col-12 ticker-item">
                  <span class="date">NOV 15</span>
                  <span class="title">Open House Day</span>
                  <a href="#" class="btn-register">Register</a>
                  </div>
                  <div class="col-md-6 col-12 col-xl-4  ticker-item">
                  <span class="date">DEC 5</span>
                  <span class="title">Application Workshop</span>
                  <a href="#" class="btn-register">Register</a>
                  </div>
                  <div class="col-md-6 col-12 col-xl-4 ticker-item">
                  <span class="date">JAN 10</span>
                  <span class="title">International Student Orientation</span>
                  <a href="#" class="btn-register">Register</a>
                  </div>
              </div>
              </div>
          </div>

          </section><!-- /Hero Section -->

          <!-- About Section -->
          <section id="about" class="about section">

          <div class="container" data-aos="fade-up" data-aos-delay="100">

              

              <div class="row mission-vision-row g-4">
              <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                  <div class="value-card h-100">
                  <div class="card-icon">
                      <i class="bi bi-rocket-takeoff"></i>
                  </div>
                  <h3>Our Mission</h3>
                  <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur excepteur sint occaecat.</p>
                  </div>
              </div>
              <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                  <div class="value-card h-100">
                  <div class="card-icon">
                      <i class="bi bi-eye"></i>
                  </div>
                  <h3>Our Vision</h3>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ut enim ad minim.</p>
                  </div>
              </div>
              <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                  <div class="value-card h-100">
                  <div class="card-icon">
                      <i class="bi bi-star"></i>
                  </div>
                  <h3>Our Values</h3>
                  <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit.</p>
                  </div>
              </div>
              </div>

          </div>

          </section><!-- /About Section -->

          {{-- Start Our Teacher Section  --}}

          <section>
            <div class="container">
              <div class="row">
                <div class="col-md-12">
                  <h2 class="text-center fw-bold">Our Teachers</h2>
                </div>

                <div class="col-12">
                    <div class="dpsection">
                        
                        <div class="card-grid d-flex justify-content-center align-items-center" id="teacher-list">
                            @forelse ($teachers as $teacher)
                                <a href="{{ route('teachers.usershow', $teacher->id) }}" class="info-card home-card">
                                    <img src="{{ asset($teacher->image) }}" alt="{{ $teacher->name }}">
                                    <h4>{{ $teacher->name }}</h4>
                                    <p>{{ $teacher->position }}</p>
                                    <p>Department of {{ $teacher->department->fullname ?? 'N/A' }}</p>
                                </a>
                            @empty
                                <p>No teachers found in this department.</p>
                            @endforelse
                        </div>
            
                        
                        </div>
                </div>

                <div class="col-12">
                    <div class="d-flex justify-content-center align-items-center">
                        <a href="{{ route('userteachers') }}" class="btn btn-primary">View All Teachers</a>
                    </div>
                </div>
              </div>
            </div>
          </section>



          {{-- End Our Teacher Section  --}}

          {{-- Start Our Student Section  --}}

          <section>
            <div class="container">
              <div class="row">
                <div class="col-md-12">
                  <h2 class="text-center fw-bold">Our Students</h2>
                </div>

                <div class="col-12">
                    <div class="dpsection">
                        
                        <div class="card-grid col-12 d-flex justify-content-start align-items-center" id="student-list">
                            @forelse ($students as $student)
                                <a href="{{ route('students.usershow', $student->id) }}" class="info-card home-card">
                                    <img src="{{ asset($student->image) }}" alt="{{ $student->name }}">
                                    <h4>{{ $student->name }}</h4>
                                    <p>Year: {{ $student->year }}</p>
                                    <p>Roll No: {{ $student->seat_number }}</p>
                                    
                                </a>
                            @empty
                                <p>No students found.</p>
                            @endforelse
                        </div>
        
                        
                    </div>
                </div>

                <div class="col-12">
                    <div class="d-flex justify-content-center align-items-center">
                        <a href="{{ route('userstudents') }}" class="btn btn-primary">View All Teachers</a>
                    </div>
                </div>
              </div>
            </div>
          </section>

          {{-- End Our Student Section  --}}

          

       
        

         


         

</main>

</x-layout>
