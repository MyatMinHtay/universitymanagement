<x-adminlayout>
    <main class="main mt-5">
        <!-- Page Title with Banner & Profile Image -->
        <div class="page-title text-center">
            @if($teacher->department && $teacher->department->banner)
                <div class="container position-relative mb-4">
                    <img class="img-fluid rounded shadow" src="{{ asset($teacher->department->banner) }}" alt="{{ $teacher->department->fullname }}">
                    <div class="my-4">
                        <img class="rounded-circle shadow" src="{{ asset($teacher->image) }}" width="100" height="100" alt="{{ $teacher->name }}">
                        <h1 class="mt-3">{{ $teacher->name }}</h1>
                        <h5 class="text-muted">
                            @if($teacher->department)
                                Department of {{ $teacher->department->fullname }}
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
                        <h4 class="mb-3 text-primary"><i class="fas fa-info-circle me-2"></i> Teacher Information</h4>
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th style="width: 35%;"><i class="fas fa-id-badge me-2"></i>ID</th>
                                    <td>{{ $teacher->id }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-user me-2"></i>Name</th>
                                    <td>{{ $teacher->name }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-venus-mars me-2"></i>Gender</th>
                                    <td>
                                        @if($teacher->gender)
                                            {{ ucfirst($teacher->gender) }}
                                        @else
                                            Not Provided
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-calendar me-2"></i>Date of Birth</th>
                                    <td>
                                        @if($teacher->date_of_birth)
                                            {{ \Carbon\Carbon::parse($teacher->date_of_birth)->format('F j, Y') }}
                                        @else
                                            Not Provided
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-briefcase me-2"></i>Position</th>
                                    <td>{{ $teacher->position }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-building me-2"></i>Department</th>
                                    <td>
                                        @if($teacher->department)
                                            Department of {{ $teacher->department->fullname }}
                                        @else
                                            No Department Assigned
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-envelope me-2"></i>Email</th>
                                    <td>
                                        @if($teacher->email)
                                            <a href="mailto:{{ $teacher->email }}">{{ $teacher->email }}</a>
                                        @else
                                            Not Provided
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-phone me-2"></i>Phone Number</th>
                                    <td>
                                        @if($teacher->phone_number)
                                            <a href="tel:{{ $teacher->phone_number }}">{{ $teacher->phone_number }}</a>
                                        @else
                                            Not Provided
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center mt-4">
                            @if($teacher->department)
                                <a href="{{ route('departments.show', $teacher->department->id) }}" class="btn btn-primary rounded-pill me-2">
                                    <i class="fas fa-eye"></i> View Department
                                </a>
                            @endif
                           
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    </x-adminlayout>
    