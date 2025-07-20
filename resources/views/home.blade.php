<x-layout>
    <main class="main">

          <!-- Hero Section -->
          <section id="hero" class="hero section dark-background" style="background-image: url(assets/img/home.jpg); background-size: cover; background-position: center center; background-repeat: no-repeat;">

           
        

          <div class="hero-container">
              
              <div class="overlay"></div>
              <div class="container">
              <div class="row align-items-center">
                  <div class="col-lg-7" data-aos="zoom-out" data-aos-delay="100">
                  <div class="hero-content">
                      <h1>Welcome to University of Mandalay</h1>
                      <p>No other light can complete with the brightess of the light of wisdom</p>
                      <div class="cta-buttons">
                      
                      </div>
                  </div>
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
                    <p>The mission of the University of Mandalay is to promote high quality teaching and training , advance cutting-edge research, build strong infrastructure and foster collaborations with both local and international partners , all aimed at enriching knowledge and benefiting society.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="value-card h-100">
                    <div class="card-icon">
                        <i class="bi bi-eye"></i>
                    </div>
                    <h3>Our Vision</h3>
                    <p>The vision of the University of Mandalay is to strive towards the emergence of a leading National Research University.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="value-card h-100">
                    <div class="card-icon">
                        <i class="bi bi-star"></i>
                    </div>
                    <h3>Our Values</h3>
                    <p>The cole values are high quality education, academic oriented research, applied research progress, internationalization and contribution to the needs of local and global community aligned with environmental responsibility. </p>
                    </div>
                </div>
                </div>
  
            </div>
  
            </section><!-- /About Section -->

          {{-- Start Search Box  --}}

          <section class="search-section">
             <div class="container">
                <div class="row mb-5">
                    <div class="col-lg-10 mx-auto">
                      <div class="main-search-container" data-aos="fade-up" data-aos-delay="200">
                        <div class="search-wrapper-main">
                          <i class="fas fa-search search-icon-main"></i>
                          <input type="text" id="mainsearch" class="form-control search-input-main" placeholder="Search departments, teachers, students, faculty...">
                          <div class="search-border-main"></div>
                        </div>
                      </div>
                    </div>
                </div>
                
                {{-- Filter Pills --}}
                <div class="row mb-4" id="filter-pills-container">
                    <div class="col-12">
                        <div class="d-flex justify-content-center align-items-center flex-wrap gap-3">
                            <button class="filter-pill-modern active" data-filter="all">
                                <i class="bi bi-grid-3x3-gap"></i> All
                            </button>
                            <button class="filter-pill-modern" data-filter="departments">
                                <i class="bi bi-building"></i> Departments
                            </button>
                            <button class="filter-pill-modern" data-filter="teachers">
                                <i class="bi bi-person-badge"></i> Teachers
                            </button>
                            <button class="filter-pill-modern" data-filter="students">
                                <i class="bi bi-mortarboard"></i> Students
                            </button>
                        </div>
                        <div class="filter-help-modern">
                            <i class="bi bi-info-circle"></i> Click multiple filters to combine search results (max 3 filters)
                        </div>
                    </div>
                </div>
                
                {{-- Search Results Container --}}
                <div class="row" id="search-results-container" style="display: none;">
                    <div class="col-12">
                        <div class="search-results-wrapper-modern">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="search-results-title-modern">Search Results</h4>
                                <button type="button" class="btn btn-outline-primary rounded-pill" id="clear-search">
                                    <i class="bi bi-x"></i> Clear Search
                                </button>
                            </div>
                            
                            {{-- Single Results Container --}}
                            <div id="results-list" class="modern-results-grid">
                                <!-- Results will be dynamically loaded here -->
                            </div>
                            
                            {{-- No Results --}}
                            <div id="no-results" class="no-results-modern" style="display: none;">
                                <i class="bi bi-search"></i>
                                <h5>No results found</h5>
                                <p>Try searching with different keywords or adjust your filters</p>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
          </section>

          {{-- End Search Box  --}}

          {{-- Start Our Teacher Section  --}}

          <section class="teachers-section">
            <div class="container">
              <div class="row">
                <div class="col-md-12">
                  <div class="section-header-main">
                    <h2 class="section-title-main">
                      <i class="fas fa-chalkboard-teacher me-3"></i>
                      Our Teachers
                    </h2>
                   
                  </div>
                </div>

                <div class="col-12">
                    {{-- Professors/Heads Section --}}
                    <div class="faculty-section professors-section">
                        <h3 class="faculty-subsection-title">
                          <i class="fas fa-crown me-2"></i>
                         Department Of Professors / Heads
                        </h3>
                        <div class="modern-card-grid justify-content-center align-items-center" id="professor-list">
                            @forelse ($teachers as $teacher)
                                @if ($teacher->position == 'Professor/Head')
                                    <a href="{{ route('teachers.usershow', $teacher->id) }}" class="modern-person-card professor-card">
                                        <div class="card-image-wrapper">
                                            <img src="{{ asset($teacher->image) }}" alt="{{ $teacher->name }}" class="person-image-modern">
                                            <div class="card-overlay-modern">
                                                <i class="fas fa-crown"></i>
                                            </div>
                                            <div class="position-badge professor-badge">Professor</div>
                                        </div>
                                        <div class="card-content-wrapper">
                                            <h4 class="person-name-modern">{{ $teacher->name }}</h4>
                                            <p class="person-position-modern">{{ $teacher->position }}</p>
                                            <p class="person-department-modern">
                                                <i class="fas fa-building me-2"></i>{{ $teacher->department->fullname ?? 'N/A' }}
                                            </p>
                                            <div class="card-action-modern">
                                                <span class="view-profile-modern">
                                                    <i class="fas fa-arrow-right"></i>
                                                    View Profile
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @empty
                                <div class="no-data-modern">
                                    <i class="fas fa-crown"></i>
                                    <p>No professors found.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Regular Teachers Section --}}
                    <div class="faculty-section teachers-section">
                        <h3 class="faculty-subsection-title">
                          <i class="fas fa-user-tie me-2"></i>
                          Teachers
                        </h3>
                        <div class="modern-card-grid" id="teacher-list">
                            @forelse ($teachers as $teacher)
                                @if ($teacher->position != 'Professor/Head')
                                    <a href="{{ route('teachers.usershow', $teacher->id) }}" class="modern-person-card teacher-card">
                                        <div class="card-image-wrapper">
                                            <img src="{{ asset($teacher->image) }}" alt="{{ $teacher->name }}" class="person-image-modern">
                                            <div class="card-overlay-modern">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                            <div class="position-badge teacher-badge">{{ $teacher->position }}</div>
                                        </div>
                                        <div class="card-content-wrapper">
                                            <h4 class="person-name-modern">{{ $teacher->name }}</h4>
                                            <p class="person-position-modern">{{ $teacher->position }}</p>
                                            <p class="person-department-modern">
                                                <i class="fas fa-building me-2"></i>{{ $teacher->department->fullname ?? 'N/A' }}
                                            </p>
                                            <div class="card-action-modern">
                                                <span class="view-profile-modern">
                                                    <i class="fas fa-arrow-right"></i>
                                                    View Profile
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @empty
                                <div class="no-data-modern">
                                    <i class="fas fa-user-tie"></i>
                                    <p>No teachers found.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="view-all-container">
                        <a href="{{ route('userteachers') }}" class="btn-view-all">
                            <i class="fas fa-users me-2"></i>
                            View All Teachers
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
              </div>
            </div>
          </section>

          {{-- End Our Teacher Section  --}}

          {{-- Start Our Student Section  --}}

          <section class="students-section">
            <div class="container">
              <div class="row">
                <div class="col-md-12">
                  <div class="section-header-main">
                    <h2 class="section-title-main">
                      <i class="fas fa-user-graduate me-3"></i>
                      Our Students
                    </h2>
                   
                  </div>
                </div>

                <div class="col-12">
                    <div class="modern-card-grid" id="student-list">
                        @forelse ($students as $student)
                            <a href="{{ route('students.usershow', $student->id) }}" class="modern-person-card student-card">
                                <div class="card-image-wrapper">
                                    <img src="{{ asset($student->image) }}" alt="{{ $student->name }}" class="person-image-modern">
                                    <div class="card-overlay-modern">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <div class="position-badge student-badge">Year {{ $student->year }}</div>
                                </div>
                                <div class="card-content-wrapper">
                                    <h4 class="person-name-modern">{{ $student->name }}</h4>
                                    <p class="person-year-modern">Academic Year: {{ $student->year }}</p>
                                    <p class="person-roll-modern">
                                        <i class="fas fa-id-card me-2"></i>Roll: {{ $student->seat_number }}
                                    </p>
                                    <div class="card-action-modern">
                                        <span class="view-profile-modern">
                                            <i class="fas fa-arrow-right"></i>
                                            View Profile
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="no-data-modern">
                                <i class="fas fa-user-graduate"></i>
                                <p>No students found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="col-12">
                    <div class="view-all-container">
                        <a href="{{ route('userstudents') }}" class="btn-view-all">
                            <i class="fas fa-users me-2"></i>
                            View All Students
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
              </div>
            </div>
          </section>

          {{-- End Our Student Section  --}}
      

