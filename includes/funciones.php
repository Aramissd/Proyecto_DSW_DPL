<?php
ob_start();

/**
* Valida las credenciales de un usuario enviadas a través de un formulario de inicio de sesión.
* @access public
* @param mysqli $db Una conexión a la base de datos.
* @return void  redirige al usuario a la página de inicio si el login se ha realizado correctamente
*/
function validarLogin($db)
{
  // Validamos los datos del formulario, con trim limpiamos los espacios que pueda haber en el email.
  $correo = trim($_POST['correo']);
  $password = $_POST['password'];

  $stmt = mysqli_prepare($db, "SELECT * FROM usuarios WHERE correo=?");
  mysqli_stmt_bind_param($stmt, "s", $correo);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if ($result && mysqli_num_rows($result) == 1) {
    $usuario = mysqli_fetch_assoc($result);
    // Comprobamos la contraseña | Volvemos a cifrarla.
    $verificar = password_verify($password, $usuario['password']);
    //Si la contraseña coincide iniciamos sesión y guardamos datos de sesión
    if ($verificar) {
      session_start();
      // Guardamos los datos del usuario logueado.
      $_SESSION['nombre'] = $usuario['nombre'];
      $_SESSION['rol'] = $usuario['rol'];
      $_SESSION['id'] = $usuario['id'];
      header('Location: ../index.php');
    }
    else{
      echo "error";
    }

  }
  mysqli_stmt_close($stmt);
}



/**

*Función para registrar un usuario nuevo en la base de datos
*@access public
*@param mysqli $con Una conexión a la base de datos.
*@return void Muestra un mensaje que informa si el registro se ha realizado correctamente o no.
*La función crear_Usuario se utiliza para registrar un usuario en la base de datos. Recoge los valores del formulario de registro, valida y procesa la información de entrada, y luego la agrega a la base de datos.
*Si el registro es exitoso, se muestra un mensaje de éxito. En caso contrario, se muestra un mensaje de error.
*/
function crear_Usuario($con)
{
  if (isset($_POST['registrar_usuario'])) {

    // Recogemos los valores del formulario.
    $nombre = isset($_POST['nombre']) ?  mysqli_real_escape_string($con,$_POST['nombre']) : false;
    $apellidos = isset($_POST['apellidos']) ?  mysqli_real_escape_string($con,$_POST['apellidos']) : false;
    $correo = isset($_POST['correo']) ? mysqli_real_escape_string($con, $_POST['correo']): false;
    $password = isset($_POST['password']) ?  mysqli_real_escape_string($con,$_POST['password']) : false;

    
    if (!empty($nombre) && !is_numeric($nombre) && !preg_match("/[0-9]/", $nombre) && !empty($apellidos) && !is_numeric($apellidos) && !preg_match("/[0-9]/", $apellidos)) 
    {
         // Ciframos la contraseña con bcrypt y le damos un valor de 4 para que la cifre 4 veces
         $password_segura = password_hash($password, PASSWORD_BCRYPT, ['cost' => 4]);

         //Preparamos la consulta
         $sql =  "INSERT INTO usuarios (nombre, apellidos, password, correo, rol) VALUES (?,?,?,?,'Normal')";
         $stmt = mysqli_prepare($con, $sql);
         mysqli_stmt_bind_param($stmt, "ssss", $nombre, $apellidos, $password_segura, $correo);
         mysqli_stmt_execute($stmt);

         if (mysqli_stmt_affected_rows($stmt) > 0) {
          echo "<div class = 'alert-success'>Registro exitoso, ya puedes iniciar sesión</div>";

           
         } else {
           echo "<div class = 'alert-danger'>Error en el registro</div>";
         }
    }

    else
    {
      echo "<div class = 'alert-danger'>Error en el registro</div>";
    }
  }
}



/**
 *Obtiene una publicación de la base de datos mediante su ID.
 *@access public
 *@param mysqli $con Conexión a la base de datos.
 *@param int $id_publicacion ID de la publicación a obtener.
 *@return array Arreglo con los detalles de la publicación incluyendo el nombre de la categoría y del usuario que la publicó.
*/

