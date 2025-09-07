<x-layout>
    <!-- Custom CSS for Enhanced Search -->
    <style>
        /* Enhanced Search Container Styling */
        .search-container {
            position: relative;
            max-width: 650px;
            margin: 0 auto;
        }

        .search-input-wrapper {
            position: relative;
            border-radius: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            background: white;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .search-input-wrapper:focus-within {
            box-shadow: 0 6px 25px rgba(0, 123, 255, 0.15);
            transform: translateY(-2px);
        }

        .search-container input {
            border: none;
            border-radius: 30px;
            padding: 15px 120px 15px 50px;
            font-size: 1.1rem;
            background: transparent;
            outline: none;
            width: 100%;
        }

        .search-icon {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        .search-input-wrapper:focus-within .search-icon {
            color: #007bff;
        }

        .advanced-btn {
            position: absolute;
            right: 58px;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            border-radius: 20px;
            padding: 8px 16px;
            color: white;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .advanced-btn:hover {
            background: linear-gradient(135deg, #0056b3, #004085);
            transform: translateY(-50%) scale(1.05);
        }

        .advanced-btn i {
            font-size: 0.8rem;
        }

        /* Bootstrap Modal Customization */
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            background: linear-gradient(135deg, #f8f9ff, #ffffff);
            border-bottom: 2px solid #f0f0f0;
            border-radius: 20px 20px 0 0;
            padding: 20px 30px;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-body {
            padding: 30px;
        }

        .filter-help {
            background: #f8f9ff;
            border: 1px solid #e7e9ff;
            border-radius: 10px;
            padding: 20px;
            font-size: 0.9rem;
            color: #6c757d;
            text-align: left;
        }

        .filter-help i {
            color: #007bff;
            margin-right: 8px;
        }

        .filter-help code {
            background: #e9ecef;
            color: #495057;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .filter-help strong {
            color: #495057;
            font-weight: 600;
        }

        .filter-help .row {
            margin-top: 10px;
        }

        .filter-help .col-md-6 {
            margin-bottom: 15px;
        }

        /* Query Builder Styling */
        .query-row {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .query-row:hover {
            border-color: #007bff;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.1);
        }

        .query-row:first-child {
            background: #fff;
            border: 2px solid #007bff;
        }

        .operator-select {
            font-weight: 600;
            color: #007bff;
            border: 2px solid #007bff;
        }

        .field-select {
            font-weight: 500;
            border: 1px solid #ced4da;
        }

        .search-value {
            border: 1px solid #ced4da;
        }

        .search-value:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .remove-row {
            border: 1px solid #dc3545;
        }

        .remove-row:hover {
            background-color: #dc3545;
            color: white;
        }

        #addRow {
            border: 1px solid #28a745;
            color: #28a745;
        }

        #addRow:hover {
            background-color: #28a745;
            color: white;
        }

        #clearAll {
            border: 1px solid #6c757d;
            color: #6c757d;
        }

        #clearAll:hover {
            background-color: #6c757d;
            color: white;
        }

        /* Modal Search Input Styling */
        #modalSearchInput {
            border-radius: 8px 0 0 8px;
            border-right: none;
            font-size: 1.1rem;
        }

        #modalSearchInput:focus {
            border-color: #007bff;
            box-shadow: none;
        }

        /* Search Examples Styling */
        .form-text code {
            background: #f8f9fa;
            color: #e83e8c;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .form-text {
            line-height: 1.6;
            margin-top: 10px;
        }

        .form-text strong {
            color: #495057;
        }

        .modal-body .btn-primary {
            border-radius: 0 8px 8px 0;
            padding: 12px 20px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
        }

        .modal-body .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3, #004085);
            transform: translateY(-1px);
        }

        /* Enhanced Card Styling */
        .teacher-card {
            width: 260px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            color: inherit;
        }

        .teacher-card:hover {
            text-decoration: none;
            color: inherit;
            transform: translateY(-8px);
        }

        .teacher-card .card {
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff 0%, #fff5f5 100%);
        }

        .teacher-card:hover .card {
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

        .teacher-card:hover .card-img-top {
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

        .position-badge {
            color: #e67e22;
            font-weight: 500;
        }

        .department {
            color: #6c757d;
            font-size: 0.85rem;
        }

        /* Badge Styling */
        .badge.badge-professor {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: #fff;
            font-size: 0.75rem;
            padding: 4px 12px;
            border-radius: 15px;
            font-weight: 500;
            margin-top: 8px;
        }

        .badge.badge-teacher {
            background: linear-gradient(135deg, #28a745, #20c997);
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
            .search-container input {
                padding: 12px 100px 12px 45px;
                font-size: 1rem;
            }
            
            .advanced-btn {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
            
            .modal-title {
                font-size: 1.3rem;
            }
            
            .teacher-card {
                width: 250px;
            }
        }

        @media (max-width: 576px) {
            .search-container {
                max-width: 100%;
            }
            
            .search-container input {
                padding: 10px 90px 10px 40px;
                font-size: 0.95rem;
            }
            
            .search-icon {
                right: 15px;
                font-size: 1rem;
            }
            
            .advanced-btn {
                padding: 5px 10px;
                font-size: 0.75rem;
                right: 55px;
            }
            
            .teacher-card {
                width: 100%;
                max-width: 300px;
                margin: 0 auto 20px auto;
            }
        }
    </style>
    <main class="container my-5">

        <!-- Page Title -->
        <div class="page-title text-center">
            <div class="container position-relative">
                <h1 class="mt-5">Teachers</h1>
                <h2 class="col-12 text-end peoplecount">
                    Total Teacher - {{ $teachercounts }}
                </h2>
                @if(request('search'))
                    <div class="mt-3">
                        <div class="alert alert-info d-inline-block">
                            <i class="bi bi-search"></i>
                            Search results for: <strong>"{{ request('search') }}"</strong>
                            <a href="{{ route('userteachers') }}" class="btn btn-sm btn-outline-secondary ms-2">
                                <i class="bi bi-x"></i> Clear
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- End Page Title -->

        <!-- Enhanced Search Box (AJAX Only) -->
        <div class="my-4">
            <div class="search-container" data-aos="fade-up" data-aos-delay="200">
                <div class="search-input-wrapper">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="searchteacher" class="form-control" 
                           placeholder="Search Teacher">
                    <button class="advanced-btn" id="advancedBtn" type="button">
                        <i class="bi bi-sliders"></i>
                        Advanced
                    </button>
                </div>
            </div>
        </div>

        <!-- Export Button -->
        <div class="text-center mb-3">
            <button id="exportPdfBtn" class="btn btn-success" 
                    style="display: {{ (request('search') || request('field') || request('value')) && $teachercounts > 0 ? 'inline-block' : 'none' }};">
                <i class="bi bi-file-earmark-pdf"></i> Export Search Results to PDF
            </button>
        </div>

        <!-- Bootstrap Filter Modal -->
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title me-5" id="filterModalLabel">
                            <i class="bi bi-search"></i>
                            Advanced Search & Filters
                        </h5>
                        <a href="{{ route('userteachers') }}" class="btn btn-outline-secondary" title="Clear all filters">
                            <i class="bi bi-x-circle"></i> Clear
                        </a>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form Search Section -->
                        <form id="modalSearchForm" action="{{ route('userteachers') }}" method="GET">
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-search"></i> Visual Query Builder
                                </label>
                                
                                <!-- Query Builder Rows -->
                                <div id="queryBuilder">
                                    <!-- First Row (no operator) -->
                                    <div class="query-row" data-row="0">
                                        <div class="row g-2 align-items-center mb-2">
                                            <div class="col-md-3">
                                                <select class="form-select field-select" name="field[]">
                                                    <option value="all">All fields</option>
                                                    <option value="name">Name</option>
                                                    <option value="position">Position</option>
                                                    <option value="phone">Phone</option>
                                                    <option value="email">Email</option>
                                                    <option value="department">Department</option>
                                                    <option value="gender">Gender</option>
                                                    <option value="date_of_birth">Date of Birth</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control search-value" name="value[]" placeholder="Enter search term...">
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-outline-danger btn-sm remove-row" style="display: none;">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Add Row and Search Buttons -->
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="addRow">
                                            <i class="bi bi-plus-circle"></i> Add row
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm ms-2" id="clearAll">
                                            <i class="bi bi-x-circle"></i> Clear all
                                        </button>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search"></i> Search
                                    </button>
                                </div>
                                
                                <!-- Hidden input for the constructed query -->
                                <input type="hidden" id="constructedQuery" name="search" value="{{ request('search') }}">
                                
                                <div class="form-text mt-3">
                                    <i class="bi bi-lightbulb"></i> 
                                    <strong>How to use:</strong> Select field type, enter search term, and use "Add row" to combine multiple conditions with AND/OR operators.
                                </div>
                            </div>
                        </form>
                        
                        <div class="filter-help">
                            <i class="bi bi-info-circle"></i>
                            <strong>Advanced Boolean Search Guide:</strong><br>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <strong>Boolean Operators:</strong><br>
                                    • <code>AND</code> - Both terms must exist<br>
                                    • <code>OR</code> - Either term can exist<br>
                                    • <code>NOT</code> - Exclude this term<br><br>
                                    <strong>Field-Specific Search:</strong><br>
                                    • <code>name:value</code> - Search in name only<br>
                                    • <code>position:value</code> - Search in position only<br>
                                    • <code>department:value</code> - Search in department<br>
                                    • <code>phone:value</code> - Search in phone number<br>
                                    • <code>email:value</code> - Search in email<br>
                                    • <code>gender:value</code> - Search in gender<br>
                                    • <code>date_of_birth:value</code> - Search in date of birth
                                </div>
                                <div class="col-md-6">
                                    <strong>Search Methods:</strong><br>
                                    <strong>1. Quick Search:</strong> Type above for instant AJAX results<br>
                                    <strong>2. Advanced Search:</strong> Use this form for complex queries with pagination<br><br>
                                    <strong>Tips:</strong><br>
                                    • Use quotes for exact phrases<br>
                                    • Combine operators for complex searches<br>
                                    • Field names: id, name, position, phone, email, department, gender, date_of_birth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teachers Card Grid -->
        <div class="dpsection">
            <div class="row flex-column flex-md-row">
                
                <div class="card-grid col-12 d-flex flex-wrap justify-content-start align-items-start gap-4" id="teacher-list">
                    @forelse ($teachers as $teacher)
                        <a href="{{ route('teachers.usershow', $teacher->id) }}" class="teacher-card text-decoration-none">
                            <div class="card h-100">
                                <div class="card-img-container">
                                    <img src="{{ $teacher->image ? asset($teacher->image) : asset('assets/img/default-teacher.png') }}"
                                         alt="{{ $teacher->name }}"
                                         class="card-img-top">
                                </div>
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $teacher->name }}</h5>
                                    <p class="card-text position-badge">{{ $teacher->position }}</p>
                                    <p class="card-text department">{{ $teacher->department->fullname ?? 'N/A' }}</p>
                                    @if($teacher->position == 'Professor' || $teacher->position == 'Professor/Head')
                                        <span class="badge badge-professor">Professor</span>
                                    @else
                                        <span class="badge badge-teacher">Teacher</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-12 text-center">
                            <p class="text-muted">No teachers found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Search Results Message -->
        <div id="search-message" class="text-center mt-3" style="display: none;"></div>

        <!-- No Results -->
        <div id="no-results" class="text-center py-4" style="display: none;">
            <i class="bi bi-search" style="font-size: 3rem; color: #6c757d;"></i>
            <h5 class="mt-3 text-muted">No teachers found</h5>
            <p class="text-muted">Try different keywords or use advanced search operators:</p>
            <div class="mt-3">
                <small class="text-muted">
                    <strong>Examples:</strong> 
                    <code>john AND computer</code>, 
                    <code>name:smith</code>, 
                    <code>department:engineering OR department:science</code>
                </small>
            </div>
            <button class="btn btn-outline-primary btn-sm mt-2" id="advancedBtnNoResults" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="bi bi-sliders"></i> Try Advanced Search
            </button>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4" id="pagination-container">
            {{ $teachers->links() }}
        </div>
    </main>

    <!-- Custom CSS for Enhanced Search -->
    <style>
        /* Enhanced Search Container Styling */
        .search-container {
            position: relative;
            max-width: 650px;
            margin: 0 auto;
        }

        .search-input-wrapper {
            position: relative;
            border-radius: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            background: white;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .search-input-wrapper:focus-within {
            box-shadow: 0 6px 25px rgba(0, 123, 255, 0.15);
            transform: translateY(-2px);
        }

        .search-container input {
            border: none;
            border-radius: 30px;
            padding: 15px 120px 15px 50px;
            font-size: 1.1rem;
            background: transparent;
            outline: none;
            width: 100%;
        }

        .search-icon {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        .search-input-wrapper:focus-within .search-icon {
            color: #007bff;
        }

        .advanced-btn {
            position: absolute;
            right: 58px;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            border-radius: 20px;
            padding: 8px 16px;
            color: white;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .advanced-btn:hover {
            background: linear-gradient(135deg, #0056b3, #004085);
            transform: translateY(-50%) scale(1.05);
        }

        .advanced-btn i {
            font-size: 0.8rem;
        }

        /* Bootstrap Modal Customization */
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            background: linear-gradient(135deg, #f8f9ff, #ffffff);
            border-bottom: 2px solid #f0f0f0;
            border-radius: 20px 20px 0 0;
            padding: 20px 30px;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-body {
            padding: 30px;
        }

        /* Visual Query Builder Styles */
        .query-row {
            margin-bottom: 10px;
        }
        
        .query-row .form-select,
        .query-row .form-control {
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        .query-row .form-select:focus,
        .query-row .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .operator-select {
            font-weight: 600;
            color: #495057;
        }
        
        .field-select {
            background-color: #f8f9fa;
        }
        
        .remove-row {
            transition: all 0.3s ease;
        }
        
        .remove-row:hover {
            transform: translateY(-1px);
        }

        .filter-help {
            background: #f8f9ff;
            border: 1px solid #e7e9ff;
            border-radius: 10px;
            padding: 15px;
            font-size: 0.9rem;
            color: #6c757d;
            text-align: center;
        }

        .filter-help i {
            color: #007bff;
            margin-right: 8px;
        }

        /* Modal Search Input Styling */
        #modalSearchInput {
            border-radius: 8px 0 0 8px;
            border-right: none;
            font-size: 1.1rem;
        }

        #modalSearchInput:focus {
            border-color: #007bff;
            box-shadow: none;
        }

        .modal-body .btn-primary {
            border-radius: 0 8px 8px 0;
            padding: 12px 20px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
        }

        .modal-body .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3, #004085);
            transform: translateY(-1px);
        }

        .filter_box .form-label {
            color: #495057;
            font-weight: 600;
        }

        /* Enhanced Card Styling */
        .teacher-card {
            width: 260px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            color: inherit;
        }

        .teacher-card:hover {
            text-decoration: none;
            color: inherit;
            transform: translateY(-8px);
        }

        .teacher-card .card {
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff 0%, #fff5f5 100%);
        }

        .teacher-card:hover .card {
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

        .teacher-card:hover .card-img-top {
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

        .position-badge {
            color: #e67e22;
            font-weight: 500;
        }

        .department {
            color: #6c757d;
            font-size: 0.85rem;
        }

        /* Badge Styling */
        .badge.badge-professor {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: #fff;
            font-size: 0.75rem;
            padding: 4px 12px;
            border-radius: 15px;
            font-weight: 500;
            margin-top: 8px;
        }

        .badge.badge-teacher {
            background: linear-gradient(135deg, #28a745, #20c997);
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
            .search-container input {
                padding: 12px 100px 12px 45px;
                font-size: 1rem;
            }
            
            .advanced-btn {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
            
            .filter-pill {
                padding: 6px 12px;
                font-size: 0.8rem;
                margin: 2px;
            }
            
            .modal-title {
                font-size: 1.3rem;
            }
            
            .teacher-card {
                width: 250px;
            }
            
            /* Hide sidebar filter box on mobile and show only modal */
            .filter_box {
                display: none;
            }
            
            /* Make teacher grid full width on mobile */
            .card-grid {
                width: 80% !important;
            }
        }

        @media (max-width: 576px) {
            .search-container {
                max-width: 100%;
            }
            
            .search-container input {
                padding: 10px 90px 10px 40px;
                font-size: 0.95rem;
            }
            
            .search-icon {
                right: 15px;
                font-size: 1rem;
            }
            
            .advanced-btn {
                padding: 5px 10px;
                font-size: 0.75rem;
                right: 55px;
            }
            
            .filter-pills-container {
                gap: 5px;
            }
            
            .filter-pill {
                padding: 5px 10px;
                font-size: 0.75rem;
            }
            
            .teacher-card {
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
        // Initialize activeFilters from URL parameters or default to 'all'
        @php
            $jsFilters = request('filter', ['all']);
            if (is_string($jsFilters)) {
                $jsFilters = explode(',', $jsFilters);
            }
        @endphp
        let urlFilters = '{{ implode(",", $jsFilters) }}';
        let activeFilters = urlFilters ? urlFilters.split(',') : ['all'];
        let searchTimeout;
        let originalTeachers = null;
        let rowCounter = 0;
        
        // Store original teachers HTML for reset
        if (!originalTeachers) {
            originalTeachers = $('#teacher-list').html();
        }

        // Visual query builder initialization
        updateRemoveButtons();
        constructQuery();

        // Check if page loaded with search parameters (form submission)
        const hasFormSearch = '{{ request("search") }}' !== '';
        if (hasFormSearch) {
            // Clear AJAX search box when showing form results to avoid confusion
            $('#searchteacher').val('');
            // Hide AJAX search elements when showing form results
            $('#search-message').hide();
            $('#no-results').hide();
            $('#pagination-container').show();
        }
        
        // Bootstrap Modal functionality
        $('#advancedBtn').on('click', function() {
            var modal = new bootstrap.Modal(document.getElementById('filterModal'));
            modal.show();
        });

        // Visual Query Builder Functions
        function updateRemoveButtons() {
            const rows = $('#queryBuilder .query-row');
            rows.each(function(index) {
                const removeBtn = $(this).find('.remove-row');
                if (index === 0 && rows.length === 1) {
                    removeBtn.hide();
                } else {
                    removeBtn.show();
                }
            });
        }

        function constructQuery() {
            const rows = $('#queryBuilder .query-row');
            let query = '';
            
            rows.each(function(index) {
                const operator = $(this).find('.operator-select').val() || '';
                const field = $(this).find('.field-select').val();
                const value = $(this).find('.search-value').val().trim();
                
                if (value) {
                    if (index > 0 && operator) {
                        query += ` ${operator} `;
                    }
                    
                    if (field === 'all') {
                        query += value;
                    } else {
                        query += `${field}:${value}`;
                    }
                }
            });
            
            $('#constructedQuery').val(query);
            return query;
        }

        // Add new query row
        $('#addRow').on('click', function() {
            rowCounter++;
            const newRow = `
                <div class="query-row" data-row="${rowCounter}">
                    <div class="row g-2 align-items-center mb-2">
                        <div class="col-md-2">
                            <select class="form-select operator-select" name="operator[]">
                                <option value="AND">AND</option>
                                <option value="OR">OR</option>
                                <option value="NOT">NOT</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select field-select" name="field[]">
                                <option value="all">All fields</option>
                                <option value="name">Name</option>
                                <option value="position">Position</option>
                                <option value="phone">Phone</option>
                                <option value="email">Email</option>
                                <option value="department">Department</option>
                                <option value="gender">Gender</option>
                                <option value="date_of_birth">Date of Birth</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control search-value" name="value[]" placeholder="Enter search term...">
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            $('#queryBuilder').append(newRow);
            updateRemoveButtons();
        });

        // Remove query row
        $(document).on('click', '.remove-row', function() {
            $(this).closest('.query-row').remove();
            updateRemoveButtons();
            constructQuery();
        });

        // Clear all rows
        $('#clearAll').on('click', function() {
            const firstRow = $('#queryBuilder .query-row[data-row="0"]');
            
            // Clear all rows except the first one
            $('#queryBuilder .query-row').not('[data-row="0"]').remove();
            
            // Reset first row
            firstRow.find('.field-select').val('all');
            firstRow.find('.search-value').val('');
            
            rowCounter = 0;
            updateRemoveButtons();
            constructQuery();
        });

        // Update query when inputs change
        $(document).on('input change', '.search-value, .field-select, .operator-select', function() {
            constructQuery();
        });

        // Query builder is already initialized above

        // Modal form submission handler
        $('#modalSearchForm').on('submit', function() {
            // Form will handle the submission with the constructed query
        });

        // AJAX Search input handler (primary search box)
        $('#searchteacher').on('keyup', function () {
            const searchQuery = $(this).val();

        
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Set new timeout for AJAX search
            searchTimeout = setTimeout(function() {
                if (searchQuery.length === 0) {
                    resetToOriginal();
                } else if (searchQuery.length >= 1) {
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
                url: '{{ route('teachers.search') }}',
                data: {
                    search: query
                },
                dataType: 'json',
                success: function (data) {
                    displaySearchResults(data, query);
                },
                error: function (xhr, status, error) {
                    $('#teacher-list').html('<div class="col-12 text-danger text-center">Something went wrong while searching.</div>');
                    $('#search-message').hide();
                }
            });
        }

        function displaySearchResults(data, query) {
            let cardsHtml = '';
            
            // Convert data to array if it's not already
            const teachers = Array.isArray(data) ? data : Object.values(data);
            
            if (teachers.length === 0) {
                $('#teacher-list').html('');
                $('#no-results').show();
                $('#search-message').hide();
                updateExportButton(false);
            } else {
                $.each(teachers, function (index, teacher) {
                    let baseUrl = '{{ asset('') }}';
                    let imageUrl = teacher.image ? baseUrl + teacher.image : baseUrl + 'assets/img/default-teacher.png';
                    let showUrl = '{{ url('/teachers') }}/' + teacher.id;
                    let departmentName = teacher.department?.fullname || 'N/A';
                    let badgeClass = '';
                    let badgeText = '';
                    
                    if (teacher.position === 'Professor' || teacher.position === 'Professor/Head') {
                        badgeClass = 'badge-professor';
                        badgeText = 'Professor';
                    } else {
                        badgeClass = 'badge-teacher';
                        badgeText = 'Teacher';
                    }

                    cardsHtml += `
                        <a href="${showUrl}" class="teacher-card text-decoration-none">
                            <div class="card h-100">
                                <div class="card-img-container">
                                    <img src="${imageUrl}"
                                         alt="${teacher.name}"
                                         class="card-img-top">
                                </div>
                                <div class="card-body text-center">
                                    <h5 class="card-title">${teacher.name}</h5>
                                    <p class="card-text position-badge">${teacher.position}</p>
                                    <p class="card-text department">${departmentName}</p>
                                    <span class="badge ${badgeClass}">${badgeText}</span>
                                </div>
                            </div>
                        </a>
                    `;
                });
                
                $('#teacher-list').html(cardsHtml);
                $('#no-results').hide();
                
                // Create filter text description
                let filterText = '';
                if (activeFilters.includes('all')) {
                    filterText = 'all fields';
                } else {
                    filterText = activeFilters.join(', ') + ' field' + (activeFilters.length > 1 ? 's' : '');
                }
                
                $('#search-message').show().html(`<p class="text-info"><i class="bi bi-search"></i> Found ${teachers.length} teacher(s) matching "${query}"</p>`);
                updateExportButton(true);
            }
        }

        function resetToOriginal() {
            $('#teacher-list').html(originalTeachers);
            $('#search-message').hide();
            $('#no-results').hide();
            $('#pagination-container').show();
            updateExportButton(false);
        }

        // Export functionality
        $('#exportPdfBtn').on('click', function() {
            let exportUrl = '{{ route("teachers.export.search.pdf") }}';
            let params = new URLSearchParams();
            
            // Get current search query from AJAX search
            let ajaxSearchQuery = $('#searchteacher').val();
            
            // Get search parameters from URL (for advanced search)
            let urlParams = new URLSearchParams(window.location.search);
            let urlSearchQuery = urlParams.get('search');
            let fieldParams = urlParams.getAll('field[]');
            let valueParams = urlParams.getAll('value[]');
            let operatorParams = urlParams.getAll('operator[]');
            
            // Use AJAX search query if available, otherwise use URL search query
            let searchQuery = ajaxSearchQuery || urlSearchQuery;
            if (searchQuery) {
                params.append('search', searchQuery);
            }
            
            // Add advanced search parameters if they exist
            if (fieldParams.length > 0) {
                fieldParams.forEach(field => params.append('field[]', field));
            }
            if (valueParams.length > 0) {
                valueParams.forEach(value => params.append('value[]', value));
            }
            if (operatorParams.length > 0) {
                operatorParams.forEach(operator => params.append('operator[]', operator));
            }
            
            // Get current filters
            let currentFilters = getCurrentFilters();
            if (currentFilters && currentFilters.length > 0) {
                params.append('filter', currentFilters.join(','));
            }
            
            // Add department filter if applicable
            let departmentId = urlParams.get('department_id') || getCurrentDepartmentId();
            if (departmentId) {
                params.append('department_id', departmentId);
            }
            
            // Open PDF in new window
            if (params.toString()) {
                window.open(exportUrl + '?' + params.toString(), '_blank');
            } else {
                window.open(exportUrl, '_blank');
            }
        });

        // Helper function to get current filters
        function getCurrentFilters() {
            // Return the current active filters
            // This depends on how you're tracking filters in your implementation
            return ['all']; // Default to 'all' if no specific filters are set
        }

        // Helper function to get current department ID
        function getCurrentDepartmentId() {
            // Return current department ID if filtering by department
            return null; // Return null if no department filter is active
        }

        // Show/hide export button based on search
        function updateExportButton(hasResults) {
            if (hasResults) {
                $('#exportPdfBtn').show();
            } else {
                $('#exportPdfBtn').hide();
            }
        }
    });
</script>
