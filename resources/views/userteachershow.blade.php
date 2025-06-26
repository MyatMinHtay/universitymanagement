<x-layout>

    <main>
        <!-- Page Title -->
        <div class="page-title">
            <div class="container position-relative">
              
              <h1 class="mt-5">All Teachers ({{ $teachers->count() }})</h1>
            </div>
        </div>
    <!-- End Page Title -->

    <div class="container">
        <div class="row mb-5">
          <div class="col-lg-12 mx-auto">
            <div class="search-container" data-aos="fade-up" data-aos-delay="200">
              <div class="input-group">
                <input type="text" id="searchteacher" class="form-control" placeholder="Search Teachers">
              </div>
            </div>
          </div>
        </div>

        <div class="dpsection">
            <h3 class="section-title">Teachers</h3>
            <div class="card-grid" id="teacher-list">
                @forelse ($teachers as $teacher)
                    <a href="{{ route('teachers.usershow', $teacher->id) }}" class="info-card">
                        <img src="{{ asset($teacher->image) }}" alt="{{ $teacher->name }}">
                        <h4>{{ $teacher->name }}</h4>
                        <p>{{ $teacher->position }}</p>
                        <p>{{ $teacher->phone_number }}</p>
                    </a>
                @empty
                    <p>No teachers found in this department.</p>
                @endforelse
            </div>

            <div>
                {{ $teachers->links() }}
            </div>
        </div>
    </div>
    </main>
    
</x-layout>

<script>
    $(document).ready(function () {
        $('#searchteacher').on('keyup', function () {
            let searchQuery = $(this).val();

            $.ajax({
                type: 'GET',
                url: '{{ route('teachers.search') }}',
                data: { search: searchQuery },
                dataType: 'json',
                success: function (data) {
                    let cardsHtml = '';

                    if (data.length === 0) {
                        cardsHtml = '<p class="text-center w-100">No teachers found in this department.</p>';
                    } else {
                        $.each(data, function (index, teacher) {
                            let showUrl = `/teachers/${teacher.id}`;
                            let imageUrl = `/${teacher.image}`;

                            cardsHtml += `
                                <a href="${showUrl}" class="info-card">
                                    <img src="${imageUrl}" alt="${teacher.name}">
                                    <h4>${teacher.name}</h4>
                                    <p>${teacher.position}</p>
                                    <p>${teacher.phone_number}</p>
                                </a>
                            `;
                        });
                    }

                    $('#teacher-list').html(cardsHtml);
                },
                error: function (xhr) {
                    $('#teacher-list').html('<p class="text-danger">Something went wrong while searching.</p>');
                }
            });
        });
    });
</script>