// Obtenemos la entrada completa por su id, ademas el usuario que escribio la entrada para poder modificarla o elimarla.
function obtenerUnaPublicacion($con, $id_publicacion){
  $sql = "SELECT p.*, c.nombre AS 'categoria', CONCAT(u.nombre, ' ') AS usuario 
          FROM publicaciones p 
          INNER JOIN categorias c ON p.categoria_id = c.id 
          INNER JOIN usuarios u ON p.id_usuario = u.id 
          WHERE p.id = ?";
  $stmt = mysqli_prepare($con, $sql);
  mysqli_stmt_bind_param($stmt, "i", $id_publicacion);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $publicacion = mysqli_fetch_assoc($result);
  return $publicacion;
}

/**
*Obtiene las publicaciones de la base de datos.
*@access public
*@param mysqli $conexion_db Una conexión a la base de datos.
*@return array|mysqli_result Un arreglo o un resultado mysqli con los detalles de las últimas publicaciones, en caso de haber al menos una. En caso contrario, retorna un arreglo vacío.
*/
function obtenerUltimasPublicaciones($conexion_db){
  $sql = "SELECT p.*, c.nombre AS 'categoria', u.nombre as 'usuario'
  FROM publicaciones p
  INNER JOIN categorias c ON p.categoria_id = c.id
  INNER JOIN usuarios u ON p.id_usuario = u.id
  ORDER BY p.id";
  
  $stmt = mysqli_prepare($conexion_db, $sql);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  
  $resultado = array();
  if (mysqli_num_rows($result) >= 1) {
  $resultado = $result;
  }
  
  return $resultado;
  }


/**
 * Función para crear una publicación en la base de datos.
 * Esta función verifica si se ha enviado el formulario para crear una publicación.
 * Si se ha enviado, se recogen los datos del formulario y se validan.
 * Una vez validados, se sube la imagen al servidor y se guarda en la base de datos.
 * @access public
 * @param mysqli $con Conexión a la base de datos
 * @return void Redirige al usuario a la página de inicio si la creación se ha realizado correctamente.
*/
  function crear_publicacion($con){
  if (isset($_POST['crear_publicacion'])) {

    //Recoge los campos del formulario y almacena el id del usuario a través de la sesión
    $id_usuario = $_SESSION['id'];
    $titulo = isset($_POST['titulo']) ? mysqli_real_escape_string($con, $_POST['titulo']) : false;
    $contenido = isset($_POST['contenido']) ? mysqli_real_escape_string($con, $_POST['contenido']) : false;
    $categoria = isset($_POST['categoria']) ? (int) $_POST['categoria'] : false;
    $imagen = $_FILES['imagen'];


    if (isset($imagen) && $imagen != "") {
      $tipo = $_FILES['imagen']['type'];
      $tamano = $_FILES['imagen']['size'];
      $directorio_imagenes = "images/" . basename($_FILES['imagen']['name']);
    
      if (!((strpos($tipo, "gif") || strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 2000000))) {
        echo "<div class = 'alert-danger'><b>Error. La extensión o el tamaño de los archivos no es correcta.<br/>
          - Se permiten archivos .gif, .jpg, .png. y de 200 kb como máximo.</b></div>";
      } else {
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $directorio_imagenes)) {
          chmod('images/' . $imagen, 0777);
          $imagen_nombre = $_FILES['imagen']['name'];
    
          $stmt = mysqli_prepare($con, "INSERT INTO publicaciones (id, id_usuario, titulo, contenido, categoria_id, imagen) VALUES(NULL, ?, ?, ?, ?, ?)");
          mysqli_stmt_bind_param($stmt, "issis", $id_usuario, $titulo, $contenido, $categoria, $imagen_nombre);
    
          if (mysqli_stmt_execute($stmt)) {
            $path = "../../public/index.php";
            header("Location: $path");
          } else {
            echo "Error al crear la publicación";
          }
        }
      }
    
    }
  }


}
/**

*Elimina una publicación específica de la base de datos.
*@access public
*@param mysqli $db Conexión a la base de datos
*@return void Redirige al usuario a la página "gestionar_publicaciones.php" en caso de éxito,
*o devuelve un mensaje de error si la operación no pudo ser completada.
*/

