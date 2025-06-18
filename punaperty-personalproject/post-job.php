<?php
include('inc/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $city = isset($_POST['city']) ? trim($_POST['city']) : '';
    $country_code = isset($_POST['country_code']) ? trim($_POST['country_code']) : '';
    $phone_number = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $phone = $country_code . $phone_number;
    $user_id = $_SESSION['user_id'];
    $image_path = null;

    // Handle image upload
    if (isset($_FILES['job_image']) && $_FILES['job_image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/png', 'image/jpeg', 'image/jpg'];
        $file_type = mime_content_type($_FILES['job_image']['tmp_name']);
        if (in_array($file_type, $allowed_types)) {
            $ext = pathinfo($_FILES['job_image']['name'], PATHINFO_EXTENSION);
            $new_name = uniqid('job_', true) . '.' . $ext;
            $upload_dir = __DIR__ . '/uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $destination = $upload_dir . $new_name;
            if (move_uploaded_file($_FILES['job_image']['tmp_name'], $destination)) {
                $image_path = 'uploads/' . $new_name;
            } else {
                $message = "❌ Ngarkimi i fotos dështoi.";
            }
        } else {
            $message = "❌ Lejohen vetëm formatet PNG, JPG, JPEG.";
        }
    }

    if (!$title || !$description || !$city || !$phone_number || !$country_code) {
        $message = "Ju lutem plotësoni të gjitha fushat.";
    } elseif (empty($message)) {
        $stmt = $conn->prepare("INSERT INTO jobs (user_id, title, description, city, phone, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $user_id, $title, $description, $city, $phone, $image_path);
        if ($stmt->execute()) {
            $message = "✅ Puna u postua me sukses!";

            // Notify users with job alerts
            $alert_stmt = $conn->prepare("SELECT u.email FROM job_alerts a JOIN users u ON a.user_id = u.id WHERE (? = '' OR ? LIKE CONCAT('%', a.keyword, '%')) AND (? = '' OR ? = a.city)");
            $alert_stmt->bind_param("ssss", $title, $title, $city, $city);
            $alert_stmt->execute();
            $alert_stmt->bind_result($email);
            while ($alert_stmt->fetch()) {
                // Send email (use mail() or a library)
                @mail($email, "Punë e re: $title", "Një punë e re është postuar që përputhet me interesat tuaja.", "From: noreply@punaperty.local");
            }
            $alert_stmt->close();
        } else {
            $message = "❌ Gabim gjatë postimit të punës.";
        }
        $stmt->close();
    } else {
        $message = "❌ Gabim në përgatitjen e kërkesës: " . $conn->error;
    }
}
?>

<?php include('inc/header.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">📢 Posto një Vend Pune të Ri</h2>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo strpos($message, '✅') !== false ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="post-job.php" class="mt-4 p-4 rounded-4 shadow-sm bg-light" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="title" class="form-label">Titulli i Punës</label>
                <input type="text" class="form-control" id="title" name="title" required placeholder="p.sh. Web Developer">
            </div>
            <div class="col-md-6">
                <label for="city" class="form-label">Qyteti</label>
                <input type="text" class="form-control" id="city" name="city" required placeholder="p.sh. Prishtinë">
            </div>
            <div class="col-md-6">
                <label class="form-label">Telefoni i Kontaktit</label>
                <div class="input-group">
                  <select class="form-select" name="country_code" style="max-width: 120px;" required>
                    <option value="+355">🇦🇱 +355 (Albania)</option>
                    <option value="+383" selected>🇽🇰 +383 (Kosovo)</option>
                    <option value="+389">🇲🇰 +389 (Macedonia)</option>
                    <option value="+381">🇷🇸 +381 (Serbia)</option>
                    <option value="+39">🇮🇹 +39 (Italy)</option>
                    <option value="+44">🇬🇧 +44 (UK)</option>
                    <option value="+49">🇩🇪 +49 (Germany)</option>
                    <option value="+1">🇺🇸 +1 (USA)</option>
                    <option value="+33">🇫🇷 +33 (France)</option>
                    <option value="+90">🇹🇷 +90 (Turkey)</option>
                    <option value="+30">🇬🇷 +30 (Greece)</option>
                    <option value="+34">🇪🇸 +34 (Spain)</option>
                    <option value="+7">🇷🇺 +7 (Russia)</option>
                    <option value="+86">🇨🇳 +86 (China)</option>
                    <option value="+91">🇮🇳 +91 (India)</option>
                  </select>
                  <input type="text" class="form-control" id="phone" name="phone" required placeholder="Numri pa kodin e shtetit">
                </div>
                <div class="form-text">Zgjidh shtetin dhe shkruaj numrin pa kodin e shtetit.</div>
            </div>
            <div class="col-md-6">
                <label for="job_image" class="form-label">Foto që i përshtatet punës (PNG, JPG, JPEG)</label>
                <input type="file" class="form-control" id="job_image" name="job_image" accept=".png,.jpg,.jpeg,image/png,image/jpeg">
            </div>
            <div class="col-12">
                <label for="description" class="form-label">Përshkrimi</label>
                <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Përshkruani detajet e punës..."></textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-4 w-100">Posto Punën</button>
    </form>
</div>

<?php include('inc/footer.php'); ?>
