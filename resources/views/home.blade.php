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

          {{-- Start Search Box  --}}

          <section>
             <div class="container">
                <div class="row mb-5">
                    <div class="col-lg-12 mx-auto">
                      <div class="search-container" data-aos="fade-up" data-aos-delay="200">
                        <div class="input-group">
                          <input type="text" id="mainsearch" class="form-control" placeholder="Search departments, teachers, students, faculty...">
                        </div>
                      </div>
                    </div>
                </div>
                
                {{-- Filter Pills --}}
                <div class="row mb-4" id="filter-pills-container">
                    <div class="col-12">
                        <div class="d-flex justify-content-center align-items-center flex-wrap gap-2">
                            <button class="filter-pill active" data-filter="all">
                                <i class="bi bi-grid-3x3-gap"></i> All
                            </button>
                            <button class="filter-pill" data-filter="departments">
                                <i class="bi bi-building"></i> Departments
                            </button>
                            <button class="filter-pill" data-filter="teachers">
                                <i class="bi bi-person-badge"></i> Teachers
                            </button>
                            <button class="filter-pill" data-filter="students">
                                <i class="bi bi-mortarboard"></i> Students
                            </button>
                            <button class="filter-pill" data-filter="faculty">
                                <i class="bi bi-people"></i> Faculty
                            </button>
                        </div>
                        <div class="filter-help">
                            <i class="bi bi-info-circle"></i> Click multiple filters to combine search results (max 3 filters)
                        </div>
                    </div>
                </div>
                
                {{-- Search Results Container --}}
                <div class="row" id="search-results-container" style="display: none;">
                    <div class="col-12">
                        <div class="search-results-wrapper">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="search-results-title">Search Results</h4>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="clear-search">
                                    <i class="bi bi-x"></i> Clear
                                </button>
                            </div>
                            
                            {{-- Single Results Container --}}
                            <div id="results-list" class="row">
                                <!-- Results will be dynamically loaded here -->
                            </div>
                            
                            {{-- No Results --}}
                            <div id="no-results" class="text-center py-4" style="display: none;">
                                <i class="bi bi-search" style="font-size: 3rem; color: #6c757d;"></i>
                                <h5 class="mt-3 text-muted">No results found</h5>
                                <p class="text-muted">Try searching with different keywords</p>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
          </section>

          {{-- End Search Box  --}}

          {{-- Start Our Teacher Section  --}}

          <section>
            <div class="container">
              <div class="row">
                <div class="col-md-12">
                  <h2 class="text-center fw-bold">Our Teachers</h2>
                </div>

                <div class="col-12">
                    <div class="dpsection professor">
                        
                        <div class="card-grid d-flex justify-content-center align-items-center" id="teacher-list">
                            @forelse ($teachers as $teacher)
                                @if ($teacher->position == 'Professor/Head')
                                    <a href="{{ route('teachers.usershow', $teacher->id) }}" class="info-card home-card">
                                        <img src="{{ asset($teacher->image) }}" alt="{{ $teacher->name }}">
                                        <h4>{{ $teacher->name }}</h4>
                                        <p>{{ $teacher->position }}</p>
                                        <p>Department of {{ $teacher->department->fullname ?? 'N/A' }}</p>
                                    </a>
                                @endif
                            @empty
                                <p>No teachers found in this department.</p>
                            @endforelse
                        </div>
            
                        
                    </div>

                    <div class="dpsection">
                        
                        <div class="card-grid d-flex justify-content-center align-items-center" id="teacher-list">
                            @forelse ($teachers as $teacher)
                                @if ($teacher->position != 'Professor/Head')
                                    <a href="{{ route('teachers.usershow', $teacher->id) }}" class="info-card home-card">
                                        <img src="{{ asset($teacher->image) }}" alt="{{ $teacher->name }}">
                                        <h4>{{ $teacher->name }}</h4>
                                        <p>{{ $teacher->position }}</p>
                                        <p>Department of {{ $teacher->department->fullname ?? 'N/A' }}</p>
                                    </a>
                                @endif
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

