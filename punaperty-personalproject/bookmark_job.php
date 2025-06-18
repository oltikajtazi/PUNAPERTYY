<?php
include('inc/db.php');
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$user_id = $_SESSION['user_id'];
$job_id = intval($_POST['job_id']);

$stmt = $conn->prepare("INSERT IGNORE INTO bookmarks (user_id, job_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $job_id);
$stmt->execute();
$stmt->close();

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;