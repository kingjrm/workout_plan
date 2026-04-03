<?php
session_start();
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}

if (isset($_POST['toggle_theme'])) {
    $_SESSION['theme'] = $_SESSION['theme'] == 'dark' ? 'light' : 'dark';
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$day_of_week = date('N');

$meals = [
    1 => [
        'Breakfast' => 'Oats with banana and milk (~300 cal)',
        'Lunch' => 'Grilled chicken with rice (~500 cal)',
        'Dinner' => 'Tuna salad with veggies (~400 cal)',
        'Snack' => 'Boiled eggs (~150 cal)'
    ],
    2 => [
        'Breakfast' => 'Oats with banana (~250 cal)',
        'Lunch' => 'Chicken stir-fry with rice (~550 cal)',
        'Dinner' => 'Tuna with rice (~450 cal)',
        'Snack' => 'Banana (~100 cal)'
    ],
    3 => [
        'Breakfast' => 'Eggs and oats (~350 cal)',
        'Lunch' => 'Tuna sandwich (~400 cal)',
        'Dinner' => 'Chicken with veggies (~500 cal)',
        'Snack' => 'Banana (~100 cal)'
    ],
    4 => [
        'Breakfast' => 'Oats with banana (~250 cal)',
        'Lunch' => 'Chicken with rice (~500 cal)',
        'Dinner' => 'Egg salad (~350 cal)',
        'Snack' => 'Tuna (~200 cal)'
    ],
    5 => [
        'Breakfast' => 'Eggs and banana (~300 cal)',
        'Lunch' => 'Rice with chicken (~550 cal)',
        'Dinner' => 'Tuna with veggies (~400 cal)',
        'Snack' => 'Oats (~200 cal)'
    ],
    6 => [
        'Breakfast' => 'Oats (~200 cal)',
        'Lunch' => 'Chicken salad (~450 cal)',
        'Dinner' => 'Rice with tuna (~500 cal)',
        'Snack' => 'Banana and eggs (~250 cal)'
    ],
    7 => [
        'Breakfast' => 'Banana with oats (~250 cal)',
        'Lunch' => 'Tuna with rice (~450 cal)',
        'Dinner' => 'Chicken (~400 cal)',
        'Snack' => 'Eggs (~150 cal)'
    ]
];

$current_meals = $meals[$day_of_week];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JeromeWorkoutPlan - Meal Plan</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="<?php echo $_SESSION['theme']; ?>">
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-content">
        <div class="card">
            <h2>Today's Meal Plan (Day <?php echo $day_of_week; ?>)</h2>
            <?php foreach ($current_meals as $meal => $details): ?>
                <p><strong><?php echo $meal; ?>:</strong> <?php echo $details; ?></p>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>