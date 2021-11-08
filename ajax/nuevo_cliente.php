<?php
	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/*Inicia validacion del lado del servidor*/
	if (empty($_POST['nombre'])) {
           $errors[] = "Nombre vacío";
        } else if (!empty($_POST['nombre'])){
		/* Connect To Database*/
		require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
		require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
		// escaping, additionally removing everything that could be (html/javascript-) code
        $id = mysqli_real_escape_string($con,(strip_tags($_POST["id_c"],ENT_QUOTES)));
        
		$nombre=mysqli_real_escape_string($con,(strip_tags($_POST["nombre"],ENT_QUOTES)));
		$telefono=mysqli_real_escape_string($con,(strip_tags($_POST["telefono"],ENT_QUOTES)));
		//$email=mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));
		$direccion=mysqli_real_escape_string($con,(strip_tags($_POST["direccion"],ENT_QUOTES)));
		
        $rfc = mysqli_real_escape_string($con,(strip_tags($_POST["rfc"],ENT_QUOTES)));
            
        $contacto = mysqli_real_escape_string($con,(strip_tags($_POST["contacto"],ENT_QUOTES)));    
        
        $credito = mysqli_real_escape_string($con,(strip_tags($_POST["credito"],ENT_QUOTES))); 
        
		$date_added=date("Y-m-d H:i:s");
        
		$sql="INSERT INTO `clientes` (`id_cliente`, `nombre_cliente`, `telefono_cliente`, `direccion_cliente`, `rfc`, `contacto`, `date_added`, `deuda_c`, `credito`) VALUES ('$id', '$nombre', '$telefono', '$direccion', '$rfc', '$contacto', '$date_added', 'NULL', '$credito')";
        
		$query_new_insert = mysqli_query($con,$sql);
			if ($query_new_insert){
				$messages[] = "Cliente ha sido ingresado satisfactoriamente.";
			} else{
				$errors []= "Algo ha salido mal, revise la información e intente nuevamente. ".mysqli_error($con);
			}
		} else {
			$errors []= "Error desconocido.";
		}
		
		if (isset($errors)){
			
			?>
			<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong> 
					<?php
						foreach ($errors as $error) {
								echo $error;
							}
						?>
			</div>
			<?php
			}
			if (isset($messages)){
				
				?>
				<div class="alert alert-success" role="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>¡Bien hecho!</strong>
						<?php
							foreach ($messages as $message) {
									echo $message;
								}
							?>
				</div>
				<?php
			}

?>