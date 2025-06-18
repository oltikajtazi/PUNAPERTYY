<?php
include('inc/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Kontrollo nëse kolona cv_text ekziston në tabelën applications
$checkColumn = $conn->query("SHOW COLUMNS FROM applications LIKE 'cv_text'");
if ($checkColumn && $checkColumn->num_rows == 0) {
    $alter = $conn->query("ALTER TABLE applications ADD COLUMN cv_text TEXT NOT NULL AFTER email");
    if (!$alter) {
        die("Gabim në shtimin e kolonës cv_text: " . $conn->error);
    }
}

// Merr aplikimet për punët e përdoruesit
$sql = "SELECT a.id AS application_id, a.full_name, a.email, a.cv_text, a.applied_at,
               j.title AS job_title
        FROM applications a
        JOIN jobs j ON a.job_id = j.id
        WHERE j.user_id = ? AND (a.status IS NULL OR a.status = 'pending')
        ORDER BY a.applied_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

include('inc/header.php');
?>

<div class="container mt-5" style="max-width: 900px;">
  <h2 class="mb-4 text-center">📨 Aplikimet për Punët tuaja</h2>

  <?php if ($result && $result->num_rows > 0): ?>
    <div class="list-group shadow rounded-4">
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="list-group-item p-4">
          <h5 class="mb-2">
            <?php echo htmlspecialchars($row['full_name']); ?> aplikoi për
            <strong><?php echo htmlspecialchars($row['job_title']); ?></strong>
          </h5>
          <p class="mb-1 text-muted">Email: <?php echo htmlspecialchars($row['email']); ?></p>
          <p class="mb-3">
            CV:
            <?php
              $cv = trim($row['cv_text']);
              echo $cv ? nl2br(htmlspecialchars($cv)) : '<span class="text-muted">Nuk ka CV të bashkangjitur.</span>';
            ?>
          </p>
          <small class="text-secondary">
            Aplikuar më <?php echo date('d.m.Y H:i', strtotime($row['applied_at'])); ?>
          </small>
          <div class="mt-3">
            <button class="btn btn-success rounded-pill me-2 action-btn" data-action="accept" data-id="<?php echo $row['application_id']; ?>" data-email="<?php echo htmlspecialchars($row['email']); ?>">Prano</button>
            <button class="btn btn-danger rounded-pill action-btn" data-action="decline" data-id="<?php echo $row['application_id']; ?>" data-email="<?php echo htmlspecialchars($row['email']); ?>">Refuzo</button>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info">Nuk ka aplikime për punët e tua ende.</div>
  <?php endif; ?>
</div>

<?php include('inc/footer.php'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
  $('.action-btn').click(function() {
    var btn = $(this);
    var action = btn.data('action');
    var id = btn.data('id');
    var email = btn.data('email');
    $.post('process_application.php', {
      id: id,
      action: action,
      email: email
    }, function(response) {
      if (response.success) {
        btn.closest('.list-group-item').fadeOut();
        alert(response.message);
      } else {
        alert('Gabim: ' + response.message);
      }
    }, 'json');
  });
});
</script>
