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
                <span>Dashboard</span> > Meal Plan
            </div>
            <div class="header-right">
                <div class="date-display"><?php echo date('M j, Y'); ?></div>
                <button class="filter-btn">Filter Day</button>
            </div>
        </div>

        <div class="dashboard-content">
            <!-- Page Title Section -->
            <div class="page-title-section">
                <h1 class="page-title">Today's Meal Plan</h1>
                <p class="page-subtitle">Fuel your body with nutritious meals designed for optimal performance.</p>
            </div>

            <!-- Nutrition Summary Card -->
            <div class="nutrition-summary-card">
                <div class="summary-header">
                    <h3>Daily Nutrition Overview</h3>
                    <div class="total-calories">
                        <span class="calorie-number">
                            <?php
                            $total_calories = 0;
                            foreach ($current_meals as $meal => $details) {
                                if (preg_match('/~(\d+)\s*cal/i', $details, $matches)) {
                                    $total_calories += (int)$matches[1];
                                }
                            }
                            echo $total_calories;
                            ?>
                        </span>
                        <span class="calorie-label">Calories</span>
                    </div>
                </div>
                <div class="nutrition-macros">
                    <div class="macro-item">
                        <span class="macro-value">~150g</span>
                        <span class="macro-label">Protein</span>
                    </div>
                    <div class="macro-item">
                        <span class="macro-value">~200g</span>
                        <span class="macro-label">Carbs</span>
                    </div>
                    <div class="macro-item">
                        <span class="macro-value">~70g</span>
                        <span class="macro-label">Fats</span>
                    </div>
                </div>
            </div>

            <!-- Meal Cards -->
            <div class="meals-grid">
                <?php
                $meal_icons = [
                    'Breakfast' => '<svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/><path d="M12 6.5c-1.38 0-2.5 1.12-2.5 2.5s1.12 2.5 2.5 2.5 2.5-1.12 2.5-2.5-1.12-2.5-2.5-2.5z"/></svg>',
                    'Lunch' => '<svg viewBox="0 0 24 24"><path d="M19 7h-3V6a3 3 0 0 0-3-3H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-3a3 3 0 0 0-3-3h-1V9a3 3 0 0 0-3-3z"/></svg>',
                    'Dinner' => '<svg viewBox="0 0 24 24"><path d="M19 7h-3V6a3 3 0 0 0-3-3H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-3a3 3 0 0 0-3-3h-1V9a3 3 0 0 0-3-3z"/><circle cx="9" cy="9" r="2"/><circle cx="15" cy="9" r="2"/></svg>',
                    'Snack' => '<svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/><path d="M12 6.5c-1.38 0-2.5 1.12-2.5 2.5s1.12 2.5 2.5 2.5 2.5-1.12 2.5-2.5-1.12-2.5-2.5-2.5z"/></svg>'
                ];

                $meal_colors = [
                    'Breakfast' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                    'Lunch' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                    'Dinner' => 'linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)',
                    'Snack' => 'linear-gradient(135deg, #f97316 0%, #ea580c 100%)'
                ];

                foreach ($current_meals as $meal => $details):
                    // Extract calories from details
                    $calories = 'N/A';
                    if (preg_match('/~(\d+)\s*cal/i', $details, $matches)) {
                        $calories = $matches[1] . ' cal';
                    }

                    // Remove calorie info from meal description for cleaner display
                    $clean_details = preg_replace('/\s*\(~?\d+\s*cal\)*/i', '', $details);
                ?>
                <div class="meal-card">
                    <div class="meal-header">
                        <div class="meal-icon" style="background: <?php echo $meal_colors[$meal]; ?>">
                            <?php echo $meal_icons[$meal]; ?>
                        </div>
                        <div class="meal-info">
                            <h4 class="meal-name"><?php echo $meal; ?></h4>
                            <div class="meal-calories"><?php echo $calories; ?></div>
                        </div>
                    </div>
                    <div class="meal-content">
                        <p class="meal-description"><?php echo $clean_details; ?></p>
                    </div>
                    <div class="meal-actions">
                        <button class="meal-action-btn" onclick="alert('Recipe feature coming soon!')">
                            <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            View Recipe
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Nutrition Tips Card -->
            <div class="nutrition-tips-card">
                <div class="tips-header">
                    <h3>💡 Nutrition Tips</h3>
                </div>
                <div class="tips-content">
                    <div class="tip-item">
                        <div class="tip-icon">🥤</div>
                        <div class="tip-text">
                            <strong>Stay Hydrated:</strong> Drink at least 8 glasses of water throughout the day
                        </div>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon">⏰</div>
                        <div class="tip-text">
                            <strong>Meal Timing:</strong> Eat every 3-4 hours to maintain energy levels
                        </div>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon">🥗</div>
                        <div class="tip-text">
                            <strong>Portion Control:</strong> Use smaller plates and measure portions for better control
                        </div>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon">⚖️</div>
                        <div class="tip-text">
                            <strong>Balance:</strong> Include protein, carbs, and healthy fats in every meal
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>