</main>

{{-- Enhanced Modern CSS --}}
<style>
/* Main Search Section */
.search-section {
    background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
    padding: 4rem 0;
    margin: 2rem 0;
}

.main-search-container {
    margin-bottom: 3rem;
}

.search-wrapper-main {
    position: relative;
    max-width: 700px;
    margin: 0 auto;
}

.search-icon-main {
    position: absolute;
    left: 25px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    z-index: 2;
    font-size: 1.2rem;
    transition: color 0.3s ease;
}

.search-input-main {
    padding: 20px 25px 20px 60px !important;
    border: 3px solid #e9ecef !important;
    border-radius: 50px !important;
    font-size: 1.1rem !important;
    background: white !important;
    transition: all 0.4s ease !important;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
}

.search-input-main:focus {
    border-color: #4361ee !important;
    box-shadow: 0 12px 35px rgba(67, 97, 238, 0.2) !important;
    transform: translateY(-3px) !important;
    outline: none !important;
}

.search-input-main:focus ~ .search-icon-main {
    color: #4361ee;
}

.search-border-main {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    transform: scaleX(0);
    transition: transform 0.4s ease;
    border-radius: 50px;
}

.search-input-main:focus + .search-border-main {
    transform: scaleX(1);
}

/* Modern Filter Pills */
.filter-pill-modern {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 30px;
    padding: 12px 20px;
    margin: 6px;
    font-size: 0.95rem;
    font-weight: 600;
    color: #495057;
    transition: all 0.4s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.filter-pill-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transition: left 0.6s ease;
}

