<?php
/**
 * Admin dashboard for Love View Estate
 */
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Check if user is logged in
requireLogin();

// Get property counts
$rentQuery = "SELECT COUNT(*) as count FROM properties WHERE property_category = 'rent'";
$saleQuery = "SELECT COUNT(*) as count FROM properties WHERE property_category = 'sale'";

$rentResult = $conn->query($rentQuery);
$saleResult = $conn->query($saleQuery);

$rentCount = $rentResult->num_rows > 0 ? $rentResult->fetch_assoc()['count'] : 0;
$saleCount = $saleResult->num_rows > 0 ? $saleResult->fetch_assoc()['count'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Love View Estate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background-color: #323232;
            color: #fff;
            padding: 1rem 0;
        }
        
        .sidebar-header {
            padding: 1rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1rem;
        }
        
        .sidebar-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-nav li {
            margin-bottom: 0.25rem;
        }
        
        .sidebar-nav a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: #fff;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .main-content {
            flex: 1;
            padding: 2rem;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .header h1 {
            margin: 0;
            font-size: 1.8rem;
            color: #323232;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-name {
            font-weight: 500;
        }
        
        .logout-btn {
            padding: 0.5rem 1rem;
            background-color: #e4b611;
            color: #323232;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }
        
        .stat-card h3 {
            margin-top: 0;
            margin-bottom: 0.5rem;
            color: #666;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #323232;
        }
        
        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .action-button {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            text-align: center;
            text-decoration: none;
            color: #323232;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .action-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .action-button h3 {
            margin-top: 0;
            margin-bottom: 0.5rem;
        }
        
        .action-button p {
            margin: 0;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Love View Estate</h2>
                <p>Admin Panel</p>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="index.php" class="active">Dashboard</a></li>
                    <li><a href="properties.php">Properties</a></li>
                    <li><a href="add-property.php">Add Property</a></li>
                    <li><a href="users.php">Users</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <header class="header">
                <h1>Dashboard</h1>
                
                <div class="user-info">
                    <span class="user-name">Welcome, <?php echo $_SESSION['full_name']; ?></span>
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </header>
            
            <div class="stats-cards">
                <div class="stat-card">
                    <h3>Rental Properties</h3>
                    <p class="stat-number"><?php echo $rentCount; ?></p>
                </div>
                
                <div class="stat-card">
                    <h3>Sale Properties</h3>
                    <p class="stat-number"><?php echo $saleCount; ?></p>
                </div>
            </div>
            
            <h2>Quick Actions</h2>
            
            <div class="action-buttons">
                <a href="add-property.php" class="action-button">
                    <h3>Add New Property</h3>
                    <p>Create a new property listing</p>
                </a>
                
                <a href="properties.php" class="action-button">
                    <h3>Manage Properties</h3>
                    <p>View and edit existing properties</p>
                </a>
                
                <a href="users.php" class="action-button">
                    <h3>Manage Users</h3>
                    <p>Add or edit admin users</p>
                </a>
            </div>
        </main>
    </div>
</body>
</html>