{{-- Custom CSS for Search --}}
<style>
.search-results-wrapper {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 25px;
    margin-bottom: 30px;
}

.badge.badge-primary{
    background-color: #007bff;
    color: #fff;
}

.badge.badge-success{
    background-color: #28a745;
    color: #fff;
}

.badge.badge-info{
    background-color: #17a2b8;
    color: #fff;
}

.badge.badge-warning{
    background-color: #ffc107;
    color: #fff;
}




.search-card {
    height: 350px;
    display: block;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    margin-bottom: 15px;
}

.search-card:hover {
    text-decoration: none;
    color: inherit;
    transform: translateY(-3px);
}

.search-card .card {
    border: none;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    height: 100%;
}

.search-card:hover .card {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}


.search-card .card-img-top {
    height: 200px;
    object-fit: contain;
    border-radius: 8px 8px 0 0;
}


.search-card .card-body {
    padding: 15px;
}

.search-card .card-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.search-card .card-text {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 5px;
}

.search-card .badge {
    font-size: 0.75rem;
    padding: 4px 8px;
}

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

#mainsearch {
    border-radius: 25px;
    padding: 12px 20px;
    border: 2px solid #e9ecef;
    font-size: 1.1rem;
}

#mainsearch:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Filter Pills Styles */
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
    position: relative;
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

.filter-pill.active::after {
    content: "✓";
    position: absolute;
    top: -5px;
    right: -5px;
    background: #28a745;
    color: white;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: bold;
}

.filter-pill i {
    font-size: 0.8rem;
}

/* Helper text for multiple selection */
.filter-help {
    font-size: 0.8rem;
    color: #6c757d;
    text-align: center;
    margin-top: 5px;
    font-style: italic;
}

#filter-pills-container {
    opacity: 1;
    transform: translateY(0);
    transition: all 0.3s ease;
}

@media (max-width: 768px) {
    .filter-pill {
        padding: 6px 12px;
        font-size: 0.8rem;
        margin: 2px;
    }
}
</style>

