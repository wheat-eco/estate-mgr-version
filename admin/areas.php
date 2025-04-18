<?php
/**
 * Areas Management for Love View Estate admin
 */
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Check if user is logged in
requireLogin();

// Get region ID from query string
$regionId = isset($_GET['region_id']) ? intval($_GET['region_id']) : 0;

// Process form submissions
$success = '';
$error = '';

// Add new area
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add_area') {
        $areaName = trim($_POST['area_name']);
        $regionId = intval($_POST['region_id']);
        
        if (empty($areaName) || $regionId <= 0) {
            $error = 'Area name and region are required';
        } else {
            // Check if area already exists in this region
            $checkStmt = $conn->prepare("SELECT id FROM areas WHERE name = ? AND region_id = ?");
            $checkStmt->bind_param("si", $areaName, $regionId);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            
            if ($checkResult->num_rows > 0) {
                $error = 'Area already exists in this region';
            } else {
                // Insert new area
                $stmt = $conn->prepare("INSERT INTO areas (name, region_id) VALUES (?, ?)");
                $stmt->bind_param("si", $areaName, $regionId);
                
                if ($stmt->execute()) {
                    $success = 'Area added successfully';
                } else {
                    $error = 'Error adding area: ' . $conn->error;
                }
            }
        }
    } elseif ($_POST['action'] == 'edit_area') {
        $areaId = intval($_POST['area_id']);
        $areaName = trim($_POST['area_name']);
        $regionId = intval($_POST['region_id']);
        
        if (empty($areaName) || $regionId <= 0) {
            $error = 'Area name and region are required';
        } else {
            // Update area
            $stmt = $conn->prepare("UPDATE areas SET name = ?, region_id = ? WHERE id = ?");
            $stmt->bind_param("sii", $areaName, $regionId, $areaId);
            
            if ($stmt->execute()) {
                $success = 'Area updated successfully';
            } else {
                $error = 'Error updating area: ' . $conn->error;
            }
        }
    } elseif ($_POST['action'] == 'delete_area') {
        $areaId = intval($_POST['area_id']);
        
        // Check if area has properties
        $checkStmt = $conn->prepare("SELECT COUNT(*) as count FROM properties WHERE area_id = ?");
        $checkStmt->bind_param("i", $areaId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $propertyCount = $checkResult->fetch_assoc()['count'];
        
        if ($propertyCount > 0) {
            $error = 'Cannot delete area with existing properties. Update or delete properties first.';
        } else {
            // Delete area
            $stmt = $conn->prepare("DELETE FROM areas WHERE id = ?");
            $stmt->bind_param("i", $areaId);
            
            if ($stmt->execute()) {
                $success = 'Area deleted successfully';
            } else {
                $error = 'Error deleting area: ' . $conn->error;
            }
        }
    }
}

// Get all regions for dropdown
$regionsQuery = "SELECT * FROM regions ORDER BY name";
$regionsResult = $conn->query($regionsQuery);

// Get region name if region ID is provided
$regionName = '';
if ($regionId > 0) {
    $regionStmt = $conn->prepare("SELECT name FROM regions WHERE id = ?");
    $regionStmt->bind_param("i", $regionId);
    $regionStmt->execute();
    $regionResult = $regionStmt->get_result();
    
    if ($regionResult->num_rows > 0) {
        $regionName = $regionResult->fetch_assoc()['name'];
    }
}

// Get areas based on region ID
$areasQuery = "SELECT a.*, r.name as region_name FROM areas a JOIN regions r ON a.region_id = r.id";
if ($regionId > 0) {
    $areasQuery .= " WHERE a.region_id = $regionId";
}
$areasQuery .= " ORDER BY r.name, a.name";
$areasResult = $conn->query($areasQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Areas - Love View Estate</title>
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
        
        .form-group input,
        .form-group select {
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
                    <li><a href="regions.php">Regions</a></li>
                    <li><a href="areas.php" class="active">Areas</a></li>
                    <li><a href="users.php">Users</a></li>
                    <li><a href="viewing-requests.php">Viewing Requests</a></li>
                    <li><a href="valuation-requests.php">Valuation Requests</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <header class="header">
                <h1>
                    <?php if (!empty($regionName)): ?>
                        Manage Areas in <?php echo $regionName; ?>
                    <?php else: ?>
                        Manage Areas
                    <?php endif; ?>
                </h1>
                
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
                    <h2>Add New Area</h2>
                </div>
                
                <form method="post" action="">
                    <input type="hidden" name="action" value="add_area">
                    
                    <div class="form-group">
                        <label for="region_id">Region</label>
                        <select id="region_id" name="region_id" required>
                            <option value="">Select Region</option>
                            <?php while ($region = $regionsResult->fetch_assoc()): ?>
                                <option value="<?php echo $region['id']; ?>" <?php echo ($regionId == $region['id']) ? 'selected' : ''; ?>>
                                    <?php echo $region['name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="area_name">Area Name</label>
                        <input type="text" id="area_name" name="area_name" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Add Area</button>
                </form>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h2>Existing Areas</h2>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Area Name</th>
                            <th>Region</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($areasResult->num_rows > 0): ?>
                            <?php while ($area = $areasResult->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $area['id']; ?></td>
                                    <td><?php echo $area['name']; ?></td>
                                    <td><?php echo $area['region_name']; ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-secondary" onclick="openEditModal(<?php echo $area['id']; ?>, '<?php echo $area['name']; ?>', <?php echo $area['region_id']; ?>)">Edit</button>
                                            <button class="btn btn-danger" onclick="openDeleteModal(<?php echo $area['id']; ?>, '<?php echo $area['name']; ?>')">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 2rem;">No areas found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <!-- Edit Area Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Area</h3>
                <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="post" action="">
                    <input type="hidden" name="action" value="edit_area">
                    <input type="hidden" id="edit_area_id" name="area_id" value="">
                    
                    <div class="form-group">
                        <label for="edit_region_id">Region</label>
                        <select id="edit_region_id" name="region_id" required>
                            <option value="">Select Region</option>
                            <?php 
                            // Reset the regions result pointer
                            $regionsResult->data_seek(0);
                            while ($region = $regionsResult->fetch_assoc()): 
                            ?>
                                <option value="<?php echo $region['id']; ?>">
                                    <?php echo $region['name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_area_name">Area Name</label>
                        <input type="text" id="edit_area_name" name="area_name" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="document.getElementById('editForm').submit()">Save Changes</button>
                <button class="btn btn-secondary" onclick="closeModal('editModal')">Cancel</button>
            </div>
        </div>
    </div>
    
    <!-- Delete Area Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Delete Area</h3>
                <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the area <strong id="delete_area_name"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
                
                <form id="deleteForm" method="post" action="">
                    <input type="hidden" name="action" value="delete_area">
                    <input type="hidden" id="delete_area_id" name="area_id" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" onclick="document.getElementById('deleteForm').submit()">Delete</button>
                <button class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
            </div>
        </div>
    </div>
    
    <script>
    function openEditModal(id, name, regionId) {
        document.getElementById('edit_area_id').value = id;
        document.getElementById('edit_area_name').value = name;
        document.getElementById('edit_region_id').value = regionId;
        openModal('editModal');
    }
    
    function openDeleteModal(id, name) {
        document.getElementById('delete_area_id').value = id;
        document.getElementById('delete_area_name').textContent = name;
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