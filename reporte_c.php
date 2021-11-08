<?php
    
    $deuda = "";
    $adono = "";
    
    $id_cliente = $_GET['id'];
    if(empty($id_cliente))
    {
        header("Location: clientes.php");
    }
    
	include 'plantilla.php';
    require_once 'config/db.php';
    require 'config/conexion.php';
	
	$query = "select cp.id_cpc as cuenta, c.nombre_cliente as cliente, con.desc_concepto as concepto, con.tipo_concepto as tipoc, cp.n_factura as factura, cp.fecha_aplicacion as registro, cp.fecha_vencimiento as vencimiento, cp.monto as total, cp.estatus as estado, cp.observaciones as comentarios from cuentas_pc cp, clientes c, conceptos con where cp.id_cliente = '$id_cliente' and cp.n_concepto = con.id_concepto and cp.id_cliente = c.id_cliente and con.tipo_concepto != 'Abono'";

    $abonos_suma = "select sum(monto) as suma from abonos where id_cliente = '$id_cliente'";


    $resultado = $con->query($query);

	
	$pdf = new PDF('L');
	$pdf->AliasNbPages();
	$pdf->AddPage();

    $pdf->SetFont('Arial','B',15);
    $pdf->Cell(70);
	$pdf->Cell(120,10, 'Reporte General',0,0,'C');
    $pdf->Ln(10);


    $query3 = "select * FROM clientes where clientes.id_cliente = '$id_cliente'"; 

    $resultado2 = $con->query($query3);

    while($row = $resultado2->fetch_assoc())
	{
		$nombre = utf8_decode($row['nombre_cliente']);
		
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(50,7, 'Cliente: '.utf8_decode($row['nombre_cliente']),0,1,'B');
        $pdf->Cell(50,7, 'Direccion: '.utf8_decode(substr($row['direccion_cliente'],0,81)),0,1,'B');
        $pdf->Cell(50,7,utf8_decode(substr($row['direccion_cliente'],81, -1)),0,1,'B');
        $pdf->Ln(5);


        $pdf->Cell(90,7, 'Contacto: '.utf8_decode($row['contacto']),0,0,'B');        
        $pdf->Cell(90,7, 'Telefono: '.utf8_decode($row['telefono_cliente']),0,1 ,'B');
        
        $pdf->Cell(90,7, 'RFC: '.utf8_decode($row['rfc']),0,0,'B');
        
        
        //Aqui van las observaciones 
	}
    
    $pdf->SetFillColor(232,232,232);
	$pdf->SetFont('Arial','B',16);

    //$query2 = "select sum(monto) as monto from cuentas_pc WHERE id_cliente = '$id_cliente' and estatus = 'Pendiente' or estatus = 'Expirado'"; 

    $query2 = "select sum(monto) as monto from cuentas_pc WHERE id_cliente = '$id_cliente' and estatus != 'Documentada'"; 
    

    $resultado2 = $con->query($query2);
      
    $pdf->SetXY(238.2,11);
    $pdf->Cell(47,10,'Deuda total',1,1,'C',1);
    

    while($row = $resultado2->fetch_assoc())
	{
		
		$pdf->SetXY(238.2,21);
        $pdf->Cell(47,9,"$".utf8_decode($row['monto']),1,1,'C');
        $deuda = $row['monto'];
	}  

    $res_a = mysqli_query($con,$abonos_suma);

    $pdf->SetXY(238.2,35);
    $pdf->Cell(47,10,'Abonos',1,1,'C',1);

    while($row = $res_a->fetch_assoc())
	{
		
		$pdf->SetXY(238.2,45);
        $pdf->Cell(47,9,"$".utf8_decode($row['suma']),1,1,'C');
        $abonos = $row['suma'];
	}
    
    $pdf->SetXY(238.2,59);
    $pdf->Cell(47,10,'Saldo',1,1,'C',1);
    $pdf->SetXY(238.2,69);
    $dtotal = $deuda - $abonos;

            $findme   = '.';
            $pos = strpos($dtotal, $findme);
            if ($pos === false) {
        
                $dtotal = number_format($dtotal, 2, '.', ''); 
            }


    $pdf->Cell(47,10,"$".$dtotal,1,1,'C',0);

    $pdf->Ln(15);
    $resta = 0;
	
	$pdf->SetFillColor(232,232,232);
	$pdf->SetFont('Arial','B',15);
	   
    //$pdf->Cell(275,12,'Cuentas por cobrar',1,1,'C',1);
    

    //Separación de las celdas
    $pdf->SetFont('Arial','B',11);
    
    $pdf->Cell(20,8,'#',1,0,'C',1);
	
    $pdf->Cell(41,8,'Concepto',1,0,'C',1);
	$pdf->Cell(54,8,'Factura',1,0,'C',1);

    $pdf->Cell(40,8,'Registro',1,0,'C',1);
    $pdf->Cell(40,8,'Vencimiento',1,0,'C',1);
    $pdf->Cell(47,8,'Total',1,0,'C',1);
    $pdf->Cell(33,8,'Estatus',1,1,'C',1);
   
    
	
	$pdf->SetFont('Arial','',10);

    $count = $resultado->num_rows;
    $i = 1;
    if($count > 0){
        
            while($row = $resultado->fetch_assoc())
            {
                $fact = $row['factura'];
                $n_sql = "select abonos.*, conceptos.desc_concepto as concepto, conceptos.tipo_concepto as tipo from abonos, conceptos where factura = '$fact' and abonos.concepto = conceptos.id_concepto";
		
                $pdf->Cell(20,6,utf8_decode($i),1,0,'C');

                $pdf->Cell(41,6,utf8_decode($row['concepto']." - ".$row['tipoc']),1,0,'C');
                $pdf->Cell(54,6,utf8_decode($row['factura']),1,0,'C');

                // $pdf->Cell(32,6,,1,0,'C');

                $pdf->Cell(40,6,utf8_decode($row['registro']),1,0,'C');
                $pdf->Cell(40,6,utf8_decode($row['vencimiento']),1,0,'C');
                $pdf->Cell(47,6,"$".utf8_decode($row['total']),1,0,'C');
                $pdf->Cell(33,6,utf8_decode($row['estado']),1,1,'C');   
                
                $res = $con->query($n_sql);
                
                
               // $rows = $res->fetch_assoc();
                foreach ($res as $r): 
                    $pdf->Cell(20,6,"  ",1,0,'C');    
                    $pdf->Cell(41,6,utf8_decode($r['concepto']." - ".$r['tipo']),1,0,'C');
                    $pdf->Cell(54,6,utf8_decode($r['factura']),1,0,'C');
                    $pdf->Cell(40,6,utf8_decode($r['Fecha_reg']),1,0,'C');
                    $pdf->Cell(40,6,utf8_decode("N/A"),1,0,'C');
                    $pdf->Cell(47,6,"$".utf8_decode($r['monto']),1,0,'C');
                    $pdf->Cell(33,6,utf8_decode("N/A"),1,1,'C');
                endforeach;
                
                $i++;
            }        
    }
	
	else{
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(275,20,utf8_decode("Aún no existen Cuentas por Cobrar registradas"),1,0,'C');
    }

	$pdf->Output('','Reporte CPC '.date("d-m-Y").' - '.$nombre.'.pdf');
?>