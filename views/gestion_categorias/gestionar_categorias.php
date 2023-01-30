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
  <header class="nav-fill w-100"> 
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="../../public/index.php">Aramis Blog</a>
        <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button> -->
        <div class="collapse navbar-collapse container-fluid" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="../../public/index.php">Inicio</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../gestion_usuarios/gestionar_usuarios.php"><?php if ($_SESSION['rol'] === "Administrador") {
                echo "Gestionar usuarios";
              }?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="gestionar_categorias.php"><?php if ($_SESSION['rol'] === "Administrador") {
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

    <div class="container mx-auto text-center">
        <h1 class="text-center my-5">Categorías</h1>
            <a href="crear_categoria.php" type="submit" class="btn btn-dark" value="crear" name="crear">Crear categoría</a><br><br>
        <br>


        


        <!-- Tabla para mostrar las categorias existentes -->
      
          <table class="table table-striped table-bordered table-hover table-dark">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Editar categoría</th>
                <th>Eliminar categoría</th>

              </tr>
            </thead>
            <?php
                  $categorias = obtenerCategorias($db);
                  if(!empty($categorias)):
                      while($categoria = mysqli_fetch_assoc($categorias)):
                    
                    ?>
            <tbody>
           
                  <tr>
                  
                    <td><?php echo $categoria['id']?></td>
                    <td><?php echo $categoria['nombre']?></td>
                    <td><a href="editar_categoria.php?id=<?php echo $categoria['id']?>" type="submit" class="btn btn-warning" value="editar" name="editar">Editar</td>
                    <td><a href="eliminar_categoria.php?id=<?php echo $categoria['id']?>" type="submit" class="btn btn-danger" value="eliminar" name="eliminar">Eliminar</td>
                  </tr>
                  <?php
              endwhile;
              endif;
              ?>
          </tbody>
        </table>
    </div>
    <footer class="text-center text-lg-start bg-light text-muted">
      <div class="text-center p-4 bg-light">
          © 2023 Copyright Aramis Blog
      </div>
    </footer>
    <?php include "../../parts/footer.php"?>
