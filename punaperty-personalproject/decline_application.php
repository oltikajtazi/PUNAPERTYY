<?php
// filepath: c:\xampp\htdocs\punaperty\decline_application.php
session_start();
include('inc/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$application_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Merr user_id të aplikuesit
$stmt = $conn->prepare("SELECT user_id FROM applications WHERE id = ?");
$stmt->bind_param("i", $application_id);
$stmt->execute();
$stmt->bind_result($applicant_id);
$stmt->fetch();
$stmt->close();

// Përditëso statusin e aplikimit
$stmt = $conn->prepare("UPDATE applications SET status = 'declined' WHERE id = ?");
$stmt->bind_param("i", $application_id);
$stmt->execute();
$stmt->close();

// Shto njoftimin për aplikuesin
$msg = "Aplikimi juaj është refuzuar. Ju urojmë më shumë fat herën tjetër!";
$stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
$stmt->bind_param("is", $applicant_id, $msg);
$stmt->execute();
$stmt->close();

header("Location: dashboard.php");
exit;
?>