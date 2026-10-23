<?php // Ejecuta UNA vez: http://localhost/chispa/instalar.php  y luego borra este archivo.
require_once 'includes/db.php';
$n = (int)db()->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
if ($n > 0) exit('Ya hay datos. Borra instalar.php.');
$h = password_hash('Chispa2026', PASSWORD_DEFAULT);
$i = db()->prepare("INSERT INTO usuarios (nombre,email,password,rol,empresa,carrera) VALUES (?,?,?,?,?,?)");
$i->execute(['Café Central','empresa@chispa.test',$h,'empleador','Café Central',null]);
$i->execute(['TecnoSoluciones','tecno@chispa.test',$h,'empleador','TecnoSoluciones',null]);
$i->execute(['María Fernández','estudiante@chispa.test',$h,'estudiante',null,'Bachillerato en Contaduría']);
$v = db()->prepare("INSERT INTO vacantes (empleador_id,titulo,empresa,descripcion,modalidad,zona,area) VALUES (?,?,?,?,?,?,?)");
$v->execute([1,'Asistente de ventas','Café Central','Atención al cliente, caja y apoyo en mostrador. Ideal para estudiantes con horario de tarde.','Medio tiempo','San Salvador','Ventas']);
$v->execute([2,'Soporte técnico junior','TecnoSoluciones','Apoyo en instalación de equipos y atención de tickets. Se capacita al ingresar.','Horario flexible','San Salvador','Soporte técnico']);
$v->execute([1,'Diseñador de contenido','Café Central','Diseño de publicaciones para redes sociales del negocio.','Trabajo temporal','Mejicanos','Contenido y diseño']);
echo 'Listo. Usuarios demo (clave Chispa2026): empresa@chispa.test, tecno@chispa.test, estudiante@chispa.test. <b>Borra instalar.php.</b>';
