<?php include('inc/header.php'); ?>

<div class="container mt-5" style="max-width: 600px;">
  <div class="card shadow p-4 rounded-4">
    <h2 class="text-center mb-4">Job Fit Quiz</h2>

    <form method="POST" action="process-quiz.php" novalidate>
      <div class="mb-3">
        <label for="skill" class="form-label">Cila është aftësia juaj kryesore?</label>
        <select class="form-select" id="skill" name="skill" required>
          <option value="developer">Programues (Developer)</option>
          <option value="designer">Dizajner Grafik</option>
          <option value="marketing">Marketing Digital</option>
          <option value="accountant">Kontabilist</option>
          <option value="sales">Shitës</option>
          <option value="customer-support">Mbështetje për Klientë</option>
          <option value="project-manager">Menaxher Projekti</option>
          <option value="teacher">Mësues</option>
          <option value="nurse">Infermier/e</option>
          <option value="doctor">Mjek</option>
          <option value="driver">Vozitës</option>
          <option value="electrician">Elektricist</option>
          <option value="mechanic">Mekanik</option>
          <option value="waiter">Kamerier</option>
          <option value="chef">Kuzhinier</option>
          <option value="it-support">IT Support</option>
          <option value="data-analyst">Analist të Dhënash</option>
          <option value="hr">Menaxher i Burimeve Njerëzore</option>
          <option value="lawyer">Jurist</option>
          <option value="translator">Përkthyes</option>
          <option value="content-writer">Shkrues përmbajtjeje</option>
          <option value="seo">SEO Specialist</option>
          <option value="social-media">Menaxher i Rrjeteve Sociale</option>
          <option value="photographer">Fotograf</option>
          <option value="videographer">Videograf</option>
          <option value="web-designer">Web Designer</option>
          <option value="mobile-developer">Mobile Developer</option>
          <option value="network-engineer">Inxhinier i Rrjetit</option>
          <option value="civil-engineer">Inxhinier Ndërtimi</option>
          <option value="architect">Arkitekt</option>
          <option value="logistics">Logjistikë</option>
          <option value="warehouse">Punëtor Magazine</option>
          <option value="security">Sigurim Fizik</option>
          <option value="cleaner">Pastrim</option>
          <option value="receptionist">Recepsionist</option>
          <option value="barber">Berber/Parukier</option>
          <option value="dentist">Dentist</option>
          <option value="pharmacist">Farmacist</option>
          <option value="economist">Ekonomist</option>
          <option value="business-analyst">Business Analyst</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="experience" class="form-label">Sa vite përvojë keni?</label>
        <input type="number" class="form-control" id="experience" name="experience" min="0" required>
      </div>

      <div class="mb-4">
        <label class="form-label">Lloji i preferuar i punës:</label>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="type" id="remote" value="remote" checked>
          <label class="form-check-label" for="remote">
            Remote
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="type" id="on-site" value="on-site">
          <label class="form-check-label" for="on-site">
            Në zyrë
          </label>
        </div>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary rounded-pill">Gjej Punën Time</button>
      </div>
    </form>
  </div>
</div>

<?php include('inc/footer.php'); ?>