function eliminar_publicacion($db){
  if (isset($_GET['id'])){
    $id = (int) $_GET['id'];
    $stmt = mysqli_prepare($db, "DELETE FROM publicaciones WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
      header("Location: gestionar_publicaciones.php");
    } else {
      echo "Error al eliminar la publicación";
    }
  }
}

/**
 * Edita una publicación existente en la base de datos.
 * La función valida que el usuario esté logueado y sea el autor de la publicación, 
 * y que los campos de texto (título y contenido) no estén vacíos.
 * Si se ha recibido una imagen nueva, se valida que sea una imagen válida en cuanto a su extensión y tamaño,
 * y se reemplaza la imagen anterior en la base de datos y en el directorio de imágenes.
 * @access public
 * @param mysqli $db Conexión a la base de datos.
 * @return void Redirige al usuario a la página de inicio si la edición se ha realizado correctamente.
*/

function editar_publicacion($db){
  if(isset($_POST['editar_publicacion'])){
    session_start();
    $id_publicacion = isset($_POST['id_publicacion']) ? mysqli_real_escape_string($db, $_POST['id_publicacion']) : false;
    $titulo = isset($_POST['titulo']) ? mysqli_real_escape_string($db, $_POST['titulo']) : false;
    $contenido = isset($_POST['contenido']) ? mysqli_real_escape_string($db, $_POST['contenido']) : false;
    $categoria = isset($_POST['categoria']) ? (int) $_POST['categoria'] : false;
    $imagen_nueva = $_FILES['imagen'];
    $id_usuario = $_SESSION['id'];

    $publicacion_actual = obtenerUnaPublicacion($db,$id_publicacion);

    if($titulo === "" || $contenido === ""|| $categoria == ""){
        echo "<div class = 'alert-danger'><b>Error. Ningún campo de texto puede estar vacío</b></div>";
    }
    else{
        $sql = "UPDATE publicaciones SET titulo=?, contenido=?, imagen = ?, categoria_id=? WHERE id = ? AND id_usuario = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, 'sssiis', $titulo, $contenido, $publicacion_actual['imagen'], $categoria, $id_publicacion, $id_usuario);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../../public/index.php");
        }
    }
    if (isset($imagen_nueva) && $imagen_nueva != "") {
        //Obtenemos algunos datos necesarios sobre la imagen
        $tipo = $_FILES['imagen']['type'];
        $tamano = $_FILES['imagen']['size'];
        $directorio_imagenes = "images/". basename($_FILES['imagen']['name']); 
      //Validamos que el archivo recibido sea una imagen
        if (!((strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 2000000))) 
        {
            echo "<div class = 'alert-danger'><b>Error. La extensión o el tamaño de los archivos no es correcta.<br/>
              - Se permiten archivos .jpeg, .jpg, .png. y de 200 kb como máximo.</b></div>";
        } else {
            $imagen_publicacion = $publicacion_actual['imagen'] ;
            //Eliminamos la imagen anterior del directorio donde guardamos las imagenes
            unlink("images/$imagen_publicacion"); 
            //Si la imagen es correcta en tamaño y tipo
            //Se intenta subir al servidor y también se guarda en el directorio de images
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $directorio_imagenes)) 
            {
                $imagen_nueva_nombre = $_FILES['imagen']['name'];
                $sql = "UPDATE publicaciones SET titulo=?, contenido=?, imagen = ?, categoria_id=? WHERE id = ? AND id_usuario = ?";
                $stmt = mysqli_prepare($db, $sql);
                mysqli_stmt_bind_param($stmt, 'sssiis', $titulo, $contenido, $imagen_nueva_nombre, $categoria, $id_publicacion, $id_usuario);
                if (mysqli_stmt_execute($stmt)) {
                    header("Location: ../../public/index.php");
                }
            }
          
      }


  }

  }
  

}

