<?php
/**
 * Temporary script to create admin credentials
 * DELETE THIS FILE AFTER USE!
 */

// Database configuration - update these with your actual database details
$db_host = 'srv1216.hstgr.io';
$db_user = 'u368036953_muizdev'; // Change to your database username
$db_pass = 'Adeniyi2020#'; // Change to your database password
$db_name = 'u368036953_loveview';


// Initialize variables
$success = false;
$error = '';
$username = '';
$email = '';
$full_name = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $email = $_POST['email'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    
    // Basic validation
    if (empty($username) || empty($password) || empty($confirm_password) || empty($email) || empty($full_name)) {
        $error = 'All fields are required';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        try {
            // Create database connection
            $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
            
            // Check connection
            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }
            
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
                
                // Insert new admin user
                $stmt = $conn->prepare("INSERT INTO users (username, password, email, full_name, role) VALUES (?, ?, ?, ?, 'admin')");
                $stmt->bind_param("ssss", $username, $hashed_password, $email, $full_name);
                
                if ($stmt->execute()) {
                    $success = true;
                } else {
                    $error = 'Error creating user: ' . $conn->error;
                }
            }
            
            // Close connection
            $stmt->close();
            $conn->close();
            
        } catch (Exception $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #e4b611;
            color: #333;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #f5c728;
        }
        .warning {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            margin-top: 30px;
            border-radius: 4px;
            border: 1px solid #ffeeba;
        }
    </style>
</head>
<body>
    <h1>Create Admin User</h1>
    
    <?php if ($success): ?>
        <div class="alert alert-success">
            <strong>Success!</strong> Admin user created successfully. You can now log in to the admin panel.
            <p>Please delete this file immediately for security reasons.</p>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <strong>Error!</strong> <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <?php if (!$success): ?>
        <form method="post" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required>
            </div>
            
            <button type="submit">Create Admin User</button>
        </form>
    <?php endif; ?>
    
    <div class="warning">
        <strong>Warning!</strong> This file creates an admin user with full access to your system. 
        Delete this file immediately after creating your admin user.
    </div>
</body>
</html>