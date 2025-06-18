<?php
include('inc/db.php');
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$user_id = $_SESSION['user_id'];
$conn->query("UPDATE notifications SET is_read = 1 WHERE user_id = $user_id");
$result = $conn->query("SELECT message, created_at FROM notifications WHERE user_id = $user_id ORDER BY created_at DESC");
include('inc/header.php');
?>
<div class="container mt-5" style="max-width:700px;">
  <h2>Njoftimet</h2>
  <ul class="list-group">
    <?php while($row = $result->fetch_assoc()): ?>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <?php echo htmlspecialchars($row['message']); ?>
        <span class="badge bg-secondary"><?php echo date('d.m.Y H:i', strtotime($row['created_at'])); ?></span>
      </li>
    <?php endwhile; ?>
  </ul>
</div>
<?php include('inc/footer.php'); ?>