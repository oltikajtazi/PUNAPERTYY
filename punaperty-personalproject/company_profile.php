<?php
include('inc/db.php');
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $logo_path = null;

    // Handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/png', 'image/jpeg', 'image/jpg'];
        $file_type = mime_content_type($_FILES['logo']['tmp_name']);
        if (in_array($file_type, $allowed_types)) {
            $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $new_name = uniqid('logo_', true) . '.' . $ext;
            $upload_dir = __DIR__ . '/uploads/';
            if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }
            $destination = $upload_dir . $new_name;
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $destination)) {
                $logo_path = 'uploads/' . $new_name;
            }
        }
    }

    // Check if company exists
    $stmt = $conn->prepare("SELECT id FROM companies WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update
        $stmt->close();
        if ($logo_path) {
            $stmt = $conn->prepare("UPDATE companies SET name=?, description=?, logo=? WHERE user_id=?");
            $stmt->bind_param("sssi", $name, $description, $logo_path, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE companies SET name=?, description=? WHERE user_id=?");
            $stmt->bind_param("ssi", $name, $description, $user_id);
        }
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO companies (user_id, name, description, logo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $name, $description, $logo_path);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: company_profile.php?success=1");
    exit;
}

// Fetch company info
$stmt = $conn->prepare("SELECT * FROM companies WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$company = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch all jobs for this user
$stmt = $conn->prepare("SELECT id, title, city FROM jobs WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$jobs = $stmt->get_result();
$stmt->close();

include('inc/header.php');
?>
<div class="container mt-5" style="max-width: 800px;">
  <h2 class="mb-4">Profili i Kompanisë</h2>
  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Profili u ruajt me sukses!</div>
  <?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="mb-4 p-4 rounded-4 shadow-sm bg-light">
    <div class="mb-3">
      <label class="form-label">Emri i Kompanisë</label>
      <input type="text" class="form-control" name="name" required value="<?php echo isset($company['name']) ? htmlspecialchars($company['name']) : ''; ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Përshkrimi</label>
      <textarea class="form-control" name="description" rows="4"><?php echo isset($company['description']) ? htmlspecialchars($company['description']) : ''; ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Logo (PNG, JPG, JPEG)</label>
      <input type="file" class="form-control" name="logo" accept=".png,.jpg,.jpeg,image/png,image/jpeg">
      <?php if (!empty($company['logo'])): ?>
        <img src="<?php echo htmlspecialchars($company['logo']); ?>" alt="Logo" style="max-width:120px;max-height:120px;margin-top:10px;">
      <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary">Ruaj Profilin</button>
  </form>

  <h4 class="mb-3">Punët e Postuara nga Kompania</h4>
  <?php if ($jobs->num_rows > 0): ?>
    <ul class="list-group">
      <?php while($job = $jobs->fetch_assoc()): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span>
            <strong><?php echo htmlspecialchars($job['title']); ?></strong>
            <span class="text-muted ms-2"><?php echo htmlspecialchars($job['city']); ?></span>
          </span>
          <a href="jobs.php?id=<?php echo $job['id']; ?>" class="btn btn-outline-primary btn-sm">Shiko</a>
        </li>
      <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <div class="alert alert-info">Nuk keni postuar ende asnjë punë.</div>
  <?php endif; ?>
</div>
<?php include('inc/footer.php'); ?>