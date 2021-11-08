<?php
    $findme   = '.';
    $id_cliente = $_POST['cliente'];
    
    $deuda = "";
    $abonos = "";
    
    if(empty($id_cliente))
    {
        header("Location: clientes.php");
    }
    
	include 'plantilla_p.php';
    require_once 'config/db.php';
    require 'config/conexion.php';
	
	
	$query = "select pc.n_concepto as concepto, pc.n_factura as factura, pc.fecha_vencimiento as vencimiento, pc.monto as total, c.desc_concepto as con, c.tipo_concepto as tipo from cuentas_pc pc, conceptos c where id_cliente = '$id_cliente' and pc.n_concepto = c.id_concepto";

    $resultado = $con->query($query);
	
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();

    $pdf->SetFont('Arial','B',15);
    $pdf->Cell(30);
	$pdf->Cell(120,10, 'Reporte de saldos',0,0,'C');
    $pdf->Ln(10);


    $query3 = "select * from clientes where id_cliente = '$id_cliente'"; 

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
   
        
        //Aqui van las observaciones 
	}
    
    $pdf->SetFillColor(232,232,232);
	$pdf->SetFont('Arial','B',12);

    $query2 = "select SUM(monto) as monto FROM cuentas_pc where id_cliente = '$id_cliente' and estatus != 'Documentada'"; //abonos
    $query3 = "select sum(monto) as suma from abonos where id_cliente = '$id_cliente'"; //deuda total

    $resultado2 = $con->query($query2);
    $resultado3 = $con->query($query3);
    
    $pdf->SetXY(153.2,45);
    $pdf->Cell(47,10,'Abono',1,1,'C',1);

    while($row = $resultado3->fetch_assoc())
	{
		$valor1 = $row['suma'];
        $pos = strpos($valor1, $findme);
        if ($pos === false) {
        
            $valor1 = number_format($valor1, 2, '.', ''); 
        }
        
		$pdf->SetXY(153.2,55);
        $pdf->Cell(47,9,"$".utf8_decode($valor1),1,1,'C');
        $abonos = $row['suma'];
	}

    $pdf->SetXY(153.2,21);
    $pdf->Cell(47,10,'Deuda total',1,1,'C',1);
    

    while($row = $resultado2->fetch_assoc())
	{
        $valor2 = $row['monto'];
		$pos = strpos($valor2, $findme);
        if ($pos === false) {
        
            $valor2 = number_format($valor2, 2, '.', ''); 
        }
                
		$pdf->SetXY(153.2,31);
        $pdf->Cell(47,9,"$".utf8_decode($valor2),1,1,'C');
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
	$pdf->SetFont('Arial','B',13);

    //Separación de las celdas
    
    $pdf->Cell(15,10,'#',1,0,'C',1);
	$pdf->Cell(43,10,'Concepto',1,0,'C',1);
    $pdf->Cell(32,10,'Factura',1,0,'C',1);
	$pdf->Cell(25,10,'Fech.Venc',1,0,'C',1);
    $pdf->Cell(37,10,'Monto',1,0,'C',1);
    $pdf->Cell(38,10,'Saldo',1,1,'C',1);
    
	
	$pdf->SetFont('Arial','',10);
	
    $count = $resultado->num_rows;
    $i = 1;
    $saldost = 0;
    if($count > 0){
        
        while($row = $resultado->fetch_assoc())
        {         
            $saldost += $row['total'];
            $total = $row['total']; 
            
            $findme   = '.';
            $pos = strpos($total, $findme);
            if ($pos === false) {
        
                $total = number_format($total, 2, '.', ''); 

            }
            
            $pos = strpos($saldost, $findme);
            if ($pos === false) {
        
                $saldost = number_format($saldost, 2, '.', ''); 

            }
            
            $pdf->Cell(15,6,utf8_decode($i),1,0,'C');
            $pdf->Cell(43,6,utf8_decode($row['con']." - ".$row['tipo']),1,0,'C');
            $pdf->Cell(32,6,utf8_decode($row['factura']),1,0,'C');
            $pdf->Cell(25,6,utf8_decode($row['vencimiento']),1,0,'C');
            $pdf->Cell(37,6,"$".utf8_decode($total),1,0,'C');
            $pdf->Cell(38,6,"$".utf8_decode($saldost),1,1,'C');
        
            
            $i++;
        }
        $pdf->Cell(190,15,"*Las facturas documentadas no son contempladas para la deuda todal",0,1,'R');
    }

    else{
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,20,utf8_decode("Aún no hay información para mostrar."),1,1,'C');
    }
    

    
	$pdf->Output('','Reporte de Saldos'.date("d-m-Y").' - '.$nombre.'.pdf');
?>