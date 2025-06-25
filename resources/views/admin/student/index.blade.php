<x-adminlayout>
    <div class="container my-5">
        <h1 class="text-center cmn-header">Students</h1>

        <div class="searchbox my-5">
            <input type="text" id="searchstudent" class="searchinput" placeholder="Search for a student">
        </div>

        <div class="dp-control my-5">
            <a href="{{ route('students.create') }}" class="addbtn">Add Student</a>
        </div>

        <div class="dpboxes my-5" id="student-list">
            @forelse ($students as $student)
                <div class="dpbox">
                    <div class="dplogobox">
                        <img src="{{ asset($student->image) }}" alt="{{ $student->name }}">
                    </div>
                    <h4 class="dp-name">{{ $student->name }}</h4>
                    <div class="dpcontent">
                        
                        <p>Year: {{ $student->year }}</p>
                        <p>Seat No: {{ $student->seat_number }}</p>
                        <p>Department: {{ $student->department->fullname ?? 'N/A' }}</p>
                        
                    </div>
                    <a href="{{ route('students.show', $student->id) }}" class="dp-btn">View More</a>
                </div>
            @empty
                <h4 class="empty-text">No Student Have Create Student </h4>
            @endforelse
        </div>

        <div>
            {{ $students->links() }}
        </div>
    </div>
</x-adminlayout>

<script>
    $(document).ready(function() {
        $('#searchstudent').on('keyup', function () {
            var searchQuery = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('students.search') }}',
                data: { search: searchQuery },
                dataType: 'json',
                success: function (data) {
                    $('#student-list').html('');
                    $.each(data, function (index, student) {
                        var showUrl = '/admin/students/edit/' + student.id;
                        var imageUrl = '/' + student.image;

                        var studentHtml = `
                            <div class="dpbox">
                                <div class="dplogobox">
                                    <img src="${imageUrl}" alt="${student.name}">
                                </div>
                                <div class="dpcontent">
                                    <h4 class="dp-name">${student.name}</h4>
                                    <p>Year: ${student.year}</p>
                                    <p>Seat No: ${student.seat_number}</p>
                                    <p>Department: ${student.department?.fullname ?? 'N/A'}</p>
                                    <a href="${showUrl}" class="dp-btn">View More</a>
                                </div>
                            </div>
                        `;

                        $('#student-list').append(studentHtml);
                    });
                }
            });
        });
    });
</script>
