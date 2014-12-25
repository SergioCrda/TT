<?php
	function array_recibe($url_array) { 
		 $tmp = stripslashes($url_array); 
		 $tmp = urldecode($tmp); 
		 $tmp = unserialize($tmp); 

		return $tmp; 
	}
	$data = array_recibe($_GET['data']);
	require('../fpdf/fpdf.php');
	class PDF extends FPDF
	{
		function Header()
		{
			//header
			$this->SetFont('Arial','',11);
			$this->Cell(70,10,utf8_decode('Universidad Tecnológica Metropolitana'),0,0,'L');
			$this->Ln(5);
			
			$this->SetFont('Arial','B',11);
			$this->Cell(70,10,utf8_decode('Dirección de Docencia'),0,0,'L');
			$this->Ln(15);
		}
		
		// Pie de p&aacute;gina
		function Footer()
		{
			//Posici&oacute;n: a 1,5 cm del final
			$this->SetY(-15);
			
			//fecha
			$this->SetFont('Arial','',8);
			$this->Cell(0,10,utf8_decode('Fecha de emisión'),0,0,'R');
			$this->Ln(3);
			
			//hora
			$this->SetFont('Arial','I',8);
			$this->Cell(0,10,date("d/m/Y H:i"),0,0,'R');
		}
	}

	// Creaci&oacute;n del objeto de la clase heredada
	$pdf = new PDF();
	$pdf->AddPage();
	
	//titulo 1
	$pdf->SetFont('Arial','B',20);
	$pdf->Cell(200,10,utf8_decode('Programación Docente Inicial'),0,0,'C');
	$pdf->Ln(15);
	
	//datos profe
	$pdf->SetFont('Arial','B',11);
	$pdf->SetX(30);
	$pdf->Cell(200,10,'Nombre: ',0,0,'L');
	$pdf->SetFont('Arial','',11);
	$pdf->SetX(50);
	$pdf->Cell(200,10,'NOMBRE PROFE',0,0,'L');
	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',11);
	$pdf->SetX(30);
	$pdf->Cell(200,10,'RUT: ',0,0,'L');
	$pdf->SetFont('Arial','',11);
	$pdf->SetX(50);
	$pdf->Cell(200,10,'RUT ACADEMICO',0,0,'L');
	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',11);
	$pdf->SetX(30);
	$pdf->Cell(200,10,'Escuela: ',0,0,'L');
	$pdf->SetFont('Arial','',11);
	$pdf->SetX(50);
	$pdf->Cell(200,10,'NOMBRE ESCUELA',0,0,'L');
	$pdf->Ln(5);		
	$pdf->SetFont('Arial','B',11);
	$pdf->SetX(30);
	$pdf->Cell(200,10,'Carrera: ',0,0,'L');
	$pdf->SetFont('Arial','',11);
	$pdf->SetX(50);
	$pdf->Cell(200,10,$_GET['carre'],0,0,'L');
	$pdf->Ln(5);		
	$pdf->SetFont('Arial','B',11);
	$pdf->SetX(30);
	$pdf->Cell(200,10,'Folio: ',0,0,'L');
	$pdf->SetFont('Arial','',11);
	$pdf->SetX(50);
	$pdf->Cell(200,10,$_GET['numPDI'],0,0,'L');
	$pdf->Ln(5);		
	$pdf->SetFont('Arial','B',11);
	$pdf->SetX(30);
	$pdf->Cell(200,10,'Fecha: ',0,0,'L');
	$pdf->SetFont('Arial','',11);
	$pdf->SetX(50);
	$pdf->Cell(200,10,date("d/m/Y"),0,0,'L');
	$pdf->Ln(15);
	
	//Cuerpo
	$pdf->SetFont('Arial','',11);
	$pdf->SetX(20);
	$pdf->Cell(200,10,'Estimado Director de Escuela:',0,0,'L');
	$pdf->Ln(5);
	$pdf->SetX(30);
	$pdf->Cell(200,10, utf8_decode('Se ha realizado la siguiente Programación Docente Inicial Nº '.$_GET['numPDI'].' para '),0,0,'L');
	$pdf->Ln(5);
	$pdf->SetX(30);
	$pdf->Cell(200,10,'la Carrera de ',0,0,'L');
	$pdf->SetX(70);
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(200,10,$_GET['carre'],0,0,'L');
	$pdf->Ln(5);
	$pdf->SetX(30);
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(200,10,'al Departamento de ',0,0,'L');
	$pdf->SetX(70);
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(200,10,$_GET['depa'],0,0,'L');
	$pdf->Ln(5);
	$pdf->SetX(30);
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(200,10, utf8_decode('en esta Programación se solicitan las siguientes asignaturas: '),0,0,'L');
	$pdf->Ln(15);
	
	//titulo 2
	$pdf->SetFont('Arial','BU',11);
	$pdf->SetX(20);
	$pdf->Cell(175,10,'Listado de asignaturas',0,0,'C');
	$pdf->Ln(15);
	
	//cabecera de tabla
	$fill = false;
	$pdf->SetFont('Arial','B',11);
	$pdf->SetFillColor(208,208,208);
	$pdf->SetX(20);
	$pdf->Cell(30,8, utf8_decode('Código'),1,0,'C',true);
	$pdf->SetX(50);
	$pdf->Cell(110,8,'Nombre Ramo',1,0,'C',true);
	$pdf->SetX(160);
	$pdf->Cell(30,8,'Secciones',1,0,'C',true);
	$pdf->Ln(8);
	
	//lista ramos
	$max = count($data[0]);
	for($i = 0; $i < $max; $i++){
		$pdf->SetFont('Arial','',11);
        $pdf->SetFillColor(232,232,232);
		$pdf->SetX(20);
		$pdf->Cell(30,5,$data[0][$i],1,0,'C',$fill);
		$pdf->SetX(50);
		$pdf->Cell(110,5,mb_strtoupper(substr($data[1][$i],0,45)),1,0,'C',$fill);
		$pdf->SetX(160);
		$pdf->Cell(30,5,$data[2][$i],1,0,'C',$fill);
		$pdf->Ln(5);
		$fill = !$fill;
	}
	$pdf->Ln(15);
	
	//recordatorio
	$pdf->SetX(20);
	$pdf->Cell(200,10,'Recuerda:',0,0,'L');
	$pdf->Ln(10);
	$pdf->SetX(20);
	$pdf->Cell(200,10,'- Si deseas agregar o cambiar asignaturas, puedes realizar nuevamente el proceso de',0,0,'L');
	$pdf->Ln(5);
	$pdf->SetX(20);
	$pdf->Cell(200,10, utf8_decode('  Programación Docente Inicial. Debes seleccionar la opción Inscripción de Ramos.'),0,0,'L');
	$pdf->Ln(5);
	$pdf->SetX(20);
	$pdf->Cell(200,10, utf8_decode('  Al realizarlo, la última solicitud será la válida.'),0,0,'L');
	$pdf->Ln(5);

	
	
	$pdf->Output();
?>