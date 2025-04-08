<?php
// Optional: session_start(); // Add if you want admin login in future

include "config.php";

$msg = "";

// Handle actions
if (isset($_GET["approve"])) {
    $id = $_GET["approve"];
    $stmt = $conn->prepare("UPDATE bookings SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $msg = "✅ Booking approved.";
}

if (isset($_GET["cancel"])) {
    $id = $_GET["cancel"];
    $stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $msg = "❌ Booking cancelled.";
}

if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $msg = "🗑️ Booking deleted.";
}

// Fetch all bookings
$sql = "SELECT b.id, b.date, b.time, b.guests, b.status, u.name AS user_name, u.email 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        ORDER BY b.date DESC, b.time DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel | Nikokli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Admin Panel - All Bookings</h2>
    <?php if ($msg): ?>
        <div class="alert alert-info"><?= $msg ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Date</th>
                <th>Time</th>
                <th>Guests</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row["user_name"]) ?></td>
                    <td><?= htmlspecialchars($row["email"]) ?></td>
                    <td><?= $row["date"] ?></td>
                    <td><?= date("g:i A", strtotime($row["time"])) ?></td>
                    <td><?= $row["guests"] ?></td>
                    <td><?= ucfirst($row["status"]) ?></td>
                    <td>
                        <?php if ($row["status"] == "pending"): ?>
                            <a href="admin_panel.php?approve=<?= $row["id"] ?>" class="btn btn-sm btn-success">Approve</a>
                        <?php endif; ?>
                        <?php if ($row["status"] != "cancelled"): ?>
                            <a href="admin_panel.php?cancel=<?= $row["id"] ?>" class="btn btn-sm btn-warning">Cancel</a>
                        <?php endif; ?>
                        <a href="admin_panel.php?delete=<?= $row["id"] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this booking?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7">No bookings found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="index.php" class="btn btn-secondary mt-3">Back to Home</a>
</div>
</body>
</html>
