<?php
include('inc/db.php');
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$user_id = $_SESSION['user_id'];
$keyword = trim($_POST['keyword']);
$city = trim($_POST['city']);

$stmt = $conn->prepare("INSERT INTO job_alerts (user_id, keyword, city) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $keyword, $city);
$stmt->execute();
$stmt->close();

header("Location: dashboard.php?alert=1");
exit;