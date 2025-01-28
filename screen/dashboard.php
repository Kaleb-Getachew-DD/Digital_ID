<?php 
    $lang = isset($_GET['lang']) ? $_GET['lang'] : 'en'; // Default language is English
    $translations = include "lang/$lang.php";

include 'db_connection.php';
    
// Query to get the total number of users
    $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_users FROM user");
    $stmt1->execute();
    $totalUsersResult = $stmt1->get_result();
    $totalUsers = $totalUsersResult->fetch_assoc()['total_users'];

    // Query to get the count of active users (status = 1)
    $stmt2 = $conn->prepare("SELECT COUNT(*) AS active_users FROM user WHERE status = 1");
    $stmt2->execute();
    $activeUsersResult = $stmt2->get_result();
    $activeUsers = $activeUsersResult->fetch_assoc()['active_users'];

    // Query for user growth (by month)
    $stmt5 = $conn->prepare("SELECT DATE_FORMAT(created_date, '%Y-%m') AS month, COUNT(*) AS user_count FROM user GROUP BY DATE_FORMAT(created_date, '%Y-%m')");
    $stmt5->execute();
    $userGrowthResult = $stmt5->get_result();

    $userGrowthLabels = [];
    $userGrowthData = [];
    while ($row = $userGrowthResult->fetch_assoc()) {
        $userGrowthLabels[] = $row['month'];
        $userGrowthData[] = $row['user_count'];
    }

    // Query to get active vs inactive users
    $stmt6 = $conn->prepare("SELECT COUNT(*) AS active_users FROM user WHERE status = 1");
    $stmt6->execute();
    $activeUsersResult = $stmt6->get_result();
    $activeUsersCount = $activeUsersResult->fetch_assoc()['active_users'];
    $inactiveUsersCount = $totalUsers - $activeUsersCount;
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $translations['admin_dashboard'] ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background-color: #f4f6f9;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .dashboard-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .chart-container {
            position: relative;
            height: 400px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <!-- Dashboard Header -->
        <div class="mb-4 text-center">
            <h1 class="dashboard-title"><?= $translations['admin_dashboard'] ?></h1>
        </div>

        <!-- Dashboard Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title"><?= $translations['total_users'] ?></h5>
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <h3 class="mt-2"><?= $totalUsers ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title"><?= $translations['active_users'] ?></h5>
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                        <h3 class="mt-2"><?= $activeUsers ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title"><?= $translations['pending_requests'] ?></h5>
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <h3 class="mt-2">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title"><?= $translations['issues_reported'] ?></h5>
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                        <h3 class="mt-2">0</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= $translations['user_growth'] ?></h5>
                        <div class="chart-container">
                            <canvas id="userGrowthChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= $translations['active_vs_inactive'] ?></h5>
                        <div class="chart-container">
                            <canvas id="activeInactiveChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        
    </div>

    <!-- Chart.js Logic -->
    <script>
// User Growth Chart
        const ctx1 = document.getElementById('userGrowthChart').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: <?= json_encode($userGrowthLabels) ?>,
                datasets: [{
                    label: 'Users',
                    data: <?= json_encode($userGrowthData) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Active vs Inactive Users Chart
        const ctx2 = document.getElementById('activeInactiveChart').getContext('2d');
        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['Active', 'Inactive'],
                datasets: [{
                    data: [<?= $activeUsersCount ?>, <?= $inactiveUsersCount ?>],
                    backgroundColor: ['#28a745', '#dc3545']
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
