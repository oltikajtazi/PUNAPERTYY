<?php
session_start();
include('inc/db.php');
include('inc/header.php');

$trending_jobs = [
    ['title' => 'Inxhinier i Inteligjencës Artificiale', 'description' => 'Zhvillon dhe implementon sisteme AI për automatizim dhe analitikë.'],
    ['title' => 'Analist i Sigurisë Kibernetike', 'description' => 'Mbron sistemet dhe të dhënat nga kërcënimet kibernetike.'],
    ['title' => 'Konsulent i Energjisë së Rinovueshme', 'description' => 'Konsulton për implementimin e burimeve të energjisë së pastër.'],
    ['title' => 'Krijues i Përmbajtjes Digjitale', 'description' => 'Krijon përmbajtje për platformat digjitale dhe mediat sociale.'],
    ['title' => 'Specialist i Shërbimeve Shëndetësore Virtuale', 'description' => 'Ofron konsulta mjekësore përmes platformave online.']
];

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search !== '') {
    $search_param = '%' . $search . '%';
    $stmt = $conn->prepare("SELECT id, title, city, created_at, image FROM jobs WHERE title LIKE ? OR city LIKE ? ORDER BY created_at DESC LIMIT 20");
    $stmt->bind_param("ss", $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $sql = "SELECT id, title, city, created_at, image FROM jobs ORDER BY created_at DESC LIMIT 5";
    $result = $conn->query($sql);
}
?>

<div class="container mt-5" style="max-width: 900px;">

  <div class="mb-4">
    <form method="get" class="d-flex">
      <input type="text" name="search" class="form-control me-2" placeholder="Kërko punë ose kompani..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
      <button type="submit" class="btn btn-primary">Kërko</button>
    </form>
  </div>

  <div class="mb-5">
    <h2 class="mb-4 text-center">🚀 Punët më të kërkuara për 2025</h2>
    <div class="row g-4">
      <?php foreach ($trending_jobs as $job): ?>
        <div class="col-md-6">
          <div class="card shadow-sm rounded-4 h-100">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?php echo htmlspecialchars($job['title']); ?></h5>
              <p class="card-text text-muted flex-grow-1"><?php echo htmlspecialchars($job['description']); ?></p>

              <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Trending jobs don't have job_id -->
                <a href="apply.php?title=<?php echo urlencode($job['title']); ?>" class="btn btn-success rounded-pill mt-3 align-self-start">Apliko</a>
              <?php else: ?>
                <a href="login.php" class="btn btn-outline-secondary rounded-pill mt-3 align-self-start">Kyçu për të aplikuar</a>
              <?php endif; ?>

            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div>
    <h2 class="mb-4 text-center">🧑‍💼 Punët e postuara nga përdoruesit</h2>
    <?php if ($result->num_rows > 0): ?>
      <div class="list-group shadow rounded-4">
        <?php while($row = $result->fetch_assoc()): ?>
          <div class="list-group-item p-3 d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1"><?php echo htmlspecialchars($row['title']); ?></h5>
              <?php if (!empty($row['image'])): ?>
                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Foto e punës" class="mb-2" style="max-width:180px;max-height:120px;display:block;border-radius:8px;">
              <?php endif; ?>
              <p class="mb-1 text-muted"><?php echo htmlspecialchars($row['city']); ?></p>
              <small class="text-secondary">Postuar më <?php echo date('d.m.Y', strtotime($row['created_at'])); ?></small>
            </div>

            <div class="d-flex align-items-center">
              <?php if (isset($_SESSION['user_id'])): ?>
                <!-- ✅ Pass both job_id and title to apply.php -->
                <a href="apply.php?job_id=<?php echo $row['id']; ?>&title=<?php echo urlencode($row['title']); ?>" class="btn btn-success rounded-pill me-2">Apliko</a>
              <?php else: ?>
                <a href="login.php" class="btn btn-outline-secondary rounded-pill me-2">Kyçu për të aplikuar</a>
              <?php endif; ?>

              <?php if (isset($_SESSION['user_id'])): ?>
                <form method="post" action="bookmark_job.php" class="d-inline">
                  <input type="hidden" name="job_id" value="<?php echo $row['id']; ?>">
                  <button type="submit" class="btn btn-outline-warning btn-sm">⭐ Ruaj</button>
                </form>
              <?php endif; ?>
            </div>

          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <div class="alert alert-warning">Nuk ka punë të postuara ende.</div>
    <?php endif; ?>
  </div>

</div>

<?php include('inc/footer.php'); ?>
