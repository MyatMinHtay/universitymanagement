<x-layout>
    <main class="container my-5">
      <!-- Page Title -->
      <div class="page-title">
        <div class="container position-relative">
          <h1 class="mt-5">All Students ({{ $students->count() }})</h1>
        </div>
      </div>
      <!-- End Page Title -->
  
      <div class="my-4">
        <input type="text" id="searchstudent" class="form-control" placeholder="Search Students">
      </div>
  
      <div class="table-responsive" id="student-list">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Image</th>
              <th>Name</th>
              <th>Year</th>
              <th>Seat No</th>
              <th>Department</th>
              <th>View</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($students as $student)
              <tr>
                <td>{{ $student->id }}</td>
                <td><img src="{{ asset($student->image) }}" alt="{{ $student->name }}" style="width:60px;"></td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->year }}</td>
                <td>{{ $student->seat_number }}</td>
                <td>{{ $student->department->fullname ?? 'N/A' }}</td>
                <td><a href="{{ route('students.usershow', $student->id) }}" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a></td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center">No students found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
        <div>
          {{ $students->links() }}
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
          let tableHtml = '<table class="table table-striped table-hover"><thead><tr>' +
            '<th>ID</th><th>Image</th><th>Name</th><th>Year</th><th>Seat No</th><th>Department</th><th>View</th></tr></thead><tbody>';
  
          if (data.length === 0) {
            tableHtml += '<tr><td colspan="6" class="text-center">No students found.</td></tr>';
          } else {
            $.each(data, function (index, student) {
              let showUrl = `/students/${student.id}`;
              let imageUrl = '/' + student.image;
              let department = student.department?.fullname || 'N/A';
  
              tableHtml += `<tr>
                <td>${student.id}</td>
                <td><a href="${showUrl}"><img src="${imageUrl}" alt="${student.name}" style="width:60px;"></a></td>
                <td><a href="${showUrl}">${student.name}</a></td>
                <td>${student.year}</td>
                <td>${student.seat_number}</td>
                <td>${department}</td>
                <td><a href="${showUrl}" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a></td>
              </tr>`;
            });
          }
  
          tableHtml += '</tbody></table>';
          $('#student-list').html(tableHtml);
        },
        error: function () {
          $('#student-list').html('<p class="text-danger">Something went wrong while searching.</p>');
        }
      });
    });
  });
  </script>
  