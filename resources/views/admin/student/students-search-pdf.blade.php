<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Search Results</title>
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
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Student Search Results</h1>
        <p>University Management System</p>
    </div>
    
    <div class="search-info">
        <h3>Search Information</h3>
        <p><strong>Search Type:</strong> {{ $searchQuery ? 'Filtered Search' : 'All Students' }}</p>
        @if($searchQuery)
            <p><strong>Search Query:</strong> {{ $searchQuery }}</p>
        @endif
        <p><strong>Total Results:</strong> {{ $studentcounts }}</p>
        <p><strong>Export Date:</strong> {{ $exportDate }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Roll Number</th>
                <th>Year</th>
                <th>Department</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Gender</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr>
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->roll_number }}</td>
                    <td>{{ $student->year }}</td>
                    <td>{{ $student->department->shortname ?? 'N/A' }}</td>
                    <td>{{ $student->email ?? 'N/A' }}</td>
                    <td>{{ $student->phone_number ?? 'N/A' }}</td>
                    <td>{{ ucfirst($student->gender ?? 'N/A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No students found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #666;">
        <p>Generated on {{ $exportDate }} | University Management System</p>
    </div>
</body>
</html>
