<?php require_once 'includes/auth.php';
if (user()) redirect('panel.php');
$err = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  csrf_check();
  $_SESSION['intentos'] = $_SESSION['intentos'] ?? 0;
  if ($_SESSION['intentos'] >= 5 && time() < ($_SESSION['bloqueo'] ?? 0)) $err = 'Demasiados intentos. Espera un minuto.';
  else {
    $st = db()->prepare("SELECT * FROM usuarios WHERE email=?"); $st->execute([strtolower(trim($_POST['email'] ?? ''))]); $f = $st->fetch();
    if ($f && password_verify($_POST['password'] ?? '', $f['password'])) {
      session_regenerate_id(true); unset($f['password']); $_SESSION['user'] = $f; $_SESSION['intentos'] = 0;
      redirect('panel.php');
    }
    $_SESSION['intentos']++; if ($_SESSION['intentos'] >= 5) $_SESSION['bloqueo'] = time()+60;
    $err = 'Correo o contraseña incorrectos.';
  }
}
$titulo='Iniciar sesión'; require 'includes/header.php'; ?>
<section class="card auth"><h1 class="h2">Iniciar sesión</h1>
<?php if ($err) echo '<p class="alert error">'.e($err).'</p>'; ?>
<form method="post" class="form"><?= csrf_field() ?>
  <label>Correo<input type="email" name="email" required autocomplete="email"></label>
  <label>Contraseña<input type="password" name="password" required autocomplete="current-password"></label>
  <button class="btn btn-y btn-lg">Entrar</button>
  <p class="muted">¿No tienes cuenta? <a href="registro.php">Regístrate</a></p></form></section>
<?php require 'includes/footer.php'; ?>
