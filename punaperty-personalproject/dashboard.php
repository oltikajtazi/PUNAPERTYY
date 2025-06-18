<?php
include('inc/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Merr punët e ruajtura nga databaza
$result = $conn->query("SELECT j.id, j.title, j.city FROM bookmarks b JOIN jobs j ON b.job_id = j.id WHERE b.user_id = $user_id ORDER BY b.created_at DESC");

include('inc/header.php');
?>
<div class="container mt-5" style="max-width: 900px;">
  <h2 class="mb-4">⭐ Punët e Ruajtura</h2>
  <?php if ($result->num_rows > 0): ?>
    <ul class="list-group">
      <?php while($row = $result->fetch_assoc()): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span>
            <strong><?php echo htmlspecialchars($row['title']); ?></strong>
            <span class="text-muted ms-2"><?php echo htmlspecialchars($row['city']); ?></span>
          </span>
          <a href="apply.php?job_id=<?php echo $row['id']; ?>&title=<?php echo urlencode($row['title']); ?>" class="btn btn-success btn-sm">Apliko</a>
        </li>
      <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <div class="alert alert-info">Nuk keni ruajtur asnjë punë ende.</div>
  <?php endif; ?>
</div>
<?php include('inc/footer.php'); ?>
