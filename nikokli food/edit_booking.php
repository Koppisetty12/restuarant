<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include "config.php";

$user_id = $_SESSION["user_id"];
$msg = "";

// Fetch existing booking details
if (!isset($_GET["id"])) {
    header("Location: dashboard.php");
    exit();
}

$booking_id = $_GET["id"];

// Check if booking belongs to user and not cancelled
$stmt = $conn->prepare("SELECT date, time, guests, status FROM bookings WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    $stmt->close();
    header("Location: dashboard.php");
    exit();
}

$stmt->bind_result($date, $time, $guests, $status);
$stmt->fetch();
$stmt->close();

if ($status == "cancelled") {
    $msg = "❌ Cannot edit a cancelled booking.";
}

// Update booking
if ($_SERVER["REQUEST_METHOD"] == "POST" && $status != "cancelled") {
    $new_date = $_POST["date"];
    $new_time = $_POST["time"];
    $new_guests = $_POST["guests"];

    // Check time range
    $bookingTime = strtotime($new_time);
    if ($bookingTime < strtotime("18:00") || $bookingTime > strtotime("22:00")) {
        $msg = "❌ Time must be between 6:00 PM and 10:00 PM.";
    } else {
        // Check availability
        $stmt = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE date = ? AND time = ? AND id != ? AND status != 'cancelled'");
        $stmt->bind_param("ssi", $new_date, $new_time, $booking_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count >= 5) {
            $msg = "❌ Time slot not available.";
        } else {
            $stmt = $conn->prepare("UPDATE bookings SET date = ?, time = ?, guests = ? WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ssiii", $new_date, $new_time, $new_guests, $booking_id, $user_id);
            if ($stmt->execute()) {
                header("Location: dashboard.php");
                exit();
            } else {
                $msg = "❌ Update failed.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Booking | Nikokli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Edit Booking</h2>
    <?php if ($msg): ?>
        <div class="alert alert-warning"><?= $msg ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" value="<?= $date ?>" required class="form-control" min="<?= date('Y-m-d') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Time (6–10 PM)</label>
            <input type="time" name="time" value="<?= $time ?>" required class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Guests</label>
            <input type="number" name="guests" value="<?= $guests ?>" required class="form-control" min="1" max="10">
        </div>
        <button type="submit" class="btn btn-primary">Update Booking</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
