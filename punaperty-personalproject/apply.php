<?php
session_start();
include('inc/db.php');
include 'inc/header.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get job ID and title from URL query parameters
$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;
$title = isset($_GET['title']) ? $_GET['title'] : '';

// Merr qytetin nga databaza sipas job_id
$city = '';
if ($job_id > 0) {
    $stmt_city = $conn->prepare("SELECT city FROM jobs WHERE id = ?");
    $stmt_city->bind_param("i", $job_id);
    $stmt_city->execute();
    $stmt_city->bind_result($city);
    $stmt_city->fetch();
    $stmt_city->close();
}

// Array me koordinata për qytetet kryesore të Kosovës
$city_coords = [
    'Prishtine' => ['lat' => 42.6629, 'lng' => 21.1655],
    'Prizren' => ['lat' => 42.2139, 'lng' => 20.7417],
    'Pejë' => ['lat' => 42.6591, 'lng' => 20.2883],
    'Ferizaj' => ['lat' => 42.3706, 'lng' => 21.1550],
    'Gjilan' => ['lat' => 42.4637, 'lng' => 21.4691],
    'Mitrovicë' => ['lat' => 42.8900, 'lng' => 20.8667],
    'Gjakovë' => ['lat' => 42.3803, 'lng' => 20.4308],
    'Vushtrri' => ['lat' => 42.8231, 'lng' => 20.9675],
    'Suharekë' => ['lat' => 42.3586, 'lng' => 20.8250],
    'Podujevë' => ['lat' => 42.9136, 'lng' => 21.1922],
    // shto qytete të tjera sipas nevojës
];

$lat = $lng = null;
if (!empty($city)) {
    $city = trim($city);

    // Normalizim për përputhje pa theks dhe pa ndjeshmëri ndaj shkronjave të mëdha/vogla
    function normalize($str) {
        $str = strtolower($str);
        $str = str_replace(
            ['ë','ç','ä','â','å','ã','é','è','ê','í','ì','ï','î','ó','ò','ö','ô','õ','ú','ù','ü','û','ý','ÿ'],
            ['e','c','a','a','a','a','e','e','e','i','i','i','i','o','o','o','o','o','u','u','u','u','y','y'],
            $str
        );
        return $str;
    }

    foreach ($city_coords as $key => $coords) {
        if (normalize($key) === normalize($city)) {
            $lat = $coords['lat'];
            $lng = $coords['lng'];
            $city = $key; // për popup
            break;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $cover_letter = $_POST['cover_letter'];
    $user_id = $_SESSION['user_id'];

    // These come from hidden form inputs
    $job_title = $_POST['job_title'];
    $job_id_post = intval($_POST['job_id']); // use intval to be sure

    // Prepare insert statement including job_id
    $stmt = $conn->prepare("INSERT INTO applications (user_id, job_id, job_title, full_name, email, phone, cover_letter) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssss", $user_id, $job_id_post, $job_title, $full_name, $email, $phone, $cover_letter);

    if ($stmt->execute()) {
        $success = "Aplikimi u dërgua me sukses!";
    } else {
        $error = "Gabim gjatë aplikimit: " . $stmt->error;
    }

    $stmt->close();
}
?>

<div class="container mt-5" style="max-width: 700px;">
  <h2 class="mb-4">Apliko për: <?php echo htmlspecialchars($title); ?></h2>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
  <?php elseif (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
  <?php endif; ?>

  <?php
    // Merr numrin kontaktues nga databaza për këtë punë
    $contact_phone = '';
    if ($job_id > 0) {
        $stmt_phone = $conn->prepare("SELECT phone FROM jobs WHERE id = ?");
        $stmt_phone->bind_param("i", $job_id);
        $stmt_phone->execute();
        $stmt_phone->bind_result($contact_phone);
        $stmt_phone->fetch();
        $stmt_phone->close();
    }
  ?>

  <div class="card shadow-sm rounded-4 mb-4">
    <div class="card-body">
      <h5 class="card-title mb-3">Detajet e Punës</h5>
      <div class="row g-3">
        <div class="col-md-6">
          <span class="fw-semibold">Qyteti:</span>
          <span class="text-muted"><?php echo htmlspecialchars($city); ?></span>
        </div>
        <div class="col-md-6">
          <span class="fw-semibold">Numri kontaktues:</span>
          <span class="text-muted"><?php echo $contact_phone ? htmlspecialchars($contact_phone) : 'N/A'; ?></span>
        </div>
      </div>
    </div>
  </div>

  <form method="post" action="">
    <!-- Hidden inputs for job ID and title -->
    <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job_id); ?>">
    <input type="hidden" name="job_title" value="<?php echo htmlspecialchars($title); ?>">

    <div class="mb-3">
      <label for="full_name" class="form-label">Emri i plotë</label>
      <input type="text" class="form-control" name="full_name" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email-i</label>
      <input type="email" class="form-control" name="email" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Telefoni</label>
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
          <option value="+355">🇦🇱 +355 (Albania)</option>
          <option value="+30">🇬🇷 +30 (Greece)</option>
          <option value="+34">🇪🇸 +34 (Spain)</option>
          <option value="+7">🇷🇺 +7 (Russia)</option>
          <option value="+86">🇨🇳 +86 (China)</option>
          <option value="+91">🇮🇳 +91 (India)</option>
          <!-- Shto shtete të tjera sipas nevojës -->
        </select>
        <input type="text" class="form-control" name="phone" required placeholder="Numri pa kodin e shtetit">
      </div>
      <div class="form-text">Zgjidh shtetin dhe shkruaj numrin pa kodin e shtetit.</div>
    </div>

    <div class="mb-3">
      <label for="cover_letter" class="form-label">Letër motivuese</label>
      <textarea class="form-control" name="cover_letter" rows="5"></textarea>
    </div>

    <button type="submit" class="btn btn-success w-100">Dërgo Aplikimin</button>
  </form>
</div>

<?php if ($lat && $lng): ?>
<div class="container mt-4 mb-5">
  <h5>Lokacioni i punës: <?php echo htmlspecialchars($city); ?></h5>
  <iframe
    width="100%"
    height="350"
    style="border-radius: 12px; border:0;"
    loading="lazy"
    allowfullscreen
    referrerpolicy="no-referrer-when-downgrade"
    src="https://www.openstreetmap.org/export/embed.html?bbox=<?php echo $lng-0.05; ?>%2C<?php echo $lat-0.03; ?>%2C<?php echo $lng+0.05; ?>%2C<?php echo $lat+0.03; ?>&layer=mapnik&marker=<?php echo $lat; ?>%2C<?php echo $lng; ?>">
  </iframe>
  <div class="small mt-2">
    <a href="https://www.openstreetmap.org/?mlat=<?php echo $lat; ?>&mlon=<?php echo $lng; ?>#map=12/<?php echo $lat; ?>/<?php echo $lng; ?>" target="_blank">
      Shiko në hartë të madhe
    </a>
  </div>
</div>
<?php endif; ?>

<?php include 'inc/footer.php'; ?>
