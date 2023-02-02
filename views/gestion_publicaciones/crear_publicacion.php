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
<?php  include "../../parts/header.php"?>
<?php 
	if(isset($_POST['crear_publicacion'])){
		crear_publicacion($db);
	}
?>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
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
            <a class="nav-link" href="#"></a>
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
            <a class="nav-link" href="../gestion_publicaciones/gestionar_publicaciones.php"><?php if ($_SESSION['rol'] === "Administrador") {
              echo "Gestionar publicaciones";
            }?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../controllers/logout.php">Cerrar sesión</a>
          </li>

        </ul>
      </div>
    </nav>
  </header>

 <div class="container-fluid mx-auto text-center">
   <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
      <div class="form-group col-6 mx-auto"><br>
          <h1 class="text-center">Crear publicaciones</h1><br>
        <input type="text" class="form-control" placeholder="Titulo de la entrada"  name="titulo" maxlength="100" required>
      </div>
      <div class="form-group col-6 mx-auto">
          <label for="FormControlContenido">Contenido:</label>
          <textarea class="form-control" rows="3" name="contenido" required></textarea>
      </div>

      <div class="form-group col-6 mx-auto">
          <div class="input-group mb-5">
            <div class="input-group-prepend">
              <input type="file" class="custom-file-input" id="inputGroupFile01" name="imagen" aria-describedby="inputGroupFileAddon01">
              <label class="custom-file-label" for="inputGroupFile01">Elige una imagen para la publicación</label>
            </div>
        
          </div>
      </div>

      <div class="form-group col-6 mx-auto">
          <select multiple class="form-control" name="categoria" title = "Elige una categoría" required>
              <!-- Obtenemos las categorias para enlistarlas en el select -->
              <?php $categorias = obtenerCategorias($db);
                      if(!empty($categorias)):
                      while($categoria = mysqli_fetch_assoc($categorias)):
              ?>
              <option value="<?=$categoria['id']?>">
              <?=$categoria['nombre']; ?>
              </option>
              <?php endwhile;
                  endif;
              ?>
        </select><br>
        <input  type="submit" class="btn btn-success text-center" name="crear_publicacion" value="Crear publicación"><br><br>
      </div>

    </form>
 </div>

 <footer class="text-center text-lg-end bg-light text-muted" style= "position:absolute; bottom:0; width: 100%;">

    <div class="text-center p-4 bg-light">
            © 2023 Copyright Aramis Blog
      </div>
 </footer>
<?php  include "../../parts/footer.php";?>
