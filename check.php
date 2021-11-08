<?php 
    
    $cliente = $_POST['elegido'];

    $html = '';
    require_once 'config/db.php';
    require 'config/conexion.php';
            
    $query = "select id_cpc as ID, n_factura as factura, monto as total from cuentas_pc where id_cliente = '$cliente' and n_concepto != '4' and estatus != 'Pagado' and estatus != 'Documentada'";
    $result = mysqli_query($con, $query);
         
    foreach ($result as $r): 
                
        $html .= '<input type="checkbox" name="datos[]" id="datos[]" value="'.$r["ID"].'" /> '.$r["factura"].' por $'.$r["total"].'<br />';
                
    endforeach; 

    echo $html;

?>

