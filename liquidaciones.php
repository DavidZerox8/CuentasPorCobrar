<?php
    
    require_once 'config/db.php';
    require 'config/conexion.php';
    
    $tipo = implode(",", $_POST['tipo']);
    $factura = implode(",", $_POST['factura']);

    if($tipo == 'Liquidacion'){
        $query1 = "select sum(monto) as suma from pagos where documento = '$factura'";
        $query2 =  "select monto from cuentas_pc where n_factura = '$factura'";  
        
        $q1 = mysqli_query($con, $query1);
        foreach ($q1 as $x):
            $suma = $x['suma'];
        endforeach;
        
        
        $q2 = mysqli_query($con, $query2);
        foreach ($q2 as $x):
            $monto = $x['monto'];
        endforeach;
        
        echo $monto-$suma; 
    }

    else{
        
        echo "0.00";
    }

?>