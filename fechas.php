<?php      

    require_once 'config/db.php';
    require 'config/conexion.php';
    
    $html = '';
    $dias = ''; 
    $bol = 0;
    error_reporting(0);

    $separado_por_comas = implode(",", $_POST['fecha']);
    //obtencion del ID del cliente en cualquiera de las formas. 
    $id_cliente_1 = implode(",", $_POST['id_c']);
    $id_cliente_2 = implode(",", $_POST['id_c2']);
    
    if(empty($id_cliente_1)){
        $id_cliente = implode(",", $_POST['id_c2']);
    }

    elseif(empty($id_cliente_2)){
        $id_cliente = implode(",", $_POST['id_c']);
    }

    elseif($id_cliente_1 === $id_cliente_2){
        $id_cliente = implode(",", $_POST['id_c']);
    }

    else{
        echo "Favor de revisar la clave del cliente. ";
    }
    
    $date1 = new DateTime ($separado_por_comas);

    $query = "select credito from clientes where id_cliente = '$id_cliente'";
    $result = mysqli_query($con, $query);
                           

    foreach ($result as $r): 

        $dias = $r['credito'];

    endforeach;  

    date_add($date1, date_interval_create_from_date_string("".$dias." days"));
    echo date_format($date1,"Y-m-d");

?>