/**
 * Obtiene una categoría específica de la tabla 'categorías' de la base de datos.
 * @access public
 * @param mysqli $con Conexión a la base de datos
 * @param int $id_categoria ID de la categoría a obtener
 * @return array Devuelve un array vacío si no hay resultados, o un array asociativo con los detalles de la categoría encontrada
 */
function obtenerCategoria($con, $id_categoria){
  $id_categoria = mysqli_real_escape_string($con, $id_categoria);
  $sql = "SELECT * FROM categorias where id = ?";
  
  $consulta_preparada = mysqli_prepare($con, $sql);
  mysqli_stmt_bind_param($consulta_preparada, "i", $id_categoria);
  mysqli_stmt_execute($consulta_preparada);
  $categoria = mysqli_stmt_get_result($consulta_preparada);
  
  $resultado = array();

  if($categoria && mysqli_num_rows($categoria) >= 1){
    $resultado = mysqli_fetch_assoc($categoria);
  }
  mysqli_stmt_close($consulta_preparada);
  return $resultado;

}


/**
*Obtiene todas las categorías existentes en la tabla "categorías" de la base de datos.
*@param mysqli $con Una conexión abierta a la base de datos.
*@return array|mysqli_result Un objeto mysqli_result con todas las categorías encontradas o un array vacío en caso de no encontrar resultados.
*/
function obtenerCategorias($con){
  $sql = "SELECT *FROM categorias ORDER BY id ASC;";
  $categorias = mysqli_query($con, $sql);
  $resultado = array();

  if($categorias && mysqli_num_rows($categorias) >= 1)
  {
      $resultado = $categorias;
  }
  return $resultado;
}

/**

*Obtiene todos los usuarios de la tabla 'usuarios' de la base de datos.
*@access public
*@param mysqli $db Conexión a la base de datos
*@return array|mysqli_result Devuelve un array con los datos de todos los usuarios de la base de datos. En caso de no haber usuarios, el array estará vacío.
*/
function ver_usuarios($db)
{
  $sql = "SELECT * FROM usuarios ORDER BY id ASC";
  $consulta_preparada = mysqli_prepare($db, $sql);
  mysqli_stmt_execute($consulta_preparada);
  $usuarios = mysqli_stmt_get_result($consulta_preparada);
  $resultado = array();

  if(mysqli_num_rows($usuarios) >= 1)
  {
    $resultado = $usuarios;
  }

  mysqli_stmt_close($consulta_preparada);
  return $resultado;
}


/**
 * Obtiene un usuario de la base de datos mediante su ID.
 *@access public
 * @param mysqli $con Una conexión a la base de datos.
 * @param int $id_usuario El ID del usuario a obtener.
 * @return mysqli_result|array Un arreglo con los datos del usuario.
 */
function obtener_usuario($con, $id_usuario){
  $id_usuario = mysqli_real_escape_string($con, $id_usuario);
  $sql = "SELECT * FROM usuarios where id = ?";
  
  $consulta_preparada = mysqli_prepare($con, $sql);
  mysqli_stmt_bind_param($consulta_preparada, "i", $id_usuario);
  mysqli_stmt_execute($consulta_preparada);
  $usuario = mysqli_stmt_get_result($consulta_preparada);
  
  $resultado = array();

  if($usuario && mysqli_num_rows($usuario) >= 1){
    $resultado = mysqli_fetch_assoc($usuario);
  }
  mysqli_stmt_close($consulta_preparada);
  return $resultado;
}

/**
 * Elimina un usuario de la base de datos mediante su ID.
 *@access public
 * @param mysqli $db Una conexión a la base de datos.
 * @return void si todo va bien redirige al usuario administrador a la página donde se gestionan los usuarios
 */
