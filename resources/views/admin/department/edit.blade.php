<x-adminlayout>

    <div class="container mt-5">

        <h1 class="text-center form_header">Update Department</h1>

        <form action="{{ route('departments.update', $department->id) }}" method="POST" action="{{ route('departments.store') }}" id="dpForm" class="forms py-5" method="post" enctype="multipart/form-data">
            @csrf
            <div class="p-3 mx-auto col-12 col-lg-8 rounded-2">

                

                <div class="mb-5 form-group">
                    <label for="fullname">Full Name of the Department</label>
                    <input type="text" class="form-control inputbox" value="{{ old('fullname' , $department->fullname) }}" name="fullname"
                        id="fullname" placeholder="Enter fullname of the Department">

                    <x-error name="fullname"></x-error>
                </div>

                <div class="mb-5 form-group">
                    <label for="shortname">Short Name of the Department</label>
                    <input type="text" class="form-control inputbox" value="{{ old('shortname' , $department->shortname) }}"
                        name="shortname" id="shortname" placeholder="Enter shortname of the Department">

                    <x-error name="shortname"></x-error>
                </div>

                <div class="mb-5 form-group">
                    <label for="deptCode">Department Code</label>
                    <input type="text" class="form-control inputbox" value="{{ old('deptCode' , $department->deptCode) }}"
                        name="deptCode" id="deptCode"
                        placeholder="Enter Department Code">

                    <x-error name="deptCode"></x-error>
                </div>

                <div class="mb-5 form-group">
                    <label for="description">Department Description</label>
                    <textarea name="description" id="description2" class="form-control inputbox textareaInput" cols="10" rows="1">{{ old('description' , $department->description) }}</textarea>
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


            <div class="formbtnboxes col-6">

                <button type="submit" id="submitbtn" name="submitLogin" class="formSubtmiBtn">Update</button>

                
            </div>

        </form>

    </div>

  

</x-adminlayout>

<script>
    $(document).ready(function() {
        $('#description2').summernote({
            placeholder: 'Enter department description here...',
            tabsize: 2,
            height: 300,
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
</script>


