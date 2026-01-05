<?php
// view_submissions.php - Admin Dashboard to View All Registrations
// IMPORTANT: Password protect this file!

session_start();

// Simple password protection
$admin_password = "converge2026"; // CHANGE THIS PASSWORD!

if (!isset($_SESSION['admin_logged_in'])) {
    if (isset($_POST['password']) && $_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Admin Login - CONVERGE 2026</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background: linear-gradient(135deg, #0d5c4b 0%, #094538 100%);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .login-box {
                    background: white;
                    padding: 40px;
                    border-radius: 15px;
                    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
                    text-align: center;
                }
                .login-box h2 {
                    color: #0d5c4b;
                    margin-bottom: 20px;
                }
                .login-box input {
                    width: 100%;
                    padding: 12px;
                    margin: 10px 0;
                    border: 2px solid #ddd;
                    border-radius: 8px;
                    font-size: 16px;
                }
                .login-box button {
                    width: 100%;
                    padding: 12px;
                    background: #0d5c4b;
                    color: white;
                    border: none;
                    border-radius: 8px;
                    font-size: 16px;
                    cursor: pointer;
                    margin-top: 10px;
                }
                .login-box button:hover {
                    background: #094538;
                }
            </style>
        </head>
        <body>
            <div class="login-box">
                <h2>🔒 Admin Access</h2>
                <p>CONVERGE 2026 - Registration Dashboard</p>
                <form method="POST">
                    <input type="password" name="password" placeholder="Enter Admin Password" required>
                    <button type="submit">Login</button>
                </form>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: view_submissions.php');
    exit;
}

// Read CSV file
$csvFile = 'registrations/converge_2026_registrations.csv';
$registrations = [];

if (file_exists($csvFile)) {
    $file = fopen($csvFile, 'r');
    $headers = fgetcsv($file); // First row as headers
    
    while (($row = fgetcsv($file)) !== false) {
        $registrations[] = array_combine($headers, $row);
    }
    
    fclose($file);
}

// Export functionality
if (isset($_GET['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="converge_2026_export_' . date('Y-m-d') . '.csv"');
    readfile($csvFile);
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CONVERGE 2026</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --pgdav-green: #0d5c4b;
            --pgdav-dark-green: #094538;
        }
        
        body {
            background: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .header {
            background: linear-gradient(135deg, var(--pgdav-green) 0%, var(--pgdav-dark-green) 100%);
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--pgdav-green);
        }
        
        .table-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        
        .btn-pgdav {
            background: var(--pgdav-green);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-pgdav:hover {
            background: var(--pgdav-dark-green);
            color: white;
            transform: translateY(-2px);
        }
        
        .search-box {
            margin-bottom: 20px;
        }
        
        .badge-custom {
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="fas fa-chart-line"></i> CONVERGE 2026</h1>
                    <p class="mb-0">Admin Dashboard - Registration Management</p>
                </div>
                <div>
                    <a href="?export=true" class="btn btn-light me-2">
                        <i class="fas fa-download"></i> Export CSV
                    </a>
                    <a href="?logout=true" class="btn btn-outline-light">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card text-center">
                    <i class="fas fa-users fa-3x mb-3" style="color: var(--pgdav-green);"></i>
                    <div class="stats-number"><?php echo count($registrations); ?></div>
                    <p class="text-muted mb-0">Total Registrations</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card text-center">
                    <i class="fas fa-calendar-day fa-3x mb-3" style="color: var(--pgdav-green);"></i>
                    <div class="stats-number">
                        <?php 
                        $today = date('Y-m-d');
                        $todayCount = 0;
                        foreach ($registrations as $reg) {
                            if (isset($reg['Timestamp']) && strpos($reg['Timestamp'], $today) === 0) {
                                $todayCount++;
                            }
                        }
                        echo $todayCount;
                        ?>
                    </div>
                    <p class="text-muted mb-0">Today's Registrations</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card text-center">
                    <i class="fas fa-file-alt fa-3x mb-3" style="color: var(--pgdav-green);"></i>
                    <div class="stats-number"><?php echo count($registrations); ?></div>
                    <p class="text-muted mb-0">Resumes Submitted</p>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3><i class="fas fa-list"></i> All Registrations</h3>
                <div class="search-box" style="width: 300px;">
                    <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search by name, email, college...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="registrationTable">
                    <thead style="background: var(--pgdav-green); color: white;">
                        <tr>
                            <th>#</th>
                            <th>Registration ID</th>
                            <th>Timestamp</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>College</th>
                            <th>Course</th>
                            <th>Year</th>
                            <th>Companies</th>
                            <th>Resume</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($registrations)): ?>
                            <tr>
                                <td colspan="11" class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No registrations yet. When students register, they'll appear here.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($registrations as $index => $reg): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><span class="badge badge-custom bg-success"><?php echo htmlspecialchars($reg['Registration ID'] ?? ''); ?></span></td>
                                    <td><?php echo htmlspecialchars($reg['Timestamp'] ?? ''); ?></td>
                                    <td><strong><?php echo htmlspecialchars($reg['Name'] ?? ''); ?></strong></td>
                                    <td><?php echo htmlspecialchars($reg['Email'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($reg['Phone'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($reg['College'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($reg['Course'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($reg['Year'] ?? ''); ?></td>
                                    <td><small><?php echo htmlspecialchars($reg['Companies'] ?? ''); ?></small></td>
                                    <td>
                                        <?php if (!empty($reg['Resume Name'])): ?>
                                            <i class="fas fa-file-pdf text-danger"></i>
                                            <small><?php echo htmlspecialchars($reg['Resume Name']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-center mt-4 mb-5">
            <p class="text-muted">
                <i class="fas fa-shield-alt"></i> Admin Dashboard - The Placement Cell, P.G.D.A.V. College
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = document.getElementById('registrationTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let row of rows) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            }
        });

        // Auto-refresh every 30 seconds
        setTimeout(function() {
            location.reload();
        }, 30000);
    </script>
</body>
</html>