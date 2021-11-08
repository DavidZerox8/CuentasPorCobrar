<?php

    $id_cliente = $_GET['id'];
    $factura = $_GET['fact'];
    
    $deuda = "";
    $abonos = "";
    
    if(empty($id_cliente))
    {
        header("Location: clientes.php");
    }
    
	include 'plantilla_p.php';
    require_once 'config/db.php';
    require 'config/conexion.php';
	
	$query = "select cuentas_pc.fecha_aplicacion as fecha_a, cuentas_pc.fecha_vencimiento as limite, cuentas_pc.monto as cargo, cuentas_pc.observaciones as comentarios, abonos.factura as factura, abonos.id_abono as pago, abonos.fecha_pago as fecha_p, abonos.monto as abono from cuentas_pc, abonos where abonos.factura = '$factura' and abonos.id_cliente = '$id_cliente' and cuentas_pc.n_factura = abonos.factura";

	//$resultado = $mysqli->query($query);

    $resultado = $con->query($query);
	
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();

    $pdf->SetFont('Arial','B',15);
    $pdf->Cell(30);
	$pdf->Cell(120,10, 'Cobranza',0,0,'C');
    $pdf->Ln(10);


    $query3 = "select clientes.*, cuentas_pc.observaciones as comentarios FROM clientes, cuentas_pc where clientes.id_cliente = '$id_cliente' and cuentas_pc.n_factura = '$factura'"; 

    $resultado2 = $con->query($query3);

    while($row = $resultado2->fetch_assoc())
	{
		$nombre = utf8_decode($row['nombre_cliente']);
		
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(50,7, 'Cliente: '.utf8_decode($row['nombre_cliente']),0,1,'B');
        $pdf->Cell(50,7, 'Direccion: '.utf8_decode(substr($row['direccion_cliente'],0,61)),0,1,'B');
        $pdf->Cell(50,7,utf8_decode(substr($row['direccion_cliente'],61, -1)),0,1,'B');
        $pdf->Ln(5);


        $pdf->Cell(60,7, 'Contacto: '.utf8_decode($row['contacto']),0,0,'B');        
        $pdf->Cell(60,7, 'Telefono: '.utf8_decode($row['telefono_cliente']),0,1 ,'B');
        
        $pdf->Cell(60,7, 'RFC: '.utf8_decode($row['rfc']),0,1,'B');
        
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(60,15, 'Factura(s): '.$factura,0,1,'B'); 
   
        
        //Aqui van las observaciones 
	}
    
    $pdf->SetFillColor(232,232,232);
	$pdf->SetFont('Arial','B',16);

    $query2 = "select monto FROM cuentas_pc where n_factura = '$factura'"; 
    $query3 = "select SUM(monto) as suma FROM abonos where id_cliente = '$id_cliente' and factura = '$factura'";

    $resultado2 = $con->query($query2);
    $resultado3 = $con->query($query3);
    
    $pdf->SetXY(153.2,45);
    $pdf->Cell(47,10,'Abono',1,1,'C',1);

    while($row = $resultado3->fetch_assoc())
	{
		
		$pdf->SetXY(153.2,55);
        $pdf->Cell(47,9,"$".utf8_decode($row['suma']),1,1,'C');
        $abonos = $row['suma'];
	}

    $pdf->SetXY(153.2,21);
    $pdf->Cell(47,10,'Deuda total',1,1,'C',1);
    

    while($row = $resultado2->fetch_assoc())
	{
		
		$pdf->SetXY(153.2,31);
        $pdf->Cell(47,9,"$".utf8_decode($row['monto']),1,1,'C');
        $deuda = $row['monto'];
	}

    $saldo = $deuda - $abonos;

    $pdf->SetXY(153.2,69);
    $pdf->Cell(47,10,'Saldo',1,1,'C',1);
    $pdf->SetXY(153.2,79);
    
    //Validacion del punto decimal
    
    $findme   = '.';
    $pos = strpos($saldo, $findme);
    if ($pos === false) {
        
        $saldo = number_format($saldo, 2, '.', ''); 

    }
    //Fin de la validacion del punto decimal
    $pdf->Cell(47,9,"$".$saldo,1,1,'C');
    

    $pdf->Ln(10);
    $resta = 0;
	
	$pdf->SetFillColor(232,232,232);
	$pdf->SetFont('Arial','B',12);
	   
    $pdf->Cell(95,10,'Cargos',1,0,'C',1);
    $pdf->Cell(95,10,'Abonos',1,1,'C',1);

    //Separación de las celdas
    
    $pdf->Cell(25,6,'Fecha',1,0,'C',1);
	$pdf->Cell(38,6,'Importe',1,0,'C',1);
    $pdf->Cell(32,6,'Factura',1,0,'C',1);
	$pdf->Cell(20,6,'# pago',1,0,'C',1);
    $pdf->Cell(37,6,'Fecha de pago  ',1,0,'C',1);
    $pdf->Cell(38,6,'Monto',1,1,'C',1);
    
	
	$pdf->SetFont('Arial','',10);
	
    $count = $resultado->num_rows;
    
    if($count > 0){
        
        while($row = $resultado->fetch_assoc())
        {       
            $dinero = $row['cargo'];            
            $total = $dinero-$resta;
            
            $findme   = '.';
            $pos = strpos($total, $findme);
            if ($pos === false) {
        
                $total = number_format($total, 2, '.', ''); 

            }
            
            $pdf->Cell(25,6,utf8_decode($row['fecha_a']),1,0,'C');
            $pdf->Cell(38,6,"$".utf8_decode($total),1,0,'C');
            $pdf->Cell(32,6,utf8_decode($row['factura']),1,0,'C');
            $pdf->Cell(20,6,utf8_decode($row['pago']),1,0,'C');
            $pdf->Cell(37,6,utf8_decode($row['fecha_p']),1,0,'C');
            $pdf->Cell(38,6,"$".utf8_decode($row['abono']),1,1,'C');
        
            $resta += utf8_decode($row['abono']);
      
            $factura = utf8_decode($row['factura']);
        
            $comentarios = utf8_decode($row['comentarios']);
        }
    }

    else{
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,20,utf8_decode("Aún no hay información para mostrar."),1,1,'C');
    }
    

    
	$pdf->Output('','Reporte cobranza'.date("d-m-Y").' - Factura '.$factura.'.pdf');
?>