function eliminar_usuario($db){
  if (isset($_GET['id'])){
      $id = $_GET['id'];
      $consulta_preparada = mysqli_prepare($db, "DELETE  FROM usuarios WHERE id = ?");
      mysqli_stmt_bind_param($consulta_preparada, "i", $id);
      mysqli_stmt_execute($consulta_preparada);
      if (mysqli_stmt_affected_rows($consulta_preparada) > 0) 
      {
          header("Location: gestionar_usuarios.php");
      } 
      mysqli_stmt_close($consulta_preparada);
  }

}


/**
*Edita los datos de un usuario en la base de datos conociendo su ID.
*@param mysqli $db Conexión a la base de datos.
*@return void Redirige al usuario administrador a la página de gestión de usuarios en caso de éxito.
*/
function editar_usuario($db){
  if(isset($_POST['editar_usuario']))
  {
    $id_usuario = isset($_POST['id_usuario']) ? mysqli_real_escape_string($db, $_POST['id_usuario']) : false;
    $nombre = isset($_POST['nombre']) ? mysqli_real_escape_string($db, $_POST['nombre']) : false;
    $apellidos = isset($_POST['apellidos']) ? mysqli_real_escape_string($db, $_POST['apellidos']) : false;
    $password = isset($_POST['password']) ? mysqli_real_escape_string($db, $_POST['password']) : false;
    $correo = isset($_POST['correo']) ? mysqli_real_escape_string($db, $_POST['correo']) : false;
    $rol = isset($_POST['rol']) ? mysqli_real_escape_string($db, $_POST['rol']) : false;

    $password_segura = password_hash($password, PASSWORD_BCRYPT, ['cost' => 4]);

    


    if (empty($nombre) || is_numeric($nombre) || empty($apellidos) || is_numeric($apellidos) || preg_match("/[0-9]/", $apellidos) ||  preg_match("/[0-9]/", $nombre)){
        echo "<div class='alert-danger'><b>Error.</b></div>";
    }
    else{
        $sql = "UPDATE usuarios SET nombre='$nombre', apellidos='$apellidos', password = '$password_segura', correo='$correo', rol = '$rol'
                    WHERE id = '$id_usuario'";

                $guardar = mysqli_query($db, $sql);
                if ($guardar) {
                    header("Location: gestionar_usuarios.php");

                }
    }
  


  }

}
  
/**
 * Función para crear un usuario como administrador
 * @access public
 * @param mysqli $con Una conexión a la base de datos.
 * @return void Redirige al usuario administrador a la página de gestión de usuarios para verificar si el usuario se ha creado correctamente.
 */
function crear_Usuario_admin($con)
{
  if (isset($_POST['crear_usuario_admin'])) {

    // Recogemos los valores del formulario.
    $nombre = isset($_POST['nombre']) ? mysqli_real_escape_string($con, $_POST['nombre']) : false;
    $apellidos = isset($_POST['apellidos']) ? mysqli_real_escape_string($con, $_POST['apellidos']) : false;
    $correo = isset($_POST['correo']) ? mysqli_real_escape_string($con, $_POST['correo']) : false;
    $password = isset($_POST['password']) ? mysqli_real_escape_string($con, $_POST['password']) : false;
    $rol =  isset($_POST['rol']) ? mysqli_real_escape_string($con, $_POST['rol']) : false;

    if (!empty($nombre) && !is_numeric($nombre) && !preg_match("/[0-9]/", $nombre) && !empty($apellidos) && !is_numeric($apellidos) && !preg_match("/[0-9]/", $apellidos) && $rol === "Administrador" || $rol === "Normal") 
    {
         // Ciframos la contraseña con bcrypt y le damos un valor de 4 para que la cifre 4 veces
         $password_segura = password_hash($password, PASSWORD_BCRYPT, ['cost' => 4]);

         //Insertamos los datos a la BD con mysqli_stmt_execute
         $sql =  "INSERT INTO usuarios (nombre, apellidos, password, correo, rol) VALUES (?, ?, ?, ?, 'Normal')";
         $stmt = mysqli_prepare($con, $sql);
         mysqli_stmt_bind_param($stmt, "ssss", $nombre, $apellidos, $password_segura, $correo);
         mysqli_stmt_execute($stmt);
         $guardar = mysqli_stmt_affected_rows($stmt);
   
         if ($guardar) {
          header("Location: gestionar_usuarios.php");
         }
    }
    else
    {
      echo "<div class = 'alert-danger'>Error al crear usuario</div>";
    }
  }
}

