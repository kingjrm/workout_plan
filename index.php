<?php
session_start();
if (!isset($_SESSION['streak'])) {
    $_SESSION['streak'] = 0;
}
if (!isset($_SESSION['completed_workouts'])) {
    $_SESSION['completed_workouts'] = 0;
}
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light'; // Default to light mode
}

if (isset($_POST['toggle_theme'])) {
    $_SESSION['theme'] = $_SESSION['theme'] == 'dark' ? 'light' : 'dark';
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$day_of_week = date('N');
$focus_areas = [1 => 'Chest and Core', 2 => 'Arms and Abs', 3 => 'Full Upper Body', 4 => 'Strength and Core', 5 => 'Shoulders and Back', 6 => 'Push and Pull', 7 => 'Rest and Recovery'];
$todays_focus = $focus_areas[$day_of_week];

$workouts = [
    1 => ['Push-ups' => '3 sets of 10-12 reps', 'One-arm Dumbbell Press' => '3 sets of 8-10 reps', 'Plank' => '3 sets of 30-45s'],
    2 => ['Dumbbell Curls' => '3 sets of 10-12 reps', 'Shoulder Press' => '3 sets of 8-10 reps', 'Leg Raises' => '3 sets of 10-12 reps'],
    3 => ['Push-ups' => '3 sets of 10-12 reps', 'Lateral Raises' => '3 sets of 10-12 reps', 'Plank' => '3 sets of 30-45s'],
    4 => ['One-arm Dumbbell Press' => '3 sets of 8-10 reps', 'Dumbbell Curls' => '3 sets of 10-12 reps', 'Leg Raises' => '3 sets of 10-12 reps'],
    5 => ['Shoulder Press' => '3 sets of 8-10 reps', 'Lateral Raises' => '3 sets of 10-12 reps', 'Plank' => '3 sets of 30-45s'],
    6 => ['Push-ups' => '3 sets of 10-12 reps', 'One-arm Dumbbell Press' => '3 sets of 8-10 reps', 'Dumbbell Curls' => '3 sets of 10-12 reps'],
    7 => []
];
$current_workout = $workouts[$day_of_week];

$quotes = [
    "The only bad workout is the one that didn't happen.",
    "Push yourself, because no one else is going to do it for you.",
    "Fitness is not about being better than someone else. It's about being better than you used to be.",
    "Your body can do it. It's your mind you have to convince.",
    "Don't stop when you're tired. Stop when you're done."
];
$motivational_quote = $quotes[array_rand($quotes)];

// Weekly progress data (hardcoded for demo, in real app from session)
$weekly_progress = [80, 60, 100, 40, 90, 70, 0]; // percentages for Mon-Sun
$days = ['M', 'T', 'W', 'T', 'F', 'S', 'S'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JeromeWorkoutPlan - Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="<?php echo $_SESSION['theme']; ?>">
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-content">
        <div class="welcome-header">
            <h1>Welcome back, Jerome!</h1>
            <p>Let's crush today's workout!</p>
        </div>
        <div class="dashboard-grid">
            <div class="card">
                <h2><svg class="card-icon" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>Today's Workout Summary</h2>
                <?php if ($day_of_week == 7): ?>
                    <p>Rest Day! Take it easy.</p>
                <?php else: ?>
                    <?php foreach ($current_workout as $exercise => $details): ?>
                        <p><strong><?php echo $exercise; ?>:</strong> <?php echo $details; ?></p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="card">
                <h2><svg class="card-icon" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>Workout Streak</h2>
                <p><?php echo $_SESSION['streak']; ?> days</p>
                <div class="progress-bar"><div class="progress-fill" style="width: <?php echo min($_SESSION['streak'] * 10, 100); ?>%;"></div></div>
            </div>
            <div class="card">
                <h2><svg class="card-icon" viewBox="0 0 24 24"><path d="M3.5 18.49l6-6.01 4 4L22 6.92l-1.41-1.41-7.09 7.97-4-4L2 16.99z"/></svg>Weekly Progress</h2>
                <div class="chart">
                    <?php for ($i = 0; $i < 7; $i++): ?>
                        <div class="chart-bar">
                            <div class="chart-bar-fill" style="height: <?php echo $weekly_progress[$i]; ?>%;"></div>
                            <div class="chart-label"><?php echo $days[$i]; ?></div>
                        </div>
                    <?php endfor; ?>
                </div>
                <p><?php echo array_sum($weekly_progress) / 7; ?>% average completion</p>
            </div>
            <div class="card">
                <h2><svg class="card-icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>Today's Focus</h2>
                <p><?php echo $todays_focus; ?></p>
            </div>
            <div class="card">
                <h2><svg class="card-icon" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>Motivational Quote</h2>
                <p>"<?php echo $motivational_quote; ?>"</p>
            </div>
            <div class="card">
                <h2><svg class="card-icon" viewBox="0 0 24 24"><path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.1 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg>Quick Stats</h2>
                <p>Total Workouts: <?php echo $_SESSION['completed_workouts']; ?></p>
                <p>Current Weight: <?php echo $_SESSION['weight'] ?? 70; ?> kg</p>
                <p>Goal: <?php echo $_SESSION['goal'] ?? 'Build muscle'; ?></p>
            </div>
        </div>
    </div>
</body>
</html>