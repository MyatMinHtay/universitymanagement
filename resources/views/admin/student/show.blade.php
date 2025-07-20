<x-adminlayout>
    <main class="main mt-5">
        <!-- Page Title with Banner & Profile Image -->
        <div class="page-title text-center">
            @if($student->department && $student->department->banner)
                <div class="container position-relative mb-4">
                    <img class="img-fluid rounded shadow" src="{{ asset($student->department->banner) }}" alt="{{ $student->department->fullname }}">
                    <div class="my-4">
                        <img class="rounded-circle shadow" src="{{ asset($student->image) }}" width="100" height="100" alt="{{ $student->name }}">
                        <h1 class="mt-3">{{ $student->name }}</h1>
                        <h5 class="text-muted">
                            @if($student->department)
                                Department of {{ $student->department->fullname }}
                            @else
                                No Department Assigned
                            @endif
                        </h5>
                    </div>
                </div>
            @endif
        </div>
        <!-- End Page Title -->
    
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0 p-4 rounded-4">
                        <h4 class="mb-3 text-primary"><i class="fas fa-info-circle me-2"></i> Student Information</h4>
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th style="width: 35%;"><i class="fas fa-id-badge me-2"></i>ID</th>
                                    <td>{{ $student->id }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-user me-2"></i>Name</th>
                                    <td>{{ $student->name }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-calendar-alt me-2"></i>Year</th>
                                    <td>{{ $student->year ?? 'Not Provided' }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-hashtag me-2"></i>Roll Number</th>
                                    <td>{{ $student->roll_number ?? 'Not Provided' }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-venus-mars me-2"></i>Gender</th>
                                    <td>{{ $student->gender ? ucfirst($student->gender) : 'Not Provided' }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-birthday-cake me-2"></i>Date of Birth</th>
                                    <td>{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('F j, Y') : 'Not Provided' }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-building me-2"></i>Department</th>
                                    <td>
                                        @if($student->department)
                                            Department of {{ $student->department->fullname }}
                                        @else
                                            No Department Assigned
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-envelope me-2"></i>Email</th>
                                    <td>
                                        @if($student->email)
                                            <a href="mailto:{{ $student->email }}">{{ $student->email }}</a>
                                        @else
                                            Not Provided
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-phone me-2"></i>Phone Number</th>
                                    <td>
                                        @if($student->phone_number)
                                            <a href="tel:{{ $student->phone_number }}">{{ $student->phone_number }}</a>
                                        @else
                                            Not Provided
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center mt-4">
                            @if($student->department)
                                <a href="{{ route('departments.show', $student->department->id) }}" class="btn btn-primary rounded-pill me-2">
                                    <i class="fas fa-eye"></i> View Department
                                </a>
                            @endif
                            @if($student->email)
                                <a href="mailto:{{ $student->email }}" class="btn btn-outline-primary rounded-pill me-2">
                                    <i class="fas fa-envelope"></i> Email
                                </a>
                            @endif
                            @if($student->phone_number)
                                <a href="tel:{{ $student->phone_number }}" class="btn btn-outline-success rounded-pill">
                                    <i class="fas fa-phone"></i> Call
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    </x-adminlayout>
    