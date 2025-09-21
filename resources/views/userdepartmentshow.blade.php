<x-layout>
    <main class="main">
        <!-- Page Title -->
        <div class="page-title">
            <div class="container position-relative">
              <div class="department-header">
                <div class="dept-info">
                  <div class="dept-logo-container">
                    <img class="dept-logo-main" src="{{ asset($department->logo) }}" alt="{{ $department->fullname }}">
                  </div>
                  <div class="dept-details">
                    <h1 class="dept-title">Department Of {{ $department->fullname }}</h1>
                    
                  </div>
                </div>
                <div class="dept-banner-container">
                  <img class="dept-banner" src="{{ asset($department->banner) }}" alt="{{ $department->fullname }}">
                </div>
              </div>
            </div>
        </div>
        <!-- End Page Title -->

        <div class="container my-5">

            <!-- Teachers Section -->
            <div class="section-wrapper">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-chalkboard-teacher me-3"></i>
                        Our Teachers
                    </h3>
                    <div class="section-count">{{ $department->teachers->count() }} Members</div>
                </div>

                <div class="search-container-modern">
                    <div class="search-wrapper-modern">
                        <i class="fas fa-search search-icon-modern"></i>
                        <input type="text" id="searchtr" class="search-input-modern" placeholder="Search teachers by name, position...">
                        <div class="search-border-modern teachers"></div>
                    </div>
                </div>

                <!-- Filter Pills for Teachers -->
                <div class="filter-container">
                    <div class="filter-pills-wrapper">
                        <span class="filter-label">Filter by:</span>
                        <div class="filter-pills">
                            <button class="filter-pill teacher-filter active" data-filter="all">
                                <i class="fas fa-globe"></i> All Fields
                            </button>
                            <button class="filter-pill teacher-filter" data-filter="name">
                                <i class="fas fa-user"></i> Name
                            </button>
                            <button class="filter-pill teacher-filter" data-filter="position">
                                <i class="fas fa-briefcase"></i> Position
                            </button>
                            <button class="filter-pill teacher-filter" data-filter="phone">
                                <i class="fas fa-phone"></i> Phone
                            </button>
                            <button class="filter-pill teacher-filter" data-filter="email">
                                <i class="fas fa-envelope"></i> Email
                            </button>
                            <button class="filter-pill teacher-filter" data-filter="gender">
                                <i class="fas fa-venus-mars"></i> Gender
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-grid-modern teachers-grid" id="teacher-list">
                    @forelse ($department->teachers as $teacher)
                        <a href="{{ route('teachers.usershow', $teacher->id) }}" class="person-card teacher-card">
                            <div class="card-image-container">
                                <img src="{{ asset($teacher->image) }}" alt="{{ $teacher->name }}" class="person-image">
                                <div class="card-overlay">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                            </div>
                            <div class="card-content-modern">
                                <h4 class="person-name">{{ $teacher->name }}</h4>
                                <p class="person-position">{{ $teacher->position }}</p>
                                
                                <div class="card-action">
                                    <span class="view-profile">
                                        <i class="fas fa-arrow-right"></i>
                                        View Profile
                                    </span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="no-data">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <h4>No Teachers Found</h4>
                            <p>This department currently has no teachers assigned.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Students Section -->
            <div class="section-wrapper">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-user-graduate me-3"></i>
                        Our Students
                    </h3>
                    <div class="section-count">{{ $department->students->count() }} Students</div>
                </div>

                <div class="search-container-modern">
                    <div class="search-wrapper-modern">
                        <i class="fas fa-search search-icon-modern"></i>
                        <input type="text" id="searchst" class="search-input-modern" placeholder="Search students by name, year, roll number...">
                        <div class="search-border-modern students"></div>
                    </div>
                </div>

                <!-- Filter Pills for Students -->
                <div class="filter-container">
                    <div class="filter-pills-wrapper">
                        <span class="filter-label">Filter by:</span>
                        <div class="filter-pills">
                            <button class="filter-pill student-filter active" data-filter="all">
                                <i class="fas fa-globe"></i> All Fields
                            </button>
                            <button class="filter-pill student-filter" data-filter="name">
                                <i class="fas fa-user"></i> Name
                            </button>
                            <button class="filter-pill student-filter" data-filter="year">
                                <i class="fas fa-calendar"></i> Year
                            </button>
                            <button class="filter-pill student-filter" data-filter="roll_number">
                                <i class="fas fa-id-card"></i> Roll Number
                            </button>
                            <button class="filter-pill student-filter" data-filter="phone">
                                <i class="fas fa-phone"></i> Phone
                            </button>
                            <button class="filter-pill student-filter" data-filter="email">
                                <i class="fas fa-envelope"></i> Email
                            </button>
                            <button class="filter-pill student-filter" data-filter="gender">
                                <i class="fas fa-venus-mars"></i> Gender
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-grid-modern students-grid" id="student-list">
                    @forelse ($department->students as $student)
                        <a href="{{ route('students.usershow', $student->id) }}" class="person-card student-card">
                            <div class="card-image-container">
                                <img src="{{ asset($student->image) }}" alt="{{ $student->name }}" class="person-image">
                                <div class="card-overlay">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                            </div>
                            <div class="card-content-modern">
                                <h4 class="person-name">{{ $student->name }}</h4>
                                <p class="person-year">Year: {{ $student->year }}</p>
                                <p class="person-seat">
                                    <i class="fas fa-id-card me-2"></i>Roll: {{ $student->roll_number }}
                                </p>
                                <div class="card-action">
                                    <span class="view-profile">
                                        <i class="fas fa-arrow-right"></i>
                                        View Profile
                                    </span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="no-data">
                            <i class="fas fa-user-graduate"></i>
                            <h4>No Students Found</h4>
                            <p>This department currently has no students enrolled.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Enhanced Styling -->
        <style>
            /* Department Header */
            .department-header {
                margin-bottom: 3rem;
            }

            .dept-info {
                display: flex;
                align-items: center;
                gap: 2rem;
                margin-bottom: 2rem;
                padding: 2rem;
                background: white;
                border-radius: 20px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            }

            .dept-logo-container {
                flex-shrink: 0;
            }

            .dept-logo-main {
                width: 100px;
                height: 100px;
                object-fit: cover;
                border-radius: 50%;
                
                box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
            }

            .dept-details {
                flex: 1;
            }

            .dept-title {
                font-size: 2.5rem;
                font-weight: 700;
                color: #2c3e50;
                margin-bottom: 1rem;
                line-height: 1.2;
            }

            .dept-code-badge {
                background: linear-gradient(135deg, #4361ee, #7209b7);
                color: white;
                padding: 0.5rem 1.5rem;
                border-radius: 25px;
                font-weight: 600;
                display: inline-block;
                margin: 0;
            }

            .dept-banner-container {
                border-radius: 20px;
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            }

            .dept-banner {
                width: 100%;
                height: 400px;
                object-fit: cover;
                transition: transform 0.5s ease;
            }

            .dept-banner:hover {
                transform: scale(1.02);
            }

            /* Section Styling */
            .section-wrapper {
                margin-bottom: 4rem;
                background: white;
                border-radius: 20px;
                padding: 2rem;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            }

            .section-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 2rem;
                padding-bottom: 1rem;
                border-bottom: 2px solid #f8f9fa;
            }

            .section-title {
                font-size: 1.8rem;
                font-weight: 700;
                color: #2c3e50;
                margin: 0;
                display: flex;
                align-items: center;
            }

            .section-title.teachers i {
                color: #e76f51;
            }

            .section-title.students i {
                color: #08915e;
            }

            .section-count {
                background: #f8f9fa;
                padding: 0.5rem 1rem;
                border-radius: 20px;
                font-weight: 600;
                color: #6c757d;
                font-size: 0.9rem;
            }

            /* Modern Search Container */
            .search-container-modern {
                margin-bottom: 2rem;
            }

            .search-wrapper-modern {
                position: relative;
                max-width: 500px;
                margin: 0 auto;
            }

            .search-icon-modern {
                position: absolute;
                left: 20px;
                top: 50%;
                transform: translateY(-50%);
                color: #6c757d;
                z-index: 2;
                transition: color 0.3s ease;
            }

            .search-input-modern {
                width: 100%;
                padding: 15px 20px 15px 55px;
                border: 2px solid #e9ecef;
                border-radius: 30px;
                font-size: 1rem;
                background: #f8f9fa;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            }

            .search-input-modern:focus {
                outline: none;
                background: white;
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            }

            .search-input-modern:focus ~ .search-icon-modern {
                color: #4361ee;
            }

            .search-border-modern {
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 2px;
                transform: scaleX(0);
                transition: transform 0.3s ease;
                border-radius: 30px;
            }

            

            .search-input-modern:focus + .search-border-modern {
                transform: scaleX(1);
            }

            /* Card Grid */
            .card-grid-modern {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 1.5rem;
                margin-top: 1.5rem;
            }

            .person-card {
                width: 100%;
                max-width: 300px;
                background: white;
                border-radius: 15px;
                overflow: hidden;
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
                transition: all 0.4s ease;
                text-decoration: none;
                color: inherit;
                position: relative;
            }

            .person-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
                text-decoration: none;
                color: inherit;
            }

            .card-image-container {
                position: relative;
                height: 200px;
                overflow: hidden;
                background: linear-gradient(135deg, #f8f9fa, #e9ecef);
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .person-image {
                width: 120px;
                height: 120px;
                border-radius: 50%;
                object-fit: cover;
                border: 4px solid white;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                transition: all 0.3s ease;
            }

            .person-card:hover .person-image {
                transform: scale(1.05);
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
            }

            .card-overlay {
                position: absolute;
                top: 15px;
                right: 15px;
                background: rgba(255, 255, 255, 0.9);
                border-radius: 50%;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transition: all 0.3s ease;
            }

            .teacher-card .card-overlay {
                color: #e76f51;
            }

            .student-card .card-overlay {
                color: #08915e;
            }

            .person-card:hover .card-overlay {
                opacity: 1;
                transform: rotate(15deg);
            }

            .card-content-modern {
                padding: 1.5rem;
                text-align: center;
            }

            .person-name {
                font-size: 1.2rem;
                font-weight: 700;
                color: #2c3e50;
                margin-bottom: 0.5rem;
                transition: color 0.3s ease;
            }

            .teacher-card:hover .person-name {
                color: #e76f51;
            }

            .student-card:hover .person-name {
                color: #08915e;
            }

            .person-position, .person-year {
                font-weight: 600;
                margin-bottom: 0.5rem;
                font-size: 1rem;
            }

            .teacher-card .person-position {
                color: #e76f51;
            }

            .student-card .person-year {
                color: #08915e;
            }

            .person-contact, .person-seat {
                color: #6c757d;
                font-size: 0.9rem;
                margin-bottom: 1rem;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }

            .card-action {
                margin-top: auto;
            }

            .view-profile {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                font-weight: 600;
                font-size: 0.9rem;
                transition: all 0.3s ease;
            }

            .teacher-card .view-profile {
                color: #e76f51;
            }

            .student-card .view-profile {
                color: #08915e;
            }

            .view-profile i {
                transition: transform 0.3s ease;
            }

            .person-card:hover .view-profile i {
                transform: translateX(5px);
            }

            /* No Data Styling */
            .no-data {
                grid-column: 1 / -1;
                text-align: center;
                padding: 3rem 2rem;
                color: #6c757d;
            }

            .no-data i {
                font-size: 3rem;
                margin-bottom: 1rem;
                opacity: 0.5;
            }

            .no-data h4 {
                font-size: 1.3rem;
                font-weight: 600;
                margin-bottom: 0.5rem;
                color: #495057;
            }

            .no-data p {
                font-size: 1rem;
                opacity: 0.8;
            }

            /* Filter Pills Styling */
            .filter-container {
                margin-bottom: 2rem;
                padding: 1rem;
                background: #f8f9fa;
                border-radius: 15px;
                border: 1px solid #e9ecef;
            }

            .filter-pills-wrapper {
                display: flex;
                align-items: center;
                gap: 1rem;
                flex-wrap: wrap;
            }

            .filter-label {
                font-weight: 600;
                color: #495057;
                font-size: 0.9rem;
                white-space: nowrap;
            }

            .filter-pills {
                display: flex;
                gap: 0.5rem;
                flex-wrap: wrap;
            }

            .filter-pill {
                background: white;
                border: 2px solid #e9ecef;
                border-radius: 25px;
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
                font-weight: 500;
                color: #6c757d;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 0.4rem;
                white-space: nowrap;
            }

            .filter-pill:hover {
                background: #08915e;
                color: white;
                border-color: #08915e;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(8, 145, 94, 0.3);
            }

            .filter-pill.active {
                background: #08915e;
                color: white;
                border-color: #08915e;
                box-shadow: 0 4px 12px rgba(8, 145, 94, 0.3);
                position: relative;
            }

            .filter-pill.active:not([data-filter="all"]):after {
                content: '✓';
                position: absolute;
                top: -8px;
                right: -8px;
                background: #06a85d;
                color: white;
                border-radius: 50%;
                width: 18px;
                height: 18px;
                font-size: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                border: 2px solid white;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            }

            .filter-pill i {
                font-size: 0.8rem;
            }

            /* Teacher filter pills styling */
            .teacher-filter:hover {
                background: #e76f51;
                color: white;
                border-color: #e76f51;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(231, 111, 81, 0.3);
            }

            .teacher-filter.active {
                background: #e76f51;
                color: white;
                border-color: #e76f51;
                box-shadow: 0 4px 12px rgba(231, 111, 81, 0.3);
            }

            .teacher-filter.active:not([data-filter="all"]):after {
                background: #d63447;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .dept-info {
                    flex-direction: column;
                    text-align: center;
                    gap: 1rem;
                }

                .dept-title {
                    font-size: 2rem;
                }

                .section-header {
                    flex-direction: column;
                    gap: 1rem;
                    text-align: center;
                }

                .card-grid-modern {
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 1rem;
                }

                .dept-banner {
                    height: 200px;
                }

                .filter-pills-wrapper {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 0.5rem;
                }

                .filter-pills {
                    justify-content: center;
                    width: 100%;
                }
            }

            @media (max-width: 576px) {
                .section-wrapper {
                    padding: 1rem;
                }

                .dept-info {
                    padding: 1rem;
                }

                .card-grid-modern {
                    grid-template-columns: 1fr;
                }

                .search-wrapper-modern {
                    margin: 0 1rem;
                }
            }
        </style>

    </main>
</x-layout>

<script>
    $(document).ready(function () {
      let originalTeachersContent = $('#teacher-list').html();
      let originalStudentsContent = $('#student-list').html();
      let activeStudentFilters = ['all']; // Default filter for students
      let activeTeacherFilters = ['all']; // Default filter for teachers
      
      // Teacher search functionality
      $('#searchtr').on('keyup', function () {
        var searchQuery = $(this).val();
        
        if (searchQuery.length === 0) {
            $('#teacher-list').html(originalTeachersContent);
            return;
        }
        
        performTeacherSearch(searchQuery);
      });
      
      function performTeacherSearch(searchQuery) {
        $.ajax({
          type: 'GET',
          url: '{{ route('teachers.search') }}',
          data: { 
            search: searchQuery,
            department_id: {{ $department->id }},
            filter: activeTeacherFilters
          },
          dataType: 'json',
          success: function (data) {
            $('#teacher-list').html('');

            if (data.length === 0) {
                $('#teacher-list').html(`
                    <div class="no-data">
                        <i class="fas fa-search"></i>
                        <h4>No Teachers Found</h4>
                        <p>No teachers match your search criteria in this department.</p>
                    </div>
                `);
                return;
            }
  
            $.each(data, function (index, teacher) {
              var imageUrl = '/' + teacher.image;
              var showUrl = '/teachers/' + teacher.id;
  
              var teacherHtml = `
                <a href="${showUrl}" class="person-card teacher-card">
                    <div class="card-image-container">
                        <img src="${imageUrl}" alt="${teacher.name}" class="person-image">
                        <div class="card-overlay">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                    <div class="card-content-modern">
                        <h4 class="person-name">${teacher.name}</h4>
                        <p class="person-position">${teacher.position}</p>
                        
                        <div class="card-action">
                            <span class="view-profile">
                                <i class="fas fa-arrow-right"></i>
                                View Profile
                            </span>
                        </div>
                    </div>
                </a>
              `;
  
              $('#teacher-list').append(teacherHtml);
            });
          },
          error: function () {
            $('#teacher-list').html(`
                <div class="no-data">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h4>Search Error</h4>
                    <p>Something went wrong while searching teachers.</p>
                </div>
            `);
          }
        });
      }

      // Filter pill functionality for students
      $('.student-filter').on('click', function() {
        var selectedFilter = $(this).data('filter');
        
        if (selectedFilter === 'all') {
          // If "All Fields" is clicked, deactivate all other filters
          $('.student-filter').removeClass('active');
          $(this).addClass('active');
          activeStudentFilters = ['all'];
        } else {
          // Remove "All Fields" if a specific filter is selected
          $('.student-filter[data-filter="all"]').removeClass('active');
          
          // Toggle the clicked filter
          $(this).toggleClass('active');
          
          // Update activeStudentFilters array
          if ($(this).hasClass('active')) {
            if (!activeStudentFilters.includes(selectedFilter)) {
              activeStudentFilters.push(selectedFilter);
            }
          } else {
            activeStudentFilters = activeStudentFilters.filter(filter => filter !== selectedFilter);
          }
          
          // Remove 'all' from activeStudentFilters if it exists
          activeStudentFilters = activeStudentFilters.filter(filter => filter !== 'all');
          
          // If no filters are selected, activate "All Fields"
          if (activeStudentFilters.length === 0) {
            $('.student-filter[data-filter="all"]').addClass('active');
            activeStudentFilters = ['all'];
          }
        }
        
        // Trigger search with current query and new filters
        var currentQuery = $('#searchst').val();
        if (currentQuery.length > 0) {
          performStudentSearch(currentQuery);
        }
      });
      
      // Filter pill functionality for teachers
      $('.teacher-filter').on('click', function() {
        var selectedFilter = $(this).data('filter');
        
        if (selectedFilter === 'all') {
          // If "All Fields" is clicked, deactivate all other filters
          $('.teacher-filter').removeClass('active');
          $(this).addClass('active');
          activeTeacherFilters = ['all'];
        } else {
          // Remove "All Fields" if a specific filter is selected
          $('.teacher-filter[data-filter="all"]').removeClass('active');
          
          // Toggle the clicked filter
          $(this).toggleClass('active');
          
          // Update activeTeacherFilters array
          if ($(this).hasClass('active')) {
            if (!activeTeacherFilters.includes(selectedFilter)) {
              activeTeacherFilters.push(selectedFilter);
            }
          } else {
            activeTeacherFilters = activeTeacherFilters.filter(filter => filter !== selectedFilter);
          }
          
          // Remove 'all' from activeTeacherFilters if it exists
          activeTeacherFilters = activeTeacherFilters.filter(filter => filter !== 'all');
          
          // If no filters are selected, activate "All Fields"
          if (activeTeacherFilters.length === 0) {
            $('.teacher-filter[data-filter="all"]').addClass('active');
            activeTeacherFilters = ['all'];
          }
        }
        
        // Trigger search with current query and new filters
        var currentQuery = $('#searchtr').val();
        if (currentQuery.length > 0) {
          performTeacherSearch(currentQuery);
        }
      });

      // Student search functionality
      $('#searchst').on('keyup', function () {
        var searchQuery = $(this).val();
        
        if (searchQuery.length === 0) {
            $('#student-list').html(originalStudentsContent);
            return;
        }
        
        performStudentSearch(searchQuery);
      });
      
      function performStudentSearch(searchQuery) {
        $.ajax({
          type: 'GET',
          url: '{{ route('students.search') }}',
          data: { 
            search: searchQuery,
            department_id: {{ $department->id }},
            filter: activeStudentFilters
          },
          dataType: 'json',
          success: function (data) {
            $('#student-list').html('');

            if (data.length === 0) {
                $('#student-list').html(`
                    <div class="no-data">
                        <i class="fas fa-search"></i>
                        <h4>No Students Found</h4>
                        <p>No students match your search criteria in this department.</p>
                    </div>
                `);
                return;
            }
  
            $.each(data, function (index, student) {
              var imageUrl = '/' + student.image;
              var showUrl = '/students/' + student.id;
  
              var studentHtml = `
                <a href="${showUrl}" class="person-card student-card">
                    <div class="card-image-container">
                        <img src="${imageUrl}" alt="${student.name}" class="person-image">
                        <div class="card-overlay">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    </div>
                    <div class="card-content-modern">
                        <h4 class="person-name">${student.name}</h4>
                        <p class="person-year">Year: ${student.year}</p>
                        <p class="person-seat">
                            <i class="fas fa-id-card me-2"></i>Roll: ${student.roll_number}
                        </p>
                        <div class="card-action">
                            <span class="view-profile">
                                <i class="fas fa-arrow-right"></i>
                                View Profile
                            </span>
                        </div>
                    </div>
                </a>
              `;
  
              $('#student-list').append(studentHtml);
            });
          },
          error: function () {
            $('#student-list').html(`
                <div class="no-data">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h4>Search Error</h4>
                    <p>Something went wrong while searching students.</p>
                </div>
            `);
          }
        });
      }
    });
</script>
  


