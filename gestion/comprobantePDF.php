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
	$pdf->Cell(200,10,utf8_decode('Programación Docente Final'),0,0,'C');
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
	$pdf->Cell(200,10,$_GET['numPDF'],0,0,'L');
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
	$pdf->Cell(200,10, utf8_decode('Se ha realizado la siguiente Programación Docente Final Nº '.$_GET['numPDF'].' para '),0,0,'L');
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
    if($_GET['pdf_old'] != "") {
        $pdf->Ln(5);
        $pdf->SetX(30);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(200,10, utf8_decode('y se ha cancelado la Programación Docente Final Nº '.$_GET['pdf_old']),0,0,'L');
    }
	$pdf->Ln(5);
	$pdf->SetX(30);
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(200,10, utf8_decode('esta solicitud contiene las siguientes asignaturas: '),0,0,'L');
	$pdf->Ln(15);
	
	//titulo 2
	$pdf->SetFont('Arial','BU',11);
	$pdf->SetX(20);
	$pdf->Cell(175,10,'Listado de asignaturas',0,0,'C');
	$pdf->Ln(15);
	
	$max = count($data[0]);
	for($i = 0; $i < $max; $i++){
        $pdf->SetFont('Arial','B',11);
        $pdf->SetFillColor(208,208,208);
        $pdf->SetX(20);
        $pdf->Cell(30,10, utf8_decode('Código'),1,0,'C',true);
        $pdf->SetX(50);
        $pdf->Cell(110,10,'Nombre Ramo',1,0,'C',true);
        $pdf->SetX(160);
        $pdf->Cell(30,10,'Secciones',1,0,'C',true);
        $pdf->Ln(10);
		$pdf->SetFont('Arial','',11);		
		$pdf->SetX(20);
		$pdf->Cell(30,8,$data[0][$i],1,0,'C',false);
		$pdf->SetX(50);
		$pdf->Cell(110,8,mb_strtoupper(substr($data[1][$i],0,45)),1,0,'C',false);
		$pdf->SetX(160);
		$pdf->Cell(30,8,$data[2][$i],1,0,'C',false);
        $pdf->SetX(10);
		$pdf->Ln(8);
		
        for($j = 0; $j < $data[2][$i]; $j++) {
            $pdf->SetFont('Arial','B',11);
            $pdf->SetFillColor(232,232,232);
            $pdf->SetX(30);
            $pdf->Cell(150,8, utf8_decode('Sección Nº ').$data[3][$i][$j],1,0,'C',true);
            $pdf->Ln(8);
            
            $link = mysqli_connect('localhost', 'dbttii', 'dbttii', "ttii");
            if (mysqli_connect_errno()) echo "Falla al conectar con MySQL: " . mysqli_connect_error();
            
            $horario01 = "SELECT `Periodo` FROM `periodos` WHERE `ID_periodo` = ".$data[4][$i][$j][0];
            $horario02 = mysqli_query($link, $horario01) or die('Consulta fallida $horario02: '.mysqli_error($link));
            $horario03 = mysqli_fetch_assoc($horario02);
            $horario04 = $horario03['Periodo'];

            $horario11 = "SELECT `Periodo` FROM `periodos` WHERE `ID_periodo` = ".$data[4][$i][$j][1];
            $horario12 = mysqli_query($link, $horario11) or die('Consulta fallida $horario12: '.mysqli_error($link));
            $horario13 = mysqli_fetch_assoc($horario12);
            $horario14 = $horario13['Periodo'];

            $horario21 = "SELECT `Periodo` FROM `periodos` WHERE `ID_periodo` = ".$data[4][$i][$j][2];
            $horario22 = mysqli_query($link, $horario21) or die('Consulta fallida $horario22: '.mysqli_error($link));
            $horario23 = mysqli_fetch_assoc($horario22);
            $horario24 = $horario23['Periodo'];
            
            $sala01 = "SELECT * FROM `salas` WHERE `ID_sala`= ".$data[7][$i][$j][0];
            $sala02 = mysqli_query($link, $sala01) or die('Consulta fallida $sala02: '.mysqli_error($link));
            $sala03 = mysqli_fetch_assoc($sala02);
            $sala041 = $sala03['Nombre_sala'];
            $sala042 = $sala03['Edificio'];
            
            $sala11 = "SELECT * FROM `salas` WHERE `ID_sala`= ".$data[7][$i][$j][1];
            $sala12 = mysqli_query($link, $sala11) or die('Consulta fallida $sala12: '.mysqli_error($link));
            $sala13 = mysqli_fetch_assoc($sala12);
            $sala141 = $sala13['Nombre_sala'];
            $sala142 = $sala13['Edificio'];
            
            if($data[7][$i][$j][2] != 0){
                $sala21 = "SELECT * FROM `salas` WHERE `ID_sala`= ".$data[7][$i][$j][2];
                $sala22 = mysqli_query($link, $sala21) or die('Consulta fallida $sala22: '.mysqli_error($link));
                $sala23 = mysqli_fetch_assoc($sala22);
                $sala241 = $sala23['Nombre_sala'];
                $sala242 = $sala23['Edificio'];
            } else {
                $sala242 = "Sin Periodo";
                $sala241 = "";
            }
            mysqli_close($link);
            
            $pdf->SetX(30);
            $pdf->Cell(25,5, utf8_decode('Horario 1'),1,0,'L',true);
            $pdf->SetX(55);
            $pdf->Cell(55,5, $horario04,1,0,'C',false);
            $pdf->SetX(110);
            $pdf->Cell(25,5, utf8_decode('Sala 1'),1,0,'L',true);
            $pdf->SetX(135);
            $pdf->Cell(45,5, $sala042." ".$sala041 ,1,0,'C',false);
            $pdf->Ln(5);
            
            $pdf->SetX(30);
            $pdf->Cell(25,5, utf8_decode('Horario 2'),1,0,'L',true);
            $pdf->SetX(55);
            $pdf->Cell(55,5, $horario14,1,0,'C',false);
            $pdf->SetX(110);
            $pdf->Cell(25,5, utf8_decode('Sala 2'),1,0,'L',true);
            $pdf->SetX(135);
            $pdf->Cell(45,5, $sala142." ".$sala141 ,1,0,'C',false);
            $pdf->Ln(5);
            
            $pdf->SetX(30);
            $pdf->Cell(25,5, utf8_decode('Horario 3'),1,0,'L',true);
            $pdf->SetX(55);
            $pdf->Cell(55,5, $horario24,1,0,'C',false);
            $pdf->SetX(110);
            $pdf->Cell(25,5, utf8_decode('Sala 3'),1,0,'L',true);
            $pdf->SetX(135);
            $pdf->Cell(45,5, $sala242." ".$sala241 ,1,0,'C',false);
            $pdf->Ln(5);
            
            $pdf->SetX(30);
            $pdf->Cell(45,5,'Profesor',1,0,'L',true);
            $pdf->SetX(75);
            $pdf->Cell(105,5,$data[5][$i][$j],1,0,'C',false);
            $pdf->Ln(5);
            
            $pdf->SetX(30);
            $pdf->Cell(45,5,"Cantidad de Alumnos",1,0,"L",true);
            $pdf->SetX(75);
            $pdf->Cell(105,5,$data[6][$i][$j],1,0,'C',false);
            $pdf->Ln(5);
        }
        $pdf->Ln(5);
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
	$pdf->Cell(200,10, utf8_decode('  Programación Docente Final. Debes seleccionar la opción Inscripción de Ramos.'),0,0,'L');
	$pdf->Ln(5);
	$pdf->SetX(20);
	$pdf->Cell(200,10, utf8_decode('  Al realizarlo, la última solicitud será la válida.'),0,0,'L');
	$pdf->Ln(5);

	
	
	$pdf->Output();
?>
