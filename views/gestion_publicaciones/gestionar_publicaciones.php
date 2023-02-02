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
<?php include "../../parts/header.php";?>
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
              <a class="nav-link" href="../../index.php"><?php if ($_SESSION['rol'] === "Administrador") {
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
            <li class="nav-item cerrar">
              <a class="nav-link" href="../../controllers/logout.php">Cerrar sesión</a>
            </li>

          </ul>
        </div>
      </nav>
    </header>

    <div class="container text-center">
        <h1 class="text-center my-5">Publicaciones</h1>
            <a href="crear_publicacion.php" type="submit" class="btn btn-dark" value="crear" name="crear">Crear publicación</a><br><br>
        <br>


        <!-- Tabla para mostrar las publicaciones existentes -->
      
          <table class="table table-striped table-bordered table-hover table-dark">
            <thead>
              <tr>
                <th>Título</th>
                <th>Categoría</th>
                <th>Fecha de creación</th>
                <th>Autor</th>
                <th>ID de usuario</th>
                <th>Editar publicación</th>
                <th>Eliminar publicación</th>

              </tr>
            </thead>
        
            <tbody>
                <?php
                  $publicaciones = obtenerUltimasPublicaciones($db);
                  if(!empty($publicaciones)):
                      while($publicacion = mysqli_fetch_assoc($publicaciones)):
                    
                ?>
              
                  <tr>
                    <td><?php echo $publicacion['titulo']?></td>
                    <td><?php echo $publicacion['categoria']?></td>
                    <td><?php echo "Fecha de creación: " . $publicacion['fecha_creacion'] ?></td>
                    <td><?php echo $publicacion['usuario'] ?></td>
                    <td><?php echo $publicacion['id_usuario'] ?></td>
                    <td><a href="editar_publicacion.php?id=<?php echo $publicacion['id']?>" type="submit" class="btn btn-warning" value="editar" name="editar">Editar</td>
                    <td><a href="eliminar_publicacion.php?id=<?php echo $publicacion['id']?>" type="submit" class="btn btn-danger" value="eliminar" name="eliminar">Eliminar</td>
                  </tr>
                  <?php
              endwhile;
              endif;
              ?>
          </tbody>
        </table>
    </div>
    <footer class="text-center text-lg-start bg-light text-muted" style = "position:absolute; bottom:0; width: 100%;">
        <div class="text-center p-4 bg-light">
          © 2023 Copyright Aramis Blog
      </div>
    </footer>
    <?php include "../../parts/footer.php"?>
