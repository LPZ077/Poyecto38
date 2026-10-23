<?php require_once 'includes/auth.php'; $u = require_login(); $active='perfil'; $titulo='Mi perfil'; $err=[];
if ($_SERVER['REQUEST_METHOD']==='POST') {
  csrf_check(); $n=trim($_POST['nombre']??''); $t=trim($_POST['telefono']??''); $x=trim($_POST['extra']??''); $pw=$_POST['password']??'';
  if (mb_strlen($n)<3) $err[]='Escribe tu nombre.';
  if ($pw!=='' && (strlen($pw)<8 || !preg_match('/\d/',$pw))) $err[]='La nueva contraseña necesita 8 caracteres y un número.';
  if (!$err) {
    db()->prepare("UPDATE usuarios SET nombre=?,telefono=?,carrera=?,empresa=? WHERE id=?")->execute([$n,$t?:null,$u['rol']==='estudiante'?$x:$u['carrera'],$u['rol']==='empleador'?$x:$u['empresa'],$u['id']]);
    if ($pw!=='') db()->prepare("UPDATE usuarios SET password=? WHERE id=?")->execute([password_hash($pw,PASSWORD_DEFAULT),$u['id']]);
    $f = db()->prepare("SELECT * FROM usuarios WHERE id=?"); $f->execute([$u['id']]); $nu=$f->fetch(); unset($nu['password']); $_SESSION['user']=$nu;
    flash('Perfil guardado.'); redirect('perfil.php');
  }
}
require 'includes/header.php'; ?>
<section class="card auth"><h1 class="h2">Mi perfil</h1>
<?php foreach ($err as $x) echo '<p class="alert error">'.e($x).'</p>'; ?>
<form method="post" class="form"><?= csrf_field() ?>
 <label>Nombre<input name="nombre" value="<?= e($u['nombre']) ?>" required></label>
 <label>Correo<input value="<?= e($u['email']) ?>" disabled></label>
 <label>Teléfono<input name="telefono" value="<?= e($u['telefono']) ?>"></label>
 <label><?= $u['rol']==='estudiante'?'Carrera / bachillerato':'Empresa' ?><input name="extra" value="<?= e($u['rol']==='estudiante'?$u['carrera']:$u['empresa']) ?>"></label>
 <label>Nueva contraseña (opcional)<input type="password" name="password" autocomplete="new-password"></label>
 <button class="btn btn-y btn-lg">Guardar cambios</button></form></section>
<?php require 'includes/footer.php'; ?>
