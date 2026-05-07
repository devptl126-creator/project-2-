<?php
// ─────────────────────────────────────────────
//  login.php  –  Handles guest authentication
//  Sunset Resort | College Project
// ─────────────────────────────────────────────

session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.html');
    exit;
}

// ── 1. Collect input ──────────────────────────────────────────────────────
$email    = trim($_POST['email']    ?? '');
$password = $_POST['password']      ?? '';
$remember = isset($_POST['remember']);

// ── 2. Basic validation ───────────────────────────────────────────────────
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
    $_SESSION['errors'] = ['Please enter a valid email and password.'];
    header('Location: login.html');
    exit;
}

// ── 3. Fetch guest from DB ────────────────────────────────────────────────
$conn = get_db();

$stmt = $conn->prepare(
    "SELECT id, name, password FROM guests WHERE email = ? LIMIT 1"
);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$guest  = $result->fetch_assoc();
$stmt->close();
$conn->close();

// ── 4. Verify password ────────────────────────────────────────────────────
if (!$guest || !password_verify($password, $guest['password'])) {
    $_SESSION['errors'] = ['Invalid email or password.'];
    header('Location: login.html');
    exit;
}

// ── 5. Start authenticated session ───────────────────────────────────────
session_regenerate_id(true); // Prevent session fixation

$_SESSION['guest_id']   = $guest['id'];
$_SESSION['guest_name'] = $guest['name'];
$_SESSION['logged_in']  = true;

// Optional: set a "remember me" cookie (30 days)
if ($remember) {
    $token = bin2hex(random_bytes(32));
    setcookie('remember_token', $token, time() + (30 * 24 * 3600), '/', '', true, true);
    // NOTE: In production, store hashed token in a `remember_tokens` table
}

// ── 6. Redirect ───────────────────────────────────────────────────────────
header('Location: dashboard.html');
exit;
