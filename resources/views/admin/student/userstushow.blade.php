<x-layout>
    <main class="main mt-5">
        <!-- Back Button -->
        
    
        <!-- Page Title with Banner & Profile -->
        <div class="page-title text-center">
            @if($student->department && $student->department->banner)
            <div class="container position-relative">
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
    
        <div class="container mt-1 mb-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm p-4 border-0 rounded-4">
                        <h4 class="mb-3 text-primary"><i class="fas fa-info-circle me-2"></i> Student Information</h4>
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th style="width:35%;">ID:</th>
                                <td>{{ $student->id }}</td>
                            </tr>
                            <tr>
                                <th>Name:</th>
                                <td>{{ $student->name }}</td>
                            </tr>
                            <tr>
                                <th>Year:</th>
                                <td>{{ $student->year ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Roll Number:</th>
                                <td>{{ $student->seat_number ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Department:</th>
                                <td>{{ $student->department->fullname ?? 'No Department Assigned' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    </x-layout>
    