<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Departments Search Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .search-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }
        .search-info h3 {
            margin: 0 0 10px 0;
            color: #495057;
        }
        .info-row {
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            color: #495057;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .no-results {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Departments Search Results</h1>
        <p>University Management System</p>
    </div>
    
    <div class="search-info">
        <h3>Search Information</h3>
        <div class="info-row">
            <span class="info-label">Search Type:</span> {{ $searchType }}
        </div>
        <div class="info-row">
            <span class="info-label">Search Query:</span> {{ $searchTerm }}
        </div>
        <div class="info-row">
            <span class="info-label">Active Filters:</span> {{ implode(', ', $filters) }}
        </div>
        <div class="info-row">
            <span class="info-label">Total Results:</span> {{ $totalResults }} department(s)
        </div>
        <div class="info-row">
            <span class="info-label">Export Date:</span> {{ $exportDate }}
        </div>
    </div>

    @if($departments->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Department Name</th>
                    <th>Department Code</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departments as $department)
                    <tr>
                        <td>{{ $department->id }}</td>
                        <td>{{ $department->fullname }}</td>
                        <td>{{ $department->deptCode ?? 'N/A' }}</td>
                        <td>{{ $department->created_at ? \Carbon\Carbon::parse($department->created_at)->format('Y-m-d') : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-results">
            <h3>No Results Found</h3>
            <p>No departments match your search criteria.</p>
        </div>
    @endif
    
    <div class="footer">
        <p>Generated on {{ $exportDate }} | University Management System</p>
    </div>
</body>
</html>