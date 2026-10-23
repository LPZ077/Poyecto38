<?php
require_once __DIR__.'/auth.php';

function plantilla_correo(string $titulo, string $nombre, string $texto, string $boton = '', string $url = ''): string {
  $b = $boton ? '<p style="margin:26px 0"><a href="'.e($url).'" style="background:#ffd21f;color:#13296b;font-weight:800;text-decoration:none;padding:13px 26px;border-radius:12px;display:inline-block">'.e($boton).'</a></p>' : '';
  return '<div style="background:#eaf2fd;padding:28px;font-family:Arial,Helvetica,sans-serif">'
   .'<div style="max-width:560px;margin:auto;background:#fff;border-radius:18px;overflow:hidden">'
   .'<div style="background:#13296b;padding:18px 26px;color:#fff;font-size:22px;font-weight:800">✦ Chispa</div>'
   .'<div style="padding:30px 26px;color:#13296b"><h2 style="margin:0 0 14px;font-size:24px">'.e($titulo).'</h2>'
   .'<p style="font-size:16px;line-height:1.55">Hola '.e($nombre).',</p>'
   .'<p style="font-size:16px;line-height:1.55;color:#3b4a78">'.$texto.'</p>'.$b.'</div>'
   .'<div style="background:#13296b;color:#cfd8f5;padding:16px 26px;font-size:13px">Instituto Nacional General Francisco Menéndez · Formamos hoy el futuro de El Salvador.</div>'
   .'</div></div>';
}

function correo_aceptado(array $p): array {
  $texto = '¡Felicidades! <strong>'.e($p['empresa']).'</strong> aceptó tu postulación para el puesto de <strong>'.e($p['titulo']).'</strong> ('.e($p['modalidad']).', '.e($p['zona']).').'
   .'<br><br>La empresa se pondrá en contacto contigo pronto. Mantén tu perfil actualizado y revisa tu correo.';
  return ['¡Fuiste aceptado/a en '.$p['titulo'].'! — Chispa', plantilla_correo('¡Tu postulación fue aceptada!', $p['est_nombre'], $texto, 'Ver mis postulaciones', 'http://localhost/chispa/panel.php')];
}

function enviar_correo(string $to, string $asunto, string $html): int {
  $headers = "MIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8\r\nFrom: Chispa <".MAIL_FROM.">\r\n";
  $ok = @mail($to, '=?UTF-8?B?'.base64_encode($asunto).'?=', $html, $headers) ? 1 : 0;
  $st = db()->prepare('INSERT INTO correos (destinatario,asunto,cuerpo,enviado) VALUES (?,?,?,?)');
  $st->execute([$to,$asunto,$html,$ok]);
  return (int)db()->lastInsertId();
}
