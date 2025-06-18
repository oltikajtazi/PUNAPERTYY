<?php
session_start();
include('inc/db.php');

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Nuk jeni i kyçur.']);
    exit;
}

$application_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';

if (!$application_id || !in_array($action, ['accept', 'decline'])) {
    echo json_encode(['success' => false, 'message' => 'Kërkesë e pavlefshme.']);
    exit;
}

$status = $action === 'accept' ? 'accepted' : 'declined';
$msg = $action === 'accept'
    ? "Aplikimi juaj është pranuar! Urime!"
    : "Aplikimi juaj është refuzuar. Ju urojmë më shumë fat herën tjetër!";

// Update application status
$stmt = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $application_id);
$stmt->execute();
$stmt->close();

// Send notification email
$subject = "Njoftim për aplikimin tuaj";
$headers = "From: noreply@punaperty.local\r\nContent-Type: text/plain; charset=UTF-8";
@mail($email, $subject, $msg, $headers);

echo json_encode(['success' => true, 'message' => "Aplikimi u $status dhe njoftimi u dërgua me email."]);