/**

*Función para eliminar una categoría por su ID
*@access public
*@param mysqli $db Conexión a la base de datos.
*@return void Redirige al usuario administrador a la página de gestión de categorías para verificar si la categoría se ha eliminado correctamente.
*/

function eliminar_categoria($db){
  if (isset($_GET['id'])){
    $id = $_GET['id'];
    $consulta_preparada = mysqli_prepare($db, "DELETE  FROM categorias WHERE id = ?");
    mysqli_stmt_bind_param($consulta_preparada, "i", $id);
    mysqli_stmt_execute($consulta_preparada);
    if (mysqli_stmt_affected_rows($consulta_preparada) > 0) 
    {
        header("Location: gestionar_categorias.php");
    } else {
        echo "Error al eliminar categoría";
    }
    mysqli_stmt_close($consulta_preparada);
}
}


/**
*Función para actualizar datos de una categoría en la base de datos
*La función toma los valores recibidos del formulario de edición de categoría y los valida.
*Si los valores cumplen con las validaciones, se actualizan en la base de datos y se redirige al usuario administrador a la página donde se gestionan las categorías.
*@param mysqli $db Un objeto de conexión a la base de datos.
*@return void redirige al usuario administrador a la página de gestión de categorías para ver si los datos de la categoría se han actualizado correctamente.
*/
function editar_categoria($db){
  if(isset($_POST['editar_categoria']))
  {
    $id_categoria = isset($_POST['id_categoria']) ? mysqli_real_escape_string($db, $_POST['id_categoria']) : false;
    $nombre_categoria = isset($_POST['nombre_categoria']) ? mysqli_real_escape_string($db, $_POST['nombre_categoria']) : false;

    if (empty($nombre_categoria) || is_numeric($nombre_categoria) ||  preg_match("/[0-9]/", $nombre_categoria)){
        echo "<div class='alert-danger'><b>Error.</b></div>";
    }
    else{
        $sql = "UPDATE categorias SET nombre=? WHERE id = ?";
        //Inicializa sentencia y devuelve un objeto
        $stmt = mysqli_stmt_init($db);

        //Prepara sentencia
        if (mysqli_stmt_prepare($stmt, $sql)){
            //Asocia los valores recibidos del formulario a los interrogantes y define el tipo de valor
            mysqli_stmt_bind_param($stmt, "si", $nombre_categoria, $id_categoria);
            //Ejecuta la consulta preparada
            mysqli_stmt_execute($stmt);

            header("Location: gestionar_categorias.php");
        }
    }
  }
}



/**
 * Función para crear una categoría
 * @param mysqli $con Una conexión a la base de datos.
 * @return void redirige al usuario administrador a la página donde se gestionan las categorías para verificar si la categoría se ha creado correctamente. En caso contrario, se muestra un mensaje de error.
*/
function crear_categoria($con)
{
  if (isset($_POST['crear_categoria'])) 
  {

    // Recogemos los valores del formulario.
    $nombre = isset($_POST['nombre_categoria']) ? mysqli_real_escape_string($con, $_POST['nombre_categoria']) : false;
 

    if (!empty($nombre) && !is_numeric($nombre) && !preg_match("/[0-9]/", $nombre)) {
      $stmt = mysqli_prepare($con, "INSERT INTO categorias (id, nombre) VALUES (NULL, ?)");
      mysqli_stmt_bind_param($stmt, "s", $nombre);
      mysqli_stmt_execute($stmt);
      if (mysqli_stmt_affected_rows($stmt) > 0) {
          header("Location: gestionar_categorias.php");
      } else {
          echo "<div class = 'alert-danger'>Error al crear categoría</div>";
      }
      mysqli_stmt_close($stmt);
  } else {
      echo "<div class = 'alert-danger'>Error al crear categoría</div>";
  }
}
}
    
?>