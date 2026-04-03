<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}

if (isset($_POST['toggle_theme'])) {
    $_SESSION['theme'] = $_SESSION['theme'] == 'dark' ? 'light' : 'dark';
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle progress data submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_progress'])) {
    $date = $_POST['progress_date'];
    $weight = !empty($_POST['weight']) ? $_POST['weight'] : null;
    $body_fat = !empty($_POST['body_fat']) ? $_POST['body_fat'] : null;
    $muscle_mass = !empty($_POST['muscle_mass']) ? $_POST['muscle_mass'] : null;
    $chest = !empty($_POST['chest']) ? $_POST['chest'] : null;
    $waist = !empty($_POST['waist']) ? $_POST['waist'] : null;
    $hip = !empty($_POST['hip']) ? $_POST['hip'] : null;
    $arm = !empty($_POST['arm']) ? $_POST['arm'] : null;
    $thigh = !empty($_POST['thigh']) ? $_POST['thigh'] : null;
    $notes = $_POST['notes'];

    // Insert or update progress entry
    $existing = $db->selectOne("SELECT id FROM progress_entries WHERE date = ?", [$date]);

    if ($existing) {
        $db->update("UPDATE progress_entries SET weight = ?, body_fat_percentage = ?, muscle_mass = ?, chest_measurement = ?, waist_measurement = ?, hip_measurement = ?, arm_measurement = ?, thigh_measurement = ?, notes = ? WHERE date = ?",
            [$weight, $body_fat, $muscle_mass, $chest, $waist, $hip, $arm, $thigh, $notes, $date]);
        $progress_id = $existing['id'];
    } else {
        $progress_id = $db->insert("INSERT INTO progress_entries (date, weight, body_fat_percentage, muscle_mass, chest_measurement, waist_measurement, hip_measurement, arm_measurement, thigh_measurement, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$date, $weight, $body_fat, $muscle_mass, $chest, $waist, $hip, $arm, $thigh, $notes]);
    }

    // Handle photo uploads
    if (!empty($_FILES['progress_photos']['name'][0])) {
        $upload_dir = 'uploads/';

        foreach ($_FILES['progress_photos']['tmp_name'] as $key => $tmp_name) {
            if (!empty($tmp_name)) {
                $file_name = $_FILES['progress_photos']['name'][$key];
                $file_type = $_FILES['progress_photos']['type'][$key];
                $photo_type = $_POST['photo_types'][$key] ?? 'other';

                // Generate unique filename
                $extension = pathinfo($file_name, PATHINFO_EXTENSION);
                $unique_name = uniqid() . '_' . time() . '.' . $extension;
                $file_path = $upload_dir . $unique_name;

                if (move_uploaded_file($tmp_name, $file_path)) {
                    $caption = $_POST['photo_captions'][$key] ?? '';
                    $db->insert("INSERT INTO progress_photos (progress_id, photo_path, photo_type, caption) VALUES (?, ?, ?, ?)",
                        [$progress_id, $file_path, $photo_type, $caption]);
                }
            }
        }
    }

    header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
    exit();
}

// Get progress data for charts
$progress_data = $db->select("SELECT * FROM progress_entries ORDER BY date ASC");
$latest_entry = end($progress_data);

// Get recent photos
$recent_photos = $db->select("
    SELECT pp.*, pe.date
    FROM progress_photos pp
    JOIN progress_entries pe ON pp.progress_id = pe.id
    ORDER BY pp.uploaded_at DESC
    LIMIT 6
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JeromeWorkoutPlan - Progress Tracking</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="<?php echo $_SESSION['theme']; ?>">
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <div class="breadcrumb">
                <span>Dashboard</span> > Progress Tracking
            </div>
            <div class="header-right">
                <div class="date-display"><?php echo date('M j, Y'); ?></div>
                <button class="filter-btn">Export Data</button>
            </div>
        </div>

        <div class="dashboard-content">
            <!-- Page Title Section -->
            <div class="page-title-section">
                <h1 class="page-title">Progress Tracking</h1>
                <p class="page-subtitle">Monitor your fitness journey with detailed metrics and visual progress charts.</p>
            </div>

            <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                Progress data saved successfully!
            </div>
            <?php endif; ?>

            <!-- Progress Overview Cards -->
            <div class="stats-cards-row">
                <div class="stats-card">
                    <div class="stats-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                    <div class="stats-card-title">Current Weight</div>
                    <div class="stats-card-value"><?php echo $latest_entry ? $latest_entry['weight'] . ' kg' : 'Not set'; ?></div>
                    <div class="stats-card-subtitle">Last updated: <?php echo $latest_entry ? date('M j', strtotime($latest_entry['date'])) : 'Never'; ?></div>
                </div>

                <div class="stats-card">
                    <div class="stats-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M3 17v2h6v-2H3zM3 5v2h10V5H3zm10 16v-2h8v-2h-8v-2h-2v6h2zM7 9v2H3v2h4v2h2V9H7zm14 4v-2H11v2h10zm-6-4h2V7h4V5h-4V3h-2v6z"/></svg>
                    </div>
                    <div class="stats-card-title">Body Fat %</div>
                    <div class="stats-card-value"><?php echo $latest_entry && $latest_entry['body_fat_percentage'] ? $latest_entry['body_fat_percentage'] . '%' : 'Not set'; ?></div>
                    <div class="stats-card-subtitle">Target: 15-20%</div>
                </div>

                <div class="stats-card">
                    <div class="stats-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M19 7h-3V6a3 3 0 0 0-3-3H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-3a3 3 0 0 0-3-3h-1V9a3 3 0 0 0-3-3z"/></svg>
                    </div>
                    <div class="stats-card-title">Total Entries</div>
                    <div class="stats-card-value"><?php echo count($progress_data); ?></div>
                    <div class="stats-card-subtitle">Progress records</div>
                </div>

                <div class="stats-card">
                    <div class="stats-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                    <div class="stats-card-title">Progress Photos</div>
                    <div class="stats-card-value"><?php echo count($recent_photos); ?></div>
                    <div class="stats-card-subtitle">Transformation shots</div>
                </div>
            </div>

            <!-- Progress Charts -->
            <div class="chart-section">
                <div class="chart-title">Weight Progress Over Time</div>
                <canvas id="weightChart" width="400" height="200"></canvas>
            </div>

            <!-- Data Entry Form -->
            <div class="progress-form-card">
                <div class="form-header">
                    <h3>Log Progress</h3>
                    <p>Record your measurements and upload progress photos</p>
                </div>

                <form method="post" enctype="multipart/form-data" class="progress-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="progress_date">Date</label>
                            <input type="date" id="progress_date" name="progress_date" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4>Body Measurements</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="weight">Weight (kg)</label>
                                <input type="number" id="weight" name="weight" step="0.1" value="<?php echo $latest_entry ? $latest_entry['weight'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="body_fat">Body Fat %</label>
                                <input type="number" id="body_fat" name="body_fat" step="0.1" value="<?php echo $latest_entry ? $latest_entry['body_fat_percentage'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="muscle_mass">Muscle Mass (kg)</label>
                                <input type="number" id="muscle_mass" name="muscle_mass" step="0.1" value="<?php echo $latest_entry ? $latest_entry['muscle_mass'] : ''; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4>Circumference Measurements (cm)</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="chest">Chest</label>
                                <input type="number" id="chest" name="chest" step="0.1" value="<?php echo $latest_entry ? $latest_entry['chest_measurement'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="waist">Waist</label>
                                <input type="number" id="waist" name="waist" step="0.1" value="<?php echo $latest_entry ? $latest_entry['waist_measurement'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="hip">Hip</label>
                                <input type="number" id="hip" name="hip" step="0.1" value="<?php echo $latest_entry ? $latest_entry['hip_measurement'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="arm">Arm</label>
                                <input type="number" id="arm" name="arm" step="0.1" value="<?php echo $latest_entry ? $latest_entry['arm_measurement'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="thigh">Thigh</label>
                                <input type="number" id="thigh" name="thigh" step="0.1" value="<?php echo $latest_entry ? $latest_entry['thigh_measurement'] : ''; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4>Progress Photos</h4>
                        <div id="photo-upload-section">
                            <div class="photo-upload-item">
                                <div class="photo-input-group">
                                    <select name="photo_types[]" class="photo-type-select">
                                        <option value="front">Front View</option>
                                        <option value="side">Side View</option>
                                        <option value="back">Back View</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <input type="file" name="progress_photos[]" accept="image/*" class="photo-file-input">
                                    <input type="text" name="photo_captions[]" placeholder="Caption (optional)" class="photo-caption-input">
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-photo-btn" class="add-photo-button">
                            <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                            Add Another Photo
                        </button>
                    </div>

                    <div class="form-section">
                        <h4>Notes</h4>
                        <textarea name="notes" rows="4" placeholder="How are you feeling? Any observations about your progress?"><?php echo $latest_entry ? $latest_entry['notes'] : ''; ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="save_progress" class="save-progress-btn">
                            <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                            Save Progress
                        </button>
                    </div>
                </form>
            </div>

            <!-- Recent Photos Gallery -->
            <?php if (!empty($recent_photos)): ?>
            <div class="photos-gallery-card">
                <div class="gallery-header">
                    <h3>Recent Progress Photos</h3>
                    <p>Visual transformation over time</p>
                </div>
                <div class="photos-grid">
                    <?php foreach ($recent_photos as $photo): ?>
                    <div class="photo-item">
                        <img src="<?php echo $photo['photo_path']; ?>" alt="<?php echo $photo['caption'] ?: 'Progress photo'; ?>">
                        <div class="photo-overlay">
                            <div class="photo-info">
                                <span class="photo-date"><?php echo date('M j, Y', strtotime($photo['date'])); ?></span>
                                <span class="photo-type"><?php echo ucfirst($photo['photo_type']); ?></span>
                                <?php if ($photo['caption']): ?>
                                <span class="photo-caption"><?php echo $photo['caption']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Weight progress chart
        <?php if (!empty($progress_data)): ?>
        const weightData = <?php echo json_encode(array_map(function($entry) {
            return [
                'date' => date('M j', strtotime($entry['date'])),
                'weight' => (float)$entry['weight']
            ];
        }, array_filter($progress_data, function($entry) {
            return !empty($entry['weight']);
        }))); ?>;

        const ctx = document.getElementById('weightChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: weightData.map(d => d.date),
                datasets: [{
                    label: 'Weight (kg)',
                    data: weightData.map(d => d.weight),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });
        <?php endif; ?>

        // Add more photo upload fields
        document.getElementById('add-photo-btn').addEventListener('click', function() {
            const photoSection = document.getElementById('photo-upload-section');
            const newPhotoItem = document.createElement('div');
            newPhotoItem.className = 'photo-upload-item';
            newPhotoItem.innerHTML = `
                <div class="photo-input-group">
                    <select name="photo_types[]" class="photo-type-select">
                        <option value="front">Front View</option>
                        <option value="side">Side View</option>
                        <option value="back">Side View</option>
                        <option value="other">Other</option>
                    </select>
                    <input type="file" name="progress_photos[]" accept="image/*" class="photo-file-input">
                    <input type="text" name="photo_captions[]" placeholder="Caption (optional)" class="photo-caption-input">
                </div>
            `;
            photoSection.appendChild(newPhotoItem);
        });
    </script>
</body>
</html>