<?php
    require_once 'config/db.php';
    require 'config/conexion.php';
    if($con)
    {
        $id_cpc = $_POST['id_cpc'];
        
        $factura = "select * from cuentas_pc where id_cpc = '$id_cpc'";
        $result = mysqli_query($con, $factura);

        foreach ($result as $r):
            
            $user = $r['id_cliente'];
            $concepto = $r['n_concepto'];
            $fac = $r['n_factura'];
            $fecha_aplicacion = $r['fecha_aplicacion'];
            $fecha_vencimiento = $r['fecha_vencimiento'];
            $total = $r['monto'];
            $obs = $r['observaciones'];
            $estatus = $r['estatus'];
        
        endforeach;
        
        if($estatus != 'Documentada')
        {
            $pagos = "select sum(monto) as suma from abonos where factura = '$fac'";
            $suma = mysqli_query($con,$pagos);
        
            foreach ($suma as $x):
                $sum = $x['suma'];
            endforeach;
        
        
            $deuda_t = "select deuda_c from clientes where id_cliente = '$user'";
            $query = mysqli_query($con,$deuda_t);
        
            foreach ($query as $z):
                $deuda_c = $z['deuda_c'];
            endforeach;
        
            $monto_final = $total - $sum;  
            $fac .= "_";
        
            $documentar = "UPDATE `cuentas_pc` SET `estatus` = 'Documentada' WHERE `cuentas_pc`.`id_cpc` = '$id_cpc'";
            $insert = "INSERT INTO `cuentas_pc` (`id_cliente`, `n_concepto`, `n_factura`, `fecha_aplicacion`, `fecha_vencimiento`, `monto`, `observaciones`, `estatus`) VALUES ('$user', '4', '$fac', '$fecha_aplicacion', '$fecha_vencimiento', '$monto_final', '$obs', 'Pendiente')";
        
        
            $query_d = mysqli_query($con, $documentar);
            $query_i = mysqli_query($con, $insert);

        
            if($query_d and $query_i)
            {
                echo "Se documento la factura con éxito";
            }
        
            else{
                echo "Hubo un error, intentelo de nuevo";
            }
        }
        
        else{
            echo "Esta factura ya esta documentada";
        }
    }

    else
    {
        echo "Hubo un error al generar la solicitud, intentelo de nuevo más tarde";
    }

?>