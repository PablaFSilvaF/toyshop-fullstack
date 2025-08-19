<?php
session_start();
require __DIR__.'/config/db.php';

if($_SERVER['REQUEST_METHOD']==='POST'){
  $nombre = trim($_POST['nombre']??'');
  $desc   = trim($_POST['descripcion']??'');
  $edad   = (int)($_POST['edad']??0);
  $precio = (float)($_POST['precio']??0);
  $stock  = (int)($_POST['cantidad']??0);

  if(!$nombre || $edad<0 || $precio<=0 || $stock<0){
    $msg='Datos inválidos. Verifica edad, precio y stock.';
  } else {
    $stmt=$pdo->prepare("INSERT INTO JUGUETES(nombre,descripcion,edad,precio,cantidad_inventario) VALUES(?,?,?,?,?)");
    try{ $stmt->execute([$nombre,$desc,$edad,$precio,$stock]); $msg='Juguete registrado.'; }
    catch(Exception $e){ $msg='Error: '.$e->getMessage(); }
  }
}
?>
<!doctype html><html lang="es"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registro de Juguetes</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light">
<div class="container" style="max-width:640px">
  <h1 class="h4 my-4">Registro de Juguetes</h1>
  <?php if(!empty($msg)):?><div class="alert alert-info"><?=$msg?></div><?php endif;?>
  <form method="POST" class="card card-body shadow-sm">
    <div class="mb-3"><label class="form-label">Nombre</label><input class="form-control" name="nombre" required></div>
    <div class="mb-3"><label class="form-label">Descripción</label><textarea class="form-control" name="descripcion" rows="3"></textarea></div>
    <div class="mb-3"><label class="form-label">Edad recomendada</label><input type="number" class="form-control" name="edad" min="0" required></div>
    <div class="mb-3"><label class="form-label">Precio</label><input type="number" class="form-control" name="precio" step="0.01" min="0" required></div>
    <div class="mb-3"><label class="form-label">Stock</label><input type="number" class="form-control" name="cantidad" min="0" required></div>
    <button class="btn btn-primary">Guardar</button>
  </form>
</div>
</body></html>
