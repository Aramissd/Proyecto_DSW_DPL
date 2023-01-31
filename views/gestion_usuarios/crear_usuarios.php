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
<?php 
	if(isset($_POST['crear_usuario_admin'])){
		crear_Usuario_admin($db);
	}
?>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="../../public/index.php">Blog</a>
      <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button> -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="../../public/index.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="gestionar_usuarios.php"><?php if ($_SESSION['rol'] === "Administrador") {
              echo "Gestionar usuarios";
            }?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../gestion_categorias/gestionar_categorias.php"><?php if ($_SESSION['rol'] === "Administrador") {
              echo "Gestionar categorías";
            }?></a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="../gestion_publicaciones/gestionar_publicaciones.php"><?php if ($_SESSION['rol'] === "Administrador") {
              echo "Gestionar publicaciones";
            }?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../controllers/logout.php">Cerrar sesión</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <div class="container-fluid mx-auto text-center col-6">

       <br><h1>Crear usuario</h1>

        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
          <div class="form-group mx-auto">
              <input type="text" class="form-control"  rows = "5" placeholder = "Nombre" name="nombre"><br>
              <input class="form-control" type="text" rows="5" name="apellidos" placeholder="Apellidos"><br>
              <input type="email" class="form-control" rows="5" name="correo"  placeholder = "Correo electrónico" ><br>
              <input type="password" class="form-control" rows="5" name="password"  placeholder = "Contraseña" ><br>
              <input type="text" class="form-control" rows="5" name="rol"  placeholder = "Rol"><br>
              <input type="submit" class="btn btn-success btn-block" value="Crear usuario" name="crear_usuario_admin">
          </div>
        </form>
           
    
  
    </div>
     
  </div>

  <footer class="text-center text-lg-start bg-light text-muted">
     
        <div class="text-center p-4">
            © 2023 Copyright Aramis Blog
      </div>
    </footer>
<?php include "../../parts/footer.php";?>
