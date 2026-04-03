<?php
session_start();
if (!isset($_SESSION['completed'])) {
    $_SESSION['completed'] = [];
}
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}

if (isset($_POST['toggle_theme'])) {
    $_SESSION['theme'] = $_SESSION['theme'] == 'dark' ? 'light' : 'dark';
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$today = date('Y-m-d');
if (!isset($_SESSION['completed'][$today])) {
    $_SESSION['completed'][$today] = [];
}

$day_of_week = date('N'); // 1=Monday, 7=Sunday

$workouts = [
    1 => ['Push-ups' => '3 sets of 10-12 reps, 60s rest', 'One-arm Dumbbell Press' => '3 sets of 8-10 reps, 60s rest', 'Plank' => '3 sets of 30-45s, 60s rest'],
    2 => ['Dumbbell Curls' => '3 sets of 10-12 reps, 60s rest', 'Shoulder Press' => '3 sets of 8-10 reps, 60s rest', 'Leg Raises' => '3 sets of 10-12 reps, 60s rest'],
    3 => ['Push-ups' => '3 sets of 10-12 reps, 60s rest', 'Lateral Raises' => '3 sets of 10-12 reps, 60s rest', 'Plank' => '3 sets of 30-45s, 60s rest'],
    4 => ['One-arm Dumbbell Press' => '3 sets of 8-10 reps, 60s rest', 'Dumbbell Curls' => '3 sets of 10-12 reps, 60s rest', 'Leg Raises' => '3 sets of 10-12 reps, 60s rest'],
    5 => ['Shoulder Press' => '3 sets of 8-10 reps, 60s rest', 'Lateral Raises' => '3 sets of 10-12 reps, 60s rest', 'Plank' => '3 sets of 30-45s, 60s rest'],
    6 => ['Push-ups' => '3 sets of 10-12 reps, 60s rest', 'One-arm Dumbbell Press' => '3 sets of 8-10 reps, 60s rest', 'Dumbbell Curls' => '3 sets of 10-12 reps, 60s rest'],
    7 => [] // Rest day
];

$current_workout = $workouts[$day_of_week];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['completed'])) {
    $_SESSION['completed'][$today] = $_POST['completed'] ?? [];
    // Update streak if all completed
    if (count($_SESSION['completed'][$today]) == count($current_workout)) {
        $_SESSION['streak']++;
        $_SESSION['completed_workouts']++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JeromeWorkoutPlan - Workouts</title>
    <link rel="icon" type="image/png" href="image.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="<?php echo $_SESSION['theme']; ?>">
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <div class="breadcrumb">
                <span>Dashboard</span> > Workouts
            </div>
            <div class="header-right">
                <div class="date-display"><?php echo date('M j, Y'); ?></div>
                <button class="filter-btn">Filter Day</button>
            </div>
        </div>

        <div class="dashboard-content">
            <!-- Page Title Section -->
            <div class="page-title-section">
                <h1 class="page-title">Today's Workout</h1>
                <p class="page-subtitle">Complete your daily exercises and track your progress.</p>
            </div>

            <?php if ($day_of_week == 7): ?>
                <!-- Rest Day Card -->
                <div class="rest-day-card">
                    <div class="rest-day-content">
                        <div class="rest-icon">
                            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                        </div>
                        <h3>Rest Day!</h3>
                        <p>Take it easy today. Your body needs recovery to perform at its best.</p>
                        <div class="rest-tips">
                            <h4>Recovery Tips:</h4>
                            <ul>
                                <li>Stay hydrated</li>
                                <li>Get adequate sleep (7-9 hours)</li>
                                <li>Consider light walking or stretching</li>
                                <li>Eat nutritious meals</li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Workout Progress -->
                <div class="workout-progress-card">
                    <div class="progress-header">
                        <h3>Workout Progress</h3>
                        <div class="progress-stats">
                            <span class="completed-count"><?php echo count($_SESSION['completed'][$today]); ?>/<?php echo count($current_workout); ?> Completed</span>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo (count($_SESSION['completed'][$today]) / count($current_workout)) * 100; ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Workout Exercises -->
                <div class="workout-exercises">
                    <form method="post" id="workout-form">
                        <?php foreach ($current_workout as $exercise => $details): ?>
                            <div class="exercise-card <?php echo in_array($exercise, $_SESSION['completed'][$today]) ? 'completed' : ''; ?>">
                                <div class="exercise-header">
                                    <div class="exercise-icon">
                                        <?php
                                        // Different icons for different exercise types
                                        if (strpos($exercise, 'Push-ups') !== false) {
                                            echo '<svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
                                        } elseif (strpos($exercise, 'Press') !== false || strpos($exercise, 'Raise') !== false) {
                                            echo '<svg viewBox="0 0 24 24"><path d="M3 17v2h6v-2H3zM3 5v2h10V5H3zm10 16v-2h8v-2h-8v-2h-2v6h2zM7 9v2H3v2h4v2h2V9H7zm14 4v-2H11v2h10zm-6-4h2V7h4V5h-4V3h-2v6z"/></svg>';
                                        } elseif (strpos($exercise, 'Curl') !== false) {
                                            echo '<svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
                                        } elseif (strpos($exercise, 'Plank') !== false) {
                                            echo '<svg viewBox="0 0 24 24"><path d="M19 7h-3V6a3 3 0 0 0-3-3H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-3a3 3 0 0 0-3-3h-1V9a3 3 0 0 0-3-3z"/></svg>';
                                        } elseif (strpos($exercise, 'Raise') !== false) {
                                            echo '<svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
                                        } else {
                                            echo '<svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
                                        }
                                        ?>
                                    </div>
                                    <div class="exercise-info">
                                        <h4 class="exercise-name"><?php echo $exercise; ?></h4>
                                        <p class="exercise-details"><?php echo $details; ?></p>
                                    </div>
                                    <div class="exercise-checkbox">
                                        <input type="checkbox" name="completed[]" value="<?php echo $exercise; ?>" id="exercise-<?php echo str_replace(' ', '-', $exercise); ?>" <?php echo in_array($exercise, $_SESSION['completed'][$today]) ? 'checked' : ''; ?>>
                                        <label for="exercise-<?php echo str_replace(' ', '-', $exercise); ?>" class="checkbox-label">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                <?php if (in_array($exercise, $_SESSION['completed'][$today])): ?>
                                    <div class="completion-badge">
                                        <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                        Completed
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>

                        <div class="workout-actions">
                            <button type="submit" class="complete-workout-btn">
                                <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                Mark Workout Complete
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>