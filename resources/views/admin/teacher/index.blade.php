<x-adminlayout>
    <div class="container my-5">
        <h1 class="text-center cmn-header">Teachers</h1>

        <div class="searchbox my-5">
            <input type="text" id="searchteacher" class="searchinput" placeholder="Search for a teacher">
        </div>

        <div class="dp-control my-5">
            <a href="{{ route('teachers.create') }}" class="addbtn">Add Teacher</a>
        </div>

        <div class="table-responsive" id="teacher-list">
            <table class="table table-hover table-bordered border-1 table-primary">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Position</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Department</th>
                        <th scope="col">Edit</th>
                        <th scope="col">View</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->id }}</td>
                            <td>
                                <img src="{{ asset($teacher->image) }}" alt="{{ $teacher->name }}" style="width: 60px; height: auto;">
                            </td>
                            <td>{{ $teacher->name }}</td>
                            <td>{{ $teacher->position }}</td>
                            <td>{{ $teacher->phone_number }}</td>
                            <td>{{ $teacher->department->fullname ?? 'N/A' }}</td>
                            <td class="text-center">
                                <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-info">
                                    <i class="fa-solid fa-pencil"></i> Edit
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('teachers.show', $teacher->id) }}" class="btn btn-info">
                                    <i class="fa-solid fa-eye"></i> View
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
    </div>
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
                    var tableHtml = `
                        <table class="table table-hover table-bordered border-1 table-primary">
                            <thead>
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Position</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">Edit</th>
                                    <th scope="col">View</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    if (data.length === 0) {
                        tableHtml += `
                            <tr>
                                <td colspan="7" class="text-center">No teachers found.</td>
                            </tr>
                        `;
                    } else {
                        $.each(data, function (index, teacher) {
                            var editUrl = '/admin/teachers/edit/' + teacher.id;
                            var showUrl = '/admin/teachers/' + teacher.id;
                            var imageUrl = '/' + teacher.image;
                            var department = teacher.department?.fullname || 'N/A';

                            tableHtml += `
                                <tr>
                                    <td>${teacher.id}</td>
                                    <td><img src="${imageUrl}" alt="${teacher.name}" style="width: 60px; height: auto;"></td>
                                    <td>${teacher.name}</td>
                                    <td>${teacher.position}</td>
                                    <td>${teacher.phone_number}</td>
                                    <td>${department}</td>
                                    <td class="text-center">
                                        <a href="${editUrl}" class="btn btn-info">
                                            <i class="fa-solid fa-pencil"></i> Edit
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="${showUrl}" class="btn btn-info">
                                            <i class="fa-solid fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            `;
                        });
                    }

                    tableHtml += `</tbody></table>`;
                    $('#teacher-list').html(tableHtml);
                }
            });
        });
    });
</script>

