<x-layout>
    <main class="main mt-5">
        <!-- Page Title with Banner & Profile -->
        <div class="page-title text-center">
            @if($faculty->department && $faculty->department->banner)
            <div class="container position-relative">
                <img class="img-fluid rounded shadow" src="{{ asset($faculty->department->banner) }}" alt="{{ $faculty->department->fullname }}">
                <div class="my-4">
                    <img class="rounded-circle shadow" src="{{ asset($faculty->image) }}" width="100" height="100" alt="{{ $faculty->name }}">
                    <h1 class="mt-3">{{ $faculty->name }}</h1>
                    <h5 class="text-muted">
                        @if($faculty->department)
                            Department of {{ $faculty->department->fullname }}
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
                        <h4 class="mb-3 text-primary"><i class="fas fa-info-circle me-2"></i> Faculty Information</h4>
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th style="width:35%;">ID:</th>
                                <td>{{ $faculty->id }}</td>
                            </tr>
                            <tr>
                                <th>Name:</th>
                                <td>{{ $faculty->name }}</td>
                            </tr>
                            <tr>
                                <th>Position:</th>
                                <td>{{ $faculty->position }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>
                                    @if($faculty->phone_number)
                                        <a href="tel:{{ $faculty->phone_number }}">{{ $faculty->phone_number }}</a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Department:</th>
                                <td>{{ $faculty->department->fullname ?? 'No Department Assigned' }}</td>
                            </tr>
                        </table>
    
                        
                    </div>
                </div>
            </div>
        </div>
    </main>
    </x-layout>
    