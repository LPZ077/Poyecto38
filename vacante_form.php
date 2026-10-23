<?php require_once 'includes/auth.php'; $u = require_login('empleador'); $active='panel';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0); $v = ['titulo'=>'','descripcion'=>'','modalidad'=>$MODALIDADES[0],'zona'=>$ZONAS[0],'area'=>$AREAS[0]];
if ($id) { $st = db()->prepare("SELECT * FROM vacantes WHERE id=? AND empleador_id=?"); $st->execute([$id,$u['id']]); $v = $st->fetch() ?: redirect('panel.php'); }
$err = [];
if ($_SERVER['REQUEST_METHOD']==='POST') {
  csrf_check();
  $v = ['titulo'=>trim($_POST['titulo']??''),'descripcion'=>trim($_POST['descripcion']??''),'modalidad'=>$_POST['modalidad']??'','zona'=>$_POST['zona']??'','area'=>$_POST['area']??''];
  if (mb_strlen($v['titulo'])<4) $err[]='El cargo necesita al menos 4 caracteres.';
  if (mb_strlen($v['descripcion'])<20) $err[]='Describe el puesto con al menos 20 caracteres.';
  if (!in_array($v['modalidad'],$MODALIDADES)||!in_array($v['zona'],$ZONAS)||!in_array($v['area'],$AREAS)) $err[]='Selecciona opciones válidas.';
  if (!$err) {
    $emp = $u['empresa'] ?: $u['nombre'];
    if ($id) db()->prepare("UPDATE vacantes SET titulo=?,descripcion=?,modalidad=?,zona=?,area=? WHERE id=? AND empleador_id=?")->execute([$v['titulo'],$v['descripcion'],$v['modalidad'],$v['zona'],$v['area'],$id,$u['id']]);
    else db()->prepare("INSERT INTO vacantes (empleador_id,titulo,empresa,descripcion,modalidad,zona,area) VALUES (?,?,?,?,?,?,?)")->execute([$u['id'],$v['titulo'],$emp,$v['descripcion'],$v['modalidad'],$v['zona'],$v['area']]);
    flash($id?'Vacante actualizada.':'Vacante publicada.'); redirect('panel.php');
  }
}
$titulo = $id?'Editar vacante':'Publicar vacante'; require 'includes/header.php';
function opts($l,$s){ foreach($l as $o) echo '<option'.($o===$s?' selected':'').'>'.e($o).'</option>'; } ?>
<section class="card auth wide"><h1 class="h2"><?= e($titulo) ?></h1>
<?php foreach ($err as $x) echo '<p class="alert error">'.e($x).'</p>'; ?>
<form method="post" class="form"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $id ?>">
 <label>Cargo<input name="titulo" value="<?= e($v['titulo']) ?>" required maxlength="150"></label>
 <label>Descripción<textarea name="descripcion" rows="5" required><?= e($v['descripcion']) ?></textarea></label>
 <div class="grid3"><label>Horario<select name="modalidad"><?php opts($MODALIDADES,$v['modalidad']); ?></select></label>
 <label>Zona<select name="zona"><?php opts($ZONAS,$v['zona']); ?></select></label>
 <label>Área<select name="area"><?php opts($AREAS,$v['area']); ?></select></label></div>
 <div class="row"><button class="btn btn-y btn-lg">Guardar</button><a class="btn btn-o-d btn-lg" href="panel.php">Cancelar</a></div></form></section>
<?php require 'includes/footer.php'; ?>
