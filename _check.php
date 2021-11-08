<?php

    error_reporting(~E_WARNING & ~E_NOTICE);

    $suma = 0;
    $facturas = "";
    $ids = "";
    $pagos_r = 0;
    
    require_once 'config/db.php';
    require 'config/conexion.php';
    
    $suma = 0;
    if($_POST){
    
    if(isset($_POST['datos'])){ 
        
        //Factuas que se eligieron para documentar
        $arrletters = $_POST['datos']; 
        //fecha de vencimiento 
        $fecha_ven = $_POST['fecha_ven2'];
        //fecha de aplicacion 
        $fecha_ap = $_POST['fecha_ap2'];
        //id del dliente
        $id_cliente = $_POST['cliente'];
        //onservaciones 
        $obs = $_POST['obs2'];
        //factura
        $factura = $_POST['factura'];
        
        
        $cadena = implode($arrletters);
        $delimitador = strlen($cadena);
        
        $i = 0;
        while ($i < count($arrletters))
        {            
            
            $sql = "select id_cliente, n_factura, monto, estatus from cuentas_pc where id_cpc = '$arrletters[$i]'";
            $result = mysqli_query($con, $sql);
            if($result){
                
                $row = mysqli_fetch_array($result);
                
                $ids .= $row[0]." ";
                if($i == 0){
                    $facturas .= $row[1];
                }
                
                else{
                    $facturas .= " / ".$row[1];
                }
                $suma += $row[2];
                
                    $pagos = "select monto from abonos where factura = '$row[1]'";
                    $ex = mysqli_query($con, $pagos);
                
                    $rows = mysqli_fetch_array($ex);
                    $pagos_r += $rows[0]; 
                
                $update = "update cuentas_pc set estatus = 'Documentada' where n_factura = '$row[1]'";
                $ucpc = mysqli_query($con, $update); 
            
            }
            
            else{ //Se cierra el ciclo en caso de que la consulta no sea valida
                echo "algo salio mal, intententalo de nuevo."; 
                exit;
            }
            
            $i++;
        }

        $obs_f = $obs." Las facturas que incluye esta CPC son: ".$facturas;               
        $total = $suma - $pagos_r;

        
        $insert = "INSERT INTO `cuentas_pc` (`id_cliente`, `n_concepto`, `n_factura`, `fecha_aplicacion`, `fecha_vencimiento`, `monto`, `observaciones`, `estatus`) VALUES ('$id_cliente', '4', '$factura', '$fecha_ap', '$fecha_ven', '$total', '$obs_f', 'Pendiente')";
        
        $res = mysqli_query($con, $insert);
        if($res){
            echo "Documentción completada.";
        }
        else{
            echo 'No se han enviado parametros.';
        }
        
    }
    else{
        echo 'Favor de llenar todos los campos';
    }
}
    else{
        echo 'No se han enviado parametros.';
       
    }

?>