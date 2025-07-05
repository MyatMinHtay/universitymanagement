<x-layout>
    <main class="main mt-5">
        <!-- Page Title with Banner & Profile -->
        <div class="page-title text-center">
            @if($teacher->department && $teacher->department->banner)
            <div class="hero-section position-relative">
                <div class="banner-overlay">
                    <img class="banner-image" src="{{ asset($teacher->department->banner) }}" alt="{{ $teacher->department->fullname }}">
                </div>
                <div class="hero-content">
                    <div class="profile-container">
                        <img class="profile-image" src="{{ asset($teacher->image) }}" alt="{{ $teacher->name }}">
                        <div class="profile-info">
                            <h1 class="teacher-name">{{ $teacher->name }}</h1>
                            <h5 class="department-name">
                                @if($teacher->department)
                                    <i class="fas fa-university me-2"></i>Department of {{ $teacher->department->fullname }}
                                @else
                                    <i class="fas fa-exclamation-circle me-2"></i>No Department Assigned
                                @endif
                            </h5>
                            <div class="teacher-badge">
                                <i class="fas fa-chalkboard-teacher me-2"></i>{{ $teacher->position }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <!-- End Page Title -->
    
        <div class="container mt-5 mb-5">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="info-card tea-show-card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fas fa-info-circle me-3"></i>Teacher Information
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-hashtag me-2"></i>Teacher ID
                                    </div>
                                    <div class="info-value">{{ $teacher->id }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-user me-2"></i>Full Name
                                    </div>
                                    <div class="info-value">{{ $teacher->name }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-user-tie me-2"></i>Position
                                    </div>
                                    <div class="info-value">{{ $teacher->position }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-building me-2"></i>Department
                                    </div>
                                    <div class="info-value department-value text-center">
                                        {{ $teacher->department->fullname ?? 'No Department Assigned' }}
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Enhanced Custom Styling -->
    <style>
        /* Hero Section */
        .hero-section {
            margin-bottom: 3rem;
            overflow: hidden;
        }

        .banner-overlay {
            position: relative;
            height: 400px;
            overflow: hidden;
            border-radius: 20px;
            margin: 0 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }


        .banner-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .banner-image:hover {
            transform: scale(1.05);
        }

        .hero-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            text-align: center;
            color: white;
            width: 100%;
            padding: 0 2rem;
        }

        .profile-container {
            animation: slideInUp 0.8s ease-out;
        }

        .profile-image {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 6px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .profile-image:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.4);
        }

        .teacher-name {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8);
            animation: fadeInUp 0.8s ease-out 0.2s both;
            color: white;
        }

        .department-name {
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 1rem;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.8);
            animation: fadeInUp 0.8s ease-out 0.4s both;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 15px;
            display: inline-block;
            color: white;
        }

        .teacher-badge {
            display: inline-block;
            background: rgba(231, 111, 81, 0.9);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            border: 2px solid rgba(255, 255, 255, 0.3);
            animation: fadeInUp 0.8s ease-out 0.6s both;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        /* Information Card */
        .info-card {
            background: linear-gradient(135deg, #ffffff 0%, #fff8f5 100%);
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            animation: slideInUp 1s ease-out 0.5s both;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, #e76f51, #264653);
            color: white;
            padding: 1.5rem 2rem;
            border-bottom: none;
            border-radius: 20px;
        }

        .card-title {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .card-title i {
            font-size: 1.3rem;
            opacity: 0.9;
        }

        .card-body {
            padding: 2rem;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .info-item {
            background: white;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border-left: 5px solid #e76f51;
        }

        .info-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        .info-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .info-label i {
            color: #e76f51;
            opacity: 0.8;
        }

        .info-value {
            font-size: 1.1rem;
            font-weight: 500;
            color: #2c3e50;
            line-height: 1.4;
        }

        .department-value {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
        }

        .department-code {
            background: #e76f51;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Animations */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .banner-overlay {
                height: 250px;
                margin: 0 1rem;
            }

            .teacher-name {
                font-size: 2rem;
            }

            .department-name {
                font-size: 1rem;
            }

            .profile-image {
                width: 100px;
                height: 100px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .info-item {
                padding: 1rem;
            }
        }

        @media (max-width: 576px) {
            .banner-overlay {
                height: 200px;
                margin: 0 0.5rem;
            }

            .teacher-name {
                font-size: 1.5rem;
            }

            .hero-content {
                padding: 0 1rem;
            }
        }
    </style>
</x-layout>
    