<?php 
session_start();
if ($_SESSION['rol']){
    $rol = $_SESSION['rol'];
    $_SESSION['start'] = time();
    $_SESSION['expire'] = $_SESSION['start'] + (40 * 60);
} else{
  header('Location: views/auth/login.php');
  die() ;
}
?>
<?php include "parts/header_index.php";?>
  <header>
    <nav class="navbar navbar-expand-sm bg-light">
      <a class="navbar-brand" href="index.php">Aramis Blog</a>
      <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button> -->
        <ul class="navbar-nav">
          <li class="nav-item ">
            <a class="nav-link" href="index.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../views/gestion_usuarios/gestionar_usuarios.php"><?php if ($_SESSION['rol'] === "Administrador") {
              echo "Gestionar usuarios";
            }?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../views/gestion_categorias/gestionar_categorias.php"><?php if ($_SESSION['rol'] === "Administrador") {
              echo "Gestionar categorías";
            }?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../views/gestion_publicaciones/gestionar_publicaciones.php"><?php if ($_SESSION['rol'] === "Administrador") {
              echo "Gestionar publicaciones";
            }?></a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="/controllers/logout.php">Cerrar sesión</a>
          </li>

        </ul>
    </nav>
  </header>
  <main>
    <br>
    <br>
    <div class="container">
      <div class="row">

     
        
      <?php
        $publicaciones = obtenerUltimasPublicaciones($db);
        if(!empty($publicaciones)):
            while($publicacion = mysqli_fetch_assoc($publicaciones)):
              
      ?>


        <div class="col-md-8 mx-auto container_publicacion">
          <div class="card mb-3">
            <img src= <?php echo "../views/gestion_publicaciones/images/$publicacion[imagen]"?> class="card-img-top" alt="..." width="200" height="400"/>
            <div class="card-body">
              <h5 class="card-title"><?php echo $publicacion['titulo']?></h5>
              <p><?php echo "Fecha de creación: " . $publicacion['fecha_creacion'] ?> | <b><?php echo $publicacion['usuario']; ?></b></p>
              <p class="card-text"><?php echo $publicacion['contenido']?></p>
              <kbd><?php echo $publicacion['categoria']?></kbd>

              <!-- <a href="#" class="btn btn-primary">Read More</a> -->
            </div>
          </div>
        </div>
        <?php
        endwhile;
        endif;
        ?>

      </div>
    </div>
    

    </main>

        <footer class="text-center text-lg-start bg-light text-muted">

            <div class="text-center p-4">
              © 2023 Copyright Aramis Blog

            </div>
        

        </footer>
    
    <?php include "parts/footer.php";?>