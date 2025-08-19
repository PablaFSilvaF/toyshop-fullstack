<?php
session_start();
require __DIR__.'/config/db.php';

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $nombre = trim($_POST['nombre']??'');
  $email  = strtolower(trim($_POST['email']??''));
  $pass   = $_POST['password']??'';
  $dir    = trim($_POST['direccion']??'');
  $tel    = trim($_POST['telefono']??'');

  if(!$nombre || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($pass)<8){
    $msg='Datos inválidos: revisa nombre, correo y longitud de contraseña (≥8).';
  } else {
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO USUARIOS(nombre,email,contrasena,direccion,telefono) VALUES(?,?,?,?,?)");
    try { $stmt->execute([$nombre,$email,$hash,$dir,$tel]); $msg='Usuario registrado con éxito.'; }
    catch(Exception $e){ $msg='Error al registrar: '.$e->getMessage(); }
  }
}
?>
<!doctype html><html lang="es"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registro de Usuario</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light">
<div class="container" style="max-width:640px">
  <h1 class="h4 my-4">Registro de Usuario</h1>
  <?php if(!empty($msg)):?><div class="alert alert-info"><?=$msg?></div><?php endif;?>
  <form method="POST" class="card card-body shadow-sm">
    <div class="mb-3"><label class="form-label">Nombre</label><input class="form-control" name="nombre" required></div>
    <div class="mb-3"><label class="form-label">Correo</label><input type="email" class="form-control" name="email" required></div>
    <div class="mb-3"><label class="form-label">Contraseña</label><input type="password" class="form-control" name="password" minlength="8" required></div>
    <div class="mb-3"><label class="form-label">Dirección</label><input class="form-control" name="direccion"></div>
    <div class="mb-3"><label class="form-label">Teléfono</label><input class="form-control" name="telefono"></div>
    <button class="btn btn-primary">Registrarme</button>
  </form>
</div>
</body></html>
