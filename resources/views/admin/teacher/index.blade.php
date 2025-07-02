<x-adminlayout>
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
    
        <!-- Add Teacher Button -->
        <div class="mb-4 text-end">
            <a href="{{ route('teachers.create') }}" class="btn btn-success">
                <i class="fa-solid fa-plus"></i> Add Teacher
            </a>
        </div>
    
        <!-- Teachers Table -->
        <div class="table-responsive" id="teacher-list">
            <table class="table table-hover table-bordered border-1 table-primary">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
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
                            <td colspan="7" class="text-center">No Teachers Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    
    </main>
    
   
</x-adminlayout>

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
                                    <th class="text-center">Actions</th>
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
                            let editUrl = '/admin/teachers/edit/' + teacher.id;
                            let showUrl = '/admin/teachers/' + teacher.id;
                            let departmentName = teacher.department?.fullname || 'N/A';

                            tableHtml += `
                                <tr>
                                    <td>${teacher.name}</td>
                                    <td>${teacher.position}</td>
                                    <td>${teacher.email ? `<a href="mailto:${teacher.email}">${teacher.email}</a>` : '<span class="text-muted">N/A</span>'}</td>
                                    <td>${teacher.phone_number ? `<a href="tel:${teacher.phone_number}">${teacher.phone_number}</a>` : '<span class="text-muted">N/A</span>'}</td>
                                    <td>${departmentName}</td>
                                    <td class="text-center">
                                        ${teacher.email ? `<a href="mailto:${teacher.email}" class="btn btn-outline-primary btn-sm me-1" title="Send Email"><i class="fas fa-envelope"></i></a>` : ''}
                                        ${teacher.phone_number ? `<a href="tel:${teacher.phone_number}" class="btn btn-outline-success btn-sm me-1" title="Call"><i class="fas fa-phone"></i></a>` : ''}
                                        <a href="${editUrl}" class="btn btn-outline-info btn-sm me-1" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="${showUrl}" class="btn btn-primary btn-sm" title="View"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            `;
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

