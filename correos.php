<?php require_once 'includes/auth.php'; $u = require_login(); $active='correos'; $titulo='Mis correos';
$q = db()->prepare("SELECT * FROM correos WHERE destinatario=? ORDER BY creado DESC"); $q->execute([$u['email']]); $l = $q->fetchAll();
require 'includes/header.php'; ?>
<section class="card"><h1 class="h2">Mis correos</h1><p class="muted">Aquí ves los avisos que Chispa te envía, aunque tu servidor de correo no esté configurado.</p>
<?php foreach ($l as $c): ?><div class="fila"><div class="oferta-txt"><strong><?= e($c['asunto']) ?></strong><p class="muted"><?= fecha($c['creado']) ?> · <?= $c['enviado']?'Enviado':'Guardado (sin SMTP)' ?></p></div><a class="btn btn-sm btn-y" href="correo.php?id=<?= $c['id'] ?>" target="_blank">Abrir</a></div>
<?php endforeach; if (!$l) echo '<p class="vacio">Aún no tienes correos.</p>'; ?></section>
<?php require 'includes/footer.php'; ?>
