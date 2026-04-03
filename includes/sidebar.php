<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <div class="logo">
        <img src="image.png" alt="JeromeWorkoutPlan" class="logo-image">
        <span class="logo-text">Workout Plan</span>
    </div>
    <nav>
        <ul>
            <li><a href="index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                Dashboard
            </a></li>
            <li><a href="workouts.php" class="<?php echo $current_page == 'workouts.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24"><circle cx="6" cy="6" r="2"/><circle cx="18" cy="6" r="2"/><rect x="8" y="4" width="8" height="4"/><circle cx="6" cy="18" r="2"/><circle cx="18" cy="18" r="2"/><rect x="8" y="16" width="8" height="4"/></svg>
                Workouts
            </a></li>
            <li><a href="meals.php" class="<?php echo $current_page == 'meals.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24"><path d="M3 2v20h18V2H3zm15 18H6V4h12v16z"/><circle cx="9" cy="9" r="1"/><circle cx="15" cy="9" r="1"/><path d="M9 12h6v2H9z"/></svg>
                Meal Plan
            </a></li>
            <li><a href="progress.php" class="<?php echo $current_page == 'progress.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24"><path d="M3.5 18.49l6-6.01 4 4L22 6.92l-1.41-1.41-7.09 7.97-4-4L2 16.99z"/></svg>
                Progress
            </a></li>
            <li><a href="profile.php" class="<?php echo $current_page == 'profile.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                Profile
            </a></li>
        </ul>
    </nav>
    <div class="theme-toggle">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <button type="submit" name="toggle_theme">
                <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] == 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode'; ?>
            </button>
        </form>
    </div>
</div>