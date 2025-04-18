<?php
/**
 * Properties management page for Love View Estate admin
 */
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Check if user is logged in
requireLogin();

// Get property type from query string
$propertyType = isset($_GET['type']) ? $_GET['type'] : 'all';

// Process delete action
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $propertyId = $_POST['property_id'] ?? 0;
    
    if ($propertyId > 0) {
        // Delete property
        $stmt = $conn->prepare("DELETE FROM properties WHERE id = ?");
        $stmt->bind_param("i", $propertyId);
        
        if ($stmt->execute()) {
            $success = 'Property deleted successfully';
        } else {
            $error = 'Error deleting property: ' . $conn->error;
        }
    }
}

// Build query based on property type
$query = "SELECT p.*, a.name as area_name, r.name as region_name, 
          (SELECT image_path FROM property_images WHERE property_id = p.id AND is_featured = 1 LIMIT 1) as image 
          FROM properties p 
          JOIN areas a ON p.area_id = a.id 
          JOIN regions r ON a.region_id = r.id";

if ($propertyType != 'all') {
    $query .= " WHERE p.property_category = '$propertyType'";
}

$query .= " ORDER BY p.created_at DESC";

$propertiesResult = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Properties - Love View Estate</title>
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
        
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            padding: 1.5rem;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .card-header h2 {
            margin: 0;
            color: #323232;
        }
        
        .filter-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .filter-tab {
            padding: 0.5rem 1rem;
            background-color: #f0f0f0;
            color: #323232;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
        }
        
        .filter-tab.active {
            background-color: #e4b611;
            color: #323232;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        
        .alert-danger {
            background-color: #ffebee;
            color: #c62828;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th,
        table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        table th {
            background-color: #f5f5f5;
            font-weight: 600;
        }
        
        .property-image {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .no-image {
            width: 80px;
            height: 60px;
            background-color: #f0f0f0;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            color: #999;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-badge.available {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        
        .status-badge.let-agreed,
        .status-badge.under-offer,
        .status-badge.sold-stc {
            background-color: #fff8e1;
            color: #f57f17;
        }
        
        .status-badge.let,
        .status-badge.sold {
            background-color: #e3f2fd;
            color: #0d47a1;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn {
            padding: 0.5rem;
            border: none;
            border-radius: 4px;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-primary {
            background-color: #e4b611;
            color: #323232;
        }
        
        .btn-info {
            background-color: #2196f3;
            color: #fff;
        }
        
        .btn-danger {
            background-color: #f44336;
            color: #fff;
        }
        
        .add-property-btn {
            padding: 0.5rem 1rem;
            background-color: #e4b611;
            color: #323232;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
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
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="properties.php" class="active">Properties</a></li>
                    <li><a href="add-property.php">Add Property</a></li>
                    <li><a href="users.php">Users</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <header class="header">
                <h1>Manage Properties</h1>
                
                <div class="user-info">
                    <span class="user-name">Welcome, <?php echo $_SESSION['full_name']; ?></span>
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </header>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-header">
                    <h2>Properties</h2>
                    <a href="add-property.php" class="add-property-btn">+ Add Property</a>
                </div>
                
                <div class="filter-tabs">
                    <a href="properties.php" class="filter-tab <?php echo $propertyType == 'all' ? 'active' : ''; ?>">All Properties</a>
                    <a href="properties.php?type=rent" class="filter-tab <?php echo $propertyType == 'rent' ? 'active' : ''; ?>">For Rent</a>
                    <a href="properties.php?type=sale" class="filter-tab <?php echo $propertyType == 'sale' ? 'active' : ''; ?>">For Sale</a>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Location</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($propertiesResult->num_rows > 0): ?>
                            <?php while ($property = $propertiesResult->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <?php if ($property['image']): ?>
                                            <img src="<?php echo $property['image']; ?>" alt="<?php echo $property['title']; ?>" class="property-image">
                                        <?php else: ?>
                                            <div class="no-image">No Image</div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $property['title']; ?></td>
                                    <td><?php echo $property['area_name']; ?>, <?php echo $property['region_name']; ?></td>
                                    <td><?php echo ucfirst($property['property_category']); ?></td>
                                    <td>£<?php echo number_format($property['price'], 2); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo strtolower(str_replace(' ', '-', $property['status'])); ?>">
                                            <?php echo $property['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="edit-property.php?id=<?php echo $property['id']; ?>" class="btn btn-primary">Edit</a>
                                            <a href="../property-details.php?id=<?php echo $property['id']; ?>" target="_blank" class="btn btn-info">View</a>
                                            <form method="post" action="" onsubmit="return confirm('Are you sure you want to delete this property?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="property_id" value="<?php echo $property['id']; ?>">
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem;">No properties found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>