<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">🍽️ Nikokli</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION["admin_logged_in"])): ?>
          <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Admin Panel</a></li>
        <?php elseif (isset($_SESSION["user_id"])): ?>
          <li class="nav-item"><a class="nav-link" href="book_table.php">Book Table</a></li>
          <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="register.php">Sign Up</a></li>
          <li class="nav-item"><a class="nav-link" href="admin_login.php">Admin</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
