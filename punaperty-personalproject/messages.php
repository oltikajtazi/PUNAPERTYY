<?php
include('inc/db.php');
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$user_id = $_SESSION['user_id'];

// Get chat partner
$partner_id = isset($_GET['user']) ? intval($_GET['user']) : 0;

// Send message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $partner_id && !empty($_POST['message'])) {
    $msg = trim($_POST['message']);
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $partner_id, $msg);
    $stmt->execute();
    $stmt->close();
    // Redirect to avoid resubmission
    header("Location: messages.php?user=$partner_id");
    exit;
}

// Mark messages as read
if ($partner_id) {
    $stmt = $conn->prepare("UPDATE messages SET is_read=1 WHERE receiver_id=? AND sender_id=?");
    $stmt->bind_param("ii", $user_id, $partner_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all users except yourself, and count unread messages from each
$stmt = $conn->prepare("
    SELECT u.id, u.email,
        (SELECT COUNT(*) FROM messages m WHERE m.sender_id = u.id AND m.receiver_id = ? AND m.is_read = 0) AS unread_count
    FROM users u
    WHERE u.id != ?
");
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$users = $stmt->get_result();

// Fetch messages with selected user
$chat = [];
if ($partner_id) {
    $stmt = $conn->prepare("SELECT * FROM messages WHERE (sender_id=? AND receiver_id=?) OR (sender_id=? AND receiver_id=?) ORDER BY sent_at ASC");
    $stmt->bind_param("iiii", $user_id, $partner_id, $partner_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) $chat[] = $row;
    $stmt->close();
}

// Merr numrin total të mesazheve të pa lexuara për këtë user
$stmt = $conn->prepare("SELECT COUNT(*) AS total_unread FROM messages WHERE receiver_id = ? AND is_read = 0");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$total_unread = 0;
if ($row = $result->fetch_assoc()) {
    $total_unread = $row['total_unread'];
}
$stmt->close();

// Merr term kërkimi nëse ekziston
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Modifiko query për të kërkuar sipas emrit ose email-it
if ($search !== '') {
    $search_param = '%' . $search . '%';
    $stmt = $conn->prepare("
        SELECT u.id, u.email,
            (SELECT COUNT(*) FROM messages m WHERE m.sender_id = u.id AND m.receiver_id = ? AND m.is_read = 0) AS unread_count
        FROM users u
        WHERE u.id != ? AND (u.email LIKE ? OR u.full_name LIKE ?)
    ");
    $stmt->bind_param("iiss", $user_id, $user_id, $search_param, $search_param);
} else {
    $stmt = $conn->prepare("
        SELECT u.id, u.email,
            (SELECT COUNT(*) FROM messages m WHERE m.sender_id = u.id AND m.receiver_id = ? AND m.is_read = 0) AS unread_count
        FROM users u
        WHERE u.id != ?
    ");
    $stmt->bind_param("ii", $user_id, $user_id);
}
$stmt->execute();
$users = $stmt->get_result();

// Pastaj vazhdo me header
include('inc/header.php');
?>
<div class="container mt-5" style="max-width:900px;">
  <h2 class="mb-4">💬 Mesazhet</h2>
  <div class="row">
    <div class="col-md-4">
      <h5>Bisedat</h5>
      <!-- Search bar -->
      <form method="get" class="mb-3">
        <input type="text" name="search" class="form-control" placeholder="Kërko përdorues..." value="<?php echo htmlspecialchars($search); ?>">
      </form>
      <ul class="list-group">
        <?php while($u = $users->fetch_assoc()): ?>
          <li class="list-group-item<?php if($partner_id == $u['id']) echo ' active'; ?>">
            <a href="messages.php?user=<?php echo $u['id']; ?>" class="text-decoration-none<?php if($partner_id == $u['id']) echo ' text-white'; ?>">
              <?php echo htmlspecialchars($u['email']); ?>
              <?php if($u['unread_count'] > 0): ?>
                <span class="badge bg-danger ms-2"><?php echo $u['unread_count']; ?></span>
              <?php endif; ?>
            </a>
          </li>
        <?php endwhile; ?>
      </ul>
    </div>
    <div class="col-md-8">
      <?php if ($partner_id): ?>
        <div id="chatbox" class="border rounded-4 p-3 mb-3 bg-light" style="height:350px; overflow-y:auto;">
          <?php foreach($chat as $msg): ?>
            <div class="mb-2">
              <span class="fw-bold"><?php echo $msg['sender_id'] == $user_id ? 'Ju:' : 'Ata:'; ?></span>
              <span><?php echo nl2br(htmlspecialchars($msg['message'])); ?></span>
              <span class="text-muted small ms-2"><?php echo date('d.m.Y H:i', strtotime($msg['sent_at'])); ?></span>
            </div>
          <?php endforeach; ?>
        </div>
        <form method="post" class="d-flex">
          <input type="text" name="message" class="form-control me-2" placeholder="Shkruaj mesazhin..." required autocomplete="off">
          <button type="submit" class="btn btn-primary">Dërgo</button>
        </form>
        <script>
          // Auto-scroll chat to bottom
          var chatbox = document.getElementById('chatbox');
          if(chatbox) chatbox.scrollTop = chatbox.scrollHeight;
        </script>
      <?php else: ?>
        <div class="alert alert-info">Zgjidh një përdorues për të biseduar.</div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php include('inc/footer.php'); ?>