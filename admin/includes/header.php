<?php
/**
 * Admin header for Love View Estate
 */
require_once 'includes/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - Admin Panel' : 'Admin Panel'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">
    <?php if(isset($additionalCss)): ?>
        <?php foreach($additionalCss as $css): ?>
            <link rel="stylesheet" href="css/<?php echo $css; ?>.css">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../img/love-view-logo.png" alt="Love View Estate" class="sidebar-logo">
                <h2>Admin Panel</h2>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="index.php" <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'class="active"' : ''; ?>><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li>
                        <a href="#" class="dropdown-toggle"><i class="fas fa-home"></i> Properties <i class="fas fa-chevron-down"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="properties.php?type=rent">Rental Properties</a></li>
                            <li><a href="properties.php?type=sale">Sale Properties</a></li>
                            <li><a href="add-property.php">Add New Property</a></li>
                        </ul>
                    </li>
                    <li><a href="viewing-requests.php"><i class="fas fa-calendar-check"></i> Viewing Requests</a></li>
                    <li><a href="regions-areas.php"><i class="fas fa-map-marker-alt"></i> Regions & Areas</a></li>
                    <?php if (isAdmin()): ?>
                    <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                    <?php endif; ?>
                    <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <header class="admin-header">
                <div class="header-search">
                    <form action="search.php" method="get">
                        <input type="text" name="q" placeholder="Search...">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                
                <div class="header-user">
                    <span class="user-name"><?php echo $_SESSION['full_name']; ?></span>
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </header>
            
            <div class="content-wrapper"></div>