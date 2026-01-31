<nav>
    <div class="nav-links">
        <a href="../Home/home.php#home">Home</a>
        <a href="../Home/home.php#map">Map</a>
        <a href="../Home/home.php#about">About</a>
        <a href="../Home/home.php#contact">Contact</a>
    </div>

    <div class="auth">
        <?php if (isLoggedIn()): ?>
            <button class="auth-btn"><?php echo htmlspecialchars(getCurrentUsername()); ?> ▾</button>
            <div class="auth-dropdown">
                <a href="../../Pages/Tickets/my_tickets.php">My Tickets</a>
                <a href="../../api/logout.php">Logout</a>
            </div>
        <?php else: ?>
            <button class="auth-btn">Account ▾</button>
            <div class="auth-dropdown">
                <a href="../../Login/login.php">Login</a>
                <a href="../../Login/register.php">Register</a>
            </div>
        <?php endif; ?>
    </div>
</nav>
