<?php require_once 'includes/auth.php'; require_once 'includes/mail.php'; $u = require_login();
if (isset($_GET['preview']) && $u['rol']==='empleador') {
  $q = db()->prepare("SELECT p.*, u.nombre est_nombre, v.titulo, v.empresa, v.modalidad, v.zona FROM postulaciones p JOIN vacantes v ON v.id=p.vacante_id JOIN usuarios u ON u.id=p.estudiante_id WHERE p.id=? AND v.empleador_id=?");
  $q->execute([(int)$_GET['preview'],$u['id']]); $p = $q->fetch() ?: redirect('panel.php'); [, $html] = correo_aceptado($p); exit($html);
}
$q = db()->prepare("SELECT * FROM correos WHERE id=? AND destinatario=?"); $q->execute([(int)($_GET['id']??0),$u['email']]);
$c = $q->fetch() ?: redirect('correos.php'); exit($c['cuerpo']);
