<?php
session_start();
if (!isset($_SESSION['weight'])) {
    $_SESSION['weight'] = 70; // default kg
}
if (!isset($_SESSION['streak'])) {
    $_SESSION['streak'] = 0;
}
if (!isset($_SESSION['completed_workouts'])) {
    $_SESSION['completed_workouts'] = 0;
}
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}

if (isset($_POST['toggle_theme'])) {
    $_SESSION['theme'] = $_SESSION['theme'] == 'dark' ? 'light' : 'dark';
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['weight'])) {
    $_SESSION['weight'] = $_POST['weight'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JeromeWorkoutPlan - Progress</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="<?php echo $_SESSION['theme']; ?>">
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-content">
        <div class="card">
            <h2>Workout Streak</h2>
            <p><?php echo $_SESSION['streak']; ?> days</p>
            <div class="progress-bar"><div class="progress-fill" style="width: <?php echo min($_SESSION['streak'] * 10, 100); ?>%;"></div></div>
        </div>
        <div class="card">
            <h2>Completed Workouts</h2>
            <p><?php echo $_SESSION['completed_workouts']; ?> workouts</p>
            <div class="progress-bar"><div class="progress-fill" style="width: <?php echo min($_SESSION['completed_workouts'] * 5, 100); ?>%;"></div></div>
        </div>
        <div class="card">
            <h2>Weight Tracking</h2>
            <form method="post">
                <div class="form-group">
                    <label for="weight">Current Weight (kg):</label>
                    <input type="number" id="weight" name="weight" value="<?php echo $_SESSION['weight']; ?>" step="0.1">
                </div>
                <button type="submit" class="btn">Update Weight</button>
            </form>
        </div>
    </div>
</body>
</html>