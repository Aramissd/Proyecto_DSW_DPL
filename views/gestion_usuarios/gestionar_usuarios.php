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
<?php require "../../parts/header.php";?>
  <header> 
    <nav class="navbar navbar-expand-sm bg-light">
        <a class="navbar-brand" href="../../../index.php" style="color: inherit;">Aramis Blog</a>
        <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button> -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="../../../index.php" style="color: inherit;">Inicio</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../gestion_usuarios/gestionar_usuarios.php" style="color: inherit;"><?php if ($_SESSION['rol'] === "Administrador") {
                echo "Gestionar usuarios";
              }?>
              </a>
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
              <a class="nav-link" href="../../controllers/logout.php" style="color: inherit;">Cerrar sesión</a>
            </li>

          </ul>
      </nav>
    </header>

    <div class="container-fluid text-center">
        <h1 class="text-center my-5">Usuarios</h1>
            <a href="crear_usuarios.php" type="submit" class="btn btn-dark" value="crear" name="crear">Crear usuario</a><br><br>
        <br>


        <!-- Tabla para mostrar las publicaciones existentes -->
      
          <table class="table table-striped table-bordered table-hover table-dark">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Password</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Editar usuario</th>
                <th>Eliminar usuario</th>

              </tr>
            </thead>
        
            <tbody>
                <?php
                  $usuarios = ver_usuarios($db);
                  if(!empty($usuarios)):
                      while($usuario = mysqli_fetch_assoc($usuarios)):
                    
                ?>
              
                <tr>
                  <td><?php echo $usuario['id']?></td>
                    <td><?php echo $usuario['nombre']?></td>
                    <td><?php echo $usuario['apellidos']?></td>
                    <td><?php echo $usuario['password']?></td>
                    <td><?php echo $usuario['correo'] ?></td>
                    <td><?php echo $usuario['rol'] ?></td>
                    <td><a href="editar_usuario.php?id=<?php echo $usuario['id']?>" type="submit" class="btn btn-warning" value="editar" name="editar">Editar</td>
                    <td><a href="eliminar_usuario.php?id=<?php echo $usuario['id']?>" type="submit" class="btn btn-danger" value="eliminar" name="eliminar">Eliminar</td>
                </tr>
                  <?php
              endwhile;
              endif;
              ?>
          </tbody>
        </table>
    </div>
    <footer class="text-center text-lg-start bg-light text-muted" style= "position:absolute; bottom:0; width: 100%;">
      <div class="text-center p-4 bg-light">
          © 2023 Copyright Aramis Blog
      </div>
    </footer>
    <?php include "../../parts/footer.php"?>
