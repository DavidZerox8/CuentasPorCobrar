<?php

    $movimiento = $_POST['operacion'];
    $cliente = $_POST['cliente_m'];

    if(!empty($movimiento) and !empty($cliente)){
     
        if($movimiento == "1"){
            
        }
        elseif($movimiento == "2"){
                
        }
        
        elseif($movimiento == "3"){

        }
        
        else{
            echo"
        <script>
            alert('Sucedio un error inesperado, favor de revisar la información e intentar de nuevo');
            window.close();
        </script>
        ";
        }
    }

    else{
        echo"
        <script>
            alert('Sucedio un error inesperado, favor de revisar la información e intentar de nuevo');
            window.close();
        </script>
        ";
        
    }

?>
