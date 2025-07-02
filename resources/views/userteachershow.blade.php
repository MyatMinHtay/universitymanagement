<x-layout>
    <main class="container my-5">
    
        <!-- Page Title -->
        <div class="page-title text-center">
            <div class="container position-relative">
                <h1 class="mt-5">Teachers ({{ $teachers->count() }})</h1>
            </div>
        </div>
        <!-- End Page Title -->
    
        <!-- Search Box -->
        <div class="my-4">
            <input type="text" id="searchteacher" class="form-control" placeholder="Search Teachers">
        </div>
    
        <!-- Teachers Table -->
        <div class="table-responsive" id="teacher-list">
            <table class="table table-hover table-bordered border-1 table-primary">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Department</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->name }}</td>
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
                                @if($teacher->email)
                                    <a href="mailto:{{ $teacher->email }}" class="btn btn-outline-primary btn-sm me-1" title="Send Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                @endif
                                @if($teacher->phone_number)
                                    <a href="tel:{{ $teacher->phone_number }}" class="btn btn-outline-success btn-sm me-1" title="Call">
                                        <i class="fas fa-phone"></i>
                                    </a>
                                @endif
                                @if($teacher->department)
                                    <a href="{{ route('user.departments.show', $teacher->department->id) }}" class="btn btn-outline-info btn-sm me-1" title="View Department">
                                        <i class="fas fa-building"></i>
                                    </a>
                                @else
                                    <button class="btn btn-outline-secondary btn-sm me-1" disabled title="No Department">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                @endif
                                <a href="{{ route('teachers.usershow', $teacher->id) }}" class="btn btn-primary btn-sm" title="View Teacher">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No Teachers Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    
        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $teachers->links() }}
        </div>
    
    </main>
    
   
</x-layout>

<script>
    $(document).ready(function () {
        $('#searchteacher').on('keyup', function () {
            var searchQuery = $(this).val();

            $.ajax({
                type: 'GET',
                url: '{{ route('teachers.search') }}',
                data: { search: searchQuery },
                dataType: 'json',
                success: function (data) {
                    let tableHtml = `
                        <table class="table table-hover table-bordered border-1 table-primary">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Department</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    if (data.length === 0) {
                        tableHtml += `
                            <tr>
                                <td colspan="6" class="text-center">No teachers found.</td>
                            </tr>
                        `;
                    } else {
                        $.each(data, function (index, teacher) {
                            let showUrl = `/teachers/${teacher.id}`;
                            let departmentName = teacher.department?.fullname || 'N/A';
                            let departmentBtn = teacher.department
                                ? `<a href="/departments/${teacher.department.id}" class="btn btn-outline-info btn-sm me-1" title="View Department">
                                        <i class="fas fa-building"></i>
                                    </a>`
                                : `<button class="btn btn-outline-secondary btn-sm me-1" disabled title="No Department">
                                        <i class="fas fa-ban"></i>
                                    </button>`;

                            tableHtml += `
                                <tr>
                                    <td>${teacher.id}</td>
                                    <td>${teacher.name}</td>
                                    <td>${teacher.position}</td>
                                    <td>${teacher.email ? `<a href="mailto:${teacher.email}">${teacher.email}</a>` : '<span class="text-muted">N/A</span>'}</td>
                                    <td>${teacher.phone_number ? `<a href="tel:${teacher.phone_number}">${teacher.phone_number}</a>` : '<span class="text-muted">N/A</span>'}</td>
                                    <td>${departmentName}</td>
                                    <td class="text-center">
                                        ${teacher.email ? `<a href="mailto:${teacher.email}" class="btn btn-outline-primary btn-sm me-1" title="Send Email"><i class="fas fa-envelope"></i></a>` : ''}
                                        ${teacher.phone_number ? `<a href="tel:${teacher.phone_number}" class="btn btn-outline-success btn-sm me-1" title="Call"><i class="fas fa-phone"></i></a>` : ''}
                                        ${departmentBtn}
                                        <a href="${showUrl}" class="btn btn-primary btn-sm" title="View Teacher"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>`;
                        });
                    }

                    tableHtml += '</tbody></table>';
                    $('#teacher-list').html(tableHtml);
                },
                error: function () {
                    $('#teacher-list').html('<p class="text-danger">Something went wrong while searching.</p>');
                }
            });
        });
    });
</script>
  