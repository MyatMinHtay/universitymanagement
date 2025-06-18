<x-adminlayout>
    <div class="container my-5">
        <h1 class="text-center cmn-header">Departments</h1>
        
        <div class="searchbox my-5">
            <input type="text" id="searchdp" class="searchinput" placeholder="Search for a department">
        </div>

        <div class="dp-control my-5">
            
            <a href="{{ route('departments.create') }}" class="addbtn">Add Department</a>
        </div>
        <div class="dpboxes my-5" id="department-list">

            @foreach ($departments as  $department)
                <div class="dpbox">
                    <div class="dplogobox">
                        <img src="{{ asset($department->logo) }}" alt="{{ $department->fullname }}">
                    </div>

                <div class="dpcontent">
                    <h4 class="dp-name">{{ $department->fullname }} ({{ $department->deptCode }})</h4>
                    
                    <a href="{{ route('departments.show', $department->id) }}" class="dp-btn">View More</a>
                </div>
            </div>
            @endforeach
            
            
        </div>
    </div>
</x-adminlayout>

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
                        var showUrl = 'departments/edit/' + department.id;
                        var imageUrl = '/' + department.logo;

                        var departmentHtml = `
                            <div class="dpbox">
                                <div class="dplogobox">
                                    <img src="${imageUrl}" alt="${department.fullname}">
                                </div>
                                <div class="dpcontent">
                                    <h4 class="dp-name">${department.fullname} (${department.deptCode})</h4>
                                    <a href="${showUrl}" class="dp-btn">View More</a>
                                </div>
                            </div>
                        `;

                        $('#department-list').append(departmentHtml);
                    });
                }
            });
        });

    });
</script>