<?php
// ─────────────────────────────────────────────
//  dashboard.php  –  Show logged-in user's bookings
//  Masti Resort | College Project
// ─────────────────────────────────────────────

session_start();
require_once 'db_connect.php';

// FIX 1: Check session key that login.php actually sets
if (empty($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

$user_id   = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'Guest';

// FIX 2: Fetch bookings using MySQLi (not PDO) matching db_connect.php
$conn  = get_db();
$stmt  = $conn->prepare(
    "SELECT * FROM bookings WHERE guest_id = ? ORDER BY id DESC"
);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result   = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();

// Room prices for display
$prices = ['Deluxe' => '₹28,000', 'Premium' => '₹75,000', 'Villa' => '₹1,50,000+'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Masti Resort</title>
    <link rel="stylesheet" href="registerstyle.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .dashboard-container { max-width: 900px; margin: 2rem auto; padding: 0 1.5rem; }
        .welcome-bar {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white; padding: 1.5rem 2rem; border-radius: 16px;
            margin-bottom: 2rem; display: flex;
            justify-content: space-between; align-items: center;
        }
        .welcome-bar h2 { margin: 0; font-size: 1.6rem; }
        .booking-card {
            background: white; border-radius: 16px; padding: 1.8rem;
            margin-bottom: 1.5rem; border-left: 5px solid #667eea;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .booking-card h3 { color: #2c3e50; margin-bottom: 1rem; }
        .booking-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
            gap: 0.8rem; margin-bottom: 1rem;
        }
        .booking-grid div { color: #555; font-size: 0.95rem; }
        .booking-grid strong { color: #333; }
        .badge {
            display: inline-block; padding: 4px 14px; border-radius: 20px;
            font-size: 0.8rem; font-weight: 700; text-transform: uppercase;
        }
        .badge-pending  { background: #fff3cd; color: #856404; }
        .badge-confirmed{ background: #d1ecf1; color: #0c5460; }
        .badge-cancelled{ background: #f8d7da; color: #721c24; }
        .no-bookings {
            text-align: center; padding: 3rem; background: white;
            border-radius: 16px; color: #666;
        }
        .logout-btn {
            background: #e74c3c; color: white; padding: 0.6rem 1.4rem;
            border-radius: 20px; text-decoration: none; font-size: 0.9rem;
        }
    </style>
</head>
<body>
<div class="main">
    <!-- Header -->
    <div class="header">
        <h1><i class="fas fa-sun"></i> Masti Resort</h1>
        <nav>
            <a href="home.html">Home</a> |
            <a href="register.html">New Booking</a> |
            <a href="logout.php">Logout</a>
        </nav>
    </div>

    <div class="dashboard-container">

        <!-- Welcome bar -->
        <div class="welcome-bar">
            <h2><i class="fas fa-user-circle"></i> Welcome, <?= htmlspecialchars($user_name) ?>!</h2>
            <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>

        <!-- Success / Error messages -->
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="success-message" style="margin-bottom:1.5rem;">
                <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['errors'])): ?>
            <div class="error-message" style="margin-bottom:1.5rem;">
                <?php foreach($_SESSION['errors'] as $e) echo htmlspecialchars($e) . '<br>';
                      unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>

        <h3 style="color:#2c3e50; margin-bottom:1.5rem;">
            <i class="fas fa-calendar-check"></i> Your Bookings
        </h3>

        <!-- Bookings list -->
        <?php if (empty($bookings)): ?>
            <div class="no-bookings">
                <i class="fas fa-calendar-times" style="font-size:3rem;color:#ccc;margin-bottom:1rem;display:block;"></i>
                <h3>No bookings yet</h3>
                <p style="margin:1rem 0;">Make your first booking today!</p>
                <a href="register.html" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Book a Room
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($bookings as $b): ?>
                <div class="booking-card">
                    <h3>
                        <i class="fas fa-bed"></i>
                        <?= htmlspecialchars($b['room_type']) ?> Room
                        &nbsp;
                        <span class="badge badge-<?= strtolower($b['status']) ?>">
                            <?= ucfirst($b['status']) ?>
                        </span>
                    </h3>
                    <div class="booking-grid">
                        <div><strong>Booking ID:</strong> #<?= $b['id'] ?></div>
                        <div><strong>Room:</strong> <?= htmlspecialchars($b['room_type']) ?> (<?= $prices[$b['room_type']] ?? '' ?>/night)</div>
                        <div><strong>Check-in:</strong> <?= date('d M Y', strtotime($b['check_in'])) ?></div>
                        <div><strong>Check-out:</strong> <?= date('d M Y', strtotime($b['check_out'])) ?></div>
                        <div><strong>Guests:</strong> <?= $b['guests'] ?></div>
                        <div><strong>Booked on:</strong> <?= date('d M Y', strtotime($b['booked_at'])) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

    <footer class="footer">
        <p>&copy; 2026 Masti Resort | College Project</p>
    </footer>
</div>
</body>
</html>