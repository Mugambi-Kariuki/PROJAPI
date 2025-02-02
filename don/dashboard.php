<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Don Carlo Agency</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar bg-dark text-white p-3">
            <h2 class="text-center">Don Carlo Agency</h2>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="dashboard.php" class="nav-link text-white">🏠 Dashboard</a></li>
                <li class="nav-item"><a href="profile.php" class="nav-link text-white">👤 Profile</a></li>
                <li class="nav-item"><a href="agents.php" class="nav-link text-white">📋 Agents</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link text-white">🚪 Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-grow-1 p-4">
            <nav class="navbar navbar-light bg-light">
                <span class="navbar-brand">Welcome, <?php echo $_SESSION['user']['name']; ?>!</span>
            </nav>
            <div class="container mt-4">
                <h3>Dashboard Overview</h3>
                <p>Select an option from the sidebar to get started.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
