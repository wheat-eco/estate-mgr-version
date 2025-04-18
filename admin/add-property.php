<?php
/**
 * Add property page for Love View Estate admin
 */
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Check if user is logged in
requireLogin();

// Get regions for dropdown
$regionsQuery = "SELECT * FROM regions ORDER BY name";
$regionsResult = $conn->query($regionsQuery);

// Process form submission
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Basic validation
    if (empty($_POST['title']) || empty($_POST['description']) || empty($_POST['price'])) {
        $error = 'Please fill in all required fields';
    } else {
        try {
            // Start transaction
            $conn->begin_transaction();
            
            // Generate slug from title
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['title'])));
            
            // Check if slug exists
            $checkSlug = $conn->prepare("SELECT id FROM properties WHERE slug = ?");
            $checkSlug->bind_param("s", $slug);
            $checkSlug->execute();
            $slugResult = $checkSlug->get_result();
            
            if ($slugResult->num_rows > 0) {
                // Append random string to make slug unique
                $slug .= '-' . substr(md5(time()), 0, 6);
            }
            
            // Insert property
            $stmt = $conn->prepare("INSERT INTO properties (
                title, slug, location, area_id, bedrooms, bathrooms, 
                property_type, postcode, price, status, available_date, 
                description, property_category
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $availableDate = !empty($_POST['available_date']) ? $_POST['available_date'] : null;
            
            $stmt->bind_param(
                "sssiiissdssss",
                $_POST['title'],
                $slug,
                $_POST['location'],
                $_POST['area_id'],
                $_POST['bedrooms'],
                $_POST['bathrooms'],
                $_POST['property_type'],
                $_POST['postcode'],
                $_POST['price'],
                $_POST['status'],
                $availableDate,
                $_POST['description'],
                $_POST['property_category']
            );
            
            if ($stmt->execute()) {
                $propertyId = $conn->insert_id;
                
                // Insert additional property details
                $hasAdditionalDetails = !empty($_POST['furnished_status']) || 
                                       !empty($_POST['epc_rating']) || 
                                       !empty($_POST['council_tax_band']) || 
                                       !empty($_POST['deposit_amount']) || 
                                       !empty($_POST['pets_policy']) || 
                                       !empty($_POST['smoking_policy']);
                
                if ($hasAdditionalDetails) {
                    $detailsStmt = $conn->prepare("INSERT INTO property_details 
                        (property_id, furnished_status, epc_rating, council_tax_band, deposit_amount, pets_policy, smoking_policy) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)");
                    
                    $furnished = !empty($_POST['furnished_status']) ? $_POST['furnished_status'] : null;
                    $epc = !empty($_POST['epc_rating']) ? $_POST['epc_rating'] : null;
                    $council = !empty($_POST['council_tax_band']) ? $_POST['council_tax_band'] : null;
                    $deposit = !empty($_POST['deposit_amount']) ? $_POST['deposit_amount'] : null;
                    $pets = !empty($_POST['pets_policy']) ? $_POST['pets_policy'] : null;
                    $smoking = !empty($_POST['smoking_policy']) ? $_POST['smoking_policy'] : null;
                    
                    $smoking = !empty($_POST['smoking_policy']) ? $_POST['smoking_policy'] : null;
                    
                    $detailsStmt->bind_param("isssdss", $propertyId, $furnished, $epc, $council, $deposit, $pets, $smoking);
                    $detailsStmt->execute();
                }
                
                // Insert property features
                if (!empty($_POST['features'])) {
                    $features = explode("\n", $_POST['features']);
                    $featureStmt = $conn->prepare("INSERT INTO property_features (property_id, feature_name) VALUES (?, ?)");
                    
                    foreach ($features as $feature) {
                        $feature = trim($feature);
                        if (!empty($feature)) {
                            $featureStmt->bind_param("is", $propertyId, $feature);
                            $featureStmt->execute();
                        }
                    }
                }
                
                // Handle multiple image uploads
                $uploadDir = '../uploads/properties/' . $propertyId . '/';
                
                // Create directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Check if any images were uploaded
                $hasImages = false;
                $featuredImageSet = false;
                
                // Process each uploaded image
                if (!empty($_FILES['images']['name'][0])) {
                    $totalImages = count($_FILES['images']['name']);
                    
                    for ($i = 0; $i < $totalImages; $i++) {
                        if (!empty($_FILES['images']['name'][$i])) {
                            $fileName = basename($_FILES['images']['name'][$i]);
                            $targetFilePath = $uploadDir . $fileName;
                            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                            
                            // Allow certain file formats
                            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
                            if (in_array(strtolower($fileType), $allowTypes)) {
                                // Upload file to server
                                if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $targetFilePath)) {
                                    $hasImages = true;
                                    
                                    // Set first image as featured if none is set
                                    $isFeatured = (!$featuredImageSet) ? 1 : 0;
                                    if ($isFeatured) {
                                        $featuredImageSet = true;
                                    }
                                    
                                    // Insert image info into the database
                                    $imgStmt = $conn->prepare("INSERT INTO property_images (property_id, image_path, is_featured) VALUES (?, ?, ?)");
                                    $imagePath = '/uploads/properties/' . $propertyId . '/' . $fileName;
                                    $imgStmt->bind_param("isi", $propertyId, $imagePath, $isFeatured);
                                    $imgStmt->execute();
                                }
                            }
                        }
                    }
                }
                
                // Handle EPC document upload
                if (!empty($_FILES['epc_document']['name'])) {
                    $epcDir = '../uploads/documents/' . $propertyId . '/';
                    
                    // Create directory if it doesn't exist
                    if (!file_exists($epcDir)) {
                        mkdir($epcDir, 0777, true);
                    }
                    
                    $epcFileName = basename($_FILES['epc_document']['name']);
                    $epcTargetPath = $epcDir . $epcFileName;
                    $epcFileType = pathinfo($epcTargetPath, PATHINFO_EXTENSION);
                    
                    // Allow PDF files
                    if (strtolower($epcFileType) == 'pdf') {
                        if (move_uploaded_file($_FILES['epc_document']['tmp_name'], $epcTargetPath)) {
                            // Insert document info into the database
                            $docStmt = $conn->prepare("INSERT INTO property_documents (property_id, document_name, document_path, document_type) VALUES (?, ?, ?, 'EPC')");
                            $docPath = '/uploads/documents/' . $propertyId . '/' . $epcFileName;
                            $docName = 'Energy Performance Certificate';
                            $docStmt->bind_param("iss", $propertyId, $docName, $docPath);
                            $docStmt->execute();
                        }
                    }
                }
                
                // Commit transaction
                $conn->commit();
                $success = 'Property added successfully!';
            } else {
                $conn->rollback();
                $error = 'Error adding property: ' . $conn->error;
            }
        } catch (Exception $e) {
            $conn->rollback();
            $error = 'Error: ' . $e->getMessage();
        }
    }
}

