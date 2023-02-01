<?php include "../../parts/header_login.php";?>
<?php 
	if(isset($_POST['nombre'])){
		crear_Usuario($db);
	}
?>
  <div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h1>Aramis Blog</h1>
			</div>
	</div>
	
	<div class="row">	
		<div class="col-sm-12 col-md-6 col-lg-6">
		<h3>Crear una cuenta</h3><hr />
		<form action="<?php echo $_SERVER['PHP_SELF'] ?> " method="POST">
			<div class="form-group">				
				<input type="text" class="form-control" name="nombre" placeholder="Escribe tu nombre" required>			
		    </div>

            <div class="form-group">				
				<input type="text" class="form-control" name="apellidos" placeholder="Escribe tus apellidos" required>			
		    </div>
		  
		    <div class="form-group">				
				<input type="email" class="form-control" name="correo" aria-describedby="emailHelp" placeholder="Escribe tu email" required>
			</div>
		  
		  <div class="form-group">				
				<input type="password" class="form-control" name="password" placeholder="Crea una contraseña" required>
			</div>
		  
		  <input type="submit" class="btn btn-success btn-block" name="registrar_usuario" value="Crear mi cuenta">
		</form>		
		</div>		
		<div class="col-sm-12 col-md-6 col-lg-6">
			<h3>Iniciar sesi&oacute;n</h3><hr />
			<p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesi&oacute;n aqu&iacute;!</a></p>
		</div>
	</div>
</div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
 
	</body>
</html>