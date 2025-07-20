<x-adminlayout>
    <div class="container mt-5">

        <h1 class="text-center form_header">Update Teacher</h1>

        <form action="{{ route('teachers.update', $teacher->id) }}" method="POST" id="teacherForm" class="forms py-5" enctype="multipart/form-data">
            @csrf
            @method('POST') {{-- Optional: you can use @method('PUT') if you're using PUT in route --}}
            
            <div class="p-3 mx-auto col-12 col-lg-8 rounded-2">

                <div class="mb-5 form-group">
                    <label for="name">Teacher Name</label>
                    <input type="text" class="form-control inputbox" value="{{ old('name', $teacher->name) }}" name="name"
                        id="name" placeholder="Enter Teacher Name">
                    <x-error name="name" />
                </div>

                <div class="mb-5 form-group">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" class="form-control inputbox">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender', $teacher->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $teacher->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $teacher->gender) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <x-error name="gender" />
                </div>

                <div class="mb-5 form-group">
                    <label for="date_of_birth">Date of Birth</label>
                    <input type="date" class="form-control inputbox" value="{{ old('date_of_birth', $teacher->date_of_birth) }}" name="date_of_birth"
                        id="date_of_birth">
                    <x-error name="date_of_birth" />
                </div>

                <div class="mb-5 form-group">
                    <label for="position">Position</label>
                    <input type="text" class="form-control inputbox" value="{{ old('position', $teacher->position) }}" name="position"
                        id="position" placeholder="Enter Position (e.g. Lecturer, Professor)">
                    <x-error name="position" />
                </div>

                <div class="mb-5 form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" class="form-control inputbox" value="{{ old('phone_number', $teacher->phone_number) }}" name="phone_number"
                        id="phone_number" placeholder="Enter Phone Number">
                    <x-error name="phone_number" />
                </div>

                <div class="mb-5 form-group">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control inputbox" value="{{ old('email', $teacher->email) }}" name="email"
                        id="email" placeholder="Enter Email Address">
                    <x-error name="email"></x-error>
                </div>

                <div class="mb-5 form-group">
                    <label for="department_id">Department</label>
                    <select name="department_id" id="department_id" class="form-control inputbox">
                        <option value="">Select Department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id', $teacher->department_id) == $department->id ? 'selected' : '' }}>
                                {{ $department->fullname }}
                            </option>
                        @endforeach
                    </select>
                    <x-error name="department_id" />
                </div>

                <div class="mb-3 form-group">
                    <label for="image">Teacher Photo (optional)</label>
                    <input type="file" class="form-control inputbox fileinput" name="image" id="image">
                    <x-error name="image" />
                </div>

            </div>

            <div class="formbtnboxes col-6">
                <button type="submit" id="submitbtn" name="submitTeacher" class="formSubtmiBtn">Update</button>
            </div>
        </form>
    </div>
</x-adminlayout>
