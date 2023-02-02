<?php 
session_start();
$rol = $_SESSION['rol'];
if ($rol === "Administrador"){
    $rol = $_SESSION['rol'];
    $_SESSION['start'] = time();
    $_SESSION['expire'] = $_SESSION['start'] + (40 * 60);
} else{
  header('Location: ../../views/auth/login.php'); //Aqui lo redireccionas al lugar que quieras.
  die() ;
}
?>
<?php  include "../../parts/header.php";?>
<?php $usuario_actual = obtener_usuario($db, $_GET['id']);

  if(!isset($publicacion_actual['id'])){
    //header ("Location : index.php");
  } ?>

<header>
    <nav class="navbar navbar-expand-sm bg-light">
      <a class="navbar-brand" href="../../../index.php">Aramis Blog</a>
      <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button> -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="../../../index.php" style="color: inherit;">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../gestion_usuarios/gestionar_usuarios.php" style="color: inherit;"><?php if ($_SESSION['rol'] === "Administrador") {
              echo "Gestionar usuarios";
            }?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../gestion_categorias/gestionar_categorias.php" style="color: inherit;"><?php if ($_SESSION['rol'] === "Administrador") {
              echo "Gestionar categorías";
            }?></a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="../gestion_publicaciones/gestionar_publicaciones.php" style="color: inherit;"><?php if ($_SESSION['rol'] === "Administrador") {
              echo "Gestionar publicaciones";
            }?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../controllers/logout.php" style="color: inherit;">Cerrar sesión</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <div class="container-fluid mx-auto text-center col-6">

       <br><h1>Editar usuario</h1>

        <form action="edit_usuario.php" method="POST">
          <div class="form-group mx-auto">
              <input type="hidden" class="form-control form-control-sm mb-2" value="<?php echo $usuario_actual['id']?>" name="id_usuario">
              <label for = "FormControlTitulo">Nombre:</label>
              <input type="text" class="form-control form-control-sm mb-2" value="<?php echo $usuario_actual['nombre']?>" name="nombre" >
              <label for="FormControlContenido">Apellidos:</label>
              <input class="form-control" type="text" rows="5" name="apellidos" value = "<?php echo $usuario_actual['apellidos']?>"><br>
              <label for="FormControlContenido">Correo:</label>
              <input type="email" class="form-control" rows="5" name="correo"  value = "<?php echo $usuario_actual['correo']?>" ><br>
              <label for="FormControlContenido">Contraseña:</label>
              <input type="password" class="form-control" rows="5" name="password"  value = "<?php echo $usuario_actual['password']?>" ><br>
              <label for="FormControlContenido">Rol:</label>
              <input type="text" class="form-control" rows="5" name="rol"  value = "<?php echo $usuario_actual['rol']?>" ><br>
              <input type="submit" class="btn btn-warning " value="Editar usuario" name="editar_usuario">
          </div>
        </form>
           
    
  
    </div>
     
  </div>

  <footer class="text-center text-lg-start bg-light text-muted" style = "position:absolute; bottom:0; width:100%;">
     
        <div class="text-center p-4">
            © 2023 Copyright Aramis Blog
      </div>
    </footer>
<?php include "../../parts/footer.php";?>
