<?php
/**
 * Valuation Requests Management for Love View Estate admin
 */
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Check if user is logged in
requireLogin();

// Process status update
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'update_status') {
        $requestId = $_POST['request_id'] ?? 0;
        $status = $_POST['status'] ?? '';
        $notes = $_POST['notes'] ?? '';
        
        if ($requestId > 0 && !empty($status)) {
            $stmt = $conn->prepare("UPDATE valuation_requests SET status = ?, notes = ?, updated_at = NOW() WHERE id = ?");
            $stmt->bind_param("ssi", $status, $notes, $requestId);
            
            if ($stmt->execute()) {
                $success = 'Valuation request status updated successfully';
            } else {
                $error = 'Error updating status: ' . $conn->error;
            }
        }
    } elseif ($_POST['action'] == 'delete') {
        $requestId = $_POST['request_id'] ?? 0;
        
        if ($requestId > 0) {
            $stmt = $conn->prepare("DELETE FROM valuation_requests WHERE id = ?");
            $stmt->bind_param("i", $requestId);
            
            if ($stmt->execute()) {
                $success = 'Valuation request deleted successfully';
            } else {
                $error = 'Error deleting request: ' . $conn->error;
            }
        }
    }
}

// Get filter parameters
$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$type = isset($_GET['type']) ? $_GET['type'] : 'all';

// Build query based on filter
$query = "SELECT * FROM valuation_requests WHERE 1=1";

if ($status != 'all') {
    $query .= " AND status = '$status'";
}

if ($type != 'all') {
    $query .= " AND valuation_type = '$type'";
}

$query .= " ORDER BY created_at DESC";

$requestsResult = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valuation Requests - Love View Estate</title>
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
        
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-badge.pending {
            background-color: #fff8e1;
            color: #f57f17;
        }
        
        .status-badge.in-progress {
            background-color: #e3f2fd;
            color: #0d47a1;
        }
        
        .status-badge.completed {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        
        .status-badge.cancelled {
            background-color: #ffebee;
            color: #c62828;
        }
        
        .type-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .type-badge.sale {
            background-color: #e8eaf6;
            color: #3f51b5;
        }
        
        .type-badge.rental {
            background-color: #f3e5f5;
            color: #9c27b0;
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
            width: 500px;
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
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
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
                    <li><a href="areas.php">Areas</a></li>
                    <li><a href="users.php">Users</a></li>
                    <li><a href="viewing-requests.php">Viewing Requests</a></li>
                    <li><a href="valuation-requests.php" class="active">Valuation Requests</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <header class="header">
                <h1>Valuation Requests</h1>
                
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
                    <h2>Valuation Requests</h2>
                </div>
                
                <div class="filter-tabs">
                    <a href="valuation-requests.php" class="filter-tab <?php echo $status == 'all' && $type == 'all' ? 'active' : ''; ?>">All Requests</a>
                    <a href="valuation-requests.php?status=pending" class="filter-tab <?php echo $status == 'pending' ? 'active' : ''; ?>">Pending</a>
                    <a href="valuation-requests.php?status=in-progress" class="filter-tab <?php echo $status == 'in-progress' ? 'active' : ''; ?>">In Progress</a>
                    <a href="valuation-requests.php?status=completed" class="filter-tab <?php echo $status == 'completed' ? 'active' : ''; ?>">Completed</a>
                    <a href="valuation-requests.php?status=cancelled" class="filter-tab <?php echo $status == 'cancelled' ? 'active' : ''; ?>">Cancelled</a>
                    <a href="valuation-requests.php?type=sale" class="filter-tab <?php echo $type == 'sale' ? 'active' : ''; ?>">Sales</a>
                    <a href="valuation-requests.php?type=rental" class="filter-tab <?php echo $type == 'rental' ? 'active' : ''; ?>">Rentals</a>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Property</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Requested On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($requestsResult->num_rows > 0): ?>
                            <?php while ($request = $requestsResult->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $request['id']; ?></td>
                                    <td><?php echo $request['full_name']; ?></td>
                                    <td>
                                        <?php echo $request['email']; ?><br>
                                        <?php echo $request['phone']; ?>
                                    </td>
                                    <td>
                                        <?php echo $request['postcode']; ?><br>
                                        <?php echo $request['bedrooms']; ?> bed <?php echo $request['property_type']; ?>
                                    </td>
                                    <td>
                                        <span class="type-badge <?php echo $request['valuation_type']; ?>">
                                            <?php echo ucfirst($request['valuation_type']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge <?php echo $request['status']; ?>">
                                            <?php echo ucfirst($request['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d-m-Y H:i', strtotime($request['created_at'])); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-info" onclick="viewDetails(<?php echo $request['id']; ?>, '<?php echo htmlspecialchars($request['notes'], ENT_QUOTES); ?>')">View</button>
                                            <button class="btn btn-primary" onclick="updateStatus(<?php echo $request['id']; ?>, '<?php echo $request['status']; ?>', '<?php echo htmlspecialchars($request['notes'], ENT_QUOTES); ?>')">Update</button>
                                            <form method="post" action="" onsubmit="return confirm('Are you sure you want to delete this request?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 2rem;">No valuation requests found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <!-- View Details Modal -->
    <div id="viewDetailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Valuation Request Details</h3>
                <button class="modal-close" onclick="closeModal('viewDetailsModal')">&times;</button>
            </div>
            <div class="modal-body">
                <h4>Notes:</h4>
                <p id="requestNotes"></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="closeModal('viewDetailsModal')">Close</button>
            </div>
        </div>
    </div>
    
    <!-- Update Status Modal -->
    <div id="updateStatusModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Update Request Status</h3>
                <button class="modal-close" onclick="closeModal('updateStatusModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="updateStatusForm" method="post" action="">
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" id="updateRequestId" name="request_id" value="">
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="pending">Pending</option>
                            <option value="in-progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" class="form-control" placeholder="Add notes about this valuation request"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="document.getElementById('updateStatusForm').submit()">Update</button>
                <button class="btn" onclick="closeModal('updateStatusModal')">Cancel</button>
            </div>
        </div>
    </div>
    
    <script>
    function viewDetails(id, notes) {
        document.getElementById('requestNotes').textContent = notes || 'No notes available';
        openModal('viewDetailsModal');
    }
    
    function updateStatus(id, currentStatus, notes) {
        document.getElementById('updateRequestId').value = id;
        document.getElementById('status').value = currentStatus;
        document.getElementById('notes').value = notes || '';
        openModal('updateStatusModal');
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