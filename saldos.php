<?php
        $n_reporte = utf8_decode("Antigüedad de Saldos");
        if (isset($_GET['id'])) {
            
            //
        }
        
        else{

            $fechas = false;
            $primera = false;
            $segunda = false;    
        
            $r1 =  $_POST['fecha_1']; 
            $r2 =  $_POST['fecha_2'];
        
        if(empty($r1)){
            if(empty($r2)){
                
            }
        }

        else{
            $fechas = true;
            $primera = true;
            if(empty($r2)){
                
            }
            
            else{
               $segunda = true; 
            }
            
        }

        $cliente = $_POST['cliente'];

        if(empty($cliente))
        {
            header("location: facturas.php");
        }

        // select * from cuentas_pc where fecha_aplicacion > "2021-02-03" and fecha_aplicacion < "2021-05-27"
        
        elseif($cliente != '*' and $fechas === false){
            
            include 'header.php';
            require_once 'config/db.php';
            require 'config/conexion.php';
        
            $total = 0;
            $m1_30 = 0;
            $m31_60 = 0;
            $m61_90 = 0;
            $m91_ = 0;
            $corriente = 0;

            $query = "select c.id_cpc as cuenta, c.n_factura as factura, p.monto as monto, c.fecha_vencimiento as fechaven, p.fecha_pago from cuentas_pc c, pagos p, clientes s where s.id_cliente = '$cliente' and c.n_factura = p.Documento and c.estatus != 'Pagado' and c.estatus != 'Documentada'";
        
            $resultado = $con->query($query);
	
            $pdf = new PDF();
            $pdf->AliasNbPages();
            $pdf->AddPage();	
        
            $pdf->SetFillColor(232,232,232);

            $pdf->Cell(30);
            $pdf->Cell(120,10, 'Reporte individual',0,0,'C');
            $pdf->Ln(25);
        
            $query3 = "select * FROM clientes where id_cliente = '$cliente'"; 
            //$resultado2 = $mysqli->query($query3);

            $resultado2 = $con->query($query3);

            while($row = $resultado2->fetch_assoc())
            {   
            
                $nombre = utf8_decode($row['nombre_cliente']); 
		
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(50,7, 'Cliente: '.utf8_decode($row['nombre_cliente']),0,1,'B'); //este si
                //$pdf->Cell(50,7, 'Direccion: '.utf8_decode($row['direccion_cliente']),0,1,'B'); //este si
                $pdf->Cell(50,7, 'Direccion: '.utf8_decode(substr($row['direccion_cliente'],0,83)),0,1,'B');
                $pdf->Cell(50,7,utf8_decode(substr($row['direccion_cliente'],83, -1)),0,1,'B');
                $pdf->Ln(5);


                $pdf->Cell(60,7, 'Contacto: '.utf8_decode($row['contacto']),0,1,'B');        
        
                $pdf->Cell(60,7, 'Fecha de referencia: '.date("d-m-Y"),0,1,'B'); //este si
                //añadir la fecha de hoy qui
            
                $pdf->SetFont('Arial','B',12);
            }
            
            $pdf->Ln(10);
            $pdf->SetFont('Arial','B',12);


            $pdf->Cell(40,10,'Cliente',1,0,'C',1);
            $pdf->Cell(26,10,'Saldo',1,0,'C',1); //Generar un consulta que obtenga la suma de las cpc que estan pendientes y    expiradas. 
            $pdf->Cell(25,10,'Al corriente',1,0,'C',1);
            $pdf->Cell(25,10,'1 - 30',1,0,'C',1);
            $pdf->Cell(25,10,'31 - 60',1,0,'C',1);
            $pdf->Cell(25,10,'61 - 90',1,0,'C',1);
            $pdf->Cell(25,10,utf8_decode('91 o más'),1,1,'C',1);

            $pdf->SetFont('Arial','',10);

            $sql = "select cuentas_pc.*, clientes.nombre_cliente as nombre from cuentas_pc, clientes where cuentas_pc.id_cliente = '$cliente' and cuentas_pc.id_cliente = clientes.id_cliente and cuentas_pc.estatus != 'Pagado' and cuentas_pc.estatus != 'Documentada'";
            $res = $con->query($sql);

            while($row = $res->fetch_assoc())
            {
                $total += $row['monto'];
            
                $fecha1 = $row['fecha_aplicacion']; //Si se lleva el valor, solo falta convertirla a tipo fecha para generar los calculos. 
                $fecha2 = $row['fecha_vencimiento'];
            
                $date1 = new DateTime(date("Y-m-d"));   //fecha registro         
                $date2 = new DateTime($fecha2);   //fecha vencimiento
            
                if($date1 > $date2){
                
                    $diff = $date1->diff($date2);
                    $dias = $diff->days;
                
                    if($dias >= 1 and $dias < 31){
                        $m1_30 += $row['monto'];
                    }
                
                    elseif($dias >= 31 and $dias < 61){
                        $m31_60 += $row['monto'];
                    }
                
                    elseif($dias >= 61 and $dias < 91){
                        $m61_90 += $row['monto'];
                    }
                
                    elseif($dias >= 91){
                        $m91_ += $row['monto'];
                    }
                
                    else{
                        //Ver que erro podemos implementar
                    }
                }
            
                elseif($date2 > $date1){
                    $corriente += $row['monto'];
                }               
            }

            //Validacion del punto decimal
    
            $findme   = '.';
            $pos = strpos($total, $findme);
            if ($pos === false) {
        
                $total = number_format($total, 2, '.', ''); 
            }

            $pos = strpos($corriente, $findme);
            if ($pos === false) {
        
                $corriente = number_format($corriente, 2, '.', ''); 
            }

            $pos = strpos($m1_30, $findme);
            if ($pos === false) {
        
                $m1_30 = number_format($m1_30, 2, '.', ''); 
            }

            $pos = strpos($m31_60, $findme);
            if ($pos === false) {
        
                $m31_60 = number_format($m31_60, 2, '.', ''); 
            }

            $pos = strpos($m61_90, $findme);
            if ($pos === false) {
        
                $m61_90 = number_format($m61_90, 2, '.', ''); 
            }

            $pos = strpos($m91_, $findme);
            if ($pos === false) {
        
                $m91_ = number_format($m91_, 2, '.', ''); 
            }
            //Fin de la validacion del punto decimal

            $pdf->Cell(40,6,$nombre,1,0,'C');
            $pdf->Cell(26,6,"$".$total,1,0,'C');
            $pdf->Cell(25,6,"$".$corriente,1,0,'C');
            $pdf->Cell(25,6,"$".$m1_30,1,0,'C');
            $pdf->Cell(25,6,"$".$m31_60,1,0,'C');
            $pdf->Cell(25,6,"$".$m61_90,1,0,'C');
            $pdf->Cell(25,6,"$".$m91_,1,1,'C');            


        $pdf->Output('',$nombre." ".date("d-m-Y").' - '.$n_reporte.'.pdf');
        
        }
        //fin del reporte invividual
        

        //reporte para todos los clientes y sin fechas
        elseif($cliente == "*" and $fechas === false){
            
            include 'header.php';
            require_once 'config/db.php';
            require 'config/conexion.php';
            
            function datos($cliente)
            {              
                require 'config/conexion.php';
                
                $total = 0; //saldo
                $m1_30 = 0; 
                $m31_60 = 0;
                $m61_90 = 0;
                $m91_ = 0;
                $corriente = 0;
                
                $sql = "select cuentas_pc.*, clientes.nombre_cliente as nombre from cuentas_pc, clientes where cuentas_pc.id_cliente = clientes.id_cliente and clientes.id_cliente = '$cliente' and cuentas_pc.estatus != 'Pagado' and cuentas_pc.estatus != 'Documentada' order by clientes.id_cliente asc";
                
                $res = $con->query($sql);
                while($row = $res->fetch_assoc())
                {
                
                    if($row){
                        $total += $row['monto'];
                
            
                        $fecha1 = $row['fecha_aplicacion'];  
                        $fecha2 = $row['fecha_vencimiento'];
            
                        $date1 = new DateTime(date("Y-m-d"));   //fecha de comparación         
                        $date2 = new DateTime($fecha2);         //fecha vencimiento
            
                        if($date1 > $date2){
                
                            $diff = $date1->diff($date2);
                            $dias = $diff->days;
                
                            if($dias >= 1 and $dias < 31){
                                $m1_30 += $row['monto'];                        
                            }
                
                            elseif($dias >= 31 and $dias < 61){
                                $m31_60 += $row['monto'];                        
                        
                            }
                
                            elseif($dias >= 61 and $dias < 91){
                                $m61_90 += $row['monto'];                       
                            }
                
                            elseif($dias >= 91){
                                $m91_ += $row['monto'];                                               
                            }
                            
                            else{
                                //Error
                            }                    
                        }
            
                        elseif($date2 > $date1){
                            $corriente += $row['monto'];
                        }
                
                
                    }
                    else{
                    
                        //No hacer nada en caso de que no tenga cpc
                    }   
                    
               
                }
                $findme   = '.';
                $pos = strpos($total, $findme);
                if ($pos === false) {
        
                    $total = number_format($total, 2, '.', ''); 
                }

                $pos = strpos($corriente, $findme);
                if ($pos === false) {
        
                    $corriente = number_format($corriente, 2, '.', ''); 
                }   

                $pos = strpos($m1_30, $findme);
                if ($pos === false) {
        
                    $m1_30 = number_format($m1_30, 2, '.', ''); 
                }

                $pos = strpos($m31_60, $findme);
                if ($pos === false) {
        
                    $m31_60 = number_format($m31_60, 2, '.', ''); 
                }

                $pos = strpos($m61_90, $findme);
                if ($pos === false) {
        
                    $m61_90 = number_format($m61_90, 2, '.', ''); 
                }

                $pos = strpos($m91_, $findme);
                if ($pos === false) {
        
                    $m91_ = number_format($m91_, 2, '.', ''); 
                }
                
                return array($total, $corriente, $m1_30, $m31_60, $m61_90, $m91_);
            }
            
            
            //Varibles para la tabla
            $clientes = [];

            $pdf = new PDF();
            $pdf->AliasNbPages();
            $pdf->AddPage();	
        
            $pdf->SetFillColor(232,232,232);

            $pdf->Cell(30);
            $pdf->Cell(120,10, 'Reporte General',0,0,'C');
            $pdf->Ln(25);        
                            	
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(50,7, 'Clientes: Todos',0,1,'B'); //este si
                $pdf->Cell(50,7, 'Moneda: Pesos',0,1,'B'); //este si
                $pdf->Ln(5);

                $pdf->Cell(60,7, 'Fecha de referencia: '.date("d-m-Y"),0,0,'B');        

                $pdf->SetFont('Arial','B',12);
            
            
            $pdf->Ln(10);
            $pdf->SetFont('Arial','B',12);


            $pdf->Cell(40,10,'Cliente',1,0,'C',1);
            $pdf->Cell(26,10,'Saldo',1,0,'C',1); //Generar un consulta que obtenga la suma de las cpc que estan pendientes y    expiradas. 
            $pdf->Cell(25,10,'Al corriente',1,0,'C',1);
            $pdf->Cell(25,10,'1 - 30',1,0,'C',1);
            $pdf->Cell(25,10,'31 - 60',1,0,'C',1);
            $pdf->Cell(25,10,'61 - 90',1,0,'C',1);
            $pdf->Cell(25,10,utf8_decode('91 o más'),1,1,'C',1);

            $pdf->SetFont('Arial','',10);

            
            $i = 0;
            $len = 0;
            
            $q_clientes = "select id_cliente as ID from clientes";
            $query_r =  mysqli_query($con, $q_clientes);
            foreach ($query_r as $r): 

                $clientes[$i] = $r['ID'];
                $i++;
            endforeach; 
            
            $len = count($clientes);

            $i = 0;
            while($i < $len)
            {
                   
                list ($uno, $dos, $tres, $cuatro, $cinco, $seis) = datos($clientes[$i]);
                
                $pdf->Cell(40,6,$clientes[$i],1,0,'C');
                $pdf->Cell(26,6,'$'.$uno,1,0,'C');
                $pdf->Cell(25,6,'$'.$dos,1,0,'C');
                $pdf->Cell(25,6,'$'.$tres,1,0,'C');
                $pdf->Cell(25,6,'$'.$cuatro,1,0,'C');
                $pdf->Cell(25,6,'$'.$cinco,1,0,'C');
                $pdf->Cell(25,6,'$'.$seis,1,1,'C');
                
                $i++;
            }
            

        $pdf->Output('','Reporte '.date("d-m-Y").' - '.$n_reporte.'.pdf');
            
        } 
        //fin del reporte de todos los clientes sin fechas. 
        

        //reporte individual con periodo
        elseif($fechas === true and $cliente != '*'){
            
            $inicio = "";
            $fin = "";
            
            if($primera === true){
                $inicio = " and fecha_aplicacion > '$r1'";
            }
            
            if($segunda === true){
                $fin = " and fecha_aplicacion < '$r2'";
            }            
            
            include 'header.php';
            require_once 'config/db.php';
            require 'config/conexion.php';
        
            $total = 0;
            $m1_30 = 0;
            $m31_60 = 0;
            $m61_90 = 0;
            $m91_ = 0;
            $corriente = 0;

            $query = "select c.id_cpc as cuenta, c.n_factura as factura, p.monto as monto, c.fecha_vencimiento as fechaven, p.fecha_pago from cuentas_pc c, pagos p, clientes s where s.id_cliente = '$cliente' and c.estatus != 'Pagado' and c.estatus != 'Documentada' and c.n_factura = p.Documento";
        
            $resultado = $con->query($query);
	
            $pdf = new PDF();
            $pdf->AliasNbPages();
            $pdf->AddPage();	
        
            $pdf->SetFillColor(232,232,232);

            $pdf->Cell(30);
            $pdf->Cell(120,10, 'Reporte individual',0,0,'C');
            $pdf->Ln(25);
        
            $query3 = "select * FROM clientes where id_cliente = '$cliente'"; 
            //$resultado2 = $mysqli->query($query3);

            $resultado2 = $con->query($query3);

            while($row = $resultado2->fetch_assoc())
            {   
            
                $nombre = utf8_decode($row['nombre_cliente']); 
		
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(50,7, 'Cliente: '.utf8_decode($row['nombre_cliente']),0,1,'B'); //este si
                $pdf->Cell(50,7, 'Direccion: '.utf8_decode($row['direccion_cliente']),0,1,'B'); //este si
                $pdf->Ln(5);


                $pdf->Cell(60,7, 'Contacto: '.utf8_decode($row['contacto']),0,1,'B');        
        
                $pdf->Cell(60,7, 'Fecha de referencia: '.date("d-m-Y"),0,1,'B'); //este si
                
                $pdf->SetFont('Arial','B',12);
                $pdf->Ln(5);
                
                if($primera === true and $segunda === true){
                    $pdf->Cell(60,7, 'Periodo: Del '.$r1.' Al: '.$r2,0,1,'B'); //este si
                }
                elseif($primera === true and $segunda === false){
                    $pdf->Cell(60,7, 'Periodo: A partir del: '.$r1,0,1,'B'); //este si
                }
                
                elseif($primera === false and $segunda === true){
                    $pdf->Cell(60,7, 'Periodo: A partir del: '.$r2,0,1,'B'); //este si
                }
            
                $pdf->SetFont('Arial','B',12);
            }
            
            $pdf->Ln(5);
            $pdf->SetFont('Arial','B',12);


            $pdf->Cell(40,10,'Cliente',1,0,'C',1);
            $pdf->Cell(26,10,'Saldo',1,0,'C',1); //Generar un consulta que obtenga la suma de las cpc que estan pendientes y    expiradas. 
            $pdf->Cell(25,10,'Al corriente',1,0,'C',1);
            $pdf->Cell(25,10,'1 - 30',1,0,'C',1);
            $pdf->Cell(25,10,'31 - 60',1,0,'C',1);
            $pdf->Cell(25,10,'61 - 90',1,0,'C',1);
            $pdf->Cell(25,10,utf8_decode('91 o más'),1,1,'C',1);

            $pdf->SetFont('Arial','',10);

            $sql = "select cuentas_pc.*, clientes.nombre_cliente as nombre from cuentas_pc, clientes where cuentas_pc.id_cliente = '$cliente' and cuentas_pc.id_cliente = clientes.id_cliente and cuentas_pc.estatus != 'Pagado' and cuentas_pc.estatus != 'Documentada'".$inicio.$fin;
            $res = $con->query($sql);

            while($row = $res->fetch_assoc())
            {
                $total += $row['monto'];
            
                $fecha1 = $row['fecha_aplicacion']; //Si se lleva el valor, solo falta convertirla a tipo fecha para generar los calculos. 
                $fecha2 = $row['fecha_vencimiento'];
            
                $date1 = new DateTime(date("Y-m-d"));   //fecha registro         
                $date2 = new DateTime($fecha2);   //fecha vencimiento
            
                if($date1 > $date2){
                
                    $diff = $date1->diff($date2);
                    $dias = $diff->days;
                
                    if($dias >= 1 and $dias < 31){
                        $m1_30 += $row['monto'];
                    }
                
                    elseif($dias >= 31 and $dias < 61){
                        $m31_60 += $row['monto'];
                    }
                
                    elseif($dias >= 61 and $dias < 91){
                        $m61_90 += $row['monto'];
                    }
                
                    elseif($dias >= 91){
                        $m91_ += $row['monto'];
                    }
                
                    else{
                        //Ver que erro podemos implementar
                    }
                }
            
                elseif($date2 > $date1){
                    $corriente += $row['monto'];
                }               
            }

            //Validacion del punto decimal
    
            $findme   = '.';
            $pos = strpos($total, $findme);
            if ($pos === false) {
        
                $total = number_format($total, 2, '.', ''); 
            }

            $pos = strpos($corriente, $findme);
            if ($pos === false) {
        
                $corriente = number_format($corriente, 2, '.', ''); 
            }

            $pos = strpos($m1_30, $findme);
            if ($pos === false) {
        
                $m1_30 = number_format($m1_30, 2, '.', ''); 
            }

            $pos = strpos($m31_60, $findme);
            if ($pos === false) {
        
                $m31_60 = number_format($m31_60, 2, '.', ''); 
            }

            $pos = strpos($m61_90, $findme);
            if ($pos === false) {
        
                $m61_90 = number_format($m61_90, 2, '.', ''); 
            }

            $pos = strpos($m91_, $findme);
            if ($pos === false) {
        
                $m91_ = number_format($m91_, 2, '.', ''); 
            }
            //Fin de la validacion del punto decimal

            $pdf->Cell(40,6,$nombre,1,0,'C');
            $pdf->Cell(26,6,"$".$total,1,0,'C');
            $pdf->Cell(25,6,"$".$corriente,1,0,'C');
            $pdf->Cell(25,6,"$".$m1_30,1,0,'C');
            $pdf->Cell(25,6,"$".$m31_60,1,0,'C');
            $pdf->Cell(25,6,"$".$m61_90,1,0,'C');
            $pdf->Cell(25,6,"$".$m91_,1,1,'C');            


            $pdf->Output('',$nombre." ".date("d-m-Y").' - '.$n_reporte.'.pdf');
            
        } 
        
        //inicio de todos los clientes con periodo
        elseif($fechas === true and $cliente == '*'){
            
            include 'header.php';
            require_once 'config/db.php';
            require 'config/conexion.php';
            
            $inicio = "";
            $fin = "";
            
            if($primera === true){
                $inicio = " and fecha_aplicacion > '$r1'";
            }
            
            if($segunda === true){
                $fin = " and fecha_aplicacion < '$r2'";
            }            
            
            
            function datos($cliente,$inicio_,$fin_)
            {              
                require 'config/conexion.php';
                
                $total = 0; //saldo
                $m1_30 = 0; 
                $m31_60 = 0;
                $m61_90 = 0;
                $m91_ = 0;
                $corriente = 0;
                
                $sql = "select cuentas_pc.*, clientes.nombre_cliente as nombre from cuentas_pc, clientes where cuentas_pc.id_cliente = clientes.id_cliente and clientes.id_cliente = '$cliente' and cuentas_pc.estatus != 'Pagado' and cuentas_pc.estatus != 'Documentada'".$inicio_.$fin_;
                
                $res = $con->query($sql);
                while($row = $res->fetch_assoc())
                {
                
                    if($row){
                        $total += $row['monto'];
                
            
                        $fecha1 = $row['fecha_aplicacion'];  
                        $fecha2 = $row['fecha_vencimiento'];
            
                        $date1 = new DateTime(date("Y-m-d"));   //fecha de comparación         
                        $date2 = new DateTime($fecha2);         //fecha vencimiento
            
                        if($date1 > $date2){
                
                            $diff = $date1->diff($date2);
                            $dias = $diff->days;
                
                            if($dias >= 1 and $dias < 31){
                                $m1_30 += $row['monto'];                        
                            }
                
                            elseif($dias >= 31 and $dias < 61){
                                $m31_60 += $row['monto'];                        
                        
                            }
                
                            elseif($dias >= 61 and $dias < 91){
                                $m61_90 += $row['monto'];                       
                            }
                
                            elseif($dias >= 91){
                                $m91_ += $row['monto'];                                               
                            }
                            
                            else{
                                //Error
                            }                    
                        }
            
                        elseif($date2 > $date1){
                            $corriente += $row['monto'];
                        }
                
                
                    }
                    else{
                    
                        //No hacer nada en caso de que no tenga cpc
                    }   
                    
               
                }
                $findme   = '.';
                $pos = strpos($total, $findme);
                if ($pos === false) {
        
                    $total = number_format($total, 2, '.', ''); 
                }

                $pos = strpos($corriente, $findme);
                if ($pos === false) {
        
                    $corriente = number_format($corriente, 2, '.', ''); 
                }   

                $pos = strpos($m1_30, $findme);
                if ($pos === false) {
        
                    $m1_30 = number_format($m1_30, 2, '.', ''); 
                }

                $pos = strpos($m31_60, $findme);
                if ($pos === false) {
        
                    $m31_60 = number_format($m31_60, 2, '.', ''); 
                }

                $pos = strpos($m61_90, $findme);
                if ($pos === false) {
        
                    $m61_90 = number_format($m61_90, 2, '.', ''); 
                }

                $pos = strpos($m91_, $findme);
                if ($pos === false) {
        
                    $m91_ = number_format($m91_, 2, '.', ''); 
                }
                
                return array($total, $corriente, $m1_30, $m31_60, $m61_90, $m91_);
            }
            
            
            //Varibles para la tabla
            $clientes = [];

            $pdf = new PDF();
            $pdf->AliasNbPages();
            $pdf->AddPage();	
        
            $pdf->SetFillColor(232,232,232);

            $pdf->Cell(30);
            $pdf->Cell(120,10, 'Reporte General',0,0,'C');
            $pdf->Ln(25);        
                            	
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(50,7, 'Clientes: Todos',0,1,'B'); //este si
                $pdf->Cell(50,7, 'Moneda: Pesos',0,1,'B'); //este si
                $pdf->Ln(5);

                $pdf->Cell(60,7, 'Fecha de referencia: '.date("d-m-Y"),0,0,'B');        

                $pdf->SetFont('Arial','B',12);
            
            
            $pdf->Ln(10);
            $pdf->SetFont('Arial','B',12);


            $pdf->Cell(40,10,'Cliente',1,0,'C',1);
            $pdf->Cell(26,10,'Saldo',1,0,'C',1); //Generar un consulta que obtenga la suma de las cpc que estan pendientes y    expiradas. 
            $pdf->Cell(25,10,'Al corriente',1,0,'C',1);
            $pdf->Cell(25,10,'1 - 30',1,0,'C',1);
            $pdf->Cell(25,10,'31 - 60',1,0,'C',1);
            $pdf->Cell(25,10,'61 - 90',1,0,'C',1);
            $pdf->Cell(25,10,utf8_decode('91 o más'),1,1,'C',1);

            $pdf->SetFont('Arial','',10);

            
            $i = 0;
            $len = 0;
            
            $q_clientes = "select id_cliente as ID from clientes";
            $query_r =  mysqli_query($con, $q_clientes);
            foreach ($query_r as $r): 

                $clientes[$i] = $r['ID'];
                $i++;
            endforeach; 
            
            $len = count($clientes);

            $i = 0;
            while($i < $len)
            {
                   
                list ($uno, $dos, $tres, $cuatro, $cinco, $seis) = datos($clientes[$i],$inicio,$fin);
                
                $pdf->Cell(40,6,$clientes[$i],1,0,'C');
                $pdf->Cell(26,6,'$'.$uno,1,0,'C');
                $pdf->Cell(25,6,'$'.$dos,1,0,'C');
                $pdf->Cell(25,6,'$'.$tres,1,0,'C');
                $pdf->Cell(25,6,'$'.$cuatro,1,0,'C');
                $pdf->Cell(25,6,'$'.$cinco,1,0,'C');
                $pdf->Cell(25,6,'$'.$seis,1,1,'C');
                
                $i++;
            }
                        
        $pdf->Output('','Reporte '.date("d-m-Y").' - '.$n_reporte.'.pdf');
            
        }
    
    }
?>
