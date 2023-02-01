<?php include "../../parts/header_login.php";?>
<?php 
	if(isset($_POST['correo'])){
		validarlogin($db);
	}
?>
  <body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">		
					<div class="card">
						<div class="loginBox">
							<h2>Iniciar sesi&oacute;n</h2><br>
							<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">                           	
								<div class="form-group">									
									<input type="email" class="form-control input-lg" name="correo" placeholder="email"  required>        
								</div>							
								<div class="form-group">        
									<input type="password" class="form-control input-lg" name="password" placeholder="contrase&ntilde;a" required>       
								</div>								    
								<button type="submit"name = "boton_login" class="btn btn-success btn-block" name = "login">Iniciar sesión</button>
							</form>
						</div>					
							<hr><p>¿Nuevo en Blog Aramis? <a href="registro.php" title="Crea una cuenta">Crea una cuenta</a>.</p>								
						</div><!-- /.loginBox -->	
					</div><!-- /.card -->
				</div><!-- /.col -->
			</div><!--/.row-->
		</div><!-- /.container -->

		<!-- Optional JavaScript -->
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>	
	</body>
</html>	