.filter-pill-modern:hover::before {
    left: 100%;
}

.filter-pill-modern:hover {
    background: #f8f9fa;
    border-color: #4361ee;
    color: #4361ee;
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.2);
}

.filter-pill-modern.active {
    background: linear-gradient(135deg, #4361ee, #7209b7);
    border-color: #4361ee;
    color: white;
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
    transform: translateY(-2px);
}
  

.filter-pill-modern i {
    font-size: 0.9rem;
}

.filter-help-modern {
    font-size: 0.9rem;
    color: #6c757d;
    text-align: center;
    margin-top: 10px;
    font-style: italic;
    background: rgba(255, 255, 255, 0.7);
    padding: 8px 16px;
    border-radius: 15px;
    display: inline-block;
}

/* Search Results */
.search-results-wrapper-modern {
    background: white;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid #e9ecef;
}

.search-results-title-modern {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.modern-results-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.no-results-modern {
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.no-results-modern i {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.no-results-modern h5 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #495057;
}

.no-results-modern p {
    font-size: 1rem;
    opacity: 0.8;
}

/* Section Headers */
.section-header-main {
    text-align: center;
    margin-bottom: 3rem;
}

.section-title-main {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.section-subtitle {
    font-size: 1.1rem;
    color: #6c757d;
    font-style: italic;
}

/* Faculty Sections */
.teachers-section, .students-section {
    padding: 4rem 0;
}

.teachers-section {
    background: linear-gradient(135deg, #fff8f5 0%, #ffffff 100%);
}

.students-section {
    background: linear-gradient(135deg, #f0fff4 0%, #ffffff 100%);
}

.faculty-section {
    margin-bottom: 3rem;
}

.faculty-subsection-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 2rem;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.professors-section .faculty-subsection-title i {
    color: #ffd700;
}

.teachers-section .faculty-subsection-title i {
    color: #e76f51;
}

/* Modern Card Grid */
.modern-card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}

/* Modern Person Cards */
.modern-person-card {
    width: 100%;
    max-width: 350px;
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.4s ease;
    text-decoration: none;
    color: inherit;
    position: relative;
    height: 400px;
    display: flex;
    flex-direction: column;
}

.modern-person-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
    text-decoration: none;
    color: inherit;
}


.card-image-wrapper {
    position: relative;
    height: 250px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.person-image-modern {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid white;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    transition: all 0.4s ease;
}

.modern-person-card:hover .person-image-modern {
    transform: scale(1.05);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25);
}

.card-overlay-modern {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
    font-size: 1.2rem;
}

.professor-card .card-overlay-modern {
    color: #ffd700;
}

.teacher-card .card-overlay-modern {
    color: #e76f51;
}

.student-card .card-overlay-modern {
    color: #08915e;
}

.modern-person-card:hover .card-overlay-modern {
    opacity: 1;
    transform: rotate(15deg);
}

.position-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    color: white;
    opacity: 0;
    transition: all 0.3s ease;
}

.professor-badge {
    background: linear-gradient(135deg, #ffd700, #ffed4e);
    color: #333;
}

.teacher-badge {
    background: linear-gradient(135deg, #e76f51, #f4a261);
}

.student-badge {
    background: linear-gradient(135deg, #08915e, #2a9d8f);
}

.modern-person-card:hover .position-badge {
    opacity: 1;
}

.card-content-wrapper {
    padding: 1.5rem;
    text-align: center;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.person-name-modern {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    transition: color 0.3s ease;
}

.professor-card:hover .person-name-modern {
    color: #b8860b;
}

.teacher-card:hover .person-name-modern {
    color: #e76f51;
}

.student-card:hover .person-name-modern {
    color: #08915e;
}

.person-position-modern, .person-year-modern {
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.professor-card .person-position-modern {
    color: #b8860b;
}

.teacher-card .person-position-modern {
    color: #e76f51;
}

.student-card .person-year-modern {
    color: #08915e;
}

.person-department-modern, .person-roll-modern {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.card-action-modern {
    margin-top: auto;
}

.view-profile-modern {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.professor-card .view-profile-modern {
    color: #b8860b;
}

.teacher-card .view-profile-modern {
    color: #e76f51;
}

.student-card .view-profile-modern {
    color: #08915e;
}

.view-profile-modern i {
    transition: transform 0.3s ease;
}

.modern-person-card:hover .view-profile-modern i {
    transform: translateX(5px);
}

/* View All Button */
.view-all-container {
    text-align: center;
    margin-top: 3rem;
}

.btn-view-all {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #4361ee, #7209b7);
    color: white;
    padding: 15px 30px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.4s ease;
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
}

.btn-view-all:hover {
    background: linear-gradient(135deg, #7209b7, #4361ee);
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(67, 97, 238, 0.4);
    text-decoration: none;
    color: white;
}

.btn-view-all i:last-child {
    transition: transform 0.3s ease;
}

.btn-view-all:hover i:last-child {
    transform: translateX(5px);
}

/* Modern Search Cards for AJAX */
.search-card-modern {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transition: all 0.4s ease;
    text-decoration: none;
    color: inherit;
    height: 350px;
    display: flex;
    flex-direction: column;
}

.search-card-modern:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    text-decoration: none;
    color: inherit;
}

.search-card-modern .card-img-top {
    height: 200px;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.search-card-modern:hover .card-img-top {
    transform: scale(1.05);
}

.search-card-modern .card-body {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.search-card-modern .card-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

.search-card-modern .card-text {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.search-card-modern .badge {
    font-size: 0.8rem;
    padding: 6px 12px;
    border-radius: 15px;
    font-weight: 600;
    margin-top: auto;
}

.badge-primary { background: linear-gradient(135deg, #4361ee, #7209b7); }
.badge-success { background: linear-gradient(135deg, #08915e, #2a9d8f); }
.badge-info { background: linear-gradient(135deg, #17a2b8, #20c997); }
.badge-warning { background: linear-gradient(135deg, #ffc107, #ffed4e); color: #333; }

/* No Data Styling */
.no-data-modern {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem 2rem;
    color: #6c757d;
}

.no-data-modern i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.no-data-modern p {
    font-size: 1rem;
    opacity: 0.8;
}

/* Loading Spinner */
.loading-spinner {
    display: inline-block;
    width: 30px;
    height: 30px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #4361ee;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .section-title-main {
        font-size: 2rem;
        flex-direction: column;
        gap: 0.5rem;
    }

    .modern-card-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .modern-results-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .filter-pill-modern {
        padding: 10px 16px;
        font-size: 0.9rem;
        margin: 4px;
    }

    .search-input-main {
        padding: 15px 20px 15px 50px !important;
        font-size: 1rem !important;
    }

    .modern-person-card {
        height: 380px;
    }

    .card-image-wrapper {
        height: 220px;
    }

    .person-image-modern {
        width: 120px;
        height: 120px;
    }
}

@media (max-width: 576px) {
    .modern-card-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .search-wrapper-main {
        margin: 0 1rem;
    }

    .faculty-subsection-title {
        font-size: 1.3rem;
        flex-direction: column;
        gap: 0.5rem;
    }

    .teachers-section, .students-section {
        padding: 2rem 0;
    }
}
</style>

{{-- Enhanced JavaScript for Modern Search --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('mainsearch');
    const searchResultsContainer = document.getElementById('search-results-container');
    const clearSearchBtn = document.getElementById('clear-search');
    const filterPillsContainer = document.getElementById('filter-pills-container');
    const filterPills = document.querySelectorAll('.filter-pill-modern');
    const resultsList = document.getElementById('results-list');
    let searchTimeout;
    let currentFilters = ['all'];
    const maxFilters = 3;

    // Enhanced search function with modern styling
    function performSearch(query) {
        if (query.trim().length < 2) {
            hideSearchResults();
            return;
        }

        showLoadingState();
        const filtersParam = currentFilters.join(',');

        fetch(`{{ route('home.search') }}?query=${encodeURIComponent(query)}&filter=${filtersParam}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            displaySearchResults(data);
        })
        .catch(error => {
            console.error('Search error:', error);
            showErrorState();
        });
    }

    // Enhanced display results with modern cards
    function displaySearchResults(data) {
        if (!data.results || data.results.length === 0) {
            showNoResults();
            return;
        }

        searchResultsContainer.style.display = 'block';
        
        // Remove loading spinner
        const loadingContainer = document.querySelector('.loading-container');
        if (loadingContainer) {
            loadingContainer.remove();
        }

        displayResults(data.results);
        document.getElementById('no-results').style.display = 'none';
    }

    // Modern card display function
    function displayResults(results) {
        resultsList.innerHTML = results.map(item => {
            let cardContent = '';
            let cardClass = 'search-card-modern';
            
            if (item.type === 'department') {
                cardContent = `
                    <div class="card-body">
                        <h6 class="card-title">Department Of ${item.name}</h6>
                        <p class="card-text text-muted">${item.shortname}</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <small class="text-muted">
                                <i class="fas fa-building me-1"></i>
                                Academic Department
                            </small>
                            <span class="badge badge-${item.badge_color}">${item.badge}</span>
                        </div>
                    </div>
                `;
            } else if (item.type === 'teacher') {
                cardContent = `
                    <div class="card-body">
                        <h6 class="card-title">${item.name}</h6>
                        <p class="card-text text-muted">${item.position}</p>
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="fas fa-building me-1"></i>
                                ${item.department}
                            </small>
                        </p>
                        <div class="d-flex align-items-center justify-content-between">
                            <small class="text-muted">
                                <i class="fas fa-chalkboard-teacher me-1"></i>
                                Teacher
                            </small>
                            <span class="badge badge-${item.badge_color}">${item.badge}</span>
                        </div>
                    </div>
                `;
            } else if (item.type === 'student') {
                cardContent = `
                    <div class="card-body">
                        <h6 class="card-title">${item.name}</h6>
                        <p class="card-text text-muted">Academic Year ${item.year}</p>
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="fas fa-id-card me-1"></i>
                                Roll: ${item.seat_number}
                            </small>
                        </p>
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="fas fa-building me-1"></i>
                                ${item.department}
                            </small>
                        </p>
                        <div class="d-flex align-items-center justify-content-between">
                            <small class="text-muted">
                                <i class="fas fa-user-graduate me-1"></i>
                                Student
                            </small>
                            <span class="badge badge-${item.badge_color}">${item.badge}</span>
                        </div>
                    </div>
                `;
            } else if (item.type === 'faculty') {
                cardContent = `
                    <div class="card-body">
                        <h6 class="card-title">${item.name}</h6>
                        <p class="card-text text-muted">${item.position}</p>
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="fas fa-building me-1"></i>
                                ${item.department}
                            </small>
                        </p>
                        <div class="d-flex align-items-center justify-content-between">
                            <small class="text-muted">
                                <i class="fas fa-user-tie me-1"></i>
                                Faculty
                            </small>
                            <span class="badge badge-${item.badge_color}">${item.badge}</span>
                        </div>
                    </div>
                `;
            }

            const imageUrl = item.image || item.logo || '{{ asset("assets/img/default-avatar.png") }}';
            
            return `
                <div class="search-result-item">
                    <a href="${item.url}" class="${cardClass}">
                        <div class="card-image-container">
                            <img src="${imageUrl}" class="card-img-top" alt="${item.name}" 
                                 onerror="this.src='{{ asset('assets/img/default-avatar.png') }}'">
                            <div class="card-overlay-search">
                                <i class="fas fa-${item.type === 'department' ? 'building' : 
                                                  item.type === 'teacher' ? 'chalkboard-teacher' : 
                                                  item.type === 'student' ? 'user-graduate' : 'user-tie'}"></i>
                            </div>
                        </div>
                        ${cardContent}
                    </a>
                </div>
            `;
        }).join('');
    }

    // Enhanced loading state
    function showLoadingState() {
        searchResultsContainer.style.display = 'block';
        document.getElementById('no-results').style.display = 'none';
        
        const searchWrapper = document.querySelector('.search-results-wrapper-modern');
        if (searchWrapper) {
            const existingLoading = searchWrapper.querySelector('.loading-container');
            if (existingLoading) {
                existingLoading.remove();
            }
            
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'loading-container text-center py-5';
            loadingDiv.innerHTML = `
                <div class="loading-spinner mb-3"></div>
                <p class="text-muted">Searching across all departments...</p>
                <div class="search-progress">
                    <div class="progress-bar"></div>
                </div>
            `;
            searchWrapper.appendChild(loadingDiv);
        }
    }

    // Enhanced error state
    function showErrorState() {
        searchResultsContainer.style.display = 'block';
        
        const loadingContainer = document.querySelector('.loading-container');
        if (loadingContainer) {
            loadingContainer.remove();
        }
        
        resultsList.innerHTML = `
            <div class="error-state text-center py-5">
                <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                <h5 class="mt-3 text-muted">Search Error</h5>
                <p class="text-muted">Something went wrong while searching. Please try again.</p>
                <button class="btn btn-outline-primary btn-sm" onclick="performSearch('${searchInput.value}')">
                    <i class="fas fa-retry me-2"></i>Try Again
                </button>
            </div>
        `;
        
        document.getElementById('no-results').style.display = 'none';
    }

    // Enhanced no results state
    function showNoResults() {
        searchResultsContainer.style.display = 'block';
        
        const loadingContainer = document.querySelector('.loading-container');
        if (loadingContainer) {
            loadingContainer.remove();
        }
        
        resultsList.innerHTML = '';
        document.getElementById('no-results').style.display = 'block';
    }

    // Hide search results
    function hideSearchResults() {
        searchResultsContainer.style.display = 'none';
    }

    // Enhanced clear search
    function clearSearch() {
        searchInput.value = '';
        hideSearchResults();
        searchInput.focus();
        
        // Reset filters to 'all'
        currentFilters = ['all'];
        updateActiveFilters();
        
        // Add smooth transition
        searchInput.style.transform = 'scale(1.02)';
        setTimeout(() => {
            searchInput.style.transform = 'scale(1)';
        }, 200);
    }

    // Enhanced filter management
    function updateActiveFilters() {
        filterPills.forEach(pill => {
            const filterValue = pill.dataset.filter;
            
            if (currentFilters.includes(filterValue)) {
                pill.classList.add('active');
            } else {
                pill.classList.remove('active');
            }
        });
    }

    function canAddFilter() {
        if (currentFilters.includes('all')) {
            return false;
        }
        return currentFilters.length < maxFilters;
    }

    function toggleFilter(filterValue) {
        if (filterValue === 'all') {
            currentFilters = ['all'];
        } else {
            if (currentFilters.includes('all')) {
                currentFilters = [];
            }
            
            if (currentFilters.includes(filterValue)) {
                currentFilters = currentFilters.filter(f => f !== filterValue);
                
                if (currentFilters.length === 0) {
                    currentFilters = ['all'];
                }
            } else {
                if (canAddFilter()) {
                    currentFilters.push(filterValue);
                } else {
                    // Show modern notification
                    showFilterLimitNotification();
                    return;
                }
            }
        }
        
        updateActiveFilters();
        
        const query = searchInput.value.trim();
        if (query.length >= 2) {
            performSearch(query);
        }
    }

    // Modern notification for filter limit
    function showFilterLimitNotification() {
        const notification = document.createElement('div');
        notification.className = 'filter-notification';
        notification.innerHTML = `
            <i class="fas fa-info-circle me-2"></i>
            Maximum ${maxFilters} filters can be selected at once
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateY(0)';
        }, 100);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Enhanced event listeners
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length === 0) {
            hideSearchResults();
            return;
        }
        
        // Visual feedback while typing
        this.style.borderColor = '#4361ee';
        
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });

    searchInput.addEventListener('focus', function() {
        this.style.transform = 'translateY(-2px)';
        if (this.value.trim().length >= 2) {
            performSearch(this.value.trim());
        }
    });

    searchInput.addEventListener('blur', function() {
        this.style.transform = 'translateY(0)';
        this.style.borderColor = '#e9ecef';
    });

    // Filter pill click handlers
    filterPills.forEach(pill => {
        pill.addEventListener('click', function() {
            const filterValue = this.dataset.filter;
            toggleFilter(filterValue);
        });
    });

    clearSearchBtn.addEventListener('click', clearSearch);

    // Hide results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && 
            !searchResultsContainer.contains(e.target) && 
            !filterPillsContainer.contains(e.target)) {
            if (searchInput.value.trim().length === 0) {
                hideSearchResults();
            }
        }
    });

    // Keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            clearSearch();
        }
    });

    // Initialize
    updateActiveFilters();
});
</script>

{{-- Additional CSS for notifications and enhancements --}}
<style>
.filter-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #4361ee, #7209b7);
    color: white;
    padding: 15px 20px;
    border-radius: 10px;
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
    z-index: 1000;
    opacity: 0;
    transform: translateY(-20px);
    transition: all 0.3s ease;
    font-weight: 500;
}

.search-progress {
    width: 100%;
    height: 4px;
    background: #e9ecef;
    border-radius: 2px;
    overflow: hidden;
    margin-top: 15px;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #4361ee, #7209b7);
    border-radius: 2px;
    animation: progress 2s infinite;
}

@keyframes progress {
    0% { width: 0%; }
    50% { width: 70%; }
    100% { width: 100%; }
}

.card-overlay-search {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
    color: #4361ee;
}

.search-card-modern:hover .card-overlay-search {
    opacity: 1;
    transform: rotate(15deg);
}

.card-image-container {
    position: relative;
    overflow: hidden;
}

.search-result-item {
    height: 100%;
}

.error-state {
    grid-column: 1 / -1;
}
</style>

</x-layout>


