<x-adminlayout>
    <main class="container my-5">
    
        <!-- Page Title -->
        <div class="page-title text-center">
            <div class="container position-relative">
                <h1 class="mt-5">Students ({{ $students->count() }})</h1>
            </div>
        </div>
        <!-- End Page Title -->
    
        <!-- Search Box -->
        <div class="my-4">
            <input type="text" id="searchstudent" class="form-control" placeholder="Search Students">
        </div>
    
        <!-- Add Student Button -->
        <div class="mb-4 text-end">
            <a href="{{ route('students.create') }}" class="btn btn-success">
                <i class="fa-solid fa-plus"></i> Add Student
            </a>
        </div>
    
        <!-- Students Table -->
        <div class="table-responsive" id="student-list">
            <table class="table table-hover table-bordered border-1 table-primary">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Year</th>
                        <th>Roll Number</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Department</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->year }}</td>
                            <td>{{ $student->seat_number }}</td>
                            <td>
                                @if($student->email)
                                    <a href="mailto:{{ $student->email }}">{{ $student->email }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($student->phone_number)
                                    <a href="tel:{{ $student->phone_number }}">{{ $student->phone_number }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $student->department->fullname ?? 'N/A' }}</td>
                            <td class="text-center">
                                @if($student->email)
                                    <a href="mailto:{{ $student->email }}" class="btn btn-outline-primary btn-sm me-1" title="Send Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                @endif
                                @if($student->phone_number)
                                    <a href="tel:{{ $student->phone_number }}" class="btn btn-outline-success btn-sm me-1" title="Call">
                                        <i class="fas fa-phone"></i>
                                    </a>
                                @endif
                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-outline-info btn-sm me-1" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a href="{{ route('students.show', $student->id) }}" class="btn btn-primary btn-sm" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No Students Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    
        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $students->links() }}
        </div>
    
    </main>
    
    
</x-adminlayout>

<script>
    $(document).ready(function () {
        $('#searchstudent').on('keyup', function () {
            var searchQuery = $(this).val();

            $.ajax({
                type: 'GET',
                url: '{{ route('students.search') }}',
                data: { search: searchQuery },
                dataType: 'json',
                success: function (data) {
                    let tableHtml = `
                        <table class="table table-hover table-bordered border-1 table-primary">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Year</th>
                                    <th>Roll Number</th>
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
                                <td colspan="8" class="text-center">No students found.</td>
                            </tr>
                        `;
                    } else {
                        $.each(data, function (index, student) {
                            let editUrl = '/admin/students/edit/' + student.id;
                            let showUrl = '/admin/students/' + student.id;
                            let departmentName = student.department?.fullname || 'N/A';

                            tableHtml += `
                                <tr>
                                    <td>${student.id}</td>
                                    <td>${student.name}</td>
                                    <td>${student.year}</td>
                                    <td>${student.seat_number}</td>
                                    <td>${student.email ? `<a href="mailto:${student.email}">${student.email}</a>` : '<span class="text-muted">N/A</span>'}</td>
                                    <td>${student.phone_number ? `<a href="tel:${student.phone_number}">${student.phone_number}</a>` : '<span class="text-muted">N/A</span>'}</td>
                                    <td>${departmentName}</td>
                                    <td class="text-center">
                                        ${student.email ? `<a href="mailto:${student.email}" class="btn btn-outline-primary btn-sm me-1" title="Send Email"><i class="fas fa-envelope"></i></a>` : ''}
                                        ${student.phone_number ? `<a href="tel:${student.phone_number}" class="btn btn-outline-success btn-sm me-1" title="Call"><i class="fas fa-phone"></i></a>` : ''}
                                        <a href="${editUrl}" class="btn btn-outline-info btn-sm me-1" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="${showUrl}" class="btn btn-primary btn-sm" title="View"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            `;
                        });
                    }

                    tableHtml += '</tbody></table>';
                    $('#student-list').html(tableHtml);
                },
                error: function () {
                    $('#student-list').html('<p class="text-danger">Something went wrong while searching.</p>');
                }
            });
        });
    });
</script>

