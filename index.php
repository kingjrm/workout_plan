<?php
session_start();
if (!isset($_SESSION['streak'])) {
    $_SESSION['streak'] = 7; // Sample data
}
if (!isset($_SESSION['completed_workouts'])) {
    $_SESSION['completed_workouts'] = 24;
}
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
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

$meals = [
    1 => [
        'Breakfast' => 'Eggs + rice + banana',
        'Lunch' => 'Chicken + vegetables + rice',
        'Dinner' => 'Tuna + egg + rice',
        'Snack' => 'Oats'
    ],
    2 => [
        'Breakfast' => 'Oats with banana',
        'Lunch' => 'Chicken stir-fry with rice',
        'Dinner' => 'Tuna with rice',
        'Snack' => 'Banana'
    ],
    3 => [
        'Breakfast' => 'Eggs and oats',
        'Lunch' => 'Tuna sandwich',
        'Dinner' => 'Chicken with veggies',
        'Snack' => 'Banana'
    ],
    4 => [
        'Breakfast' => 'Oats with banana',
        'Lunch' => 'Chicken with rice',
        'Dinner' => 'Egg salad',
        'Snack' => 'Tuna'
    ],
    5 => [
        'Breakfast' => 'Eggs and banana',
        'Lunch' => 'Rice with chicken',
        'Dinner' => 'Tuna with veggies',
        'Snack' => 'Oats'
    ],
    6 => [
        'Breakfast' => 'Oats',
        'Lunch' => 'Chicken salad',
        'Dinner' => 'Rice with tuna',
        'Snack' => 'Banana and eggs'
    ],
    7 => [
        'Breakfast' => 'Banana with oats',
        'Lunch' => 'Tuna with rice',
        'Dinner' => 'Chicken',
        'Snack' => 'Eggs'
    ]
];

$current_meals = $meals[$day_of_week];

