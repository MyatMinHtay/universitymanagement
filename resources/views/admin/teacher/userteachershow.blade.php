<x-layout>
<main class="main mt-5">
    <!-- Page Title -->
    <div class="page-title no-before text-center">
        <div class="col-12 d-flex justify-content-start container mb-4">
            <a href="{{ route('user.departments.show', $teacher->department->id) }}" class="btn btn-success">
                <i class="fa-solid fa-arrow-left me-2"></i>Back
            </a>
        </div>

        <div class="container position-relative">
            <img class="img-fluid" src="{{ asset($teacher->department->banner) }}" alt="{{ $teacher->department->fullname }}">
            <div class="my-4">
                <img class="rounded-circle shadow" src="{{ asset($teacher->image) }}" width="100" height="100" alt="{{ $teacher->name }}">
                <h1 class="mt-3">{{ $teacher->name }}</h1>
                <h5 class="text-muted">Department of {{ $teacher->department->fullname ?? 'N/A' }}</h5>
            </div>
        </div>
    </div>
    <!-- End Page Title -->

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm p-4 border-0">
                    <h4 class="mb-3 text-primary">Teacher Information</h4>
                    <table class="table table-borderless">
                        <tr>
                            <th>ID:</th>
                            <td>{{ $teacher->id }}</td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td>{{ $teacher->name }}</td>
                        </tr>
                        <tr>
                            <th>Position:</th>
                            <td>{{ $teacher->position }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $teacher->phone_number }}</td>
                        </tr>
                        <tr>
                            <th>Department:</th>
                            <td>{{ $teacher->department->fullname ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
</x-layout>
