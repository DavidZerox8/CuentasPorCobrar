<?php 

    require_once 'config/db.php';
    require 'config/conexion.php';

    $facturas = $_POST['valores'];
    $cadena = implode(",", $facturas);
    $i = 0;
    $longitud = count($facturas);
    $arr = [];

    $suma = 0;
    while($i < $longitud){
        
        $total = "select monto from cuentas_pc where n_factura = '$facturas[$i]'";
        $query = mysqli_query($con, $total);
        
        foreach ($query as $q):
            $suma += $q['monto'];
        endforeach;
        $i++;
    }
    
    $res = $cadena.";".$suma;
    
    echo $res; 

?> 