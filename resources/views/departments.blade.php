<x-layout>
    <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url(assets/img/education/showcase-1.webp);">
      <div class="container position-relative">
        <h1>Academic Departments</h1>
        <p>Explore our diverse range of academic departments, each dedicated to excellence in teaching, research, and innovation across various disciplines.</p>

        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">Departments</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Faculty  Staff Section -->
    <section id="faculty--staff" class="faculty--staff section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row mb-5">
          <div class="col-lg-8 mx-auto">
            <div class="search-container" data-aos="fade-up" data-aos-delay="200">
              <div class="input-group">
                <input type="text" id="searchdp" class="form-control" placeholder="Search Departments">
                
              </div>
            </div>
          </div>
        </div>

        <div class="department-grid" id="department-list">
          @forelse ($departments as $department)
              <a href="{{ route('user.departments.show', $department->id) }}" class="department-card">
                  <div class="card-image">
                      <img src="{{ asset($department->logo) }}" alt="{{ $department->fullname }}">
                  </div>
                  <div class="card-content">
                      <h3>{{ $department->fullname }}</h3>
                      <p>{{ $department->deptCode }}</p>
                  </div>
              </a>
          @empty
              <p class="text-center">No departments found.</p>
          @endforelse
      </div>


       

      </div>

    </section><!-- /Faculty  Staff Section -->

  </main>
</x-layout>

<script>
    $(document).ready(function() {
        $('#searchdp').on('keyup', function () {
            var searchQuery = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('departments.search') }}',
                data: { search: searchQuery },
                dataType: 'json',
                success: function (data) {
                    $('#department-list').html('');
                    $.each(data, function (index, department) {
                        var showUrl = 'departments/show/' + department.id;
                        var imageUrl = '/' + department.logo;

                        var departmentHtml = `
                            <a href="${showUrl}" class="department-card">
                              <div class="card-image">
                                  <img src="${imageUrl}" alt="${department.fullname}">
                              </div>
                              <div class="card-content">
                                  <h3>${department.fullname}</h3>
                                  <p>${department.deptCode}</p>
                              </div>
                          </a>
                        `;

                        $('#department-list').append(departmentHtml);
                    });
                }
            });
        });


    });
</script>
