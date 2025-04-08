<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include "config.php";

$user_id = $_SESSION["user_id"];
$msg = "";

// Cancel booking if requested
if (isset($_GET["cancel"])) {
    $booking_id = $_GET["cancel"];
    $stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $booking_id, $user_id);
    if ($stmt->execute()) {
        $msg = "✅ Booking cancelled.";
    } else {
        $msg = "❌ Failed to cancel booking.";
    }
}

// Fetch all user's bookings
$stmt = $conn->prepare("SELECT id, date, time, guests, status FROM bookings WHERE user_id = ? ORDER BY date DESC, time DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard | Nikokli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Welcome, <?= $_SESSION["user_name"] ?></h2>
    <?php if ($msg): ?>
        <div class="alert alert-info"><?= $msg ?></div>
    <?php endif; ?>

    <a href="book_table.php" class="btn btn-success mb-3">Book a Table</a>
    <a href="logout.php" class="btn btn-danger mb-3 float-end">Logout</a>

    <h4>Your Bookings:</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
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
                        <td><?= $row["date"] ?></td>
                        <td><?= date("g:i A", strtotime($row["time"])) ?></td>
                        <td><?= $row["guests"] ?></td>
                        <td><?= ucfirst($row["status"]) ?></td>
                        <td>
                            <?php if ($row["status"] != "cancelled"): ?>
                                <a href="dashboard.php?cancel=<?= $row["id"] ?>" class="btn btn-sm btn-warning" onclick="return confirm('Cancel this booking?')">Cancel</a>
                                <a href="edit_booking.php?id=<?= $row["id"] ?>" class="btn btn-sm btn-info">Edit</a>
                            <?php else: ?>
                                <em>Cancelled</em>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No bookings found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
