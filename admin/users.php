<?php
/**
 * Users management page for Love View Estate admin
 */
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Check if user is logged in
requireLogin();

// Process form submission for adding new user
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $email = $_POST['email'] ?? '';
        $full_name = $_POST['full_name'] ?? '';
        $role = $_POST['role'] ?? 'agent';
        
        // Basic validation
        if (empty($username) || empty($password) || empty($email) || empty($full_name)) {
            $error = 'All fields are required';
        } else {
            // Check if username already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error = 'Username already exists';
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert new user
                $stmt = $conn->prepare("INSERT INTO users (username, password, email, full_name, role) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $username, $hashed_password, $email, $full_name, $role);
                
                if ($stmt->execute()) {
                    $success = 'User added successfully';
                } else {
                    $error = 'Error adding user: ' . $conn->error;
                }
            }
        }
    } elseif ($_POST['action'] == 'delete' && isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];
        
        // Don't allow deleting your own account
        if ($user_id == $_SESSION['user_id']) {
            $error = 'You cannot delete your own account';
        } else {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            
            if ($stmt->execute()) {
                $success = 'User deleted successfully';
            } else {
                $error = 'Error deleting user: ' . $conn->error;
            }
        }
    }
}

// Get all users
$usersQuery = "SELECT * FROM users ORDER BY username";
$usersResult = $conn->query($usersQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Love View Estate</title>
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
        
        .card h2 {
            margin-top: 0;
            margin-bottom: 1.5rem;
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
        
        .form-row {
            display: flex;
            gap: 1rem;
        }
        
        .form-row .form-group {
            flex: 1;
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
        
        .btn-danger {
            background-color: #f44336;
            color: #fff;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
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
                    <li><a href="users.php" class="active">Users</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <header class="header">
                <h1>Manage Users</h1>
                
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
                <h2>Add New User</h2>
                
                <form method="post" action="">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" id="full_name" name="full_name" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role">
                            <option value="admin">Admin</option>
                            <option value="agent">Agent</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Add User</button>
                </form>
            </div>
            
            <div class="card">
                <h2>Existing Users</h2>
                
                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($usersResult->num_rows > 0): ?>
                            <?php while ($user = $usersResult->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $user['username']; ?></td>
                                    <td><?php echo $user['full_name']; ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td><?php echo ucfirst($user['role']); ?></td>
                                    <td>
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <form method="post" action="" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        <?php else: ?>
                                            <em>Current User</em>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No users found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>