// Get areas for the first region (will be updated via AJAX)
$areasQuery = "SELECT * FROM areas WHERE region_id = (SELECT id FROM regions LIMIT 1) ORDER BY name";
$areasResult = $conn->query($areasQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property - Love View Estate</title>
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
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            font-family: inherit;
        }
        
        .form-group textarea {
            min-height: 150px;
            resize: vertical;
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
        
        .required {
            color: #f44336;
        }
        
        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        
        .image-preview {
            width: 100px;
            height: 100px;
            border-radius: 4px;
            object-fit: cover;
            border: 1px solid #ddd;
        }
        
        .mt-4 {
            margin-top: 1.5rem;
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
                    <li><a href="add-property.php" class="active">Add Property</a></li>
                    <li><a href="users.php">Users</a></li>
                    <li><a href="viewing-requests.php">Viewing Requests</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <header class="header">
                <h1>Add New Property</h1>
                
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
            
            <form method="post" action="" enctype="multipart/form-data">
                <div class="card">
                    <h2>Property Details</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="title">Property Title <span class="required">*</span></label>
                            <input type="text" id="title" name="title" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="property_category">Property Category <span class="required">*</span></label>
                            <select id="property_category" name="property_category" required>
                                <option value="">Select Category</option>
                                <option value="rent">For Rent</option>
                                <option value="sale">For Sale</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="location">Address <span class="required">*</span></label>
                            <input type="text" id="location" name="location" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="postcode">Postcode <span class="required">*</span></label>
                            <input type="text" id="postcode" name="postcode" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="region_id">Region <span class="required">*</span></label>
                            <select id="region_id" name="region_id" required>
                                <option value="">Select Region</option>
                                <?php while ($region = $regionsResult->fetch_assoc()): ?>
                                    <option value="<?php echo $region['id']; ?>"><?php echo $region['name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="area_id">Area <span class="required">*</span></label>
                            <select id="area_id" name="area_id" required>
                                <option value="">Select Region First</option>
                                <?php while ($area = $areasResult->fetch_assoc()): ?>
                                    <option value="<?php echo $area['id']; ?>"><?php echo $area['name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="bedrooms">Bedrooms <span class="required">*</span></label>
                            <input type="number" id="bedrooms" name="bedrooms" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="bathrooms">Bathrooms <span class="required">*</span></label>
                            <input type="number" id="bathrooms" name="bathrooms" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="property_type">Property Type <span class="required">*</span></label>
                            <select id="property_type" name="property_type" required>
                                <option value="">Select Type</option>
                                <option value="Apartment">Apartment</option>
                                <option value="Terraced">Terraced</option>
                                <option value="Semi-Detached">Semi-Detached</option>
                                <option value="Detached">Detached</option>
                                <option value="Bungalow">Bungalow</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Price <span class="required">*</span></label>
                            <input type="number" id="price" name="price" min="0" step="0.01" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="status">Status <span class="required">*</span></label>
                            <select id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="AVAILABLE">Available</option>
                                <option value="LET AGREED">Let Agreed</option>
                                <option value="FOR SALE">For Sale</option>
                                <option value="UNDER OFFER">Under Offer</option>
                                <option value="SOLD STC">Sold STC</option>
                                <option value="SOLD">Sold</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="available_date">Available Date</label>
                            <input type="date" id="available_date" name="available_date">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description <span class="required">*</span></label>
                        <textarea id="description" name="description" required></textarea>
                    </div>
                </div>
                
                <div class="card">
                    <h2>Additional Property Details</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="furnished_status">Furnished Status</label>
                            <select id="furnished_status" name="furnished_status">
                                <option value="">Select Status</option>
                                <option value="Furnished">Furnished</option>
                                <option value="Unfurnished">Unfurnished</option>
                                <option value="Part Furnished">Part Furnished</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="epc_rating">EPC Rating</label>
                            <select id="epc_rating" name="epc_rating">
                                <option value="">Select Rating</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                                <option value="G">G</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="council_tax_band">Council Tax Band</label>
                            <select id="council_tax_band" name="council_tax_band">
                                <option value="">Select Band</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                                <option value="G">G</option>
                                <option value="H">H</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="deposit_amount">Deposit Amount</label>
                            <input type="number" id="deposit_amount" name="deposit_amount" min="0" step="0.01">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="pets_policy">Pets Policy</label>
                            <select id="pets_policy" name="pets_policy">
                                <option value="">Select Policy</option>
                                <option value="Allowed">Allowed</option>
                                <option value="Not Allowed">Not Allowed</option>
                                <option value="Considered">Considered</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="smoking_policy">Smoking Policy</label>
                            <select id="smoking_policy" name="smoking_policy">
                                <option value="">Select Policy</option>
                                <option value="Allowed">Allowed</option>
                                <option value="Not Allowed">Not Allowed</option>
                                <option value="Outside Only">Outside Only</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <h2>Property Features</h2>
                    
                    <div class="form-group">
                        <label for="features">Features (One per line)</label>
                        <textarea id="features" name="features" placeholder="Gas Central Heating&#10;Double Glazing&#10;Private Garden"></textarea>
                    </div>
                </div>
                
                <div class="card">
                    <h2>Property Images</h2>
                    
                    <div class="form-group">
                        <label for="images">Upload Images (First image will be featured)</label>
                        <input type="file" id="images" name="images[]" accept="image/*" multiple onchange="previewImages(this)">
                        <p class="help-text">You can select multiple images at once. The first image will be used as the featured image.</p>
                        
                        <div class="image-preview-container" id="imagePreviewContainer"></div>
                    </div>
                </div>
                
                <div class="card">
                    <h2>Property Documents</h2>
                    
                    <div class="form-group">
                        <label for="epc_document">EPC Document (PDF)</label>
                        <input type="file" id="epc_document" name="epc_document" accept=".pdf">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Add Property</button>
            </form>
        </main>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Region and area dropdown
        const regionSelect = document.getElementById('region_id');
        const areaSelect = document.getElementById('area_id');
        
        regionSelect.addEventListener('change', function() {
            const regionId = this.value;
            
            // Clear area dropdown
            areaSelect.innerHTML = '<option value="">Select Area</option>';
            
            if (regionId) {
                // Fetch areas for selected region
                fetch(`get-areas.php?region_id=${regionId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(area => {
                            const option = document.createElement('option');
                            option.value = area.id;
                            option.textContent = area.name;
                            areaSelect.appendChild(option);
                        });
                    });
            }
        });
    });
    
    // Image preview function
    function previewImages(input) {
        const previewContainer = document.getElementById('imagePreviewContainer');
        previewContainer.innerHTML = '';
        
        if (input.files) {
            for (let i = 0; i < input.files.length; i++) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'image-preview';
                    previewContainer.appendChild(img);
                }
                
                reader.readAsDataURL(input.files[i]);
            }
        }
    }
    </script>
</body>
</html>