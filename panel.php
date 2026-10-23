<?php require_once 'includes/auth.php'; $u = require_login(); $active='panel'; $titulo='Mi panel';
if ($_SERVER['REQUEST_METHOD']==='POST' && $u['rol']==='estudiante') { // retirar postulación
  csrf_check();
  db()->prepare("DELETE FROM postulaciones WHERE id=? AND estudiante_id=? AND estado IN ('nueva','en_revision')")->execute([(int)$_POST['id'],$u['id']]);
  flash('Postulación retirada.'); redirect('panel.php');
}
require 'includes/header.php';
if ($u['rol']==='empleador'):
  $st = db()->prepare("SELECT v.*, (SELECT COUNT(*) FROM postulaciones p WHERE p.vacante_id=v.id) n FROM vacantes v WHERE empleador_id=? ORDER BY creado DESC"); $st->execute([$u['id']]); $vac = $st->fetchAll();
  $c = db()->prepare("SELECT COUNT(*) t, SUM(p.estado='aceptada') a, SUM(p.estado='nueva') nu FROM postulaciones p JOIN vacantes v ON v.id=p.vacante_id WHERE v.empleador_id=?"); $c->execute([$u['id']]); $k = $c->fetch();
  $r = db()->prepare("SELECT p.*, u.nombre, v.titulo FROM postulaciones p JOIN vacantes v ON v.id=p.vacante_id JOIN usuarios u ON u.id=p.estudiante_id WHERE v.empleador_id=? ORDER BY p.creado DESC LIMIT 5"); $r->execute([$u['id']]); $rec = $r->fetchAll();
?>
<section class="card"><div class="card-h"><div><h1 class="h2">Conecta con talento joven</h1><p class="muted">Publica vacantes, recibe postulaciones y encuentra el talento que tu empresa necesita.</p></div>
<a class="btn btn-y" href="vacante_form.php">Publicar vacante</a></div>
<div class="stats big"><div><b><?= count(array_filter($vac, fn($x)=>$x['estado']==='activa')) ?></b> vacantes activas</div><div><b><?= (int)$k['t'] ?></b> postulaciones recibidas</div><div><b><?= (int)$k['nu'] ?></b> sin revisar</div><div><b><?= (int)$k['a'] ?></b> aceptadas</div></div>
<div class="grid2">
 <div><h3>Mis vacantes</h3>
 <?php foreach ($vac as $v): ?><div class="fila"><div class="ico <?= tono($v['area']) ?>"><?= icono($v['area']) ?></div>
  <div class="oferta-txt"><strong><?= e($v['titulo']) ?></strong><p class="muted"><?= (int)$v['n'] ?> postulaciones · <?= $v['estado'] ?></p></div>
  <div class="acc"><a class="btn btn-sm btn-o-d" href="vacante_form.php?id=<?= $v['id'] ?>">Editar</a><a class="btn btn-sm btn-o-d" href="postulaciones.php?vacante=<?= $v['id'] ?>">Ver postulaciones</a>
  <form method="post" action="vacante_accion.php"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $v['id'] ?>">
   <button class="btn btn-sm btn-o-d" name="accion" value="<?= $v['estado']==='activa'?'cerrar':'abrir' ?>"><?= $v['estado']==='activa'?'Cerrar':'Reabrir' ?></button>
   <button class="btn btn-sm btn-red" name="accion" value="eliminar" data-confirm="¿Eliminar esta vacante y sus postulaciones?">Eliminar</button></form></div></div>
 <?php endforeach; if (!$vac) echo '<p class="vacio">Aún no publicas vacantes.</p>'; ?></div>
 <div><h3>Postulaciones recientes</h3>
 <?php foreach ($rec as $p): ?><div class="fila"><div class="av"><?= e(mb_substr($p['nombre'],0,1)) ?></div><div class="oferta-txt"><strong><?= e($p['nombre']) ?></strong><p class="muted"><?= e($p['titulo']) ?></p></div><span class="tag e-<?= $p['estado'] ?>"><?= e($ESTADOS[$p['estado']]) ?></span></div>
 <?php endforeach; if (!$rec) echo '<p class="vacio">Todavía no hay postulaciones.</p>'; ?></div>
</div></section>
<?php else:
  $st = db()->prepare("SELECT p.*, v.titulo, v.empresa, v.area FROM postulaciones p JOIN vacantes v ON v.id=p.vacante_id WHERE p.estudiante_id=? ORDER BY p.creado DESC"); $st->execute([$u['id']]); $mis = $st->fetchAll();
  $campos = array_filter([$u['nombre'],$u['telefono'],$u['carrera']]); $pct = (int)round((count($campos)/3)*100);
  $rec = db()->query("SELECT * FROM vacantes WHERE estado='activa' ORDER BY creado DESC LIMIT 3")->fetchAll();
?>
<section class="card"><div class="card-h"><div><h1 class="h2">Hola, <?= e(explode(' ',$u['nombre'])[0]) ?></h1><p class="muted">Encuentra oportunidades para ti.</p></div><a class="btn btn-y" href="ofertas.php">Buscar ofertas</a></div>
<div class="stats big"><div><b><?= count($mis) ?></b> postulaciones</div><div><b><?= count(array_filter($mis, fn($x)=>$x['estado']==='en_revision')) ?></b> en revisión</div><div><b><?= count(array_filter($mis, fn($x)=>$x['estado']==='aceptada')) ?></b> aceptadas</div><div><b><?= $pct ?>%</b> perfil completo <a href="perfil.php">Completar</a></div></div>
<div class="grid2"><div><h3>Mis postulaciones</h3>
<?php foreach ($mis as $p): ?><div class="fila"><div class="ico <?= tono($p['area']) ?>"><?= icono($p['area']) ?></div><div class="oferta-txt"><strong><?= e($p['titulo']) ?></strong><p class="muted"><?= e($p['empresa']) ?> · <?= fecha($p['creado']) ?></p></div><span class="tag e-<?= $p['estado'] ?>"><?= e($ESTADOS[$p['estado']]) ?></span>
 <?php if (in_array($p['estado'],['nueva','en_revision'])): ?><form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $p['id'] ?>"><button class="btn btn-sm btn-red" data-confirm="¿Retirar esta postulación?">Retirar</button></form><?php endif; ?></div>
<?php endforeach; if (!$mis) echo '<p class="vacio">Aún no te postulas. Explora las ofertas.</p>'; ?></div>
<div><h3>Ofertas destacadas</h3><?php foreach ($rec as $v) echo card_vacante($v); ?></div></div></section>
<?php endif; require 'includes/footer.php'; ?>
