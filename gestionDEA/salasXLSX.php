<?php
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../PHPExcel/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator(utf8_encode("Sergio Cerda Zúñiga"))
							 ->setLastModifiedBy(utf8_encode("UTEM"))
							 ->setTitle(utf8_encode("Asignación de Salas"))
							 ->setSubject(utf8_encode("Asignación de Salas para el Semestre en Curso"))
							 ->setDescription(utf8_encode("Asignación de salas para las distinatas salas en los distintos periodos impartidos"))
							 ->setKeywords(utf8_encode("asignación salas semestre actual universidad campus"))
							 ->setCategory(utf8_encode("Universidad"));

$link = mysqli_connect('localhost', 'dbttii', 'dbttii', "ttii");
if (mysqli_connect_errno()) echo "Falla al conectar con MySQL: " . mysqli_connect_error();

$salas1 = "SELECT * FROM `salas` WHERE `ID_sala` <> 0";
$salas2 = mysqli_query($link, $salas1) or die('Consulta fallida $salas2: '.mysqli_error($link));

$periodos1 = "SELECT * FROM `periodos` WHERE `ID_periodo` <> 0";
$periodos2 = mysqli_query($link, $periodos1) or die('Consulta fallida $periodos2: '.mysqli_error($link));

$asig_salas1 = "SELECT * FROM `asignacion_salas`";
$asig_salas2 = mysqli_query($link, $asig_salas1) or die('Consulta fallida $asig_salas2: '.mysqli_error($link));

$i = 2;
while($salas3 = mysqli_fetch_assoc($salas2)) {
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, utf8_encode($salas3["Edificio"]." ".$salas3["Nombre_sala"] ));
    $i++;
}

$lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
$columnas = $lastColumn;
$siempreA = $lastColumn;
while($periodos3 = mysqli_fetch_assoc($periodos2)) {
    $lastColumn++;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn."1",utf8_encode( $periodos3["Periodo"] ) );
}

$j = 2;
while($asig_salas3 = mysqli_fetch_assoc($asig_salas2)) {
    $columnas++;
    if($asig_salas3["ID_seccion_asignacion"] != "" || $asig_salas3["ID_seccion_asignacion"] != null) {
        $seccion1 = "SELECT * FROM `seccion_ramo_PDF` WHERE `ID_seccion_ramo_PDF` = ".$asig_salas3["ID_seccion_asignacion"];
        $seccion2 = mysqli_query($link, $seccion1) or die('Consulta fallida $seccion2: '.mysqli_error($link));
        $seccion3 = mysqli_fetch_assoc($seccion2);

        $nombre1 = "SELECT * FROM `ramos_PDF` WHERE `ID_ramos_PDF` = ".$seccion3["Ramos_PDF_id_Ramos_PDF"];
        $nombre2 = mysqli_query($link, $nombre1) or die('Consulta fallida $nombre2: '.mysqli_error($link));
        $nombre3 = mysqli_fetch_assoc($nombre2);

        $ramo1 = "SELECT * FROM `ramos` WHERE `ID_ramo` = ".$nombre3["ID_ramo"];
        $ramo2 = mysqli_query($link, $ramo1) or die('Consulta fallida $ramo2: '.mysqli_error($link));
        $ramo3 = mysqli_fetch_assoc($ramo2);

        $objPHPExcel->getActiveSheet()->setCellValue($columnas.$j, utf8_encode($ramo3["Nombre_ramo"]." Sec.: ".$seccion3["Numero_seccion"]." PDF: ".$nombre3["PDF_id_PDF"]));
    } else {
        $objPHPExcel->getActiveSheet()->setCellValue($columnas.$j, utf8_encode($asig_salas3["ID_seccion_asignacion"]));
    }
    if($asig_salas3["ID_periodo_asignacion"]==29){
        $j++;
        $columnas = $siempreA;
    }
}

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle(utf8_encode('Salas'));


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="AsignacionSalas.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>
