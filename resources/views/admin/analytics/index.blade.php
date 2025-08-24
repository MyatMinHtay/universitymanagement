<x-adminlayout>
    <div class="container py-5">
               <!-- Analytics Dashboard Header -->
          <div class="analytics-header">
               <h1 class="analytics-title">University Analytics Dashboard</h1>
               <p class="analytics-subtitle">Comprehensive insights into university data and trends</p>
          </div>

          <!-- Overview Cards -->
          <div class="analytics-overview">
               <div class="overview-card">
                    <div class="card-icon students-icon">
                         <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="card-content">
                         <h3>{{ $totalStudents }}</h3>
                         <p>Total Students</p>
                    </div>
               </div>
               
               <div class="overview-card">
                    <div class="card-icon teachers-icon">
                         <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="card-content">
                         <h3>{{ $totalTeachers }}</h3>
                         <p>Total Teachers</p>
                    </div>
               </div>
               
               <div class="overview-card">
                    <div class="card-icon departments-icon">
                         <i class="fas fa-building"></i>
                    </div>
                    <div class="card-content">
                         <h3>{{ $totalDepartments }}</h3>
                         <p>Departments</p>
                    </div>
               </div>
               
               <div class="overview-card">
                    <div class="card-icon faculty-icon">
                         <i class="fas fa-users"></i>
                    </div>
                    <div class="card-content">
                         <h3>{{ $totalFaculty }}</h3>
                         <p>Faculty Members</p>
                    </div>
               </div>
               
               <div class="overview-card">
                    <div class="card-icon users-icon">
                         <i class="fas fa-user-cog"></i>
                    </div>
                    <div class="card-content">
                         <h3>{{ $totalUsers }}</h3>
                         <p>System Users</p>
                    </div>
               </div>
          </div>

          <!-- Charts Grid -->
          <div class="charts-grid">
               <!-- Students by Department Pie Chart -->
               <div class="chart-container">
                    <div class="chart-header">
                         <h3>Students by Department</h3>
                         <p>Distribution of students across departments</p>
                    </div>
                    <div class="chart-wrapper">
                         <canvas id="studentsDepartmentChart"></canvas>
                    </div>
               </div>

               <!-- Teachers by Department Bar Chart -->
               <div class="chart-container">
                    <div class="chart-header">
                         <h3>Teachers by Department</h3>
                         <p>Number of teachers in each department</p>
                    </div>
                    <div class="chart-wrapper">
                         <canvas id="teachersDepartmentChart"></canvas>
                    </div>
               </div>

               <!-- Students by Gender Donut Chart -->
               <div class="chart-container">
                    <div class="chart-header">
                         <h3>Students by Gender</h3>
                         <p>Gender distribution of students</p>
                    </div>
                    <div class="chart-wrapper">
                         <canvas id="studentsGenderChart"></canvas>
                    </div>
               </div>

               <!-- Teachers by Gender Donut Chart -->
               <div class="chart-container">
                    <div class="chart-header">
                         <h3>Teachers by Gender</h3>
                         <p>Gender distribution of teachers</p>
                    </div>
                    <div class="chart-wrapper">
                         <canvas id="teachersGenderChart"></canvas>
                    </div>
               </div>

               <!-- Students by Year Line Chart -->
               <div class="chart-container chart-wide">
                    <div class="chart-header">
                         <h3>Students by Academic Year</h3>
                         <p>Distribution of students across different academic years</p>
                    </div>
                    <div class="chart-wrapper">
                         <canvas id="studentsYearChart"></canvas>
                    </div>
               </div>

               <!-- Registration Trend Line Chart -->
               <div class="chart-container chart-wide">
                    <div class="chart-header">
                         <h3>Registration Trends</h3>
                         <p>Monthly registration trends for students and teachers</p>
                    </div>
                    <div class="chart-wrapper">
                         <canvas id="registrationTrendChart"></canvas>
                    </div>
               </div>
          </div>
    </div>

    <!-- Chart.js Script -->
    <script src="{{ asset('assets/js/chart.js') }}"></script>
    <script>
        // Chart.js Configuration
        Chart.defaults.font.family = 'Poppins, sans-serif';
        Chart.defaults.color = '#2d465e';
        
        // Color palette using CSS variables
        const colors = {
            primary: '#08915e',
            secondary: '#00c6ad', 
            accent: '#ee982c',
            success: '#28a745',
            warning: '#ffc107',
            danger: '#dc3545',
            info: '#17a2b8',
            light: '#f8f9fa',
            dark: '#2d465e'
        };

        // Students by Department Pie Chart
        const studentsDepData = @json($studentsByDepartment);
        new Chart(document.getElementById('studentsDepartmentChart'), {
            type: 'pie',
            data: {
                labels: studentsDepData.map(item => item.name),
                datasets: [{
                    data: studentsDepData.map(item => item.count),
                    backgroundColor: [
                        colors.primary,
                        colors.secondary,
                        colors.accent,
                        colors.success,
                        colors.warning,
                        colors.info,
                        colors.danger
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });

        // Teachers by Department Bar Chart
        const teachersDepData = @json($teachersByDepartment);
        new Chart(document.getElementById('teachersDepartmentChart'), {
            type: 'bar',
            data: {
                labels: teachersDepData.map(item => item.name),
                datasets: [{
                    label: 'Teachers',
                    data: teachersDepData.map(item => item.count),
                    backgroundColor: colors.primary,
                    borderColor: colors.primary,
                    borderWidth: 1,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Students by Gender Donut Chart
        const studentsGenderData = @json($studentsByGender);
        new Chart(document.getElementById('studentsGenderChart'), {
            type: 'doughnut',
            data: {
                labels: studentsGenderData.map(item => item.gender),
                datasets: [{
                    data: studentsGenderData.map(item => item.count),
                    backgroundColor: [colors.primary, colors.secondary, colors.accent],
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });

        // Teachers by Gender Donut Chart
        const teachersGenderData = @json($teachersByGender);
        new Chart(document.getElementById('teachersGenderChart'), {
            type: 'doughnut',
            data: {
                labels: teachersGenderData.map(item => item.gender),
                datasets: [{
                    data: teachersGenderData.map(item => item.count),
                    backgroundColor: [colors.accent, colors.success, colors.warning],
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });

        // Students by Year Line Chart
        const studentsYearData = @json($studentsByYear);
        new Chart(document.getElementById('studentsYearChart'), {
            type: 'line',
            data: {
                labels: studentsYearData.map(item => 'Year ' + item.year),
                datasets: [{
                    label: 'Students',
                    data: studentsYearData.map(item => item.count),
                    borderColor: colors.primary,
                    backgroundColor: colors.primary + '20',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors.primary,
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Registration Trend Line Chart
        const registrationData = @json($registrationTrend);
        new Chart(document.getElementById('registrationTrendChart'), {
            type: 'line',
            data: {
                labels: registrationData.map(item => item.month),
                datasets: [
                    {
                        label: 'Students',
                        data: registrationData.map(item => item.students),
                        borderColor: colors.primary,
                        backgroundColor: colors.primary + '20',
                        borderWidth: 3,
                        fill: false,
                        tension: 0.4,
                        pointBackgroundColor: colors.primary,
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6
                    },
                    {
                        label: 'Teachers',
                        data: registrationData.map(item => item.teachers),
                        borderColor: colors.secondary,
                        backgroundColor: colors.secondary + '20',
                        borderWidth: 3,
                        fill: false,
                        tension: 0.4,
                        pointBackgroundColor: colors.secondary,
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</x-adminlayout>