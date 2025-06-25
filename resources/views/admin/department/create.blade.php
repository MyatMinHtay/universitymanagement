<x-adminlayout>




    <div class="container mt-5">

        <h1 class="text-center form_header">Add Department</h1>

        <form action="{{ route('departments.store') }}" id="dpForm" class="forms py-5" method="post" enctype="multipart/form-data">
            @csrf
            <div class="p-3 mx-auto col-12 col-lg-8 rounded-2">

                

                <div class="mb-5 form-group">
                    <label for="fullname">Full Name of the Department</label>
                    <input type="text" class="form-control inputbox" value="{{ old('fullname') }}" name="fullname"
                        id="fullname" placeholder="Enter fullname of the Department">

                    <x-error name="fullname"></x-error>
                </div>

                <div class="mb-5 form-group">
                    <label for="shortname">Short Name of the Department</label>
                    <input type="text" class="form-control inputbox" value="{{ old('shortname') }}"
                        name="shortname" id="shortname" placeholder="Enter shortname of the Department">

                    <x-error name="shortname"></x-error>
                </div>

                <div class="mb-5 form-group">
                    <label for="deptCode">Department Code</label>
                    <input type="text" class="form-control inputbox" value="{{ old('deptCode') }}"
                        name="deptCode" id="deptCode"
                        placeholder="Enter Department Code">

                    <x-error name="deptCode"></x-error>
                </div>

                <div class="mb-5 form-group">
                    <label for="description">Department Description</label>
                    <textarea name="description" id="description" class="form-control inputbox textareaInput" cols="10" rows="1"></textarea>
                </div>

                <div class="mb-3 form-group">
                    <label for="deptLogo">Department Logo</label>
                    <input type="file" class="form-control inputbox fileinput" name="logo" id="deptLogo">

                    <x-error name="logo"></x-error>
                </div>

                <div class="mb-3 form-group">
                    <label for="deptBanner">Banner</label>
                    <input type="file" class="form-control inputbox fileinput" name="banner" id="deptBanner">

                    <x-error name="banner"></x-error>
                </div>

            </div>


            <div class="mx-auto mt-5 d-flex justify-content-center col-6">
                <button type="submit" id="submitbtn" name="submitLogin" class="formSubtmiBtn">Submit</button>
            </div>

        </form>

    </div>

</x-adminlayout>

<script>
    $(document).ready(function() {
        $('#description').summernote({
            placeholder: 'Enter department description here...',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
    $(document).ready(function () {



        $('#studentSearch').on('keyup', function () {
            let query = $(this).val();
            if (query.length < 2) {
                $('#searchResults').empty();
                return;
            }

            $.ajax({
                url: "{{ route('students.search') }}", // Laravel route for searching students
                method: 'GET',
                data: { name: query },
                success: function (data) {
                    $('#searchResults').empty();
                    if (data.length === 0) {
                        $('#searchResults').append('<div class="list-group-item">No students found</div>');
                    } else {
                        data.forEach(function (student) {
                            $('#searchResults').append(
                                `<a href="#" class="list-group-item list-group-item-action" data-id="${student.id}" data-name="${student.studentName}" data-sirb="${student.sirb}" data-dob="${student.studentDOB}">
                                    ${student.studentName}
                                </a>`
                                
                            );
                        });
                    }
                }
            });
        });

        // Click to add selected student
        $(document).on('click', '#searchResults .list-group-item', function (e) {
            e.preventDefault();


            let studentId = $(this).data('id');
            let studentName = $(this).data('name');
            let studentSirb = $(this).data('sirb');
            let studentDOB = $(this).data('dob');


            

            // Check if already added
            if ($('#selectedStudents input[value="' + studentId + '"]').length > 0) return;

            if($("#studentExpRow").length !== 0){
                $("#studentExpRow").remove();
            }

            $('#selectedStudents').append(`<tr>
                                        <td scope="row">${studentName}</td>
                                        <td scope="row">${studentSirb}</td>
                                        <td scope="row">${studentDOB}</td>
                                        <td scope="row"><button type="button" class="btn-close btn-close-black btn-sm ms-2 remove-student" data-id="${studentId}"></button>
                <input type="hidden" name="selected_students[]" value="${studentId}">
                                    </tr>
            `);
            $('#searchResults').empty();
            $('#studentSearch').val('');
        });

        // Remove selected student
        $(document).on('click', '.remove-student', function () {
            $(this).closest('tr').remove();

            if($("#selectedStudents tr").length == 0){
                $("#selectedStudents").append('<tr id="studentExpRow"><td scope="row" colspan="4" class="text-center">There is no selected Student</td></tr>');
            }
        });
    

});
</script>



