<?php include('inc/header.php'); ?>

<div class="hero">
  <h1>💼 JOBS</h1>
  <p>Platforma më e mirë për punë në Kosovë 🇽🇰</p>
  <?php if (!isset($_SESSION['user_id'])): ?>
    <a href="register.php" class="btn btn-lg btn-light">Regjistrohu Tani</a>
  <?php else: ?>
    <a href="../jobs.php" class="btn btn-lg btn-light">Shfleto Punët</a>
  <?php endif; ?>
</div>

<div class="row text-center features">
  <div class="col-md-4">
    <h3>👨‍💻 Gjej Punë</h3>
    <p>Shfleto mundësitë e punës lokale dhe online.</p>
  </div>
  <div class="col-md-4">
    <h3>🎯 Shfaq Aftësitë</h3>
    <p>Krijo një profil që bie në sy për punëdhënësit.</p>
  </div>
  <div class="col-md-4">
    <h3>📚 Mëso Aftësi të Reja</h3>
    <p>Kurset më të kërkuara në treg – të gjitha në një vend.</p>
  </div>
</div>

<?php include('inc/footer.php'); ?>
