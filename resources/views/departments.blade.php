<x-layout>
    <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url(assets/img/education/showcase-1.webp);">
      <div class="container position-relative">
        <h1>Academic Departments</h1>
        <p>Explore our diverse range of academic departments, each dedicated to excellence in teaching, research, and innovation across various disciplines.</p>

        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">Departments</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Faculty Staff Section -->
    <section id="faculty--staff" class="faculty--staff section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row mb-5">
          <div class="col-lg-10 col-12  mx-auto">
            <div class="search-container" data-aos="fade-up" data-aos-delay="200">
              <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="searchdp" class="form-control search-input" placeholder="Search Departments by name or code...">
                <div class="search-border"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="department-grid" id="department-list">
          @forelse ($departments as $department)
              <div class="department-card-wrapper" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <a href="{{ route('user.departments.show', $department->id) }}" class="department-card">
                    <div class="card-header-dept">
                        <div class="dept-logo">
                            <img src="{{ asset($department->logo) }}" alt="{{ $department->fullname }}">
                        </div>
                        <div class="dept-overlay">
                            <i class="fas fa-university dept-icon"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3 class="dept-name">{{ $department->fullname }}</h3>
                        
                        <div class="card-footer">
                            <span class="view-more">
                                <i class="fas fa-arrow-right"></i>
                                Explore Department
                            </span>
                        </div>
                    </div>
                </a>
              </div>
          @empty
              <div class="no-results">
                <i class="fas fa-university"></i>
                <h4>No Departments Found</h4>
                <p>There are currently no departments available.</p>
              </div>
          @endforelse
        </div>

      </div>
    </section>

    </main>

    <!-- Enhanced Styling -->
    <style>
        /* Search Container Enhancement */
        .faculty--staff .search-container {
            background-color: transparent;
        }
        .search-container {
            margin-bottom: 3rem;
            background: transparent;
        }

        .search-wrapper {
            position: relative;
            max-width: 600px;
            margin: 0 auto;
        }

        .search-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 2;
            transition: color 0.3s ease;
        }

        .search-input {
            padding: 18px 20px 18px 55px;
            border: 2px solid #e9ecef;
            border-radius: 50px;
            font-size: 1.1rem;
            background: ;
            transition: all 0.4s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .search-input:focus {
            outline: none;
            border-color: #4361ee;
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.15);
            transform: translateY(-2px);
        }

        .search-input:focus + .search-border {
            transform: scaleX(1);
        }

        .search-input:focus ~ .search-icon {
            color: #4361ee;
        }

        .search-border {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            transform: scaleX(0);
            transition: transform 0.4s ease;
            border-radius: 50px;
        }

        /* Department Grid */
        .department-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .department-card-wrapper {
            animation: slideInUp 0.6s ease-out;
        }

        .department-card {
            display: block;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            text-decoration: none;
            color: inherit;
            position: relative;
            height: 280px;
        }

        .department-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            text-decoration: none;
            color: inherit;
        }

        .card-header-dept {
            position: relative;
            height: 160px;
            background: linear-gradient(135deg, #08915e 0%, #0ca678 50%, #13b58e 100%);

            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .dept-logo {
            position: relative;
            z-index: 2;
            background: white;
            border-radius: 50%;
            padding: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .department-card:hover .dept-logo {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25);
        }

        .dept-logo img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
        }

        .dept-overlay {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .dept-icon {
            color: white;
            font-size: 1.2rem;
        }

        .department-card:hover .dept-overlay {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(15deg);
        }

        .card-content {
            padding: 1.5rem;
            text-align: center;
            height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .dept-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            line-height: 1.3;
            transition: color 0.3s ease;
        }

        .department-card:hover .dept-name {
            color: #4361ee;
        }

        .dept-code {
            color: #6c757d;
            font-weight: 500;
            font-size: 0.95rem;
            margin-bottom: 1rem;
            background: #f8f9fa;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            display: inline-block;
        }

        .card-footer {
            margin-top: auto;
        }

        .view-more {
            color: #4361ee;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .view-more i {
            transition: transform 0.3s ease;
        }

        .department-card:hover .view-more {
            color: #7209b7;
        }

        .department-card:hover .view-more i {
            transform: translateX(5px);
        }

        /* No Results Styling */
        .no-results {
            grid-column: 1 / -1;
            text-align: center;
            padding: 4rem 2rem;
            color: #6c757d;
        }

        .no-results i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .no-results h4 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #495057;
        }

        .no-results p {
            font-size: 1rem;
            opacity: 0.8;
        }

        /* Animations */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .department-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 1.5rem;
            }

            .search-input {
                padding: 15px 18px 15px 50px;
                font-size: 1rem;
            }

            .dept-name {
                font-size: 1.1rem;
            }

            .department-card {
                height: 260px;
            }

            .card-header-dept {
                height: 140px;
            }

            .dept-logo img {
                width: 60px;
                height: 60px;
            }
        }

        @media (max-width: 576px) {
            .department-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .search-wrapper {
                margin: 0 1rem;
            }
        }
    </style>
</x-layout>

<script>
    $(document).ready(function() {
        let originalContent = $('#department-list').html();
        
        $('#searchdp').on('keyup', function () {
            var searchQuery = $(this).val();
            
            if (searchQuery.length === 0) {
                $('#department-list').html(originalContent);
                return;
            }
            
            $.ajax({
                type: 'GET',
                url: '{{ route('departments.search') }}',
                data: { search: searchQuery },
                dataType: 'json',
                success: function (data) {
                    $('#department-list').html('');
                    
                    if (data.length === 0) {
                        $('#department-list').html(`
                            <div class="no-results">
                                <i class="fas fa-search"></i>
                                <h4>No Departments Found</h4>
                                <p>No departments match your search criteria. Try different keywords.</p>
                            </div>
                        `);
                        return;
                    }
                    
                    $.each(data, function (index, department) {
                        var showUrl = 'departments/show/' + department.id;
                        var imageUrl = '/' + department.logo;

                        var departmentHtml = `
                            <div class="department-card-wrapper" data-aos="fade-up" data-aos-delay="${index * 100}">
                                <a href="${showUrl}" class="department-card">
                                    <div class="card-header-dept">
                                        <div class="dept-logo">
                                            <img src="${imageUrl}" alt="${department.fullname}">
                                        </div>
                                        <div class="dept-overlay">
                                            <i class="fas fa-university dept-icon"></i>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <h3 class="dept-name">${department.fullname}</h3>
                                        <p class="dept-code">${department.deptCode}</p>
                                        <div class="card-footer">
                                            <span class="view-more">
                                                <i class="fas fa-arrow-right"></i>
                                                Explore Department
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        `;

                        $('#department-list').append(departmentHtml);
                    });
                },
                error: function() {
                    $('#department-list').html(`
                        <div class="no-results">
                            <i class="fas fa-exclamation-triangle"></i>
                            <h4>Search Error</h4>
                            <p>Something went wrong while searching. Please try again.</p>
                        </div>
                    `);
                }
            });
        });
    });
</script>
