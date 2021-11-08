<?php
	include('is_logged.php');

	if (empty($_POST['mod_id'])) {
           $errors[] = "ID vacío";
        }else if (empty($_POST['mod_monto'])) {
           $errors[] = "Monto vacío";
        }  else if ($_POST['mod_concepto']==""){
			$errors[] = "Ingrese el documento correspondiente, por favor";
		}  else if (
			!empty($_POST['mod_id']) &&
			!empty($_POST['mod_concepto']) &&
			$_POST['mod_monto']!="" 
		){
	
		require_once ("../config/db.php");
		require_once ("../config/conexion.php");
		
        
        $idcliente = mysqli_real_escape_string($con,(strip_tags($_POST["mod_cliente"],ENT_QUOTES)));
        $nconcepto = mysqli_real_escape_string($con,(strip_tags($_POST["mod_concepto"],ENT_QUOTES)));
        $nfactura = mysqli_real_escape_string($con,(strip_tags($_POST["mod_factura"],ENT_QUOTES)));
        $fechaapp = $_POST['mod_echa_ap'];
        $fechaven = $_POST['mod_fecha_ven'];
        $monto = mysqli_real_escape_string($con,(strip_tags($_POST["mod_monto"],ENT_QUOTES)));
        $obs = mysqli_real_escape_string($con,(strip_tags($_POST["mod_obs"],ENT_QUOTES)));
		        		
		$id_cpc=intval($_POST['mod_id']);
        
        $sql = "UPDATE `cuentas_pc` SET `id_cliente` = '$idcliente', `n_concepto` = '$nconcepto', `n_factura` = '$nfactura', `fecha_aplicacion` = '$fechaapp', `fecha_vencimiento` = '$fechaven', `monto` = '$monto', `observaciones` = '$obs' WHERE `cuentas_pc`.`id_cpc` = '$id_cpc'";
        
        
		$query_update = mysqli_query($con,$sql);
			if ($query_update){
				$messages[] = "CPC ha sido actualizada satisfactoriamente.";
			} else{
				$errors []= "Lo siento algo ha salido mal intenta nuevamente.".mysqli_error($con);
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