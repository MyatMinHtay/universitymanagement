<x-layout>
    <main class="container cus-margin">
     <!-- Page Title -->
     <div class="page-title">
        <div class="container position-relative">
          
          <h1 class="mt-5">All Teachers ({{ $teachers->count() }})</h1>
        </div>
    </div>
<!-- End Page Title -->
  
  
  <div class="my-4">
    <input type="text" id="searchteacher" class="form-control" placeholder="Search Teachers">
  </div>
  
  <div class="table-responsive" id="teacher-list">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Image</th>
          <th>Name</th>
          <th>Position</th>
          <th>Phone</th>
          <th>Department</th>
          <th>View</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($teachers as $teacher)
          <tr>
            <td>{{ $teacher->id }}</td>
            <td><img src="{{ asset($teacher->image) }}" alt="{{ $teacher->name }}" style="width:60px;"></td>
            <td>{{ $teacher->name }}</td>
            <td>{{ $teacher->position }}</td>
            <td>{{ $teacher->phone_number }}</td>
            <td>{{ $teacher->department->fullname ?? 'N/A' }}</td>
            <td><a href="{{ route('teachers.usershow', $teacher->id) }}" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a></td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center">No teachers found in this department.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
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
          let tableHtml = '<table class="table table-striped table-hover"><thead><tr>' +
            '<th>ID</th><th>Image</th><th>Name</th><th>Position</th><th>Phone</th><th>Department</th><th>View</th></tr></thead><tbody>';
  
          if (data.length === 0) {
            tableHtml += '<tr><td colspan="6" class="text-center">No teachers found.</td></tr>';
          } else {
            $.each(data, function (index, teacher) {
              let imageUrl = '/' + teacher.image;
              let showUrl = `/teachers/${teacher.id}`;
              let department = teacher.department?.fullname || 'N/A';
              tableHtml += `<tr>
                <td>${teacher.id}</td>
                <td><img src="${imageUrl}" alt="${teacher.name}" style="width:60px;"></td>
                <td>${teacher.name}</td>
                <td>${teacher.position}</td>
                <td>${teacher.phone_number}</td>
                <td>${department}</td>
                <td><a href="${showUrl}" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a></td>
              </tr>`;
            });
          }
  
          tableHtml += '</tbody></table>';
          $('#teacher-list').html(tableHtml);
        },
        error: function () {
          $('#teacher-list').html('<p class="text-danger">Something went wrong while searching.</p>');
        }
      });
    });
  });
  </script>
  