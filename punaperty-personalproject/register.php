<?php
include('inc/db.php');
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!$username || !$email || !$password) {
        $message = "Ju lutem plotësoni të gjitha fushat.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email-i nuk është i vlefshëm.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        if (!$stmt) {
            die("Gabim në përgatitjen e kërkesës: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Ky email tashmë është përdorur.";
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt->close();

            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if (!$stmt) {
                die("Gabim në përgatitjen e kërkesës për regjistrim: " . $conn->error);
            }

            $stmt->bind_param("sss", $username, $email, $hash);
            if ($stmt->execute()) {
                header('Location: login.php');
                exit;
            } else {
                $message = "Gabim gjatë regjistrimit.";
            }
        }
        $stmt->close();
    }
}
?>

<?php include 'inc/header.php'; ?>

<div class="container mt-5" style="max-width: 500px;">
  <div class="card shadow p-4 rounded-4">
    <h2 class="text-center mb-4">Regjistrohu</h2>

    <?php if ($message): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="post" action="register.php" novalidate>
      <div class="mb-3">
        <label for="username" class="form-label">Emri</label>
        <input type="text" class="form-control" id="username" name="username" required value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email-i</label>
        <input type="email" class="form-control" id="email" name="email" required value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Fjalëkalimi</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-primary rounded-pill">Regjistrohu</button>
      </div>
    </form>
  </div>
</div>

<?php include 'inc/footer.php'; ?>
