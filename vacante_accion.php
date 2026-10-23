<?php require_once 'includes/auth.php'; $u = require_login('empleador');
if ($_SERVER['REQUEST_METHOD']!=='POST') redirect('panel.php');
csrf_check(); $id=(int)$_POST['id']; $a=$_POST['accion']??'';
if ($a==='eliminar') db()->prepare("DELETE FROM vacantes WHERE id=? AND empleador_id=?")->execute([$id,$u['id']]);
elseif (in_array($a,['cerrar','abrir'])) db()->prepare("UPDATE vacantes SET estado=? WHERE id=? AND empleador_id=?")->execute([$a==='cerrar'?'cerrada':'activa',$id,$u['id']]);
flash('Vacante actualizada.'); redirect('panel.php');
