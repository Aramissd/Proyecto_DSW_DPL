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
<?php $categoria_actual = obtenerCategoria($db, $_GET['id']); ?>

<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="../../index.php" style="color: inherit;">Aramis Blog</a>
      <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button> -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="../../index.php" style="color: inherit;">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../gestion_usuarios/gestionar_usuarios.php" style="color: inherit;"><?php if ($_SESSION['rol'] === "Administrador") {
              echo "Gestionar usuarios";
            }?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="gestionar_categorias.php" style="color: inherit;"><?php if ($_SESSION['rol'] === "Administrador") {
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
  <div class="container-fluid mx-auto text-center col-2">

       <br><h1>Editar la categoría</h1>

            <div class="form-group mx-auto ">
                <form action="edit_categoria.php" method="POST">

                    <input type="hidden" class="form-control form-control-sm mb-2" value="<?=$categoria_actual['id']?>" autocomplete="off" name="id_categoria" maxlength="30" required>
                    <input type="text" class="form-control form-control-sm mb-2" value="<?=$categoria_actual['nombre']?>" autocomplete="off" name="nombre_categoria" maxlength="30" required>
                    <input type="submit" class="btn btn-warning " name="editar_categoria" value="Editar categoría">
                </form>
            </div>
     

    </div>

  <footer class="text-center text-lg-start bg-light text-muted" style = "position:absolute; bottom:0; width: 100%;">
     
        <div class="text-center p-4">
            © 2023 Copyright Aramis Blog
      </div>
    </footer>
<?php include "../../parts/footer.php";?>
