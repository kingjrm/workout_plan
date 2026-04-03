<?php
session_start();
if (!isset($_SESSION['goal'])) {
    $_SESSION['goal'] = 'Build muscle and lose fat';
}
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}

if (isset($_POST['toggle_theme'])) {
    $_SESSION['theme'] = $_SESSION['theme'] == 'dark' ? 'light' : 'dark';
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['goal'])) {
    $_SESSION['goal'] = $_POST['goal'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JeromeWorkoutPlan - Profile</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="<?php echo $_SESSION['theme']; ?>">
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-content">
        <div class="card">
            <h2>User Details</h2>
            <p><strong>Age:</strong> 21</p>
            <p><strong>Height:</strong> 5’5”</p>
            <p><strong>Equipment:</strong> 4kg dumbbell</p>
        </div>
        <div class="card">
            <h2>Goal</h2>
            <form method="post">
                <div class="form-group">
                    <label for="goal">Fitness Goal:</label>
                    <textarea id="goal" name="goal" rows="3"><?php echo $_SESSION['goal']; ?></textarea>
                </div>
                <button type="submit" class="btn">Update Goal</button>
            </form>
        </div>
    </div>
</body>
</html>