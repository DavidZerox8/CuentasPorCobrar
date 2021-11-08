<?php  

    require_once 'config/db.php';
    require 'config/conexion.php';
    $dias = ''; 

    $fechan = implode(",", $_POST['fecha']);
    
    $id_cliente = implode(",", $_POST['id_c']);
    
    $date1 = new DateTime ($fechan);

    $query = "select credito from clientes where id_cliente = '$id_cliente'";
    $result = mysqli_query($con, $query);
                           

    foreach ($result as $r): 

        $dias = $r['credito'];

    endforeach;  

    date_add($date1, date_interval_create_from_date_string("".$dias." days"));
    echo date_format($date1,"Y-m-d");

?>