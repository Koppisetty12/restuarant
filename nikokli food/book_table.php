<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include "config.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $name = $_SESSION["user_name"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $guests = $_POST["guests"];

    // Check if time is within 6 PM to 10 PM
    $bookingTime = strtotime($time);
    if ($bookingTime < strtotime("18:00") || $bookingTime > strtotime("22:00")) {
        $msg = "❌ Time must be between 6:00 PM and 10:00 PM.";
    } else {
        // Check if the same slot already has 5+ bookings (example limit)
        $stmt = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE date = ? AND time = ? AND status != 'cancelled'");
        $stmt->bind_param("ss", $date, $time);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count >= 5) {
            $msg = "❌ Slot not available. Please choose another time.";
        } else {
            $stmt = $conn->prepare("INSERT INTO bookings (user_id, name, date, time, guests) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("isssi", $user_id, $name, $date, $time, $guests);
            if ($stmt->execute()) {
                $msg = "✅ Booking successful! Waiting for approval.";
            } else {
                $msg = "❌ Booking failed. Try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book a Table | Nikokli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Book a Table</h2>
    <?php if ($msg): ?>
        <div class="alert alert-info"><?= $msg ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" required class="form-control" min="<?= date('Y-m-d') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Time (6 PM to 10 PM only)</label>
            <input type="time" name="time" required class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Number of Guests</label>
            <input type="number" name="guests" required min="1" max="10" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Book Now</button>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </form>
</div>
</body>
</html>
