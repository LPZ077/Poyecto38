<?php require_once 'includes/auth.php'; require_once 'includes/mail.php'; $u = require_login('empleador'); $active='panel';
$vid = (int)($_GET['vacante'] ?? 0);
$st = db()->prepare("SELECT * FROM vacantes WHERE id=? AND empleador_id=?"); $st->execute([$vid,$u['id']]); $v = $st->fetch() ?: redirect('panel.php');
if ($_SERVER['REQUEST_METHOD']==='POST') {
  csrf_check(); $pid=(int)$_POST['pid']; $est=$_POST['estado']??'';
  if (array_key_exists($est,$ESTADOS)) {
    $q = db()->prepare("SELECT p.*, u.nombre est_nombre, u.email est_email, v.titulo, v.empresa, v.modalidad, v.zona FROM postulaciones p JOIN vacantes v ON v.id=p.vacante_id JOIN usuarios u ON u.id=p.estudiante_id WHERE p.id=? AND v.empleador_id=?");
    $q->execute([$pid,$u['id']]); if ($p = $q->fetch()) {
      db()->prepare("UPDATE postulaciones SET estado=? WHERE id=?")->execute([$est,$pid]);
      if ($est==='aceptada' && $p['estado']!=='aceptada') {
        [$as,$html] = correo_aceptado($p); $cid = enviar_correo($p['est_email'],$as,$html);
        flash('Postulación aceptada. Se envió un correo a '.$p['est_email'].' (ver en "Mis correos" del estudiante: #'.$cid.').');
      } else flash('Estado actualizado.');
    }
  }
  redirect('postulaciones.php?vacante='.$vid);
}
$l = db()->prepare("SELECT p.*, u.nombre, u.email, u.telefono, u.carrera FROM postulaciones p JOIN usuarios u ON u.id=p.estudiante_id WHERE p.vacante_id=? ORDER BY p.creado DESC"); $l->execute([$vid]); $lista=$l->fetchAll();
$titulo='Postulaciones'; require 'includes/header.php'; ?>
<section class="card"><div class="card-h"><div><h1 class="h2"><?= e($v['titulo']) ?></h1><p class="muted"><?= count($lista) ?> postulación(es)</p></div><a class="btn btn-o-d" href="panel.php">Volver</a></div>
<?php foreach ($lista as $p): ?><div class="fila col"><div class="fila"><div class="av"><?= e(mb_substr($p['nombre'],0,1)) ?></div>
 <div class="oferta-txt"><strong><?= e($p['nombre']) ?></strong><p class="muted"><?= e($p['email']) ?><?= $p['telefono']?' · '.e($p['telefono']):'' ?><?= $p['carrera']?' · '.e($p['carrera']):'' ?></p></div><span class="tag e-<?= $p['estado'] ?>"><?= e($ESTADOS[$p['estado']]) ?></span></div>
 <?php if ($p['mensaje']) echo '<p class="texto">“'.e($p['mensaje']).'”</p>'; ?>
 <form method="post" class="acc"><?= csrf_field() ?><input type="hidden" name="pid" value="<?= $p['id'] ?>">
  <button class="btn btn-sm btn-o-d" name="estado" value="en_revision">Marcar en revisión</button>
  <button class="btn btn-sm btn-y" name="estado" value="aceptada">Aceptar y avisar por correo</button>
  <button class="btn btn-sm btn-red" name="estado" value="rechazada">Rechazar</button>
  <a class="btn btn-sm btn-o-d" href="correo.php?preview=<?= $p['id'] ?>" target="_blank">Vista previa del correo</a></form></div>
<?php endforeach; if (!$lista) echo '<p class="vacio">Aún nadie se postula a esta vacante.</p>'; ?></section>
<?php require 'includes/footer.php'; ?>
