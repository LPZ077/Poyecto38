<?php $active='ofertas'; require 'includes/header.php';
$st = db()->prepare("SELECT * FROM vacantes WHERE id=?"); $st->execute([(int)($_GET['id'] ?? 0)]); $v = $st->fetch();
if (!$v) { http_response_code(404); exit('<main class="wrap"><p class="vacio">Esa vacante no existe.</p></main>'); }
$ya = null;
if ($u && $u['rol']==='estudiante') {
  if ($_SERVER['REQUEST_METHOD']==='POST') {
    csrf_check();
    if ($v['estado']!=='activa') flash('Esta vacante ya está cerrada.','error');
    else try {
      db()->prepare("INSERT INTO postulaciones (vacante_id,estudiante_id,mensaje) VALUES (?,?,?)")->execute([$v['id'],$u['id'],mb_substr(trim($_POST['mensaje'] ?? ''),0,1000)]);
      flash('¡Postulación enviada! Te avisaremos por correo si te aceptan.');
    } catch (PDOException $e) { flash('Ya te postulaste a esta vacante.','error'); }
    redirect('oferta.php?id='.$v['id']);
  }
  $c = db()->prepare("SELECT estado FROM postulaciones WHERE vacante_id=? AND estudiante_id=?"); $c->execute([$v['id'],$u['id']]); $ya = $c->fetchColumn();
}
$titulo = $v['titulo'];
?>
<section class="card detalle">
  <div class="oferta big"><div class="ico <?= tono($v['area']) ?>"><?= icono($v['area']) ?></div>
    <div class="oferta-txt"><h1 class="h2"><?= e($v['titulo']) ?></h1><p class="muted"><?= e($v['empresa']) ?></p>
    <p class="punto"><?= e($v['modalidad']) ?> · <?= e($v['zona']) ?> · <?= e($v['area']) ?></p></div></div>
  <h3>Descripción</h3><p class="texto"><?= nl2br(e($v['descripcion'])) ?></p>
  <p class="muted">Publicada el <?= fecha($v['creado']) ?></p>
  <?php if ($ya): ?><p class="alert ok">Ya te postulaste. Estado: <strong><?= e($ESTADOS[$ya]) ?></strong></p>
  <?php elseif ($u && $u['rol']==='estudiante' && $v['estado']==='activa'): ?>
    <form method="post" class="form"><?= csrf_field() ?>
      <label>Mensaje para la empresa (opcional)<textarea name="mensaje" rows="3" maxlength="1000" placeholder="Cuéntales por qué te interesa este puesto"></textarea></label>
      <button class="btn btn-y btn-lg">Postularme</button></form>
  <?php elseif (!$u): ?><a class="btn btn-y btn-lg" href="login.php">Inicia sesión para postularte</a>
  <?php elseif ($v['estado']!=='activa'): ?><p class="alert error">Esta vacante está cerrada.</p><?php endif; ?>
</section>
<?php require 'includes/footer.php'; ?>
