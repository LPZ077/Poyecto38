<?php
require_once __DIR__.'/db.php';
if (session_status() === PHP_SESSION_NONE) {
  session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax']);
  session_start();
}
function e($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function user(): ?array { return $_SESSION['user'] ?? null; }
function redirect(string $to): never { header('Location: '.$to); exit; }
function flash(?string $msg = null, string $tipo = 'ok') {
  if ($msg !== null) { $_SESSION['flash'] = [$msg,$tipo]; return; }
  $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f;
}
function csrf(): string { return $_SESSION['csrf'] ??= bin2hex(random_bytes(24)); }
function csrf_field(): string { return '<input type="hidden" name="csrf" value="'.csrf().'">'; }
function csrf_check(): void {
  if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(419); exit('Sesión expirada. Recarga la página.'); }
}
function require_login(?string $rol = null): array {
  $u = user();
  if (!$u) { flash('Inicia sesión para continuar.','error'); redirect('login.php'); }
  if ($rol && $u['rol'] !== $rol) { http_response_code(403); exit('No tienes permiso para ver esta página.'); }
  return $u;
}
function fecha($s): string { return date('d/m/Y', strtotime($s)); }

function icono(string $area): string {
  $p = [
    'Ventas' => '<path d="M6 8h12l-1 12H7L6 8zM9 8a3 3 0 016 0"/>',
    'Soporte técnico' => '<path d="M4 14v-2a8 8 0 0116 0v2M4 14h3v5H4zM17 14h3v5h-3zM17 19c0 1-2 2-5 2"/>',
    'Contenido y diseño' => '<path d="M4 20l1-4L16 5l3 3L8 19l-4 1zM14 7l3 3"/>',
  ][$area] ?? '<rect x="4" y="8" width="16" height="11" rx="2"/><path d="M9 8V6h6v2"/>';
  return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.$p.'</svg>';
}
function tono(string $area): string {
  return ['Ventas'=>'t-blue','Soporte técnico'=>'t-teal','Contenido y diseño'=>'t-yellow'][$area] ?? 't-blue';
}
function card_vacante(array $v): string {
  return '<article class="oferta"><div class="ico '.tono($v['area']).'">'.icono($v['area']).'</div>'
   .'<div class="oferta-txt"><h3>'.e($v['titulo']).'</h3><p class="muted">'.e($v['empresa']).'</p>'
   .'<p class="punto">'.e($v['modalidad']).' · '.e($v['zona']).'</p></div>'
   .'<a class="btn btn-sm btn-y" href="oferta.php?id='.(int)$v['id'].'">Ver detalles</a></article>';
}
