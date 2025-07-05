<x-layout>
    <main class="container my-5">

        <!-- Page Title -->
        <div class="page-title text-center">
            <div class="container position-relative">
                <h1 class="mt-5">Students ({{ $studentcounts }})</h1>
            </div>
        </div>
        <!-- End Page Title -->

        <!-- Enhanced Search Box -->
        <div class="my-4">
            <div class="search-container" data-aos="fade-up" data-aos-delay="200">
                <div class="input-group">
                    <input type="text" id="searchstudent" class="form-control" placeholder="Search students with multiple keywords: e.g., 'John 2023' or 'Computer Science'">
                </div>
            </div>
        </div>

        <!-- Filter Pills -->
        <div class="row mb-4" id="filter-pills-container">
            <div class="col-12">
                <div class="d-flex justify-content-center align-items-center flex-wrap gap-2">
                    <button class="filter-pill active" data-filter="all">
                        <i class="bi bi-grid-3x3-gap"></i> All
                    </button>
                    <button class="filter-pill" data-filter="name">
                        <i class="bi bi-person"></i> Name
                    </button>
                    <button class="filter-pill" data-filter="year">
                        <i class="bi bi-calendar"></i> Year
                    </button>
                    <button class="filter-pill" data-filter="department">
                        <i class="bi bi-building"></i> Department
                    </button>
                </div>
                <div class="filter-help">
                    <i class="bi bi-info-circle"></i> Type multiple keywords separated by spaces. Use filters to search specific fields only.
                </div>
            </div>
        </div>

        <!-- Students Card Grid -->
        <div class="dpsection">
            <div class="card-grid col-12 d-flex flex-wrap justify-content-start align-items-start gap-4" id="student-list">
                @forelse ($students as $student)
                    <a href="{{ route('students.usershow', $student->id) }}" class="student-card text-decoration-none">
                        <div class="card h-100">
                            <div class="card-img-container">
                                <img src="{{ $student->image ? asset($student->image) : asset('assets/img/default-student.png') }}"
                                     alt="{{ $student->name }}"
                                     class="card-img-top">
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $student->name }}</h5>
                                <p class="card-text year-badge">Year {{ $student->year }}</p>
                                <p class="card-text roll-number">Roll: {{ $student->seat_number }}</p>
                                <p class="card-text department">{{ $student->department->shortname ?? 'N/A' }}</p>
                                <span class="badge badge-student">Student</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">No students found.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Search Results Message -->
        <div id="search-message" class="text-center mt-3" style="display: none;"></div>

        <!-- No Results -->
        <div id="no-results" class="text-center py-4" style="display: none;">
            <i class="bi bi-search" style="font-size: 3rem; color: #6c757d;"></i>
            <h5 class="mt-3 text-muted">No students found</h5>
            <p class="text-muted">Try different keywords or change the search filter. You can search multiple terms separated by spaces.</p>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4" id="pagination-container">
            {{ $students->links() }}
        </div>

    </main>

    <!-- Custom CSS for Enhanced Search -->
    <style>
        /* Search Container Styling */
        .search-container {
            position: relative;
            max-width: 600px;
            margin: 0 auto;
        }

        .search-container input {
            border-radius: 25px;
            padding: 12px 20px;
            border: 2px solid #e9ecef;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .search-container input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Filter Pills */
        .filter-pill {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 8px 16px;
            margin: 4px;
            font-size: 0.9rem;
            font-weight: 500;
            color: #6c757d;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .filter-pill:hover {
            background: #e9ecef;
            border-color: #dee2e6;
            color: #495057;
            transform: translateY(-2px);
        }

        .filter-pill.active {
            background: #007bff;
            border-color: #007bff;
            color: #fff;
            box-shadow: 0 3px 10px rgba(0, 123, 255, 0.3);
        }

        .filter-pill i {
            font-size: 0.8rem;
        }

        .filter-help {
            font-size: 0.8rem;
            color: #6c757d;
            text-align: center;
            margin-top: 5px;
            font-style: italic;
        }

        /* Enhanced Card Styling */
        .student-card {
            width: 280px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            color: inherit;
        }

        .student-card:hover {
            text-decoration: none;
            color: inherit;
            transform: translateY(-8px);
        }

        .student-card .card {
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        }

        .student-card:hover .card {
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
        }

        .card-img-container {
            position: relative;
            padding: 20px 20px 0 20px;
            text-align: center;
        }

        .card-img-top {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .student-card:hover .card-img-top {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .card-body {
            padding: 15px 20px 20px 20px;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: #2c3e50;
        }

        .card-text {
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .year-badge {
            color: #7209b7;
            font-weight: 500;
        }

        .roll-number {
            color: #0d6efd;
            font-weight: 500;
        }

        .department {
            color: #6c757d;
            font-size: 0.85rem;
        }

        /* Badge Styling */
        .badge.badge-student {
            background: linear-gradient(135deg, #17a2b8, #20c997);
            color: #fff;
            font-size: 0.75rem;
            padding: 4px 12px;
            border-radius: 15px;
            font-weight: 500;
            margin-top: 8px;
        }

        /* Loading State */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .filter-pill {
                padding: 6px 12px;
                font-size: 0.8rem;
                margin: 2px;
            }
            
            .student-card {
                width: 250px;
            }
        }

        @media (max-width: 576px) {
            .student-card {
                width: 100%;
                max-width: 300px;
                margin: 0 auto 20px auto;
            }
        }
    </style>

    

    
</x-layout>

<!-- Enhanced JavaScript -->
<script>
    $(document).ready(function () {
        let currentFilter = 'all';
        let searchTimeout;
        let originalStudents = null;
        
        // Store original students HTML for reset
        if (!originalStudents) {
            originalStudents = $('#student-list').html();
        }
        
        // Filter pill click handler
        $('.filter-pill').on('click', function() {
            $('.filter-pill').removeClass('active');
            $(this).addClass('active');
            currentFilter = $(this).data('filter');
            
            // Re-run search with new filter
            const searchQuery = $('#searchstudent').val();
            if (searchQuery.length > 0) {
                performSearch(searchQuery);
            } else {
                resetToOriginal();
            }
        });

        // Search input handler
        $('#searchstudent').on('keyup', function () {
            const searchQuery = $(this).val();
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Set new timeout
            searchTimeout = setTimeout(function() {
                if (searchQuery.length === 0) {
                    resetToOriginal();
                } else if (searchQuery.length >= 2) {
                    performSearch(searchQuery);
                }
            }, 300);
        });

        function performSearch(query) {
            // Show loading state
            $('#search-message').show().html('<div class="loading-spinner"></div> Searching...');
            $('#pagination-container').hide();
            $('#no-results').hide();

            $.ajax({
                type: 'GET',
                url: '{{ route('students.search') }}',
                data: { 
                    search: query,
                    filter: currentFilter
                },
                dataType: 'json',
                success: function (data) {
                    displaySearchResults(data, query);
                },
                error: function () {
                    $('#student-list').html('<div class="col-12 text-danger text-center">Something went wrong while searching.</div>');
                    $('#search-message').hide();
                }
            });
        }

        function displaySearchResults(data, query) {
            let cardsHtml = '';
            
            if (data.length === 0) {
                $('#student-list').html('');
                $('#no-results').show();
                $('#search-message').hide();
            } else {
                $.each(data, function (index, student) {
                    let imageUrl = student.image ? `/${student.image}` : '/assets/img/default-student.png';
                    let showUrl = `/students/${student.id}`;
                    let departmentName = student.department?.shortname || 'N/A';

                    cardsHtml += `
                        <a href="${showUrl}" class="student-card text-decoration-none">
                            <div class="card h-100">
                                <div class="card-img-container">
                                    <img src="${imageUrl}"
                                         alt="${student.name}"
                                         class="card-img-top">
                                </div>
                                <div class="card-body text-center">
                                    <h5 class="card-title">${student.name}</h5>
                                    <p class="card-text year-badge">Year ${student.year}</p>
                                    <p class="card-text roll-number">Roll: ${student.seat_number}</p>
                                    <p class="card-text department">${departmentName}</p>
                                    <span class="badge badge-student">Student</span>
                                </div>
                            </div>
                        </a>
                    `;
                });
                
                $('#student-list').html(cardsHtml);
                $('#no-results').hide();
                
                const filterText = currentFilter === 'all' ? 'all fields' : `${currentFilter} field`;
                $('#search-message').show().html(`<p class="text-info"><i class="bi bi-search"></i> Found ${data.length} student(s) matching "${query}" in ${filterText}</p>`);
            }
        }

        function resetToOriginal() {
            $('#student-list').html(originalStudents);
            $('#search-message').hide();
            $('#no-results').hide();
            $('#pagination-container').show();
        }
    });
</script>
