<x-adminlayout>
    <div class="container my-5">
        <h1 class="text-center cmn-header">Teachers</h1>

        <div class="searchbox my-5">
            <input type="text" id="searchteacher" class="searchinput" placeholder="Search for a teacher">
        </div>

        <div class="dp-control my-5">
            <a href="{{ route('teachers.create') }}" class="addbtn">Add Teacher</a>
        </div>

        <div class="dpboxes my-5" id="teacher-list">
            @forelse ($teachers as $teacher)
                <div class="dpbox">
                    <div class="dplogobox">
                        <img src="{{ asset($teacher->image) }}" alt="{{ $teacher->name }}">
                    </div>
                    <h4 class="dp-name">{{ $teacher->name }}</h4>
                    <div class="dpcontent">
                        
                        <p class="dp-content-text">Position: {{ $teacher->position }}</p>
                        <p class="dp-content-text">Phone: {{ $teacher->phone_number }}</p>
                        <p class="dp-content-text">Department: {{ $teacher->department->fullname ?? 'N/A' }}</p>
                        
                    </div>

                    <a href="{{ route('teachers.show', $teacher->id) }}" class="dp-btn">View More</a>
                </div>
            @empty
                <h4 class="empty-text">No Teachers Found</h4>
            @endforelse
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
                    $('#teacher-list').html('');
                    $.each(data, function (index, teacher) {
                        var showUrl = '/admin/teachers/edit/' + teacher.id;
                        var imageUrl = '/' + teacher.image;

                        var html = `
                            <div class="dpbox">
                                <div class="dplogobox">
                                    <img src="${imageUrl}" alt="${teacher.name}">
                                </div>
                                <div class="dpcontent">
                                    <h4 class="dp-name">${teacher.name}</h4>
                                    <p>Position: ${teacher.position}</p>
                                    <p>Phone: ${teacher.phone_number}</p>
                                    <p>Department: ${teacher.department?.fullname ?? 'N/A'}</p>
                                    <a href="${showUrl}" class="dp-btn">View More</a>
                                </div>
                            </div>
                        `;

                        $('#teacher-list').append(html);
                    });
                }
            });
        });
    });
</script>
