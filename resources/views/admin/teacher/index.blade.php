<x-adminlayout>
    <main class="container my-5">
    
        <!-- Page Title -->
        <div class="page-title text-center">
            <div class="container position-relative">
                <h1 class="mt-5">Teachers</h1>

                <h2 class="col-12 text-end peoplecount">
                    Total Teacher - {{ $teachercounts }}
                </h2>
            </div>
        </div>
        <!-- End Page Title -->
    
        <!-- Enhanced Search Box -->
        <div class="my-4">
            <input type="text" id="searchteacher" class="form-control" placeholder="Search teachers with multiple keywords">
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
                    <button class="filter-pill" data-filter="position">
                        <i class="bi bi-person-badge"></i> Position
                    </button>
                    <button class="filter-pill" data-filter="department">
                        <i class="bi bi-building"></i> Department
                    </button>
                    <button class="filter-pill" data-filter="id">
                        <i class="bi bi-hash"></i> ID
                    </button>
                    <button class="filter-pill" data-filter="phone">
                        <i class="bi bi-telephone"></i> Phone
                    </button>
                    <button class="filter-pill" data-filter="email">
                        <i class="bi bi-envelope"></i> Email
                    </button>
                    <button class="filter-pill" data-filter="gender">
                        <i class="bi bi-gender-ambiguous"></i> Gender
                    </button>
                    <button class="filter-pill" data-filter="date_of_birth">
                        <i class="bi bi-calendar"></i> Date of Birth
                    </button>
                </div>
                <div class="filter-help">
                    <i class="bi bi-info-circle"></i> Type multiple keywords separated by spaces. Click multiple filters to search across specific fields.
                </div>
            </div>
        </div>
    
        <!-- Add Teacher Button and Export PDF Button -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <button onclick="exportToPDF()" class="btn btn-primary">
                <i class="fa-solid fa-file-pdf"></i> Export PDF
            </button>
            <a href="{{ route('teachers.create') }}" class="btn btn-success">
                <i class="fa-solid fa-plus"></i> Add Teacher
            </a>
        </div>

        <!-- Search Results Message -->
        <div id="search-message" class="text-center mb-3" style="display: none;"></div>
    
        <!-- Teachers Table -->
        <div class="table-responsive" id="teacher-list">
            <table class="table table-hover table-bordered border-1 table-primary">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Position</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Department</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->id }}</td>
                            <td>{{ $teacher->name }}</td>
                            <td>
                                @if($teacher->gender)
                                    {{ ucfirst($teacher->gender) }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            
                            <td>{{ $teacher->position }}</td>
                            <td>
                                @if($teacher->email)
                                    <a href="mailto:{{ $teacher->email }}">{{ $teacher->email }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($teacher->phone_number)
                                    <a href="tel:{{ $teacher->phone_number }}">{{ $teacher->phone_number }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $teacher->department->fullname ?? 'N/A' }}</td>
                            <td class="text-center">
                               
                                <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-outline-info btn-sm me-1" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a href="{{ route('teachers.show', $teacher->id) }}" class="btn btn-primary btn-sm" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No Teachers Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="col-12" id="pagination-container">
                {{ $teachers->links() }}
            </div>
        </div>
    
    </main>
    
   
</x-adminlayout>

<!-- Filter Pills CSS -->
<style>
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

    .filter-pill.active:not([data-filter="all"]) {
        background: #28a745;
        border-color: #28a745;
        box-shadow: 0 3px 10px rgba(40, 167, 69, 0.3);
    }

    .filter-pill.active:not([data-filter="all"]):after {
        content: "✓";
        position: absolute;
        top: -5px;
        right: -5px;
        background: #fff;
        color: #28a745;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        font-size: 10px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #28a745;
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
</style>

<script>
    $(document).ready(function () {
        let currentFilters = ['all']; // Array of selected filters
        let searchTimeout;
        let originalTableHtml = null;
        
        // Store original table HTML for reset
        if (!originalTableHtml) {
            originalTableHtml = $('#teacher-list').html();
        }
        
        // Filter pill click handler
        $('.filter-pill').on('click', function() {
            const filterValue = $(this).data('filter');
            
            // Handle "All" filter
            if (filterValue === 'all') {
                $('.filter-pill').removeClass('active');
                $(this).addClass('active');
                currentFilters = ['all'];
            } else {
                // Remove "All" filter if other filters are selected
                if (currentFilters.includes('all')) {
                    currentFilters = [];
                    $('.filter-pill[data-filter="all"]').removeClass('active');
                }
                
                // Toggle filter selection
                if (currentFilters.includes(filterValue)) {
                    currentFilters = currentFilters.filter(f => f !== filterValue);
                    $(this).removeClass('active');
                } else {
                    currentFilters.push(filterValue);
                    $(this).addClass('active');
                }
                
                // If no filters selected, default to "All"
                if (currentFilters.length === 0) {
                    currentFilters = ['all'];
                    $('.filter-pill[data-filter="all"]').addClass('active');
                }
            }
            
            // Re-run search with new filters
            const searchQuery = $('#searchteacher').val();
            if (searchQuery.length > 0) {
                performSearch(searchQuery);
            } else {
                resetToOriginal();
            }
        });

        // Search input handler
        $('#searchteacher').on('keyup', function () {
            const searchQuery = $(this).val();
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Set new timeout
            searchTimeout = setTimeout(function() {
                if (searchQuery.length === 0) {
                    resetToOriginal();
                } else if (searchQuery.length >= 0) {
                    performSearch(searchQuery);
                }
            }, 300);
        });

        function performSearch(query) {
            // Show loading state
            $('#search-message').show().html('<div class="loading-spinner"></div> Searching...');
            $('#pagination-container').hide();

            $.ajax({
                type: 'GET',
                url: '{{ route('teachers.search') }}',
                data: { 
                    search: query,
                    filter: currentFilters
                },
                dataType: 'json',
                success: function (data) {
                    displaySearchResults(data, query);
                },
                error: function () {
                    $('#teacher-list').html('<p class="text-danger">Something went wrong while searching.</p>');
                    $('#search-message').hide();
                }
            });
        }

        function displaySearchResults(data, query) {
            let tableHtml = `
                <table class="table table-hover table-bordered border-1 table-primary">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Position</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Department</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            if (data.length === 0) {
                tableHtml += `
                    <tr>
                        <td colspan="8" class="text-center">No teachers found.</td>
                    </tr>
                `;
                $('#search-message').hide();
            } else {
                $.each(data, function (index, teacher) {
                    let editUrl = '/admin/teachers/edit/' + teacher.id;
                    let showUrl = '/admin/teachers/' + teacher.id;
                    let departmentName = teacher.department?.fullname || 'N/A';

                    tableHtml += `
                        <tr>
                            <td>${teacher.id}</td>
                            <td>${teacher.name}</td>
                            <td>${teacher.gender}</td>
                            <td>${teacher.position}</td>
                            <td>${teacher.email ? `<a href="mailto:${teacher.email}">${teacher.email}</a>` : '<span class="text-muted">N/A</span>'}</td>
                            <td>${teacher.phone_number ? `<a href="tel:${teacher.phone_number}">${teacher.phone_number}</a>` : '<span class="text-muted">N/A</span>'}</td>
                            <td>${departmentName}</td>
                            <td class="text-center">
                                
                                <a href="${editUrl}" class="btn btn-outline-info btn-sm me-1" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                <a href="${showUrl}" class="btn btn-primary btn-sm" title="View"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    `;
                });
                
                const filterText = currentFilters.includes('all') ? 'all fields' : currentFilters.join(', ') + ' field(s)';
                $('#search-message').show().html(`<p class="text-info"><i class="bi bi-search"></i> Found ${data.length} teacher(s) matching "${query}" in ${filterText}</p>`);
            }

            tableHtml += '</tbody></table>';
            $('#teacher-list').html(tableHtml);
        }

        function resetToOriginal() {
            $('#teacher-list').html(originalTableHtml);
            $('#search-message').hide();
            $('#pagination-container').show();
        }
    });
</script>

<script>
function exportToPDF() {
    // Get current search parameters
    const searchQuery = document.getElementById('searchteacher').value;
    const activeFilters = [];
    
    // Get active filter pills
    document.querySelectorAll('.filter-pill.active').forEach(pill => {
        activeFilters.push(pill.dataset.filter);
    });
    
    // Build export URL with current search parameters
    let exportUrl = '{{ route("teachers.export.search.pdf") }}';
    const params = new URLSearchParams();
    
    if (searchQuery) {
        params.append('search', searchQuery);
    }
    
    if (activeFilters.length > 0) {
        params.append('filter', activeFilters.join(','));
    }
    
    if (params.toString()) {
        exportUrl += '?' + params.toString();
    }
    
    // Open PDF in new tab
    window.open(exportUrl, '_blank');
}
</script>

