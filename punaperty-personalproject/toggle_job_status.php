<?php
<?php
include('inc/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['job_id'])) {
    $job_id = intval($_POST['job_id']);
    // Merr statusin aktual
    $stmt = $conn->prepare("SELECT status FROM jobs WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $job_id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($current_status);
    $stmt->fetch();
    $stmt->close();

    $new_status = ($current_status === 'closed') ? 'open' : 'closed';

    $stmt = $conn->prepare("UPDATE jobs SET status = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $new_status, $job_id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
}

header("Location: dashboard.php");
exit;