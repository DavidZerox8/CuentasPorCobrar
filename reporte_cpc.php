<?php
    
    $id_cliente = $_GET['id'];
    if(empty($id_cliente))
    {
        header("Location: clientes.php");
    } 
    
	include 'plantilla.php';
    require_once 'config/db.php';
    require 'config/conexion.php';
	
	$query = "select cp.id_cpc as cuenta, c.nombre_cliente as cliente, con.desc_concepto as concepto, cp.n_factura as factura,  cp.fecha_aplicacion as registro, cp.fecha_vencimiento as vencimiento, cp.monto as total, cp.estatus as estado, cp.observaciones as comentarios from cuentas_pc cp, clientes c, conceptos con where cp.n_concepto = con.id_concepto and cp.id_cliente = c.id_cliente";


	//$resultado = $mysqli->query($query);
    $resultado = $con->query($query);

	$pdf = new PDF('L');
	$pdf->AliasNbPages();
	$pdf->AddPage();

    $pdf->SetFont('Arial','B',15);
    $pdf->Cell(70);
	$pdf->Cell(120,10, 'Reporte General - '.date("d-m-Y"),0,0,'C');
    $pdf->Ln(10);

    
    $pdf->SetFillColor(232,232,232);
	$pdf->SetFont('Arial','B',16);

    $query2 = "select sum(monto) as monto from cuentas_pc"; 
    //$resultado2 = $mysqli->query($query2);
    $resultado2 = $con->query($query2);

    $pdf->SetXY(238.2,21);
    $pdf->Cell(47,10,'Monto total',1,1,'C',1);
    

    while($row = $resultado2->fetch_assoc())
	{
		
		$pdf->SetXY(238.2,31);
        $pdf->Cell(47,9,"$".utf8_decode($row['monto']),1,1,'C');
        $deuda = $row['monto'];
	}  


    $pdf->Ln(10);
	
	$pdf->SetFillColor(232,232,232);
	$pdf->SetFont('Arial','B',15);
	   
    

    //Separación de las celdas
    $pdf->SetFont('Arial','B',12);
    
    $pdf->Cell(35,8,'#',1,0,'C',1);
	
    $pdf->Cell(35,8,'Concepto',1,0,'C',1);
	$pdf->Cell(35,8,'Factura',1,0,'C',1);

    $pdf->Cell(44,8,'Registro',1,0,'C',1);
    $pdf->Cell(42,8,'Vencimiento',1,0,'C',1);
    $pdf->Cell(42,8,'Total',1,0,'C',1);
    $pdf->Cell(42,8,'Estatus',1,1,'C',1);
	
	$pdf->SetFont('Arial','',10);

    $count = $resultado->num_rows;
    if($count > 0)
    {
        $i = 1;
        while($row = $resultado->fetch_assoc())
        {
		
            $pdf->Cell(35,6,utf8_decode($i),1,0,'C');

            $pdf->Cell(35,6,utf8_decode($row['concepto']),1,0,'C');
            $pdf->Cell(35,6,utf8_decode($row['factura']),1,0,'C');

            $pdf->Cell(44,6,utf8_decode($row['registro']),1,0,'C');
            $pdf->Cell(42,6,utf8_decode($row['vencimiento']),1,0,'C');
            $pdf->Cell(42,6,"$".utf8_decode($row['total']),1,0,'C');
            $pdf->Cell(42,6,utf8_decode($row['estado']),1,1,'C');
            $pdf->Cell(275,8,utf8_decode($row['comentarios']),1,1,'C');
            
            $i++;
        }
    }
	else{
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(275,20,utf8_decode("Aún no hay Cuentas por Cobrar para mostrar."),1,1,'C');
    }
	

	$pdf->Output('','Reporte general '.date("d-m-Y").' - Cuentas por Cobrar.pdf');
?>