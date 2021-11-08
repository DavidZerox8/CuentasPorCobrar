<?php
    require_once 'config/db.php';
    require 'config/conexion.php';
    $html = '';

    $concepto = $_POST['elegido'];
    
    if(!empty($concepto)){
        $sql = "select tipo_concepto from conceptos where id_concepto = '$concepto'";

        $result = mysqli_query($con, $sql);

        if($result){
            
            foreach ($result as $r):
                $tipo = $r['tipo_concepto'];
            endforeach;

            echo $tipo;
        }
        
        else{
            echo "Ocurrio un error, intentelo de nuevo.";
        }
    }

    else{
        echo "Ocurrio un error, revise la información e intentelo de nuevo.";
    }


?>
