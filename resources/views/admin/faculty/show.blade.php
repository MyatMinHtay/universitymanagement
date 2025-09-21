<x-adminlayout>
    <main class="main mt-5">
        <!-- Page Title with Banner & Profile Image -->
        {{-- <div class="page-title text-center">
            @if($faculty->department && $faculty->department->banner)
                <div class="container position-relative mb-4">
                    <div class="col-12">
                        
                    </div>
                    <img class="img-fluid rounded shadow" src="{{ asset($faculty->department->banner) }}" alt="{{ $faculty->department->fullname }}">
                    <div class="my-4">
                        <img class="rounded-circle shadow" src="{{ asset($faculty->image) }}" width="100" height="100" alt="{{ $faculty->name }}">
                        <h1 class="mt-3">{{ $faculty->name }}</h1>
                        
                    </div>
                </div>
            @endif
        </div> --}}
        <!-- End Page Title -->
    
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0 p-4 rounded-4">
                        <h4 class="mb-3 text-primary"><i class="fas fa-info-circle me-2"></i> Faculty Information</h4>
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th style="width: 35%;"><i class="fas fa-id-badge me-2"></i>ID</th>
                                    <td>{{ $faculty->id }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-user me-2"></i>Name</th>
                                    <td>{{ $faculty->name }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-briefcase me-2"></i>Position</th>
                                    <td>{{ $faculty->position }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-building me-2"></i>Department</th>
                                    <td>
                                        {{ $faculty->department }}
                                    </td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-envelope me-2"></i>Email</th>
                                    <td>
                                        @if($faculty->email)
                                            <a href="mailto:{{ $faculty->email }}">{{ $faculty->email }}</a>
                                        @else
                                            Not Provided
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-phone me-2"></i>Phone Number</th>
                                    <td>
                                        @if($faculty->phone_number)
                                            <a href="tel:{{ $faculty->phone_number }}">{{ $faculty->phone_number }}</a>
                                        @else
                                            Not Provided
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        {{-- <div class="text-center mt-4">
                            @if($faculty->department)
                                <a href="{{ route('departments.show', $faculty->department->id) }}" class="btn btn-primary rounded-pill me-2">
                                    <i class="fas fa-eye"></i> View Department
                                </a>
                            @endif
                            
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </main>
    </x-adminlayout>
    