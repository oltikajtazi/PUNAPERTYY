<?php
include('inc/db.php');
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!$email || !$password) {
        $message = "Ju lutem plotësoni të gjitha fushat.";
    } else {
        $stmt = $conn->prepare("SELECT id, password, username FROM users WHERE email = ?");
        if (!$stmt) {
            die("Gabim në përgatitjen e kërkesës: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $hashed_password, $username);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                header('Location: dashboard.php');
                exit;
            } else {
                $message = "Fjalëkalimi është gabim.";
            }
        } else {
            $message = "Email-i nuk është i regjistruar.";
        }
        $stmt->close();
    }
}
?>

<?php include 'inc/header.php'; ?>

<div class="container mt-5" style="max-width: 500px;">
  <div class="card shadow p-4 rounded-4">
    <h2 class="text-center mb-4">Hyr</h2>

    <?php if ($message): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="post" action="login.php" novalidate>
      <div class="mb-3">
        <label for="email" class="form-label">Email-i</label>
        <input type="email" class="form-control" id="email" name="email" required value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Fjalëkalimi</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-primary rounded-pill">Hyr</button>
      </div>
    </form>
  </div>
</div>

<h1>Kjo është faqja kryesore</h1>
<p>Përmbajtja shfaqet këtu, në të djathtë të sidebar-it.</p>

<?php include 'inc/footer.php'; ?>
