<?php
	require 'fpdf/fpdf.php';
	
	class PDF extends FPDF
	{
		function Header()
		{ 
            $this->Image('img/fondo_a.png',0,0,300,300);
			$this->Image('img/logo.png', 5, 5, 30 );
			$this->SetFont('Arial','B',15);
			$this->Cell(70);
			$this->Cell(120,10, 'Cuentas por Cobrar',0,0,'C');
			$this->Ln(20);
		}
		
		function Footer()
		{
			$this->SetY(-15);
			$this->SetFont('Arial','I', 8);
			$this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
		}		
	}
?>