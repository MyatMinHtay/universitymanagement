<x-adminlayout>
<main class="main mt-5">
    <!-- Page Title -->

     
    <div class="page-title text-center">

        
        
        <div class="container position-relative">

            <div class="my-4 col-12 d-flex justify-content-start align-items-center">
                <img class="rounded-circle shadow me-5" src="{{ asset($department->logo) }}" width="100" height="100" alt="Logo">
                <h1 class="mt-3">Department of {{ $department->fullname }}</h1>
            </div>
           
            <img class="img-fluid" src="{{ asset($department->banner) }}" alt="{{ $department->fullname }}">
            
        </div>
    </div>
    <!-- End Page Title -->

    <div class="container my-5">

        <!-- Teacher Search -->
        <div class="row mb-4">
            <div class="col-lg-12 mx-auto">
                <input type="text" id="searchtr" class="form-control" placeholder="Search Teachers">
            </div>
        </div>

        <!-- Teacher Table -->
        <div class="dpsection mb-5">
            <h3 class="section-title">Teachers in {{ $department->fullname }} ({{ $department->teachers->count() }} Teachers)</h3>
            <div class="table-responsive mt-3" id="teacher-list">
                <table class="table table-hover table-bordered border-1 table-primary">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Image</th>
                            <th scope="col">Name</th>
                            <th scope="col">Position</th>
                            <th scope="col">Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($department->teachers as $teacher)
                            <tr>
                                <td>{{ $teacher->id }}</td>
                                <td><img src="{{ asset($teacher->image) }}" alt="{{ $teacher->name }}" width="60" height="auto"></td>
                                <td>{{ $teacher->name }}</td>
                                <td>{{ $teacher->position }}</td>
                                <td>{{ $teacher->phone_number }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">No teachers found in this department.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Student Search -->
        <div class="row mb-4">
            <div class="col-lg-12 mx-auto">
                <input type="text" id="searchst" class="form-control" placeholder="Search Students">
            </div>
        </div>

        <!-- Student Table -->
        <div class="dpsection">
            <h3 class="section-title">Students in {{ $department->fullname }} ({{ $department->students->count() }} Students)</h3>
            <div class="table-responsive mt-3" id="student-list">
                <table class="table table-hover table-bordered border-1 table-primary">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Image</th>
                            <th scope="col">Name</th>
                            <th scope="col">Year</th>
                            <th scope="col">Roll Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($department->students as $student)
                            <tr>
                                <td>{{ $student->id }}</td>
                                <td><img src="{{ asset($student->image) }}" alt="{{ $student->name }}" width="60" height="auto"></td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->year }}</td>
                                <td>{{ $student->roll_number }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">No students found in this department.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
</x-adminlayout>


<script>
    $(document).ready(function () {
        const departmentId = {{ $department->id ?? 'null' }};

        // Teacher search
        $('#searchtr').on('keyup', function () {
            var searchQuery = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('teachers.search') }}',
                data: { search: searchQuery, department_id: departmentId },
                dataType: 'json',
                success: function (data) {
                    var html = `
                        <table class="table table-hover table-bordered border-1 table-primary">
                            <thead>
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Position</th>
                                    <th scope="col">Phone</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    if (data.length === 0) {
                        html += `<tr><td colspan="5" class="text-center">No teachers found.</td></tr>`;
                    } else {
                        $.each(data, function (index, teacher) {
                            var imageUrl = '/' + teacher.image;
                            html += `
                                <tr>
                                    <td>${teacher.id}</td>
                                    <td><img src="${imageUrl}" width="60" height="auto" alt="${teacher.name}"></td>
                                    <td>${teacher.name}</td>
                                    <td>${teacher.position}</td>
                                    <td>${teacher.phone_number}</td>
                                </tr>
                            `;
                        });
                    }

                    html += `</tbody></table>`;
                    $('#teacher-list').html(html);
                }
            });
        });

        // Student search
        $('#searchst').on('keyup', function () {
            var searchQuery = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('students.search') }}',
                data: { search: searchQuery, department_id: departmentId },
                dataType: 'json',
                success: function (data) {
                    var html = `
                        <table class="table table-hover table-bordered border-1 table-primary">
                            <thead>
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Year</th>
                                    <th scope="col">Roll Number</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    if (data.length === 0) {
                        html += `<tr><td colspan="5" class="text-center">No students found.</td></tr>`;
                    } else {
                        $.each(data, function (index, student) {
                            var imageUrl = '/' + student.image;
                            html += `
                                <tr>
                                    <td>${student.id}</td>
                                    <td><img src="${imageUrl}" width="60" height="auto" alt="${student.name}"></td>
                                    <td>${student.name}</td>
                                    <td>${student.year}</td>
                                    <td>${student.roll_number}</td>
                                </tr>
                            `;
                        });
                    }

                    html += `</tbody></table>`;
                    $('#student-list').html(html);
                }
            });
        });
    });
</script>



