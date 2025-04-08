<?php
session_start();
include "config.php";

if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: admin_login.php");
    exit();
}

$msg = "";

if (isset($_GET["approve"])) {
    $id = $_GET["approve"];
    $conn->query("UPDATE bookings SET status = 'approved' WHERE id = $id");
}
if (isset($_GET["cancel"])) {
    $id = $_GET["cancel"];
    $conn->query("UPDATE bookings SET status = 'cancelled' WHERE id = $id");
}

$result = $conn->query("SELECT * FROM bookings ORDER BY date DESC, time DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard | Nikokli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "navbar.php"; ?>
<div class="container mt-5">
    <h2 class="mb-4">Admin Dashboard - All Bookings</h2>
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>User ID</th>
                <th>Date</th>
                <th>Time</th>
                <th>Guests</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row["user_id"] ?></td>
                    <td><?= $row["date"] ?></td>
                    <td><?= date("g:i A", strtotime($row["time"])) ?></td>
                    <td><?= $row["guests"] ?></td>
                    <td><?= ucfirst($row["status"]) ?></td>
                    <td>
                        <?php if ($row["status"] == "pending"): ?>
                            <a href="?approve=<?= $row["id"] ?>" class="btn btn-success btn-sm">Approve</a>
                            <a href="?cancel=<?= $row["id"] ?>" class="btn btn-danger btn-sm">Cancel</a>
                        <?php elseif ($row["status"] == "approved"): ?>
                            <a href="?cancel=<?= $row["id"] ?>" class="btn btn-warning btn-sm">Cancel</a>
                        <?php else: ?>
                            <em>Cancelled</em>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No bookings found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
