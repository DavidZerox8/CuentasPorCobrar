<?php
    $sql = "";
    $procededer = false;
    error_reporting(0);

	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/*Inicia validacion del lado del servidor*/
	if (empty($_POST['cliente_c']) and empty($_POST['cliente_cc'])) {
           $errors[] = "Cliente vacío";
        } 
            elseif(!empty($_POST['cliente_c']) and !empty($_POST['cliente_cc']) and $_POST['cliente_c'] != $_POST['cliente_cc']){
                $errors[] = "Las claves no coinciden, favor de revisar la información del cliente";
            }

        else if (!empty($_POST['cliente_c']) or !empty($_POST['cliente_cc'])){
		/* Connect To Database*/
		require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
		require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
        
		// escaping, additionally removing everything that could be (html/javascript-) code
        
        if(!empty($_POST['cliente_c'])){
            $cliente=mysqli_real_escape_string($con,(strip_tags($_POST["cliente_c"],ENT_QUOTES)));
        }
        
        elseif(!empty($_POST['cliente_cc'])){
            $cliente=mysqli_real_escape_string($con,(strip_tags($_POST["cliente_cc"],ENT_QUOTES)));
        }
        
		$concepto=mysqli_real_escape_string($con,(strip_tags($_POST["concepto"],ENT_QUOTES)));
		$factura=mysqli_real_escape_string($con,(strip_tags($_POST["factura"],ENT_QUOTES)));    
        $fecha_ap=mysqli_real_escape_string($con,(strip_tags($_POST["fecha_ap"],ENT_QUOTES)));
        $fecha_ven=mysqli_real_escape_string($con,(strip_tags($_POST["fecha_ven"],ENT_QUOTES)));
        $monto=mysqli_real_escape_string($con,(strip_tags($_POST["monto"],ENT_QUOTES)));
        $observaciones=mysqli_real_escape_string($con,(strip_tags($_POST["obs"],ENT_QUOTES)));    
        
		$date_added=date("d-m-Y");        
        $valor_r = "";
           
        $valor = "";
        $query = "select deuda_c as deuda from clientes where id_cliente = '$cliente'";
        $result = mysqli_query($con, $query);
                   
        if($result){
            foreach ($result as $r): 

            $valor = $r['deuda'];
            endforeach; 
        }
        
        else{
            echo "Error desonocido";
        }
        
        $update = "";
        $res_t = "";
        $consultar_tipo_c = "select tipo_concepto as tipo from conceptos where id_concepto = '$concepto'";
        $result_c = mysqli_query($con, $consultar_tipo_c);
        
        if($result_c){
            foreach ($result_c as $r): 

            $res_t = $r['tipo'];
            endforeach; 
        }
        
        else{
            echo "Error desonocido";
        }
        
        if($res_t == "Abono"){
            if(is_numeric($valor) and is_numeric($monto)){
                $suma = $valor;
                
                $findme   = ',';
                $pos = strpos($factura, $findme);
                if ($pos === false) {
        
                    $sql = "INSERT INTO `abonos` (`id_cliente`, `monto`, `concepto`, `factura`, `fecha_pago`, `Fecha_reg`, `observaciones`) VALUES ('$cliente', '$monto', '$concepto', '$factura', '$fecha_ap', '$date_added', '$observaciones')";
                }
            
                else{
                    
                    $documentos = explode(",",$factura);
                    $arr = [];
                    $uno = 0;
                    $dos = 0;
                    $x = 0;
                    $longitud = count($documentos);
                    $a = 0; //la deuda todal de la cpc
                    $b = 0; //los abonos totales de la facutura
                    $c = 0; //la diferencia entre ambas cantidades
                    
                    while($x < $longitud){
                        
                        $deuda = "select monto as deuda from cuentas_pc where n_factura = '$documentos[$x]'";
                        $res = mysqli_query($con, $deuda);
                        //inicio del ciclo para obtener las cantidades
                        foreach ($res as $r): 

                            $a += $r['deuda'];
                            $uno = $r['deuda'];
                        
                        endforeach; 
                        
                        $pagos = "select sum(monto) as pagos from abonos where factura = '$documentos[$x]'";
                        $res = mysqli_query($con, $pagos);
                        
                        foreach ($res as $p): 

                            $b += $p['pagos'];
                            $dos = $p['pagos'];
                        
                        endforeach;
                        $arr[$x] = $uno - $dos;
                        $x++;    
                    }
                    
                    $c = $a - $b;
                    if($c == $monto){
                        $x = 0;
                        while($x < $longitud)
                        {
                            $actualizar = "update cuentas_pc set estatus = 'Pagado' where n_factura = '$documentos[$x]'";
                            $agregar_pago = "INSERT INTO `abonos` (`id_cliente`, `monto`, `concepto`, `factura`, `fecha_pago`, `Fecha_reg`, `observaciones`) VALUES ('$cliente', '$arr[$x]', '$concepto', '$documentos[$x]', '$fecha_ap', '$date_added', '$observaciones')";
                            
                            $exx = mysqli_query($con, $agregar_pago);
                            $ex = mysqli_query($con, $actualizar);
                            if($exx and $ex){
                                $procededer = true;
                            }
                            
                            else{
                                $procededer = false;
                            }
                            
                            $x++;
                        }
                    }
                    elseif($monto < $c){
                       $errors[] = "El monto ingresado es menor a la deuda, favor de verificar. <br>";
                        
                        /* $x = 0;
                        while($x < $longitud)
                        {
                            
                            $agregar_pago = "INSERT INTO `abonos` (`id_cliente`, `monto`, `concepto`, `factura`, `fecha_pago`, `Fecha_reg`, `observaciones`) VALUES ('$cliente', '$arr[$x]', '$concepto', '$documentos[$x]', '$fecha_ap', '$date_added', '$observaciones')";
                            
                            $exx = mysqli_query($con, $agregar_pago);
                            if($exx){
                                $procededer = true;
                            }
                            
                            else{
                                $procededer = false;
                            }
                            
                            $x++;
                        } */
                    }
                    
                    else{
                        $errors[] = "El monto ingresado es mayor a la deuda, favor de verificar. <br>";
                    }
                    
                    
                }    

                    $valor_r = "El Abono";                
                }
            else{
                $errors[] = "Clave de cliente erronea, favor de verificar. <br>";
            }
            
        }
        // fin de los abonos
        elseif($res_t == "Cargo"){
                $valor_r = "La CPC";
                    
                if(is_numeric($valor) and is_numeric($monto)){
                $suma = $valor+$monto;
            
                $sql="INSERT INTO `cuentas_pc` (`id_cliente`, `n_concepto`, `n_factura`, `fecha_aplicacion`, `fecha_vencimiento`, `monto`, `observaciones`) VALUES ('$cliente', '$concepto', '$factura', '$fecha_ap', '$fecha_ven', '$monto', '$observaciones')";
                    
                $update = "UPDATE `clientes` SET `deuda_c` = '$suma' WHERE `clientes`.`id_cliente` = '$cliente'";    
            }
            else
            {
               $errors[] = "Clave de cliente erronea, favor de verificar. <br>";
            }
            
        }
        
        
		$query_new_insert = mysqli_query($con,$sql);
			
        if ($query_new_insert or $procededer === true){
            
                if($res_t == "Abono"){
                    
                    $sumatoria = "select sum(monto) as suma from abonos where factura = '$factura'";
                    $res = mysqli_query($con,$sumatoria);
                    $rows = $res->fetch_assoc();
                    $total = $rows['suma']; //total de pagos. 
                
                    $deuda = "select monto from cuentas_pc where n_factura = '$factura'";
                    $q = mysqli_query($con,$deuda);
                    $row = $q->fetch_assoc();
                    $monto_t = $row['monto']; //total de la deuda correspondondiente a la factura;
                
                
                            $findme   = '.';
                            $pos = strpos($total, $findme);
                            if ($pos === false) {
        
                                $total = number_format($total, 2, '.', ''); 
                            }
                
                            $findme   = '.';
                            $pos = strpos($monto_t, $findme);
                            if ($pos === false) {
        
                                $monto_t = number_format($monto_t, 2, '.', ''); 
                            }
                
                    $final = $monto_t - $total;
                
                    if($final <= 0 )
                    {
                        $update = "update cuentas_pc set estatus = 'Pagado' where n_factura = '$factura'";
              
                    }
                }
                
                $query_new_update = mysqli_query($con,$update);
				$messages[] = $valor_r." se registró satisfactoriamente.";
            
			} else{
				$errors []= "Lo siento, algo ha salido mal, intentalo nuevamente. ".mysqli_error($con);
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
                                    echo $$sql;
								}
							?>
</div>
<?php
			}

?>
