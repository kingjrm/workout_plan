<?php
session_start();

// Initialize session variables if not set
if (!isset($_SESSION['name'])) {
    $_SESSION['name'] = 'Your Name';
}
if (!isset($_SESSION['height'])) {
    $_SESSION['height'] = null;
}
if (!isset($_SESSION['weight'])) {
    $_SESSION['weight'] = null;
}
if (!isset($_SESSION['age'])) {
    $_SESSION['age'] = null;
}
if (!isset($_SESSION['equipment'])) {
    $_SESSION['equipment'] = 'Basic equipment';
}
if (!isset($_SESSION['goal'])) {
    $_SESSION['goal'] = 'Build muscle and lose fat';
}
if (!isset($_SESSION['profile_picture'])) {
    $_SESSION['profile_picture'] = '';
}
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}

if (isset($_POST['toggle_theme'])) {
    $_SESSION['theme'] = $_SESSION['theme'] == 'dark' ? 'light' : 'dark';
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle profile updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        // Validate and sanitize input
        $_SESSION['name'] = !empty($_POST['name']) ? trim($_POST['name']) : 'Your Name';
        $_SESSION['height'] = !empty($_POST['height']) && is_numeric($_POST['height']) ? floatval($_POST['height']) : null;
        $_SESSION['weight'] = !empty($_POST['weight']) && is_numeric($_POST['weight']) ? floatval($_POST['weight']) : null;
        $_SESSION['age'] = !empty($_POST['age']) && is_numeric($_POST['age']) ? intval($_POST['age']) : null;
        $_SESSION['equipment'] = !empty($_POST['equipment']) ? trim($_POST['equipment']) : 'Basic equipment';
        $_SESSION['goal'] = !empty($_POST['goal']) ? trim($_POST['goal']) : 'Build muscle and lose fat';

        // Handle profile picture upload
        if (!empty($_FILES['profile_picture']['name'])) {
            $upload_dir = 'uploads/';
            $file_name = $_FILES['profile_picture']['name'];
            $file_tmp = $_FILES['profile_picture']['tmp_name'];
            $file_size = $_FILES['profile_picture']['size'];
            $file_type = $_FILES['profile_picture']['type'];

            // Validate file type
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (in_array($file_type, $allowed_types) && in_array($file_extension, $allowed_extensions)) {
                // Check file size (max 5MB)
                if ($file_size <= 5 * 1024 * 1024) {
                    $unique_name = 'profile_' . time() . '_' . rand(1000, 9999) . '.' . $file_extension;
                    $file_path = $upload_dir . $unique_name;

                    if (move_uploaded_file($file_tmp, $file_path)) {
                        // Delete old profile picture if it exists and is not the default
                        if (!empty($_SESSION['profile_picture']) && $_SESSION['profile_picture'] !== 'uploads/default-avatar.svg' && file_exists($_SESSION['profile_picture'])) {
                            unlink($_SESSION['profile_picture']);
                        }
                        $_SESSION['profile_picture'] = $file_path;
                    }
                }
            }
        }

        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JeromeWorkoutPlan - Profile</title>
    <link rel="icon" type="image/png" href="image.png">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="<?php echo $_SESSION['theme']; ?>">
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <div class="breadcrumb">
                <span>Dashboard</span> > Profile
            </div>
            <div class="header-right">
                <div class="date-display"><?php echo date('M j, Y'); ?></div>
                <button class="filter-btn">Settings</button>
            </div>
        </div>

        <div class="dashboard-content">
            <!-- Page Title Section -->
            <div class="page-title-section">
                <h1 class="page-title">Profile Settings</h1>
                <p class="page-subtitle">Manage your personal information and fitness preferences.</p>
            </div>

            <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                Profile updated successfully!
            </div>
            <?php endif; ?>

            <!-- Profile Picture & Basic Info -->
            <div class="profile-main-card">
                <div class="profile-picture-section">
                    <div class="current-picture">
                        <img src="<?php echo !empty($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'uploads/default-avatar.svg'; ?>" alt="Profile Picture" id="profile-preview">
                        <div class="picture-overlay">
                            <svg viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="profile-basic-info">
                    <div class="info-field">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="<?php echo $_SESSION['name']; ?>" placeholder="Enter your full name">
                    </div>
                    <div class="info-field">
                        <label for="age">Age</label>
                        <input type="number" id="age" name="age" value="<?php echo $_SESSION['age']; ?>" placeholder="Your age" min="1" max="120">
                    </div>
                </div>
            </div>

            <!-- Physical Measurements -->
            <div class="profile-form-card">
                <div class="form-header">
                    <h3>Physical Measurements</h3>
                    <p>Track your body measurements for progress monitoring</p>
                </div>

                <form method="post" enctype="multipart/form-data" class="profile-form">
                    <!-- Profile Picture Upload (moved inside form) -->
                    <div class="form-section">
                        <h4>Profile Picture</h4>
                        <div class="form-group">
                            <input type="file" id="profile-picture-input" name="profile_picture" accept="image/*" style="display: none;">
                            <button type="button" class="upload-picture-btn" onclick="document.getElementById('profile-picture-input').click()">
                                <svg viewBox="0 0 24 24"><path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>
                                Choose New Picture
                            </button>
                            <span class="file-info">Supported formats: JPG, PNG, GIF</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="height">Height (cm)</label>
                            <input type="number" id="height" name="height" value="<?php echo $_SESSION['height']; ?>" placeholder="e.g., 175" step="0.1" min="100" max="250">
                        </div>
                        <div class="form-group">
                            <label for="weight">Weight (kg)</label>
                            <input type="number" id="weight" name="weight" value="<?php echo $_SESSION['weight']; ?>" placeholder="e.g., 70" step="0.1" min="30" max="300">
                        </div>
                    </div>

                    <div class="form-section">
                        <h4>Equipment & Preferences</h4>
                        <div class="form-group">
                            <label for="equipment">Available Equipment</label>
                            <textarea id="equipment" name="equipment" rows="3" placeholder="Describe your available workout equipment"><?php echo $_SESSION['equipment']; ?></textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4>Fitness Goals</h4>
                        <div class="form-group">
                            <label for="goal">Your Fitness Goal</label>
                            <textarea id="goal" name="goal" rows="4" placeholder="Describe your fitness goals and objectives"><?php echo $_SESSION['goal']; ?></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="update_profile" class="save-profile-btn">
                            <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                            Save Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- BMI Calculator Display -->
            <?php if ($_SESSION['weight'] && $_SESSION['height']): ?>
            <div class="bmi-display-card">
                <div class="bmi-header">
                    <h3>Your BMI</h3>
                    <div class="bmi-value">
                        <?php
                        $height_m = $_SESSION['height'] / 100;
                        $bmi = round($_SESSION['weight'] / ($height_m * $height_m), 1);
                        $bmi_category = '';
                        $bmi_color = '';

                        if ($bmi < 18.5) {
                            $bmi_category = 'Underweight';
                            $bmi_color = '#f59e0b';
                        } elseif ($bmi < 25) {
                            $bmi_category = 'Normal';
                            $bmi_color = '#10b981';
                        } elseif ($bmi < 30) {
                            $bmi_category = 'Overweight';
                            $bmi_color = '#f97316';
                        } else {
                            $bmi_category = 'Obese';
                            $bmi_color = '#ef4444';
                        }
                        ?>
                        <span style="color: <?php echo $bmi_color; ?>; font-size: 32px; font-weight: 700;"><?php echo $bmi; ?></span>
                        <span style="color: <?php echo $bmi_color; ?>; font-size: 14px; margin-left: 8px;"><?php echo $bmi_category; ?></span>
                    </div>
                </div>
                <div class="bmi-metrics">
                    <div class="bmi-metric">
                        <span class="metric-label">Weight:</span>
                        <span class="metric-value"><?php echo $_SESSION['weight']; ?> kg</span>
                    </div>
                    <div class="bmi-metric">
                        <span class="metric-label">Height:</span>
                        <span class="metric-value"><?php echo $_SESSION['height']; ?> cm</span>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Profile picture preview
        document.getElementById('profile-picture-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPG, PNG, GIF, or WebP).');
                    this.value = '';
                    return;
                }

                // Validate file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size must be less than 5MB.');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Form validation
        document.querySelector('.profile-form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const height = document.getElementById('height').value;
            const weight = document.getElementById('weight').value;

            if (!name) {
                alert('Please enter your name.');
                e.preventDefault();
                return;
            }

            if (height && (height < 100 || height > 250)) {
                alert('Please enter a valid height between 100-250 cm.');
                e.preventDefault();
                return;
            }

            if (weight && (weight < 30 || weight > 300)) {
                alert('Please enter a valid weight between 30-300 kg.');
                e.preventDefault();
                return;
            }
        });

        // Auto-hide success message after 5 seconds
        const successMessage = document.querySelector('.success-message');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.opacity = '0';
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 300);
            }, 5000);
        }
    </script>
</body>
</html>