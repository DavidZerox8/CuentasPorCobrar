<?php
    require_once 'config/db.php';
    require 'config/conexion.php';
    $html = '';

    $cliente = $_POST['elegido'];
    
    if(!empty($cliente)){
        $sql = "select id_cpc as ID, n_factura as cuentas, monto as total from cuentas_pc where id_cliente = '$cliente' and estatus = 'Pendiente' or estatus = 'Expirado'";

        $result = mysqli_query($con, $sql);

        foreach ($result as $r):
            $id = $r['ID'];
            $factura = $r['cuentas'];
            $monto = $r['total'];
            
            $html .= '<input type="checkbox" name="dato[]" id="dato[]" value="'.$factura.'"/> '.$factura.' por $'.$monto.'<br />';
        endforeach;

        echo $html;
    }

?>