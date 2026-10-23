<?php require_once 'includes/auth.php';
if (user()) redirect('panel.php');
$rol = ($_GET['rol'] ?? $_POST['rol'] ?? 'estudiante') === 'empleador' ? 'empleador' : 'estudiante';
$err = []; $d = ['nombre'=>'','email'=>'','extra'=>''];
if ($_SERVER['REQUEST_METHOD']==='POST') {
  csrf_check();
  $d = ['nombre'=>trim($_POST['nombre'] ?? ''),'email'=>strtolower(trim($_POST['email'] ?? '')),'extra'=>trim($_POST['extra'] ?? '')];
  $pw = $_POST['password'] ?? '';
  if (mb_strlen($d['nombre']) < 3) $err[] = 'Escribe tu nombre completo.';
  if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) $err[] = 'El correo no es válido.';
  if (strlen($pw) < 8 || !preg_match('/\d/',$pw)) $err[] = 'La contraseña debe tener 8 caracteres o más e incluir un número.';
  if ($pw !== ($_POST['password2'] ?? '')) $err[] = 'Las contraseñas no coinciden.';
  if ($rol==='empleador' && $d['extra']==='') $err[] = 'Indica el nombre de tu empresa.';
  if (!$err) {
    try {
      db()->prepare("INSERT INTO usuarios (nombre,email,password,rol,carrera,empresa) VALUES (?,?,?,?,?,?)")
        ->execute([$d['nombre'],$d['email'],password_hash($pw,PASSWORD_DEFAULT),$rol,$rol==='estudiante'?$d['extra']:null,$rol==='empleador'?$d['extra']:null]);
      flash('Cuenta creada. Ya puedes iniciar sesión.'); redirect('login.php');
    } catch (PDOException $e) { $err[] = 'Ese correo ya está registrado.'; }
  }
}
$titulo='Crear cuenta'; require 'includes/header.php'; ?>
<section class="card auth"><h1 class="h2">Crear cuenta</h1>
<div class="tabs"><a class="<?= $rol==='estudiante'?'on':'' ?>" href="?rol=estudiante">Soy estudiante</a><a class="<?= $rol==='empleador'?'on':'' ?>" href="?rol=empleador">Soy empleador</a></div>
<?php foreach ($err as $x) echo '<p class="alert error">'.e($x).'</p>'; ?>
<form method="post" class="form"><?= csrf_field() ?><input type="hidden" name="rol" value="<?= $rol ?>">
  <label>Nombre completo<input name="nombre" value="<?= e($d['nombre']) ?>" required></label>
  <label>Correo<input type="email" name="email" value="<?= e($d['email']) ?>" required></label>
  <label><?= $rol==='estudiante' ? 'Carrera / bachillerato' : 'Nombre de la empresa' ?><input name="extra" value="<?= e($d['extra']) ?>" <?= $rol==='empleador'?'required':'' ?>></label>
  <label>Contraseña<input type="password" name="password" required minlength="8" autocomplete="new-password"></label>
  <label>Repite la contraseña<input type="password" name="password2" required autocomplete="new-password"></label>
  <button class="btn btn-y btn-lg">Crear cuenta</button>
  <p class="muted">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p></form></section>
<?php require 'includes/footer.php'; ?>
