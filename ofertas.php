<?php $active='ofertas'; $titulo='Ofertas de empleo'; require 'includes/header.php';
$q = trim($_GET['q'] ?? ''); $zona = $_GET['zona'] ?? ''; $mod = $_GET['modalidad'] ?? ''; $area = $_GET['area'] ?? '';
$sql = "SELECT * FROM vacantes WHERE estado='activa'"; $p = [];
if ($q !== '') { $sql .= " AND (titulo LIKE ? OR empresa LIKE ? OR descripcion LIKE ?)"; array_push($p,"%$q%","%$q%","%$q%"); }
foreach (['zona'=>$zona,'modalidad'=>$mod,'area'=>$area] as $c=>$val) if ($val !== '') { $sql .= " AND $c = ?"; $p[] = $val; }
$st = db()->prepare($sql.' ORDER BY creado DESC LIMIT 60'); $st->execute($p); $lista = $st->fetchAll();
?>
<section class="card">
  <div class="card-h"><h1 class="h2">Ofertas de empleo</h1><span class="muted"><?= count($lista) ?> resultado(s)</span></div>
  <form class="buscar" method="get">
    <input type="search" name="q" value="<?= e($q) ?>" placeholder="Buscar por cargo, empresa o palabra clave">
    <select name="zona"><option value="">Zona</option><?php foreach($ZONAS as $z) echo '<option'.($z===$zona?' selected':'').'>'.e($z).'</option>'; ?></select>
    <select name="modalidad"><option value="">Horario</option><?php foreach($MODALIDADES as $m) echo '<option'.($m===$mod?' selected':'').'>'.e($m).'</option>'; ?></select>
    <select name="area"><option value="">Área</option><?php foreach($AREAS as $a) echo '<option'.($a===$area?' selected':'').'>'.e($a).'</option>'; ?></select>
    <button class="btn btn-y">Buscar</button>
  </form>
  <div class="grid3"><?php foreach ($lista as $v) echo card_vacante($v); ?></div>
  <?php if (!$lista): ?><p class="vacio">No encontramos vacantes con esos filtros. Prueba quitando alguno.</p><?php endif; ?>
</section>
<?php require 'includes/footer.php'; ?>
