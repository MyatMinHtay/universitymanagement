<x-adminlayout>
    <div class="container my-5">
        <h1 class="text-center cmn-header">Students</h1>

        <div class="searchbox my-5">
            <input type="text" id="searchstudent" class="searchinput" placeholder="Search for a student">
        </div>

        <div class="dp-control my-5">
            <a href="{{ route('students.create') }}" class="addbtn">Add Student</a>
        </div>

        <div class="table-responsive" id="student-list">
            <table class="table table-hover table-bordered border-1 table-primary">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Year</th>
                        <th scope="col">Roll Number</th>
                        <th scope="col">Department</th>
                        <th scope="col">Edit</th>
                        <th scope="col">View</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>
                                <img src="{{ asset($student->image) }}" alt="{{ $student->name }}" style="width: 60px; height: auto;">
                            </td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->year }}</td>
                            <td>{{ $student->seat_number }}</td>
                            <td>{{ $student->department->fullname ?? 'N/A' }}</td>
                            <td class="text-center">
                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-info">
                                    <i class="fa-solid fa-eye"></i> Edit
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('students.show', $student->id) }}" class="btn btn-info">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $students->links() }}
        </div>
    </div>
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
                    var tableHtml = `
                        <table class="table table-hover table-bordered border-1 table-primary">
                            <thead>
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Year</th>
                                    <th scope="col">Roll Number</th>
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
                                <td colspan="7" class="text-center">No students found.</td>
                            </tr>
                        `;
                    } else {
                        $.each(data, function (index, student) {
                            var editUrl = '/admin/students/edit/' + student.id;
                            var showUrl = '/admin/students/' + student.id;
                            var imageUrl = '/' + student.image;
                            var departmentName = student.department?.fullname || 'N/A';

                            tableHtml += `
                                <tr>
                                    <td>${student.id}</td>
                                    <td><img src="${imageUrl}" alt="${student.name}" style="width: 60px; height: auto;"></td>
                                    <td>${student.name}</td>
                                    <td>${student.year}</td>
                                    <td>${student.seat_number}</td>
                                    <td>${departmentName}</td>
                                    <td class="text-center">
                                        <a href="${editUrl}" class="btn btn-info">
                                            <i class="fa-solid fa-eye"></i> Edit
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
                    $('#student-list').html(tableHtml);
                }
            });
        });
    });
</script>