// Sample data for charts and stats
$fitness_score = 78;
$workout_consistency = 85;
$completion_rate = 90;
$avg_session_time = 35;
$current_streak = $_SESSION['streak'];
$total_workouts = $_SESSION['completed_workouts'];
$avg_reps = 120;
$calories_burned = 1850;
$missed_workouts = 3;
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
        <!-- Top Header -->
        <div class="top-header">
            <div class="breadcrumb">
                <span>Dashboard</span> > Fitness Overview
            </div>
            <div class="header-right">
                <div class="date-display"><?php echo date('M j, Y'); ?></div>
                <button class="filter-btn">Filter Day</button>
            </div>
        </div>

        <div class="dashboard-content">
            <!-- Page Title Section -->
            <div class="page-title-section">
                <h1 class="page-title">Workout Dashboard</h1>
                <p class="page-subtitle">Monitor your fitness progress, consistency, and daily performance.</p>
            </div>

            <!-- Main Highlight Card -->
            <div class="main-highlight-card">
                <div class="highlight-content">
                    <div class="highlight-title">Overall Fitness Score</div>
                    <div class="fitness-score"><?php echo $fitness_score; ?>/100</div>
                    <div class="score-note">Good progress — stay consistent</div>

                    <div class="metrics-grid">
                        <div class="metric-item">
                            <span class="metric-value"><?php echo $workout_consistency; ?>%</span>
                            <div class="metric-label">Workout Consistency</div>
                        </div>
                        <div class="metric-item">
                            <span class="metric-value"><?php echo $completion_rate; ?>%</span>
                            <div class="metric-label">Completion Rate</div>
                        </div>
                        <div class="metric-item">
                            <span class="metric-value"><?php echo $avg_session_time; ?> mins</span>
                            <div class="metric-label">Avg Session Time</div>
                        </div>
                        <div class="metric-item">
                            <span class="metric-value"><?php echo $current_streak; ?> days</span>
                            <div class="metric-label">Current Streak</div>
                        </div>
                    </div>

                    <button class="view-progress-btn">View Full Progress</button>
                </div>
            </div>

            <!-- Stats Cards Row -->
            <div class="stats-cards-row">
                <div class="stats-card">
                    <div class="stats-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                    <div class="stats-card-title">Total Workouts</div>
                    <div class="stats-card-value"><?php echo $total_workouts; ?></div>
                    <div class="stats-card-subtitle">Last 30 days</div>
                </div>

                <div class="stats-card">
                    <div class="stats-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                    </div>
                    <div class="stats-card-title">Avg Reps Completed</div>
                    <div class="stats-card-value"><?php echo $avg_reps; ?></div>
                    <div class="stats-card-subtitle">Per session</div>
                    <div class="mini-progress-bar">
                        <div class="mini-progress-fill" style="width: 75%;"></div>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="stats-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                    </div>
                    <div class="stats-card-title">Calories Burned</div>
                    <div class="stats-card-value"><?php echo number_format($calories_burned); ?></div>
                    <div class="stats-card-subtitle">This month</div>
                </div>

                <div class="stats-card">
                    <div class="stats-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>
                    </div>
                    <div class="stats-card-title">Missed Workouts</div>
                    <div class="stats-card-value"><?php echo $missed_workouts; ?></div>
                    <div class="stats-card-subtitle">Needs improvement</div>
                </div>
            </div>

            <!-- Performance Trends Chart -->
            <div class="chart-section">
                <h2 class="chart-title">Workout Trends</h2>
                <div class="simple-chart">
                    <div class="chart-line"></div>
                    <div class="chart-points">
                        <div class="chart-point" style="height: 8px; width: 8px;"></div>
                        <div class="chart-point" style="height: 12px; width: 8px;"></div>
                        <div class="chart-point" style="height: 16px; width: 8px;"></div>
                        <div class="chart-point" style="height: 20px; width: 8px;"></div>
                        <div class="chart-point" style="height: 24px; width: 8px;"></div>
                        <div class="chart-point" style="height: 18px; width: 8px;"></div>
                        <div class="chart-point" style="height: 22px; width: 8px;"></div>
                    </div>
                </div>
                <div class="chart-labels">
                    <span class="chart-label">Mon</span>
                    <span class="chart-label">Tue</span>
                    <span class="chart-label">Wed</span>
                    <span class="chart-label">Thu</span>
                    <span class="chart-label">Fri</span>
                    <span class="chart-label">Sat</span>
                    <span class="chart-label">Sun</span>
                </div>
            </div>

            <!-- Workout Completion Breakdown -->
            <div class="completion-breakdown">
                <h2 class="breakdown-title">Workout Completion</h2>
                <div class="breakdown-item">
                    <div class="breakdown-label">
                        <span>Completed</span>
                        <span>80%</span>
                    </div>
                    <div class="breakdown-bar">
                        <div class="breakdown-fill completed" style="width: 80%;"></div>
                    </div>
                </div>
                <div class="breakdown-item">
                    <div class="breakdown-label">
                        <span>Skipped</span>
                        <span>10%</span>
                    </div>
                    <div class="breakdown-bar">
                        <div class="breakdown-fill skipped" style="width: 10%;"></div>
                    </div>
                </div>
                <div class="breakdown-item">
                    <div class="breakdown-label">
                        <span>Partial</span>
                        <span>10%</span>
                    </div>
                    <div class="breakdown-bar">
                        <div class="breakdown-fill partial" style="width: 10%;"></div>
                    </div>
                </div>
            </div>

            <!-- Today's Workout Card -->
            <div class="workout-card">
                <h2 class="workout-title">Today's Workout Plan</h2>
                <?php if ($day_of_week == 7): ?>
                    <p>Rest Day! Take it easy and recover.</p>
                <?php else: ?>
                    <?php foreach ($current_workout as $exercise => $details): ?>
                        <div class="workout-item">
                            <input type="checkbox" class="workout-checkbox">
                            <div class="workout-name"><?php echo $exercise; ?></div>
                            <div class="workout-details"><?php echo $details; ?></div>
                        </div>
                    <?php endforeach; ?>
                    <div class="focus-muscles">
                        <strong>Focus Muscles:</strong> <?php echo $todays_focus; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Daily Meal Plan Card -->
            <div class="meal-card">
                <h2 class="meal-title">Today's Meal Plan</h2>
                <?php foreach ($current_meals as $meal_time => $meal_content): ?>
                    <div class="meal-item">
                        <div class="meal-time"><?php echo $meal_time; ?>:</div>
                        <div class="meal-content"><?php echo $meal_content; ?></div>
                    </div>
                <?php endforeach; ?>
                <div class="hydration-reminder">
                    💧 Remember to stay hydrated! Aim for 8 glasses of water today.
                </div>
            </div>
        </div>
    </div>
</body>
</html>