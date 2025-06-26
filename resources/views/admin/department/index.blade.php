<x-adminlayout>
    <div class="container my-5">
        <h1 class="text-center cmn-header">Departments</h1>
        
        <div class="searchbox my-5">
            <input type="text" id="searchdp" class="searchinput" placeholder="Search for a department">
        </div>

        <div class="dp-control my-5">
            
            <a href="{{ route('departments.create') }}" class="addbtn">Add Department</a>
        </div>
        <div class="table-responsive" id="department-list">
    <table class="table table-hover table-bordered border-1 table-primary">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Logo</th>
                <th scope="col">Department Name</th>
                <th scope="col">Department Code</th>
                <th scope="col">Edit</th>
                <th scope="col">View</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($departments as $department)
                <tr>
                    <td>{{ $department->id }}</td>
                    <td>
                        <img src="{{ asset($department->logo) }}" alt="{{ $department->fullname }}" style="width: 60px; height: auto;">
                    </td>
                    <td>{{ $department->fullname }}</td>
                    <td>{{ $department->deptCode }}</td>
                    <td class="text-center">
                        <a href="{{ route('departments.adminshow', $department->id) }}" class="btn btn-info">
                            <i class="fa-solid fa-pencil"></i> Edit
                        </a>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('departments.show', $department->id) }}" class="btn btn-info">
                            <i class="fa-solid fa-eye"></i> View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No departments found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

    </div>
</x-adminlayout>

<script>
    $(document).ready(function () {
        $('#searchdp').on('keyup', function () {
            var searchQuery = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('departments.search') }}',
                data: { search: searchQuery },
                dataType: 'json',
                success: function (data) {
                    var tableHtml = `
                        <table class="table table-hover table-bordered border-1 table-primary">
                            <thead>
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Logo</th>
                                    <th scope="col">Department Name</th>
                                    <th scope="col">Department Code</th>
                                    <th scope="col">Edit</th>
                                    <th scope="col">View</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    if (data.length === 0) {
                        tableHtml += `
                            <tr>
                                <td colspan="5" class="text-center">No departments found.</td>
                            </tr>
                        `;
                    } else {
                        $.each(data, function (index, department) {
                            var editUrl = 'departments/edit/' + department.id;
                            var showUrl = 'departments/show/' + department.id;
                            var imageUrl = '/' + department.logo;

                            tableHtml += `
                                <tr>
                                    <td>${department.id}</td>
                                    <td><img src="${imageUrl}" alt="${department.fullname}" style="width: 60px; height: auto;"></td>
                                    <td>${department.fullname}</td>
                                    <td>${department.deptCode}</td>
                                    <td class="text-center">
                                        <a href="${editUrl}" class="btn btn-info">
                                            <i class="fa-solid fa-pencil"></i> Edit
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
                    $('#department-list').html(tableHtml);
                }
            });
        });
    });
</script>
