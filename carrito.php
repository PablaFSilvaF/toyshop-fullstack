<?php
require __DIR__.'/auth/check.php';
require __DIR__.'/config/db.php';

$toys = $pdo->query("SELECT id,nombre,precio,cantidad_inventario FROM JUGUETES ORDER BY nombre")->fetchAll();

$uid = (int)$_SESSION['uid'];
$stmt = $pdo->prepare("SELECT c.id, j.nombre, c.cantidad, c.monto_total
                       FROM CARRITO c JOIN JUGUETES j ON j.id=c.producto_id
                       WHERE c.usuario_id=?");
$stmt->execute([$uid]);
$items = $stmt->fetchAll();

$total = 0; foreach($items as $it){ $total += (float)$it['monto_total']; }
?>
<!doctype html><html lang="es"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Carrito | Tienda</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center">
    <h1 class="h4">Hola, <?=htmlspecialchars($_SESSION['uname'])?></h1>
    <a class="btn btn-outline-danger" href="auth/logout.php">Cerrar sesión</a>
  </div>

  <!-- Añadir al carrito -->
  <div class="card my-3">
    <div class="card-body">
      <form class="row g-3" method="POST" action="cart_ops.php" id="addForm">
        <input type="hidden" name="op" value="add">
        <div class="col-md-6">
          <label class="form-label">Juguete</label>
          <select class="form-select" name="producto_id" id="producto_id" required>
            <option value="">Selecciona…</option>
            <?php foreach($toys as $t): ?>
              <option value="<?=$t['id']?>" data-precio="<?=$t['precio']?>" data-stock="<?=$t['cantidad_inventario']?>">
                <?=$t['nombre']?> — $<?=$t['precio']?> (stock: <?=$t['cantidad_inventario']?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Cantidad</label>
          <input type="number" class="form-control" name="cantidad" id="cantidad" min="1" value="1" required>
          <div class="form-text text-danger d-none" id="qtyErr">Cantidad supera el stock.</div>
        </div>
        <div class="col-md-3 d-grid">
          <label class="form-label">&nbsp;</label>
          <button class="btn btn-primary">Agregar al carrito</button>
        </div>
      </form>
      <div class="mt-2"><strong>Subtotal estimado:</strong> $<span id="subtotal">0</span></div>
    </div>
  </div>

  <!-- Tabla carrito -->
  <h2 class="h5 mt-4">Tu carrito</h2>
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead><tr><th>Producto</th><th>Cantidad</th><th>Monto</th><th>Acciones</th></tr></thead>
      <tbody>
        <?php foreach($items as $it): ?>
        <tr>
          <td><?=htmlspecialchars($it['nombre'])?></td>
          <td>
            <form class="d-flex" method="POST" action="cart_ops.php">
              <input type="hidden" name="op" value="update">
              <input type="hidden" name="carrito_id" value="<?=$it['id']?>">
              <input type="number" class="form-control me-2" name="cantidad" min="1" value="<?=$it['cantidad']?>" required>
              <button class="btn btn-outline-secondary btn-sm">Actualizar</button>
            </form>
          </td>
          <td>$<?=number_format($it['monto_total'],0,',','.')?></td>
          <td>
            <form method="POST" action="cart_ops.php" onsubmit="return confirm('¿Eliminar este ítem?')">
              <input type="hidden" name="op" value="delete">
              <input type="hidden" name="carrito_id" value="<?=$it['id']?>">
              <button class="btn btn-outline-danger btn-sm">Eliminar</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot><tr><th colspan="2" class="text-end">Total</th><th>$<?=number_format($total,0,',','.')?></th><th></th></tr></tfoot>
    </table>
  </div>
</div>

<script>
// Validación básica y subtotal en cliente
const select = document.getElementById('producto_id');
const qty = document.getElementById('cantidad');
const sub = document.getElementById('subtotal');
const err = document.getElementById('qtyErr');
function recalc(){
  const opt = select.options[select.selectedIndex];
  const precio = parseFloat(opt?.dataset.precio||0);
  const stock = parseInt(opt?.dataset.stock||0,10);
  const c = Math.max(1, parseInt(qty.value||1,10));
  sub.textContent = (precio*c).toLocaleString('es-CL');
  const invalida = c>stock && stock>0;
  err.classList.toggle('d-none', !invalida);
}
select.addEventListener('change', recalc);
qty.addEventListener('input', recalc);
recalc();
</script>
</body></html>
