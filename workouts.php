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
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="<?php echo $_SESSION['theme']; ?>">
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-content">
        <div class="card">
            <h2>Today's Workout (Day <?php echo $day_of_week; ?>)</h2>
            <?php if ($day_of_week == 7): ?>
                <p>Rest Day! Take it easy.</p>
            <?php else: ?>
                <form method="post">
                    <?php foreach ($current_workout as $exercise => $details): ?>
                        <div>
                            <input type="checkbox" name="completed[]" value="<?php echo $exercise; ?>" class="checkbox" <?php echo in_array($exercise, $_SESSION['completed'][$today]) ? 'checked' : ''; ?>>
                            <strong><?php echo $exercise; ?>:</strong> <?php echo $details; ?>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" class="btn">Mark Completed</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>