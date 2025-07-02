<x-layout>
    <main class="container my-5">
    
        <!-- Page Title -->
        <div class="page-title text-center">
            <div class="container position-relative">
                <h1 class="mt-5">Faculty Members ({{ $faculty->count() }})</h1>
            </div>
        </div>
        <!-- End Page Title -->
    
        <!-- Search Box -->
        <div class="my-4">
            <input type="text" id="searchfaculty" class="form-control" placeholder="Search Faculty Members">
        </div>
    
        <!-- Faculty Table -->
        <div class="table-responsive" id="faculty-list">
            <table class="table table-hover table-bordered border-1 table-primary">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Department</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($faculty as $member)
                        <tr>
                            <td>{{ $member->id }}</td>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->position }}</td>
                            <td>
                                @if($member->email)
                                    <a href="mailto:{{ $member->email }}">{{ $member->email }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($member->phone_number)
                                    <a href="tel:{{ $member->phone_number }}">{{ $member->phone_number }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $member->department->fullname ?? 'N/A' }}</td>
                            <td class="text-center">
                                @if($member->email)
                                    <a href="mailto:{{ $member->email }}" class="btn btn-outline-primary btn-sm me-1" title="Send Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                @endif
                                @if($member->phone_number)
                                    <a href="tel:{{ $member->phone_number }}" class="btn btn-outline-success btn-sm me-1" title="Call">
                                        <i class="fas fa-phone"></i>
                                    </a>
                                @endif
                                @if($member->department)
                                    <a href="{{ route('user.departments.show', $member->department->id) }}" class="btn btn-outline-info btn-sm me-1" title="View Department">
                                        <i class="fas fa-building"></i>
                                    </a>
                                @else
                                    <button class="btn btn-outline-secondary btn-sm me-1" disabled title="No Department">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                @endif
                                <a href="{{ route('faculty.usershow', $member->id) }}" class="btn btn-primary btn-sm" title="View Faculty">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No Faculty Members Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    
        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $faculty->links() }}
        </div>
    
    </main>
    
    <script>
        $(document).ready(function () {
            $('#searchfaculty').on('keyup', function () {
                var searchQuery = $(this).val();
    
                $.ajax({
                    type: 'GET',
                    url: '{{ route('faculty.search') }}',
                    data: { search: searchQuery },
                    dataType: 'json',
                    success: function (data) {
                        let tableHtml = `
                            <table class="table table-hover table-bordered border-1 table-primary">
                                <thead>
                                    <tr>
                                        <th>ID</th>
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
                                    <td colspan="6" class="text-center">No faculty members found.</td>
                                </tr>
                            `;
                        } else {
                            $.each(data, function (index, faculty) {
                                let showUrl = `/faculty/${faculty.id}`;
                                let departmentName = faculty.department?.fullname || 'N/A';
                                let departmentBtn = faculty.department
                                    ? `<a href="/departments/${faculty.department.id}" class="btn btn-outline-info btn-sm me-1" title="View Department">
                                            <i class="fas fa-building"></i>
                                        </a>`
                                    : `<button class="btn btn-outline-secondary btn-sm me-1" disabled title="No Department">
                                            <i class="fas fa-ban"></i>
                                        </button>`;
    
                                tableHtml += `
                                    <tr>
                                        <td>${faculty.id}</td>
                                        <td>${faculty.name}</td>
                                        <td>${faculty.position}</td>
                                        <td>${faculty.email ? `<a href="mailto:${faculty.email}">${faculty.email}</a>` : '<span class="text-muted">N/A</span>'}</td>
                                        <td>${faculty.phone_number ? `<a href="tel:${faculty.phone_number}">${faculty.phone_number}</a>` : '<span class="text-muted">N/A</span>'}</td>
                                        <td>${departmentName}</td>
                                        <td class="text-center">
                                            ${faculty.email ? `<a href="mailto:${faculty.email}" class="btn btn-outline-primary btn-sm me-1" title="Send Email"><i class="fas fa-envelope"></i></a>` : ''}
                                            ${faculty.phone_number ? `<a href="tel:${faculty.phone_number}" class="btn btn-outline-success btn-sm me-1" title="Call"><i class="fas fa-phone"></i></a>` : ''}
                                            ${departmentBtn}
                                            <a href="${showUrl}" class="btn btn-primary btn-sm" title="View Faculty"><i class="fas fa-eye"></i></a>
                                        </td>
                                    </tr>`;
                            });
                        }
    
                        tableHtml += '</tbody></table>';
                        $('#faculty-list').html(tableHtml);
                    },
                    error: function () {
                        $('#faculty-list').html('<p class="text-danger">Something went wrong while searching.</p>');
                    }
                });
            });
        });
    </script>
    </x-layout>
    