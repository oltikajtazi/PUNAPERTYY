<?php
include(__DIR__ . '/db.php');
if (session_status() == PHP_SESSION_NONE) session_start();
global $total_unread;

// Merr numrin e aplikimeve të reja për user-in aktual (punëdhënësin)
$new_applications = 0;
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM applications a JOIN jobs j ON a.job_id = j.id WHERE j.user_id = ? AND (a.status IS NULL OR a.status = 'pending')");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($new_applications);
    $stmt->fetch();
    $stmt->close();
}

$notif_count = 0;
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($notif_count);
    $stmt->fetch();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Puna përty</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
<div class="d-flex">
  <nav class="sidebar bg-primary text-white vh-100 p-3">
    <!-- Sidebar menu ... -->
    <a class="navbar-brand text-white mb-4 d-block" href="index.php">dboltii</a>
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link text-white" href="index.php">Kreu</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="jobs.php">Punët</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="quiz.php">Take Quiz</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="courses.php">Free Courses</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="review.php">Reviews</a></li>
      <?php if (isset($_SESSION['user_id'])): ?>
        <li class="nav-item"><a class="nav-link text-white" href="post-job.php">Posto Punë</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="logout.php">Dil</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="company_profile.php">Kompania Ime</a></li>
      <?php else: ?>
        <li class="nav-item"><a class="nav-link text-white" href="login.php">Hyr</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="register.php">Regjistrohu</a></li>
      <?php endif; ?>
      <li class="nav-item">
        <a class="nav-link text-white position-relative" href="notifications.php">
          🔔
          <?php if ($notif_count > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              <?php echo $notif_count; ?>
            </span>
          <?php endif; ?>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white position-relative" href="/punaperty/messages.php">
          <span class="bi bi-chat-dots"></span> Mesazhet
          <?php if (isset($total_unread) && $total_unread > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              <?php echo $total_unread; ?>
            </span>
          <?php endif; ?>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white position-relative" href="review.php">
          Reviews
          <?php if ($new_applications > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              <?php echo $new_applications; ?>
            </span>
          <?php endif; ?>
        </a>
      </li>
    </ul>
  </nav>
  <div class="flex-grow-1 p-4" style="min-height:100vh;">
    <!-- Këtu fillon përmbajtja e faqes -->
