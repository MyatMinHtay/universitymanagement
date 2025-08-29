<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Faculty Search Results</title>
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
        <h1>Faculty Search Results</h1>
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
            <span class="info-label">Total Results:</span> {{ $totalResults }} faculty member(s)
        </div>
        <div class="info-row">
            <span class="info-label">Export Date:</span> {{ $exportDate }}
        </div>
    </div>

    @if($faculty->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Phone</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach($faculty as $member)
                    <tr>
                        <td>{{ $member->id }}</td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->position }}</td>
                        <td>{{ $member->department ?? 'N/A' }}</td>
                        <td>{{ $member->phone_number ?? 'N/A' }}</td>
                        <td>{{ $member->email ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-results">
            <h3>No Results Found</h3>
            <p>No faculty members match your search criteria.</p>
        </div>
    @endif
    
    <div class="footer">
        <p>Generated on {{ $exportDate }} | University Management System</p>
    </div>
</body>
</html>