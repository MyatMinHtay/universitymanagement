<x-adminlayout>
    <div class="container mt-5">

        <h1 class="text-center form_header">Update Student</h1>

        <form action="{{ route('students.update', $student->id) }}" method="POST" id="studentForm" class="forms py-5" enctype="multipart/form-data">
            @csrf

            <div class="p-3 mx-auto col-12 col-lg-8 rounded-2">

                <div class="mb-5 form-group">
                    <label for="name">Student Name</label>
                    <input type="text" class="form-control inputbox" value="{{ old('name', $student->name) }}" name="name"
                        id="name" placeholder="Enter Student Name">
                    <x-error name="name" />
                </div>

                <div class="mb-5 form-group">
                    <label for="year">Academic Year</label>
                    <input type="text" class="form-control inputbox" value="{{ old('year', $student->year) }}" name="year"
                        id="year" placeholder="Enter Academic Year (e.g. 2024)">
                    <x-error name="year" />
                </div>

                <div class="mb-5 form-group">
                    <label for="seat_number">Seat Number</label>
                    <input type="text" class="form-control inputbox" value="{{ old('seat_number', $student->seat_number) }}"
                        name="seat_number" id="seat_number" placeholder="Enter Seat Number">
                    <x-error name="seat_number" />
                </div>

                <div class="mb-5 form-group">
                    <label for="department_id">Department</label>
                    <select name="department_id" id="department_id" class="form-control inputbox">
                        <option value="">Select Department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id', $student->department_id) == $department->id ? 'selected' : '' }}>
                                {{ $department->fullname }}
                            </option>
                        @endforeach
                    </select>
                    <x-error name="department_id" />
                </div>

                <div class="mb-3 form-group">
                    <label for="image">Student Photo (optional)</label>
                    <input type="file" class="form-control inputbox fileinput" name="image" id="image">
                    <x-error name="image" />
                </div>

            </div>

            <div class="formbtnboxes col-6">

                <button type="submit" id="submitbtn" name="submitStudent" class="formSubtmiBtn">Update</button>
            </div>

        </form>
    </div>
</x-adminlayout>
