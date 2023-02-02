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
<?php if (isset($_POST['crear_categoria']))
    {
    crear_categoria($db);

    } ?>

<header>
    <nav class="navbar navbar-expand-sm bg-light">
      <a class="navbar-brand" href="../../index.php">Aramis Blog</a>
      <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button> -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="../../index.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../gestion_usuarios/gestionar_usuarios.php"><?php if ($_SESSION['rol'] === "Administrador") {
              echo "Gestionar usuarios";
            }?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../gestion_categorias/gestionar_categorias.php"><?php if ($_SESSION['rol'] === "Administrador") {
              echo "Gestionar categorías";
            }?></a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="gestionar_publicaciones.php"><?php if ($_SESSION['rol'] === "Administrador") {
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
  <div class="container-fluid mx-auto text-center col-2">

       <br><h1>Crear la categoría</h1>

            <div class="form-group mx-auto ">
                <form action="<?php echo $_SERVER['PHP_SELF']?>"method="POST">
                    <input type="text" class="form-control form-control-sm mb-2" placeholder = "Nombre de la categoría"  autocomplete="off" name="nombre_categoria" maxlength="30" required>
                    <input type="submit" class="btn btn-warning " name="crear_categoria" value="Crear categoría">
                </form>
            </div>
     

    </div>

  <footer class="text-center text-lg-start bg-light text-muted" style = "position:absolute; bottom:0; width: 100%;">
     
        <div class="text-center p-4">
            © 2023 Copyright Aramis Blog
      </div>
    </footer>
<?php include "../../parts/footer.php";?>
