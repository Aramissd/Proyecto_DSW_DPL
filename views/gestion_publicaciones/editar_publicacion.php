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
<?php $publicacion_actual = obtenerUnaPublicacion($db, $_GET['id']);

  if(!isset($publicacion_actual['id'])){
    //header ("Location : index.php");
  } ?>

<header>
    <nav class="navbar navbar-expand-sm bg-light">
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
            <a class="nav-link" href="../gestion_categorias/gestionar_categorias.php" style="color: inherit;"><?php if ($_SESSION['rol'] === "Administrador") {
              echo "Gestionar categorías";
            }?></a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="gestionar_publicaciones.php" style="color: inherit;"><?php if ($_SESSION['rol'] === "Administrador") {
              echo "Gestionar publicaciones";
            }?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../controllers/logout.php" style="color: inherit;">Cerrar sesión</a>
          </li>
        </ul>
    </nav>
  </header>
  <div class="container-fluid mx-auto text-center col-6">

       <br><h1>Editar la publicación</h1>

        <form action="editar.php" method="POST" enctype="multipart/form-data">
        <div class="form-group mx-auto">
            <input type="hidden" class="form-control form-control-sm mb-2" value="<?=$publicacion_actual['id']?>" autocomplete="off" name="id_publicacion" maxlength="30" required>
            <label for = "FormControlTitulo">Titulo:</label>
            <input type="text" class="form-control form-control-sm mb-2" value="<?=$publicacion_actual['titulo']?>" autocomplete="off" name="titulo" maxlength="30" required>
        </div>
            <label for="FormControlContenido">Contenido:</label>
            <textarea class="form-control" rows="5" name="contenido"required><?=$publicacion_actual['contenido']?></textarea><br>
        <div class="form-group col-8 mx-auto">
            <label for="FormControlImagen">Imagen a editar</label><br>
            <img src=<?php echo "images/$publicacion_actual[imagen]"?> width="200" height="200" name = ""><br><br>
            <div class="input-group">
                <div class="input-group-prepend mx-auto">
                    <label class="custom-file-label" for="inputGroupFile01">Elige una nueva imagen</label>
                    <br><br><input type="file" class="custom-file-input" id="inputGroupFile01" name="imagen" aria-describedby="inputGroupFileAddon01">
                </div>
            </div>
        </div>
    

    <div class="form-group mx-auto text-center">
            <label for="FormControlCategoria">Categoria</label>
            <select multiple class="form-control " name="categoria" required>

                <!-- Obtenemos las categorias para enlistarlas en el select -->
                <?php $categorias = obtenerCategorias($db);
                        if(!empty($categorias)):
                        while($categoria = mysqli_fetch_assoc($categorias)):
                ?>

            <option value="<?=$categoria['id']?>" <?=($categoria['id'] == $publicacion_actual['categoria_id']) ? 'selected="selected"' : ''  ?>>
            <?=$categoria['nombre']; ?>
            </option>
            <?php endwhile;
                endif;
            ?>
        </select>
    </div>
        <input type="submit" class="btn btn-warning " name="editar_publicacion" value="Editar publicación"><br><br>
        </form>
  </div>

  <footer class="text-center text-lg-start bg-light text-muted" style = "width:100%; position:relative; bottom:0;">
     
        <div class="text-center p-4">
            © 2023 Copyright Aramis Blog
      </div>
    </footer>
<?php include "../../parts/footer.php";?>
