<?php
require_once __DIR__.'/auth.php';
$u = user(); $active = $active ?? ''; $titulo = $titulo ?? 'Chispa';
?><!DOCTYPE html>
<html lang="es"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($titulo) ?> · Chispa</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head><body>
<header class="nav">
  <a class="logo" href="index.php"><svg viewBox="0 0 24 24" width="34" height="34" aria-hidden="true"><path fill="#ffd21f" d="M12 2l2.2 6.3L21 10l-6.8 1.7L12 18l-2.2-6.3L3 10l6.8-1.7z"/></svg> Chispa</a>
  <button class="burger" aria-label="Menú" aria-expanded="false"><span></span><span></span><span></span></button>
  <nav class="menu" id="menu">
    <a href="index.php" class="<?= $active==='inicio'?'on':'' ?>">Inicio</a>
    <a href="ofertas.php" class="<?= $active==='ofertas'?'on':'' ?>">Ofertas de empleo</a>
    <a href="como-funciona.php" class="<?= $active==='como'?'on':'' ?>">Cómo funciona</a>
    <?php if ($u): ?>
      <a href="panel.php" class="<?= $active==='panel'?'on':'' ?>">Mi panel</a>
      <a href="correos.php" class="<?= $active==='correos'?'on':'' ?>">Mis correos</a>
      <a href="perfil.php" class="<?= $active==='perfil'?'on':'' ?>">Mi perfil</a>
      <a class="btn btn-y btn-sm" href="logout.php">Salir (<?= e(explode(' ',$u['nombre'])[0]) ?>)</a>
    <?php else: ?>
      <a class="btn btn-y btn-sm" href="login.php">Iniciar sesión</a>
    <?php endif; ?>
  </nav>
</header>
<main class="wrap">
<?php if ($f = flash()): ?><div class="alert <?= $f[1] ?>" role="status"><?= e($f[0]) ?></div><?php endif; ?>