{{-- JavaScript for Real-time Search --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('mainsearch');
    const searchResultsContainer = document.getElementById('search-results-container');
    const clearSearchBtn = document.getElementById('clear-search');
    const filterPillsContainer = document.getElementById('filter-pills-container');
    const filterPills = document.querySelectorAll('.filter-pill');
    const resultsList = document.getElementById('results-list');
    let searchTimeout;
    let currentFilters = ['all']; // Changed to array to support multiple filters
    const maxFilters = 3; // Maximum number of filters that can be selected

    // Search function
    function performSearch(query) {
        if (query.trim().length < 2) {
            hideSearchResults();
            return;
        }

        // Show loading state
        showLoadingState();

        // Convert filters array to string for URL
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
            hideSearchResults();
        });
    }

    // Display search results
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

        // Display results
        displayResults(data.results);

        // Hide no results
        document.getElementById('no-results').style.display = 'none';
    }

    // Display results in single container
    function displayResults(results) {
        resultsList.innerHTML = results.map(item => {
            let cardContent = '';
            
            if (item.type === 'department') {
                cardContent = `
                    <h6 class="card-title">Department Of ${item.name}</h6>
                    <p class="card-text text-muted">${item.shortname}</p>
                    <span class="badge badge-${item.badge_color}">${item.badge}</span>
                `;
            } else if (item.type === 'teacher') {
                cardContent = `
                    <h6 class="card-title">${item.name}</h6>
                    <p class="card-text text-muted">${item.position} - <small>Department Of ${item.department}</small></p>
                    <span class="badge badge-${item.badge_color}">${item.badge}</span>
                `;
            } else if (item.type === 'student') {
                cardContent = `
                    <h6 class="card-title">${item.name}</h6>
                    <p class="card-text text-muted">Year ${item.year} - <small>Roll: ${item.seat_number}</small></p>
                    <p class="card-text"><small>Department Of ${item.department}</small></p>
                    <span class="badge badge-${item.badge_color}">${item.badge}</span>
                `;
            } else if (item.type === 'faculty') {
                cardContent = `
                    <h6 class="card-title">${item.name}</h6>
                    <p class="card-text text-muted">${item.position} - <small>Department Of ${item.department}</small></p>
                    <span class="badge badge-${item.badge_color}">${item.badge}</span>
                `;
            }

            const imageUrl = item.image || item.logo || '{{ asset("assets/img/default-avatar.png") }}';
            
            return `
                <div class="col-md-4 col-sm-6">
                    <a href="${item.url}" class="search-card home-card">
                        <div class="card">
                            <img src="${imageUrl}" class="card-img-top" alt="${item.name}" onerror="this.src='{{ asset('assets/img/default-avatar.png') }}'">
                            <div class="card-body">
                                ${cardContent}
                            </div>
                        </div>
                    </a>
                </div>
            `;
        }).join('');
    }

    // Show loading state
    function showLoadingState() {
        searchResultsContainer.style.display = 'block';
        
        // Hide no results
        document.getElementById('no-results').style.display = 'none';
        
        // Show loading in the search wrapper
        const searchWrapper = document.querySelector('.search-results-wrapper');
        if (searchWrapper) {
            const existingLoading = searchWrapper.querySelector('.loading-container');
            if (existingLoading) {
                existingLoading.remove();
            }
            
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'loading-container text-center py-4';
            loadingDiv.innerHTML = `
                <div class="loading-spinner"></div>
                <p class="mt-2 text-muted">Searching...</p>
            `;
            searchWrapper.appendChild(loadingDiv);
        }
    }

    // Show no results
    function showNoResults() {
        searchResultsContainer.style.display = 'block';
        
        // Remove loading spinner
        const loadingContainer = document.querySelector('.loading-container');
        if (loadingContainer) {
            loadingContainer.remove();
        }
        
        // Clear results
        resultsList.innerHTML = '';
        
        // Show no results message
        document.getElementById('no-results').style.display = 'block';
    }

    // Hide search results
    function hideSearchResults() {
        searchResultsContainer.style.display = 'none';
    }

    // Clear search
    function clearSearch() {
        searchInput.value = '';
        hideSearchResults();
        searchInput.focus();
        
        // Reset filters to 'all'
        currentFilters = ['all'];
        updateActiveFilters();
    }

    // Update active filters - handles multiple selections
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

    // Check if we can add more filters
    function canAddFilter() {
        // If 'all' is selected, can't add more
        if (currentFilters.includes('all')) {
            return false;
        }
        // Check if we haven't reached the maximum
        return currentFilters.length < maxFilters;
    }

    // Handle filter toggle
    function toggleFilter(filterValue) {
        if (filterValue === 'all') {
            // If 'all' is clicked, clear other filters and set only 'all'
            currentFilters = ['all'];
        } else {
            // Remove 'all' if it exists when selecting specific filters
            if (currentFilters.includes('all')) {
                currentFilters = [];
            }
            
            // Toggle the specific filter
            if (currentFilters.includes(filterValue)) {
                // Remove filter if already selected
                currentFilters = currentFilters.filter(f => f !== filterValue);
                
                // If no filters left, default to 'all'
                if (currentFilters.length === 0) {
                    currentFilters = ['all'];
                }
            } else {
                // Add filter if not selected and we can add more
                if (canAddFilter()) {
                    currentFilters.push(filterValue);
                } else {
                    // Show message if max filters reached
                    alert(`You can select maximum ${maxFilters} filters at once.`);
                    return;
                }
            }
        }
        
        updateActiveFilters();
        
        // Perform search with new filters if there's a query
        const query = searchInput.value.trim();
        if (query.length >= 2) {
            performSearch(query);
        }
    }

    // Event listeners
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length === 0) {
            hideSearchResults();
            return;
        }
        
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300); // Debounce search
    });

    // Filter pill click handlers - updated for multiple selection
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

    // Show results when focusing on search input (if there's a query)
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 2) {
            performSearch(this.value.trim());
        }
    });

    // Initialize filters
    updateActiveFilters();
});
</script>

</x-layout>


