<?php $active='inicio'; $titulo='Inicio'; require 'includes/header.php';
$ofertas = db()->query("SELECT * FROM vacantes WHERE estado='activa' ORDER BY creado DESC LIMIT 3")->fetchAll();
$s = db()->query("SELECT (SELECT COUNT(*) FROM vacantes WHERE estado='activa') v,(SELECT COUNT(*) FROM usuarios WHERE rol='empleador') e,(SELECT COUNT(*) FROM usuarios WHERE rol='estudiante') s")->fetch();
?>
<section class="hero">
  <div class="hero-t">
    <span class="pill">Empleo y vinculación laboral juvenil</span>
    <h1>Encuentra tu primera oportunidad</h1>
    <p class="lead">Chispa conecta a estudiantes del INFRAMEN con oportunidades laborales y empresas locales.</p>
    <div class="row">
      <a class="btn btn-y btn-lg" href="registro.php?rol=estudiante">Soy estudiante</a>
      <a class="btn btn-o btn-lg" href="registro.php?rol=empleador">Soy empleador</a>
    </div>
  </div>
  <img class="hero-i" src="assets/img/hero.svg" alt="Estudiantes con libreta y laptop">
</section>

<section class="card destacadas">
  <div class="card-h"><h2>Ofertas destacadas</h2>
    <form class="filtros" action="ofertas.php" method="get">
      <select name="zona" aria-label="Zona"><option value="">Zona</option><?php foreach($ZONAS as $z) echo '<option>'.e($z).'</option>'; ?></select>
      <select name="modalidad" aria-label="Horario"><option value="">Horario</option><?php foreach($MODALIDADES as $m) echo '<option>'.e($m).'</option>'; ?></select>
      <select name="area" aria-label="Área"><option value="">Área</option><?php foreach($AREAS as $a) echo '<option>'.e($a).'</option>'; ?></select>
    </form>
  </div>
  <div class="grid3">
    <?php foreach ($ofertas as $v) echo card_vacante($v); ?>
    <?php if (!$ofertas) echo '<p class="muted">Aún no hay vacantes publicadas. ¡Sé la primera empresa!</p>'; ?>
  </div>
  <div class="beneficios">
    <div><span class="b-ico"><?= icono('Soporte técnico') ?></span><p><strong>Perfiles seguros</strong>Verificamos estudiantes y empresas para tu confianza.</p></div>
    <div><span class="b-ico"><?= icono('Contenido y diseño') ?></span><p><strong>Postulación sencilla</strong>Postúlate en pocos pasos y da el primer paso hacia tu futuro.</p></div>
    <div><span class="b-ico"><?= icono('Otra') ?></span><p><strong>Oportunidades locales</strong>Conectamos talento joven con empresas de tu comunidad.</p></div>
  </div>
  <div class="stats"><div><b><?= (int)$s['v'] ?></b> vacantes activas</div><div><b><?= (int)$s['e'] ?></b> empresas</div><div><b><?= (int)$s['s'] ?></b> estudiantes</div></div>
</section>
<?php require 'includes/footer.php'; ?>
