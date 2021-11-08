<?php
    date_default_timezone_set('America/Mexico_City');

	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/*Inicia validacion del lado del servidor*/
	if (empty($_POST['documento'])) {
           $errors[] = "Documento vacío";
        } else if (!empty($_POST['documento'])){
		/* Connect To Database*/
		require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
		require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
		// escaping, additionally removing everything that could be (html/javascript-) code
		$cliente=mysqli_real_escape_string($con,(strip_tags($_POST["cliente"],ENT_QUOTES)));
		$monto=mysqli_real_escape_string($con,(strip_tags($_POST["monto"],ENT_QUOTES)));
		$tipo=mysqli_real_escape_string($con,(strip_tags($_POST["tipo"],ENT_QUOTES)));
		$documento=mysqli_real_escape_string($con,(strip_tags($_POST["documento"],ENT_QUOTES)));
		$fecha=mysqli_real_escape_string($con,(strip_tags($_POST["fecha1"],ENT_QUOTES)));
        $fecha2=date("Y-m-d H:i:s");
        
        $concepto = mysqli_real_escape_string($con,(strip_tags($_POST["concepto"],ENT_QUOTES)));
        
		$sql="INSERT INTO `pagos` (`id_cliente`, `Monto`, `Tipo`, `Documento`, `Fecha_pago`, `Fecha_reg`, `p_concepto`) VALUES ( '$cliente', '$monto', '$tipo', '$documento', '$fecha', '$fecha2','$concepto')";                
            
        if($tipo == "Adelanto"){
            $query = "select monto, estatus from cuentas_pc where id_cliente = '$cliente' and n_factura = '$documento'";
            $total_deuda = "select deuda_c from clientes where id_cliente = '$cliente'";
            
            $valor1 = ""; //monto de la cpc
            $valor2 = ""; // deuda del cliente
            $estatus = "";
                        
            if ($resultado = $con->query($query)) {    
                while ($fila = $resultado->fetch_row()) {
                    
                    
                    $valor1 = $fila[0]; //Monto de la cuenta por cobrar
                    $estatus = $fila[1]; //Estatus de la cuenta por cobrar
                }
                
                $nuevo_monto = $valor1 - $monto; 
                
                if($nuevo_monto <= 0)
                {
                     if ($resultados = $con->query($total_deuda)) {    
                         
                         while ($fila = $resultados->fetch_row()) {
                    
                             //deuda     pago
                        $valor2 = $fila[0];
                     }
                    
                    $nueva_deuda = $valor2 - $monto;
                    
                    if($estatus == "Expirado")
                    {
                        $update_cpc = "UPDATE `cuentas_pc` SET `estatus` = 'Pagado' WHERE `cuentas_pc`.`n_factura` = '$documento'";
                    }
                         
                    else{
                        
                        $update_cpc = "UPDATE `cuentas_pc` SET `estatus` = 'Pagado' WHERE `cuentas_pc`.`n_factura` = '$documento'";
                    }     
                    
                    $updatef = "UPDATE `clientes` SET `deuda_c` = '$nueva_deuda' WHERE `clientes`.`id_cliente` = '$cliente'";
                    
                    $query_new_insertup = mysqli_query($con,$update_cpc);
                    $query_new_insertfact = mysqli_query($con,$updatef);
                
                  
                    }
                }
                else{
                    
                    if ($resultados = $con->query($total_deuda)) {    
                         
                         while ($fila = $resultados->fetch_row()) {
                    
                             //deuda     pago
                        $valor2 = $fila[0];
                     }
                    }
                    
                    $nueva_deuda = $valor2 - $monto;
                    
                    $update_cpc = "UPDATE `clientes` SET `deuda_c` = '$nueva_deuda' WHERE `clientes`.`id_cliente` = '$cliente'";
                
                    $query_new_insertup = mysqli_query($con,$update_cpc);
                }
            
            }
        }
        
        elseif ($tipo == "Liquidacion"){
            $update2 = "";
            $query1 = "select deuda_c from clientes where id_cliente = '$cliente'";             
            if ($resultado = $con->query($query1)) {
                
                while ($fila = $resultado->fetch_row()) {
        
                    $valor1 = $fila[0];
                }
                
                $nueva_deuda = $valor1 - $monto;
                
                $update1 = "update clientes set deuda_c = '$nueva_deuda' where id_cliente = '$cliente'";
                
                $query = "select estatus from cuentas_pc where id_cliente = '$cliente' and n_factura = '$documento'";
                if ($resultado = $con->query($query)) {    
                    while ($fila = $resultado->fetch_row()) {
                   
                        $estatus = $fila[0];
                    }
                }
                
                if($estatus == "Expirado"){
                    $update2 = "update cuentas_pc set estatus = 'Pagado' where n_factura = '$documento'";
                }
                
                else
                {
                    $update2 = "update cuentas_pc set estatus = 'Pagado' where n_factura = '$documento'";
                }
                
                $query_new_insert1 = mysqli_query($con,$update1);
                $query_new_insert2 = mysqli_query($con,$update2);
            }
        }
        
        
		$query_new_insert = mysqli_query($con,$sql);
			if ($query_new_insert){
				$messages[] = "El pago ha sido ingresado satisfactoriamente.";
			} else{
				$errors []= "Lo siento algo ha salido mal intenta nuevamente. ".mysqli_error($con);
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
