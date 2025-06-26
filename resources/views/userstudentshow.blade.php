<x-layout>
    <main>
        <!-- Page Title -->
        <div class="page-title">
            <div class="container position-relative">
                <h1 class="mt-5">All Students ({{ $students->count() }})</h1>
            </div>
        </div>
        <!-- End Page Title -->

        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-12 mx-auto">
                    <div class="search-container" data-aos="fade-up" data-aos-delay="200">
                        <div class="input-group">
                            <input type="text" id="searchstudent" class="form-control" placeholder="Search Students">
                        </div>
                    </div>
                </div>
            </div>

            <div class="dpsection">
                <h3 class="section-title">Students</h3>
                <div class="card-grid" id="student-list">
                    @forelse ($students as $student)
                        <a href="{{ route('students.show', $student->id) }}" class="info-card">
                            <img src="{{ asset($student->image) }}" alt="{{ $student->name }}">
                            <h4>{{ $student->name }}</h4>
                            <p>Year: {{ $student->year }}</p>
                            <p>Seat No: {{ $student->seat_number }}</p>
                            
                        </a>
                    @empty
                        <p>No students found.</p>
                    @endforelse
                </div>

                <div>
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </main>
</x-layout>

<script>
    $(document).ready(function () {
        $('#searchstudent').on('keyup', function () {
            let searchQuery = $(this).val();

            $.ajax({
                type: 'GET',
                url: '{{ route('students.search') }}',
                data: { search: searchQuery },
                dataType: 'json',
                success: function (data) {
                    let cardsHtml = '';

                    if (data.length === 0) {
                        cardsHtml = '<p class="text-center w-100">No students found.</p>';
                    } else {
                        $.each(data, function (index, student) {
                            let showUrl = `/students/${student.id}`;
                            let imageUrl = `/${student.image}`;

                            cardsHtml += `
                                <a href="${showUrl}" class="info-card">
                                    <img src="${imageUrl}" alt="${student.name}">
                                    <h4>${student.name}</h4>
                                    <p>Year: ${student.year}</p>
                                    <p>Seat No: ${student.seat_number}</p>
                                    
                                </a>
                            `;
                        });
                    }

                    $('#student-list').html(cardsHtml);
                },
                error: function () {
                    $('#student-list').html('<p class="text-danger">Something went wrong while searching.</p>');
                }
            });
        });
    });
</script>
