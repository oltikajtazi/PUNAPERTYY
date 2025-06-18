<?php include('inc/header.php'); ?>

<?php
$professions = [
    'Programues (Developer)',
    'Dizajner Grafik',
    'Marketing Digital',
    'Kontabilist',
    'Shitës',
    'Mbështetje për Klientë',
    'Menaxher Projekti',
    'Mësues',
    'Infermier/e',
    'Mjek',
    'Vozitës',
    'Elektricist',
    'Mekanik',
    'Kamerier',
    'Kuzhinier',
    'IT Support',
    'Analist të Dhënash',
    'Menaxher i Burimeve Njerëzore',
    'Jurist',
    'Përkthyes',
    'Shkrues përmbajtjeje',
    'SEO Specialist',
    'Menaxher i Rrjeteve Sociale',
    'Fotograf',
    'Videograf',
    'Web Designer',
    'Mobile Developer',
    'Inxhinier i Rrjetit',
    'Inxhinier Ndërtimi',
    'Arkitekt',
    'Logjistikë',
    'Punëtor Magazine',
    'Sigurim Fizik',
    'Pastrim',
    'Recepsionist',
    'Berber/Parukier',
    'Dentist',
    'Farmacist',
    'Ekonomist',
    'Business Analyst'
];

function generateModules($profession) {
    return [
        "Hyrje në $profession",
        "Aftësitë bazë për $profession",
        "Veglat dhe teknologjitë kryesore",
        "Shembuj praktikë dhe detyra",
        "Si të zhvillosh karrierën si $profession"
    ];
}

$courses = [];
foreach ($professions as $prof) {
    $courses[] = [
        'title' => $prof,
        'description' => "Kurs për të filluar karrierën si $prof.",
        'short' => "Mëso bazat dhe hapat e parë si $prof.",
        'modules' => generateModules($prof),
        'link' => '#'
    ];
}
?>

<div class="container mt-5" style="max-width: 900px;">
  <div class="text-center mb-4">
    <h2 class="fw-bold">🎓 Kurse Falas për Zhvillim Profesional</h2>
    <p class="text-muted">Zgjidh një kurs për të rritur aftësitë dhe shanset tuaja për punësim.</p>
  </div>

  <!-- Search bar -->
  <div class="mb-4">
    <input type="text" id="course-search" class="form-control form-control-lg" placeholder="Kërko profesion ose kurs...">
  </div>

  <div class="row g-4" id="courses-list">
    <?php foreach ($courses as $index => $course): ?>
      <div class="col-md-6 course-card" data-title="<?php echo strtolower($course['title']); ?>">
        <div class="card shadow-sm rounded-4 h-100">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?php echo $course['title']; ?></h5>
            <p class="card-text text-muted flex-grow-1"><?php echo $course['short']; ?></p>
            <ul class="list-group list-group-flush mb-3 modules-list" style="display: none;">
              <?php foreach ($course['modules'] as $module): ?>
                <li class="list-group-item py-1 small"><?php echo htmlspecialchars($module); ?></li>
              <?php endforeach; ?>
            </ul>
            <button type="button" class="btn btn-primary rounded-pill mt-auto toggle-modules-btn" data-target="modules-<?php echo $index; ?>">
              Shiko Kursin
            </button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Toggle modules
    document.querySelectorAll('.toggle-modules-btn').forEach(function(button) {
      button.addEventListener('click', function() {
        const cardBody = this.closest('.card-body');
        const modulesList = cardBody.querySelector('.modules-list');
        if (modulesList.style.display === 'none' || modulesList.style.display === '') {
          modulesList.style.display = 'block';
          this.textContent = 'Fshih Modulet';
        } else {
          modulesList.style.display = 'none';
          this.textContent = 'Shiko Kursin';
        }
      });
    });

    // Search functionality
    const searchInput = document.getElementById('course-search');
    searchInput.addEventListener('input', function() {
      const query = this.value.trim().toLowerCase();
      let found = false;
      document.querySelectorAll('.course-card').forEach(function(card) {
        const title = card.getAttribute('data-title');
        if (title.includes(query)) {
          card.style.display = '';
          if (query.length > 1 && !found) {
            // Hap automatikisht modulin e parë që përputhet
            const btn = card.querySelector('.toggle-modules-btn');
            const modulesList = card.querySelector('.modules-list');
            modulesList.style.display = 'block';
            btn.textContent = 'Fshih Modulet';
            found = true;
            // Shkruaj te kursi i parë që përputhet
            card.scrollIntoView({behavior: "smooth", block: "center"});
          } else {
            // Mbyll modulin nëse nuk është i pari që përputhet
            const btn = card.querySelector('.toggle-modules-btn');
            const modulesList = card.querySelector('.modules-list');
            if (!found) {
              modulesList.style.display = 'none';
              btn.textContent = 'Shiko Kursin';
            }
          }
        } else {
          card.style.display = 'none';
        }
      });
    });
  });
</script>

<?php include('inc/footer.php'); ?>
