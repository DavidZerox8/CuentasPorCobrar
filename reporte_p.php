<?php

    $id_c = $_GET['id'];

    if(empty($id_c))
    {
        header("location: facturas.php");
    }

    elseif ($id_c == "z")
    {
        include 'plantilla_p.php';
        require_once 'config/db.php';
        require 'config/conexion.php';
        //$mysqli = new mysqli("127.0.0.1", "solucion", "c5(vF4]7mYr2SA", "solucion_c_textil");
	
        $query = "SELECT p.id_pago as pagos, p.Monto as monto, p.Tipo as tipo, p.Documento as documento, p.Fecha_pago as fecha, c.nombre_cliente as cliente FROM pagos p, clientes c WHERE p.id_cliente = c.id_cliente";
        //$resultado = $mysqli->query($query);
        $resultado = $con->query($query);
	
        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
	
        
        $pdf->SetFont('Arial','B',15);
        $pdf->Cell(30);
        $pdf->Cell(120,10, 'Reporte de pagos',0,0,'C');
        
         $pdf->Ln(15);
        
        $pdf->SetFillColor(232,232,232);

        $pdf->SetFont('Arial','B',12);
        
        $deuda = "SELECT SUM(Monto) as deuda FROM cuentas_pc where estatus != 'Documentada'";
        //$resd = $mysqli->query($deuda);
        $resd = $con->query($deuda);
        $pdf->Cell(47,10,'Deuda total',1,1,'C',1);
        while($row = $resd->fetch_assoc())
        {
            $pdf->Cell(47,6,"$".utf8_decode($row['deuda']),1,0,'C');
        }
        
        $pdf->SetXY(158.2,43);
    
        $pdf->Cell(47,10,'Abonos Globales',1,1,'C',1);
        
        $query2 = "SELECT SUM(Monto) as suma FROM pagos"; 
        //$resultado2 = $mysqli->query($query2);
        $resultado2 = $con->query($query2);

        while($row = $resultado2->fetch_assoc())
        {
		
            $pdf->SetFont('Arial','B',12);
            $pdf->SetXY(158.2,53);
            $pdf->Cell(47,6,"$".utf8_decode($row['suma']),1,1,'C');
        }
        
        $pdf->Ln(25);
        $pdf->SetFont('Arial','B',12);


	    $pdf->Cell(35,10,'Fecha de pago',1,0,'C',1);
        $pdf->Cell(22,10,'# de pago',1,0,'C',1);
        $pdf->Cell(40,10,'Cliente',1,0,'C',1);
        $pdf->Cell(45,10,'Monto',1,0,'C',1);
        $pdf->Cell(28,10,'Tipo de pago',1,0,'C',1);
        $pdf->Cell(25,10,'# Factura',1,1,'C',1);
        	
        $pdf->SetFont('Arial','',10);
        
        $count = $resultado->num_rows;
        if($count > 0)
        {
            while($row = $resultado->fetch_assoc())
            {
		    
                $pdf->Cell(35,6,utf8_decode($row['fecha']),1,0,'C');
                $pdf->Cell(22,6,$row['pagos'],1,0,'C');
                $pdf->Cell(40,6,utf8_decode($row['cliente']),1,0,'C');
                $pdf->Cell(45,6,"$".utf8_decode($row['monto']),1,0,'C');
                $pdf->Cell(28,6,utf8_decode($row['tipo']),1,0,'C');
                $pdf->Cell(25,6,utf8_decode($row['documento']),1,1,'C');
            }
        }
        
        else{
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(195,20,utf8_decode("Aún no hay Pagos para mostrar."),1,1,'C');
        }
        $pdf->Output('','Reporte '.date("d-m-Y").' - Pagos.pdf');
    }

?>
