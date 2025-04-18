<?php
/**
 * Regions Management for Love View Estate admin
 */
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Check if user is logged in
requireLogin();

// Process form submissions
$success = '';
$error = '';

// Add new region
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add_region') {
        $regionName = trim($_POST['region_name']);
        
        if (empty($regionName)) {
            $error = 'Region name cannot be empty';
        } else {
            // Check if region already exists
            $checkStmt = $conn->prepare("SELECT id FROM regions WHERE name = ?");
            $checkStmt->bind_param("s", $regionName);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            
            if ($checkResult->num_rows > 0) {
                $error = 'Region already exists';
            } else {
                // Insert new region
                $stmt = $conn->prepare("INSERT INTO regions (name) VALUES (?)");
                $stmt->bind_param("s", $regionName);
                
                if ($stmt->execute()) {
                    $success = 'Region added successfully';
                } else {
                    $error = 'Error adding region: ' . $conn->error;
                }
            }
        }
    } elseif ($_POST['action'] == 'edit_region') {
        $regionId = intval($_POST['region_id']);
        $regionName = trim($_POST['region_name']);
        
        if (empty($regionName)) {
            $error = 'Region name cannot be empty';
        } else {
            // Update region
            $stmt = $conn->prepare("UPDATE regions SET name = ? WHERE id = ?");
            $stmt->bind_param("si", $regionName, $regionId);
            
            if ($stmt->execute()) {
                $success = 'Region updated successfully';
            } else {
                $error = 'Error updating region: ' . $conn->error;
            }
        }
    } elseif ($_POST['action'] == 'delete_region') {
        $regionId = intval($_POST['region_id']);
        
        // Check if region has areas
        $checkStmt = $conn->prepare("SELECT COUNT(*) as count FROM areas WHERE region_id = ?");
        $checkStmt->bind_param("i", $regionId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $areaCount = $checkResult->fetch_assoc()['count'];
        
        if ($areaCount > 0) {
            $error = 'Cannot delete region with existing areas. Delete areas first.';
        } else {
            // Delete region
            $stmt = $conn->prepare("DELETE FROM regions WHERE id = ?");
            $stmt->bind_param("i", $regionId);
            
            if ($stmt->execute()) {
                $success = 'Region deleted successfully';
            } else {
                $error = 'Error deleting region: ' . $conn->error;
            }
        }
    }
}

// Get all regions
$regionsQuery = "SELECT * FROM regions ORDER BY name";
$regionsResult = $conn->query($regionsQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Regions - Love View Estate</title>
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
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .btn {
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
        }
        
        .btn-primary {
            background-color: #e4b611;
            color: #323232;
        }
        
        .btn-secondary {
            background-color: #f0f0f0;
            color: #323232;
        }
        
        .btn-danger {
            background-color: #f44336;
            color: #fff;
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
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: #fff;
            border-radius: 8px;
            width: 400px;
            padding: 2rem;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .modal-header h3 {
            margin: 0;
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        .modal-body {
            margin-bottom: 1.5rem;
        }
        
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
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
                    <li><a href="properties.php">Properties</a></li>
                    <li><a href="add-property.php">Add Property</a></li>
                    <li><a href="regions.php" class="active">Regions</a></li>
                    <li><a href="areas.php">Areas</a></li>
                    <li><a href="users.php">Users</a></li>
                    <li><a href="viewing-requests.php">Viewing Requests</a></li>
                    <li><a href="valuation-requests.php">Valuation Requests</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <header class="header">
                <h1>Manage Regions</h1>
                
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
                    <h2>Add New Region</h2>
                </div>
                
                <form method="post" action="">
                    <input type="hidden" name="action" value="add_region">
                    
                    <div class="form-group">
                        <label for="region_name">Region Name</label>
                        <input type="text" id="region_name" name="region_name" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Add Region</button>
                </form>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h2>Existing Regions</h2>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Region Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($regionsResult->num_rows > 0): ?>
                            <?php while ($region = $regionsResult->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $region['id']; ?></td>
                                    <td><?php echo $region['name']; ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-secondary" onclick="openEditModal(<?php echo $region['id']; ?>, '<?php echo $region['name']; ?>')">Edit</button>
                                            <button class="btn btn-danger" onclick="openDeleteModal(<?php echo $region['id']; ?>, '<?php echo $region['name']; ?>')">Delete</button>
                                            <a href="areas.php?region_id=<?php echo $region['id']; ?>" class="btn btn-primary">Manage Areas</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 2rem;">No regions found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <!-- Edit Region Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Region</h3>
                <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="post" action="">
                    <input type="hidden" name="action" value="edit_region">
                    <input type="hidden" id="edit_region_id" name="region_id" value="">
                    
                    <div class="form-group">
                        <label for="edit_region_name">Region Name</label>
                        <input type="text" id="edit_region_name" name="region_name" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="document.getElementById('editForm').submit()">Save Changes</button>
                <button class="btn btn-secondary" onclick="closeModal('editModal')">Cancel</button>
            </div>
        </div>
    </div>
    
    <!-- Delete Region Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Delete Region</h3>
                <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the region <strong id="delete_region_name"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
                
                <form id="deleteForm" method="post" action="">
                    <input type="hidden" name="action" value="delete_region">
                    <input type="hidden" id="delete_region_id" name="region_id" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" onclick="document.getElementById('deleteForm').submit()">Delete</button>
                <button class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
            </div>
        </div>
    </div>
    
    <script>
    function openEditModal(id, name) {
        document.getElementById('edit_region_id').value = id;
        document.getElementById('edit_region_name').value = name;
        openModal('editModal');
    }
    
    function openDeleteModal(id, name) {
        document.getElementById('delete_region_id').value = id;
        document.getElementById('delete_region_name').textContent = name;
        openModal('deleteModal');
    }
    
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'flex';
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }
    </script>
</body>
</html>