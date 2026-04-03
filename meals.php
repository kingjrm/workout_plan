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
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}

if (isset($_POST['toggle_theme'])) {
    $_SESSION['theme'] = $_SESSION['theme'] == 'dark' ? 'light' : 'dark';
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle meal completion
if (isset($_POST['complete_meal'])) {
    $meal_type = $_POST['meal_type'];
    $date = date('Y-m-d');

    if (!isset($_SESSION['completed_meals'])) {
        $_SESSION['completed_meals'] = [];
    }
    if (!isset($_SESSION['completed_meals'][$date])) {
        $_SESSION['completed_meals'][$date] = [];
    }

    $_SESSION['completed_meals'][$date][$meal_type] = true;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle meal customization
if (isset($_POST['customize_meal'])) {
    $meal_type = $_POST['meal_type'];
    $custom_meal = trim($_POST['custom_meal']);
    $custom_calories = intval($_POST['custom_calories']);

    if (!isset($_SESSION['custom_meals'])) {
        $_SESSION['custom_meals'] = [];
    }

    $_SESSION['custom_meals'][$meal_type] = [
        'description' => $custom_meal,
        'calories' => $custom_calories
    ];

    header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
    exit();
}

// Get current day and meals
$day_of_week = date('N');
$date_today = date('Y-m-d');

// Default meal plans
$default_meals = [
    1 => [ // Monday
        'Breakfast' => ['description' => 'Oats with banana and almond milk', 'calories' => 320, 'protein' => 12, 'carbs' => 45, 'fat' => 8],
        'Lunch' => ['description' => 'Grilled chicken breast with brown rice and broccoli', 'calories' => 520, 'protein' => 35, 'carbs' => 55, 'fat' => 12],
        'Dinner' => ['description' => 'Baked salmon with quinoa and mixed vegetables', 'calories' => 480, 'protein' => 32, 'carbs' => 35, 'fat' => 18],
        'Snack' => ['description' => 'Greek yogurt with berries', 'calories' => 180, 'protein' => 15, 'carbs' => 20, 'fat' => 3]
    ],
    2 => [ // Tuesday
        'Breakfast' => ['description' => 'Whole grain toast with avocado and eggs', 'calories' => 380, 'protein' => 18, 'carbs' => 32, 'fat' => 22],
        'Lunch' => ['description' => 'Turkey wrap with mixed greens and hummus', 'calories' => 420, 'protein' => 28, 'carbs' => 45, 'fat' => 14],
        'Dinner' => ['description' => 'Lean beef stir-fry with vegetables and rice', 'calories' => 500, 'protein' => 38, 'carbs' => 48, 'fat' => 16],
        'Snack' => ['description' => 'Apple with almond butter', 'calories' => 220, 'protein' => 5, 'carbs' => 25, 'fat' => 12]
    ],
    3 => [ // Wednesday
        'Breakfast' => ['description' => 'Smoothie bowl with spinach, banana, and protein powder', 'calories' => 340, 'protein' => 25, 'carbs' => 42, 'fat' => 8],
        'Lunch' => ['description' => 'Tuna salad with mixed greens and olive oil dressing', 'calories' => 380, 'protein' => 32, 'carbs' => 15, 'fat' => 22],
        'Dinner' => ['description' => 'Grilled chicken with sweet potato and asparagus', 'calories' => 460, 'protein' => 40, 'carbs' => 35, 'fat' => 14],
        'Snack' => ['description' => 'Cottage cheese with pineapple', 'calories' => 160, 'protein' => 18, 'carbs' => 12, 'fat' => 2]
    ],
    4 => [ // Thursday
        'Breakfast' => ['description' => 'Oatmeal with chia seeds and fresh berries', 'calories' => 310, 'protein' => 10, 'carbs' => 52, 'fat' => 7],
        'Lunch' => ['description' => 'Quinoa bowl with black beans, corn, and avocado', 'calories' => 480, 'protein' => 18, 'carbs' => 65, 'fat' => 16],
        'Dinner' => ['description' => 'Baked cod with couscous and zucchini', 'calories' => 420, 'protein' => 35, 'carbs' => 38, 'fat' => 10],
        'Snack' => ['description' => 'Handful of mixed nuts', 'calories' => 200, 'protein' => 7, 'carbs' => 8, 'fat' => 18]
    ],
    5 => [ // Friday
        'Breakfast' => ['description' => 'Scrambled eggs with spinach and whole grain toast', 'calories' => 360, 'protein' => 22, 'carbs' => 28, 'fat' => 18],
        'Lunch' => ['description' => 'Chicken Caesar salad with light dressing', 'calories' => 380, 'protein' => 35, 'carbs' => 12, 'fat' => 18],
        'Dinner' => ['description' => 'Turkey meatballs with pasta and tomato sauce', 'calories' => 520, 'protein' => 42, 'carbs' => 45, 'fat' => 15],
        'Snack' => ['description' => 'Banana with peanut butter', 'calories' => 240, 'protein' => 6, 'carbs' => 32, 'fat' => 10]
    ],
    6 => [ // Saturday
        'Breakfast' => ['description' => 'Pancakes made with oats and topped with fruit', 'calories' => 350, 'protein' => 12, 'carbs' => 55, 'fat' => 8],
        'Lunch' => ['description' => 'Grilled vegetable sandwich with hummus', 'calories' => 380, 'protein' => 15, 'carbs' => 48, 'fat' => 12],
        'Dinner' => ['description' => 'Shrimp stir-fry with brown rice and vegetables', 'calories' => 460, 'protein' => 32, 'carbs' => 50, 'fat' => 12],
        'Snack' => ['description' => 'Protein bar', 'calories' => 220, 'protein' => 20, 'carbs' => 22, 'fat' => 6]
    ],
    7 => [ // Sunday
        'Breakfast' => ['description' => 'Yogurt parfait with granola and fruit', 'calories' => 320, 'protein' => 18, 'carbs' => 45, 'fat' => 8],
        'Lunch' => ['description' => 'Salmon salad with mixed greens', 'calories' => 420, 'protein' => 35, 'carbs' => 15, 'fat' => 24],
        'Dinner' => ['description' => 'Roast chicken with potatoes and green beans', 'calories' => 540, 'protein' => 45, 'carbs' => 35, 'fat' => 22],
        'Snack' => ['description' => 'Dark chocolate and almonds', 'calories' => 180, 'protein' => 5, 'carbs' => 15, 'fat' => 12]
    ]
];

// Get current meals (custom or default)
$current_meals = [];
foreach ($default_meals[$day_of_week] as $meal_type => $meal_data) {
    if (isset($_SESSION['custom_meals'][$meal_type])) {
        $current_meals[$meal_type] = $_SESSION['custom_meals'][$meal_type];
    } else {
        $current_meals[$meal_type] = $meal_data;
    }
}

// Calculate totals
$total_calories = array_sum(array_column($current_meals, 'calories'));
$total_protein = array_sum(array_column($current_meals, 'protein'));
$total_carbs = array_sum(array_column($current_meals, 'carbs'));
$total_fat = array_sum(array_column($current_meals, 'fat'));

// Check completed meals
$completed_today = isset($_SESSION['completed_meals'][$date_today]) ? $_SESSION['completed_meals'][$date_today] : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JeromeWorkoutPlan - Meal Plan</title>
    <link rel="icon" type="image/png" href="image.png">
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
                <div class="date-display"><?php echo date('l, M j, Y'); ?></div>
                <button class="filter-btn" onclick="toggleMealCustomizer()">Customize Meals</button>
            </div>
        </div>

        <div class="dashboard-content">
            <!-- Page Title Section -->
            <div class="page-title-section">
                <h1 class="page-title">Daily Meal Plan</h1>
                <p class="page-subtitle">Track your nutrition and fuel your body for optimal performance.</p>
            </div>

            <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                Meal plan updated successfully!
            </div>
            <?php endif; ?>

            <!-- Nutrition Summary Card -->
            <div class="nutrition-summary-card">
                <div class="summary-header">
                    <h3>Daily Nutrition Summary</h3>
                    <div class="total-calories">
                        <span class="calorie-number"><?php echo $total_calories; ?></span>
                        <span class="calorie-label">Calories</span>
                    </div>
                </div>
                <div class="nutrition-macros">
                    <div class="macro-item">
                        <span class="macro-value"><?php echo $total_protein; ?>g</span>
                        <span class="macro-label">Protein</span>
                    </div>
                    <div class="macro-item">
                        <span class="macro-value"><?php echo $total_carbs; ?>g</span>
                        <span class="macro-label">Carbs</span>
                    </div>
                    <div class="macro-item">
                        <span class="macro-value"><?php echo $total_fat; ?>g</span>
                        <span class="macro-label">Fats</span>
                    </div>
                </div>
                <div class="progress-indicator">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo min(100, ($total_calories / 2500) * 100); ?>%"></div>
                    </div>
                    <span class="progress-text"><?php echo round(($total_calories / 2500) * 100); ?>% of daily goal</span>
                </div>
            </div>

            <!-- Meal Cards -->
            <div class="meals-grid">
                <?php
                $meal_icons = [
                    'Breakfast' => '<svg viewBox="0 0 24 24"><path d="M12 3c-1.1 0-2 .9-2 2v8c0 .55-.45 1-1 1s-1-.45-1-1V8c0-.55-.45-1-1-1s-1 .45-1 1v5c0 2.21 1.79 4 4 4v3h2v-3c2.21 0 4-1.79 4-4V8c0-.55-.45-1-1-1s-1 .45-1 1v5c0 .55-.45 1-1 1s-1-.45-1-1V5c0-1.1-.9-2-2-2z"/></svg>',
                    'Lunch' => '<svg viewBox="0 0 24 24"><path d="M19 7h-3V6a3 3 0 0 0-3-3H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-3a3 3 0 0 0-3-3h-1V9a3 3 0 0 0-3-3z"/></svg>',
                    'Dinner' => '<svg viewBox="0 0 24 24"><path d="M19 7h-3V6a3 3 0 0 0-3-3H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-3a3 3 0 0 0-3-3h-1V9a3 3 0 0 0-3-3z"/><circle cx="9" cy="9" r="2"/><circle cx="15" cy="9" r="2"/></svg>',
                    'Snack' => '<svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>'
                ];

                $meal_colors = [
                    'Breakfast' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                    'Lunch' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                    'Dinner' => 'linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)',
                    'Snack' => 'linear-gradient(135deg, #f97316 0%, #ea580c 100%)'
                ];

                foreach ($current_meals as $meal_type => $meal_data):
                    $is_completed = isset($completed_today[$meal_type]);
                ?>
                <div class="meal-card <?php echo $is_completed ? 'completed' : ''; ?>">
                    <div class="meal-header">
                        <div class="meal-icon" style="background: <?php echo $meal_colors[$meal_type]; ?>">
                            <?php echo $meal_icons[$meal_type]; ?>
                        </div>
                        <div class="meal-info">
                            <h4 class="meal-name"><?php echo $meal_type; ?></h4>
                            <div class="meal-calories"><?php echo $meal_data['calories']; ?> cal</div>
                        </div>
                        <?php if ($is_completed): ?>
                        <div class="completion-badge">
                            <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="meal-content">
                        <p class="meal-description"><?php echo $meal_data['description']; ?></p>
                        <div class="meal-macros">
                            <span class="macro-item">P: <?php echo $meal_data['protein']; ?>g</span>
                            <span class="macro-item">C: <?php echo $meal_data['carbs']; ?>g</span>
                            <span class="macro-item">F: <?php echo $meal_data['fat']; ?>g</span>
                        </div>
                    </div>

                    <div class="meal-actions">
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="meal_type" value="<?php echo $meal_type; ?>">
                            <button type="submit" name="complete_meal" class="meal-action-btn complete-btn <?php echo $is_completed ? 'completed' : ''; ?>">
                                <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                <?php echo $is_completed ? 'Completed' : 'Mark Complete'; ?>
                            </button>
                        </form>
                        <button class="meal-action-btn recipe-btn" onclick="showMealDetails('<?php echo $meal_type; ?>')">
                            <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            Details
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Weekly Meal Planner -->
            <div class="weekly-planner-card">
                <div class="planner-header">
                    <h3>Weekly Meal Overview</h3>
                    <p>Plan ahead and stay consistent with your nutrition</p>
                </div>
                <div class="weekly-calendar">
                    <?php
                    $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    for ($i = 0; $i < 7; $i++):
                        $day_num = $i + 1;
                        $is_today = ($day_of_week == $day_num);
                        $day_meals = $default_meals[$day_num];
                        $day_completed = isset($_SESSION['completed_meals'][date('Y-m-d', strtotime("-$day_of_week days +$day_num days"))]) ?
                            count($_SESSION['completed_meals'][date('Y-m-d', strtotime("-$day_of_week days +$day_num days"))]) : 0;
                        $completion_rate = round(($day_completed / 4) * 100);
                    ?>
                    <div class="calendar-day <?php echo $is_today ? 'today' : ''; ?>">
                        <div class="day-header">
                            <span class="day-name"><?php echo $days[$i]; ?></span>
                            <span class="day-date"><?php echo date('j', strtotime("-$day_of_week days +$day_num days")); ?></span>
                        </div>
                        <div class="day-meals">
                            <?php foreach ($day_meals as $meal_type => $meal): ?>
                            <div class="mini-meal <?php echo $meal_type; ?>"></div>
                            <?php endforeach; ?>
                        </div>
                        <div class="day-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo $completion_rate; ?>%"></div>
                            </div>
                            <span class="progress-text"><?php echo $completion_rate; ?>%</span>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
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

    <!-- Meal Customizer Modal -->
    <div id="meal-customizer" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Customize Your Meals</h3>
                <button class="modal-close" onclick="toggleMealCustomizer()">&times;</button>
            </div>
            <div class="modal-body">
                <?php foreach ($current_meals as $meal_type => $meal_data): ?>
                <div class="customize-meal-item">
                    <h4><?php echo $meal_type; ?></h4>
                    <form method="post" class="customize-form">
                        <input type="hidden" name="meal_type" value="<?php echo $meal_type; ?>">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Meal Description</label>
                                <input type="text" name="custom_meal" value="<?php echo $meal_data['description']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Calories</label>
                                <input type="number" name="custom_calories" value="<?php echo $meal_data['calories']; ?>" min="0" max="2000" required>
                            </div>
                        </div>
                        <button type="submit" name="customize_meal" class="save-customization-btn">Save Changes</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        function toggleMealCustomizer() {
            const modal = document.getElementById('meal-customizer');
            modal.style.display = modal.style.display === 'none' ? 'flex' : 'none';
        }

        function showMealDetails(mealType) {
            // This could be expanded to show detailed recipes
            alert(`${mealType} details and recipes coming soon!`);
        }

        // Close modal when clicking outside
        document.getElementById('meal-customizer').addEventListener('click', function(e) {
            if (e.target === this) {
                toggleMealCustomizer();
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