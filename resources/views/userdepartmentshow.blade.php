<x-layout>
    <main class="main">
            <!-- Page Title -->
        <div class="page-title">
            <div class="container position-relative ">
              <div class="col-12 d-flex justify-content-start align-items-center my-5">
                <img class="mt-5 me-3" style="object-fit: cover" src="{{ asset($department->logo) }}" width="100px" height="100px" alt="">
                <h1 class="mt-5 ms-3">Department Of {{ $department->fullname }}</h1>
              </div>
              <div class="col-12 dep-banner">
                <img class="img-fluid" src="{{ asset($department->banner) }}" alt="{{ $department->fullname }}">
                
              </div>
              
              
            </div>
        </div>
    <!-- End Page Title -->

    <div class="container my-5">
        

        

        <div class="row mb-5">
          <div class="col-lg-12 mx-auto">
            <div class="search-container" data-aos="fade-up" data-aos-delay="200">
              <div class="input-group">
                <input type="text" id="searchtr" class="form-control" placeholder="Search Teachers">
              </div>
            </div>
          </div>
        </div>

        <div class="dpsection">
            <h3 class="section-title">Teachers</h3>
            <div class="card-grid" id="teacher-list">
                @forelse ($department->teachers as $teacher)
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
        </div>

        <div class="row mb-5">
            <div class="col-lg-12 mx-auto">
              <div class="search-container" data-aos="fade-up" data-aos-delay="200">
                <div class="input-group">
                  <input type="text" id="searchst" class="form-control" placeholder="Search Students">
                </div>
              </div>
            </div>
        </div>

        <div class="dpsection">
            <h3 class="section-title">Students</h3>
            <div class="card-grid" id="student-list">
                @forelse ($department->students as $student)
                    <a href="{{ route('students.usershow', $student->id) }}" class="info-card">
                        <img src="{{ asset($student->image) }}" alt="{{ $student->name }}">
                        <h4>{{ $student->name }}</h4>
                        <p>Year: {{ $student->year }}</p>
                        <p>Seat No: {{ $student->seat_number }}</p>
                    </a>
                @empty
                    <p>No students found in this department.</p>
                @endforelse
            </div>
        </div>
    </div>

    </main>
</x-layout>

<script>
    $(document).ready(function () {
      // Teacher search functionality
      $('#searchtr').on('keyup', function () {
        var searchQuery = $(this).val();
        var departmentId = {{ $department->id ?? 'null' }};
        $.ajax({
          type: 'GET',
          url: '{{ route('teachers.search') }}', // ✅ Adjust to your correct route
          data: { 
            search: searchQuery,
             department_id: departmentId
          },
          dataType: 'json',
          success: function (data) {
            $('#teacher-list').html('');

  
            $.each(data, function (index, teacher) {
              var imageUrl = '/' + teacher.image;
  
              var teacherHtml = `
                <div class="info-card">
                  <img src="${imageUrl}" alt="${teacher.name}">
                  <h4>${teacher.name}</h4>
                  <p>${teacher.position}</p>
                  <p>${teacher.phone_number}</p>
                </div>
              `;
  
              $('#teacher-list').append(teacherHtml);
            });
          },
          
        });
      });

      // Student search functionality
      $('#searchst').on('keyup', function () {
        var searchQuery = $(this).val();
  
        $.ajax({
          type: 'GET',
          url: '{{ route('students.search') }}',
          data: { 
            search: searchQuery,
            department_id: {{ $department->id }} // Pass the current department ID
          },
          dataType: 'json',
          success: function (data) {
            $('#student-list').html('');
  
  
            $.each(data, function (index, student) {
              var imageUrl = '/' + student.image;
  
              var studentHtml = `
                <div class="info-card">
                  <img src="${imageUrl}" alt="${student.name}">
                  <h4>${student.name}</h4>
                  <p>Year: ${student.year}</p>
                  <p>Seat No: ${student.seat_number}</p>
                </div>
              `;
  
              $('#student-list').append(studentHtml);
            });
          },
          error: function (xhr, status, error) {
            console.error(error);
          }
        });
      });
    });
  </script>
  


