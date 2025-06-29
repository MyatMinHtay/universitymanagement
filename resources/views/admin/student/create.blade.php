<x-adminlayout>
    <div class="container mt-5">
        <h1 class="text-center form_header">Add Student</h1>

        <form action="{{ route('students.store') }}" id="studentForm" class="forms py-5" method="post" enctype="multipart/form-data">
            @csrf
            <div class="p-3 mx-auto col-12 col-lg-8 rounded-2">

                <div class="mb-5 form-group">
                    <label for="name">Student Name</label>
                    <input type="text" class="form-control inputbox" value="{{ old('name') }}" name="name"
                        id="name" placeholder="Enter Student Name">
                    <x-error name="name"></x-error>
                </div>

                <div class="mb-5 form-group">
                    <label for="year">Academic Year</label>
                    <input type="text" class="form-control inputbox" value="{{ old('year') }}" name="year"
                        id="year" placeholder="Enter Academic Year (e.g. 2024)">
                    <x-error name="year"></x-error>
                </div>

                <div class="mb-5 form-group">
                    <label for="seat_number">Roll Number</label>
                    <input type="text" class="form-control inputbox" value="{{ old('seat_number') }}"
                        name="seat_number" id="seat_number" placeholder="Enter Roll Number">
                    <x-error name="seat_number"></x-error>
                </div>

                <div class="mb-5 form-group">
                    <label for="department_id">Department</label>
                    <select name="department_id" id="department_id" class="form-control inputbox">
                        <option value="">Select Department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->fullname }}
                            </option>
                        @endforeach
                    </select>
                    <x-error name="department_id"></x-error>
                </div>

                <div class="mb-3 form-group">
                    <label for="image">Student Photo</label>
                    <input type="file" class="form-control inputbox fileinput" name="image" id="image">
                    <x-error name="image"></x-error>
                </div>

            </div>

            <div class="mx-auto mt-5 d-flex justify-content-center col-6">
                <button type="submit" id="submitbtn" name="submitStudent" class="formSubtmiBtn">Submit</button>
            </div>
        </form>
    </div>
</x-adminlayout>
