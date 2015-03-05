<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
<html >
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Creaci&oacute;n de Programaci&oacute;n Docente Inicial</title>
		<link rel="StyleSheet" type="text/css" href="gestion.css">
		<script src="../general/ayuda.js" type="text/javascript" language="JavaScript"></script>
		<script src="../general/prototype.js" type="text/javascript" language="JavaScript"></script>
	</head>
	<body>
		<div id="fecha">
			<?php echo date("d/m/Y"). "<br/>" . date("H:i");?>
		</div>
		<h3>Programaci&oacute;n Docente Inicial<br><small>Selecci&oacute;n de docente por secci&oacute;n</small></h3>
		<?php
			$idPDI = $_POST['id_pdi'];
			$pdf_old = $_POST['pdf_old'];
			$fechaPDI = $_POST['fecha_pdi'];
			$nombreDocente = $_POST['nombre_docente'];
			$IDEscuela = $_POST['ID_Escuela'];
			$depto = $_POST['depto'];
			$carrera = $_POST['carrera'];
			$codramos = $_POST['codramos'];
            $ramos = $_POST['ramos'];
            $cantidadSecciones = $_POST['cantidad_secciones'];
            for($i = 0; $i < count($ramos); $i++) {
                $noSeccion[$i] = $_POST['no_seccion'.$i];
            }
            for($i = 0; $i < count($ramos); $i++) {
                $horario[$i] = $_POST['horario'.$i];
            }
            for($i = 0; $i < count($ramos); $i++) {
                $profe[$i] = $_POST['profe_id'.$i];
            }
            for($i = 0; $i < count($ramos); $i++) {
                $sala[$i] = $_POST['salas_id'.$i];
            }
            for($i = 0; $i < count($ramos); $i++) {
                $alumnos[$i] = $_POST['cant_alumnos'.$i];
            }
            $data = array($codramos, $ramos, $cantidadSecciones, $noSeccion, $horario, $profe, $alumnos, $sala);
            function array_envia($array) {
				$tmp = serialize($array);
				$tmp = urlencode($tmp);
				return $tmp;
			}
			$data = array_envia($data);
            $reversar = false;
            //conexion a base de datos
            $link = mysql_connect('localhost', 'dbttii', 'dbttii') or die('No se pudo conectar: ' . mysql_error());
            mysql_select_db('ttii') or die('No se pudo seleccionar la base de datos');

            //obtiene el ID_depto
            $consultaID_depto1 = "SELECT `ID_depto` FROM `departamentos` WHERE `Nombre_depto` = '".$depto."'";
            $consultaID_depto2 = mysql_query($consultaID_depto1) or die('Consulta fallida $consultaID_depto2: ' . mysql_error());
            $consultaID_depto3 = mysql_fetch_assoc($consultaID_depto2);

            //obtiene el ID_carrera
            $consultaID_carrera1 = "SELECT `ID_carrera` FROM `carreras` WHERE `Nombre_carrera` = '".$carrera."'";
            $consultaID_carrera2 = mysql_query($consultaID_carrera1) or die('Consulta fallida $consultaID_carrera2: ' . mysql_error());
            $consultaID_carrera3 = mysql_fetch_assoc($consultaID_carrera2);

            //inserta datos en PDF
            $fechaHora = date('Y-m-j H:i:s');
            if($pdf_old == "") {
                $nuevaPDF1 = "INSERT INTO `PDF`(`Estado_PDF`,`Nombre_docente`,`ID_profesor`,`ID_escuela`,`Fecha_PDF`,`carreras_ID_carrera`,`departamentos_ID_depto`,`ID_PDI`) VALUES (12,'NOMBRE_PRUEBA',1,1,'".$fechaHora."','".$consultaID_carrera3['ID_carrera']."','".$consultaID_depto3['ID_depto']."','".$idPDI."')";
            } else {
                $nuevaPDF1 = "INSERT INTO `PDF`(`Estado_PDF`,`Nombre_docente`,`ID_profesor`,`ID_escuela`,`Fecha_PDF`,`carreras_ID_carrera`,`departamentos_ID_depto`,`ID_PDI`,`PDF_cancelado`) VALUES (12,'NOMBRE_PRUEBA',1,1,'".$fechaHora."','".$consultaID_carrera3['ID_carrera']."','".$consultaID_depto3['ID_depto']."','".$idPDI."','".$pdf_old."')";
            }
            $nuevaPDF2 = mysql_query($nuevaPDF1) or die('Consulta fallida $nuevaPDF2 $nuevaPDF2: ' . mysql_error());

            //actualiza estado PDF
            if($pdf_old != "") {
                $actualizarPDI1 = "UPDATE `PDF` SET `Estado_PDF`= 17 WHERE `ID_PDF`= '".$pdf_old."'";
                $actualizarPDI2 = mysql_query($actualizarPDI1) or die('Consulta fallida $actualizarPDI2: ' . mysql_error());
            }

            //obtiene el ID_PDF
            $conocePDF1 = "SELECT `ID_PDF` FROM `PDF` WHERE `Nombre_docente` = 'NOMBRE_PRUEBA' AND `ID_profesor` = 1 AND `ID_escuela` = 1 AND `Fecha_PDF` = '".$fechaHora."' AND `carreras_ID_carrera` = '".$consultaID_carrera3['ID_carrera']."' AND `departamentos_ID_depto` = '".$consultaID_depto3['ID_depto']."' ";
            $conocePDF2 = mysql_query($conocePDF1) or die('Consulta fallida $conocePDF2: ' . mysql_error());
            $conocePDF3 = mysql_fetch_assoc($conocePDF2);
            $ID_PDF = $conocePDF3['ID_PDF'];

            //inserta los ramos en PDF
            for($i = 0; $i < count($ramos); $i++){
                //obtiene el ID_ramo
                $conoceIDRAMO1 = "SELECT `ID_ramo` FROM `ramos` WHERE `Nombre_ramo` = '".$ramos[$i]."'";
                $conoceIDRAMO2 = mysql_query($conoceIDRAMO1) or die('Consulta fallida $conoceIDRAMO2: ' . mysql_error());
                $conoceIDRAMO3 = mysql_fetch_assoc($conoceIDRAMO2);
                $ID_ramo = $conoceIDRAMO3['ID_ramo'];

                //inserta los ramos
                $nuevoRamo1 = "INSERT INTO `ramos_PDF`(`ID_ramo`, `Cantidad_secciones`, `PDF_ID_PDF`) VALUES (".$ID_ramo.",".$cantidadSecciones[$i].",".$ID_PDF.")";
                $nuevoRamo2 = mysql_query($nuevoRamo1) or die('Consulta fallida $nuevoRamo2: ' . mysql_error());

                //obtiene el ID_ramo_PDI
                $conoceIDRAMOPDF1 = "SELECT `ID_ramos_PDF` FROM `ramos_PDF` WHERE `Cantidad_secciones` = '".$cantidadSecciones[$i]."' AND `ID_ramo` = '".$ID_ramo."' AND `PDF_ID_PDF` = '".$ID_PDF."'";
                $conoceIDRAMOPDF2 = mysql_query($conoceIDRAMOPDF1) or die('Consulta fallida $conoceIDRAMOPDF2: ' . mysql_error());
                $conoceIDRAMOPDF3 = mysql_fetch_assoc($conoceIDRAMOPDF2);
                $ID_ramo_PDF = $conoceIDRAMOPDF3['ID_ramos_PDF'];

                //inserta las secciones de un ramo
                for($j = 0; $j < count($horario[$i]); $j++){
                    if($horario[$i][$j][2]==""){
                        $nuevaSeccion1 = "INSERT INTO `seccion_ramo_PDF` (`Numero_seccion`, `Ramos_PDF_id_Ramos_PDF`, `Horario_1`, `Horario_2`, `Horario_3`, `Sala_1`, `Sala_2`, `Sala_3`, `Nombre_docente`, `Cantidad_alumnos`)
                                        VALUES (".($j+1).", '".$ID_ramo_PDF."', ".$horario[$i][$j][0].", ".$horario[$i][$j][1].", 0, ".$sala[$i][$j][0].", ".$sala[$i][$j][1].", 0, " .$profe[$i][$j].", ".$alumnos[$i][$j].")";
                    } else {
                        $nuevaSeccion1 = "INSERT INTO `seccion_ramo_PDF`(`Numero_seccion`, `Ramos_PDF_id_Ramos_PDF`, `Horario_1`, `Horario_2`, `Horario_3`, `Sala_1`, `Sala_2`, `Sala_3`, `Nombre_docente`, `Cantidad_alumnos`)
                                        VALUES (".($j+1).", '".$ID_ramo_PDF."', ".$horario[$i][$j][0].", ".$horario[$i][$j][1].", ".$horario[$i][$j][2].", ".$sala[$i][$j][0].", ".$sala[$i][$j][1].", ".$sala[$i][$j][2].", ".$profe[$i][$j].", ".$alumnos[$i][$j].")";
                    }
                    $nuevaSeccion2 = mysql_query($nuevaSeccion1) or die('Consulta fallida $nuevaSeccion2: ' . mysql_error());

                    $seccionRamoPDI1 = "SELECT * FROM `seccion_ramo_PDF` WHERE `Numero_seccion` = ".($j+1)." AND `Ramos_PDF_id_Ramos_PDF` = ".$ID_ramo_PDF;
                    $seccionRamoPDI2 = mysql_query($seccionRamoPDI1) or die('Consulta fallida $seccionRamoPDI2: ' . mysql_error());
                    $seccionRamoPDI3 = mysql_fetch_assoc($seccionRamoPDI2);

                    $agregarSala11 = "UPDATE `asignacion_salas` SET `ID_PDF_asignacion` = ".$ID_PDF.", `ID_ramos_asignacion`= ".$ID_ramo_PDF.", `ID_seccion_asignacion` = ".$seccionRamoPDI3['ID_seccion_ramo_PDF']." WHERE `ID_sala_asignacion` = ".$sala[$i][$j][0]." AND `ID_periodo_asignacion` = ".$horario[$i][$j][0]." AND `ID_PDF_asignacion` IS NULL AND `ID_ramos_asignacion` IS NULL AND `ID_seccion_asignacion` IS NULL";
                    $agregarSala12 = mysql_query($agregarSala11) or die('Consulta fallida $agregarSala12: ' . mysql_error());
                    $colAgregarSala1 = mysql_affected_rows();

                    $agregarSala21 = "UPDATE `asignacion_salas` SET `ID_PDF_asignacion` = ".$ID_PDF.", `ID_ramos_asignacion`= ".$ID_ramo_PDF.", `ID_seccion_asignacion` = ".$seccionRamoPDI3['ID_seccion_ramo_PDF']." WHERE `ID_sala_asignacion` = ".$sala[$i][$j][1]." AND `ID_periodo_asignacion` = ".$horario[$i][$j][1]." AND `ID_PDF_asignacion` IS NULL AND `ID_ramos_asignacion` IS NULL AND `ID_seccion_asignacion` IS NULL";
                    $agregarSala22 = mysql_query($agregarSala21) or die('Consulta fallida $agregarSala22: ' . mysql_error());
                    $colAgregarSala2 = mysql_affected_rows();

                    if($sala[$i][$j][2] != 0 && $horario[$i][$j][2] != 0){
                        $agregarSala31 = "UPDATE `asignacion_salas` SET `ID_PDF_asignacion` = ".$ID_PDF.", `ID_ramos_asignacion`= ".$ID_ramo_PDF.", `ID_seccion_asignacion` = ".$seccionRamoPDI3['ID_seccion_ramo_PDF']." WHERE `ID_sala_asignacion` = ".$sala[$i][$j][2]." AND `ID_periodo_asignacion` = ".$horario[$i][$j][2]." AND `ID_PDF_asignacion` IS NULL AND `ID_ramos_asignacion` IS NULL AND `ID_seccion_asignacion` IS NULL";
                        $agregarSala32 = mysql_query($agregarSala31) or die('Consulta fallida $agregarSala32: ' . mysql_error());
                        $colAgregarSala3 = mysql_affected_rows();
                    }
                    if($sala[$i][$j][2] != 0 && $horario[$i][$j][2] != 0){
                        if($colAgregarSala1 != 1 || $colAgregarSala2 != 1 || $colAgregarSala3 != 1){
                            //echo "<br> hay que reversar :( ";

                            //determinar las salas que están con su mismo numero de PDF y dejarlas en null
                            //borrar el PDF creado, los ramos del PDF y las secciones de los ramos del PDF
                            $reversar = true;
                            echo $reversar;
                        }
                    } else {
                        if($colAgregarSala1 != 1 || $colAgregarSala2 != 1){
                            //echo "<br> hay que reversar :( ";
                            $reversar = true;
                            echo $reversar;
                        }
                    }
                }
            }
            echo "<div><dd>";
            echo "<strong>Estimado Director de Escuela:</strong></br></br>";
            if($reversar == false){
                //actualiza estado PDI
                $actualizarPDI1 = "UPDATE `PDI` SET `Estado_PDI`= 5 WHERE `ID_PDI`= '".$idPDI."'";
                $actualizarPDI2 = mysql_query($actualizarPDI1) or die('Consulta fallida $actualizarPDI2: ' . mysql_error());

                echo "Se ha realizado la siguiente <strong>Programaci&oacute;n Docente Final</strong> N&deg;".$ID_PDF;
                echo " para la carrera de <strong>".$carrera."</strong>";
                echo " al Departamento de <strong>".$depto."</strong>";
                if($pdf_old != "") {
                    $PDF_cancelado = ' y se ha cancelado la Programaci&oacute;n Docente Final N&deg; '.$pdf_old;
                    echo $PDF_cancelado;
                }
                echo ", esta solicitud contiene las siguientes asignaturas: </br></br>";
                echo "<center><strong><ins>Listado de asignaturas</ins></strong></center></br>";
                echo "<table align='center' border='1' cellspacing='0' cellpadding='3' class='pequena' width='80%'>";
                $max1 = count($ramos);
                for($i = 0; $i < $max1; $i++){
                    echo "<tr class='titulo_fila'>";
                    echo "<td>C&oacute;digo</td>";
                    echo "<td>Nombre Ramo</td>";
                    echo "<td>Cantiadad de Secciones</td>";
                    echo "</tr>";
                    echo "<tr class='centro'>";
                    echo "<td>".$codramos[$i]."</td>";
                    echo "<td>".$ramos[$i]."</td>";
                    echo "<td>".$cantidadSecciones[$i]."</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<th colspan='3'>";
                    echo "<br>";
                    $max2 = $cantidadSecciones[$i];
                    for($j = 0; $j < $max2; $j++){
                        echo "<table align='center' border='1' cellspacing='0' cellpadding='3' width='700px' class='media'>";
                        echo "<tr><td class='titulo_fila media' colspan='4'>Secci&oacute;n N&uacute;mero ".$noSeccion[$i][$j]."</td></tr>";
                        for($k = 0; $k < 3; $k++) {
                            $horarioE1 = "SELECT `Periodo` FROM `periodos` WHERE `ID_periodo` = ".$horario[$i][$j][$k];
                            $horarioE2 = mysql_query($horarioE1) or die('Consulta fallida $horarioE2: '.mysql_error());
                            $horarioE3 = mysql_fetch_assoc($horarioE2);

                            $salaE1 = "SELECT * FROM `salas` WHERE `ID_sala` = ".$sala[$i][$j][$k];
                            $salaE2 = mysql_query($salaE1) or die('Consulta fallida $salaE2: '.mysql_error());
                            $salaE3 = mysql_fetch_assoc($salaE2);

                            echo "<tr>";
                            echo "<td class='titulo_fila' width='35%'>Horario ".($k+1)."</td>";
                            echo "<td>".$horarioE3['Periodo']."</td>";
                            echo "<td class='titulo_fila' width='25%'>Sala ".($k+1)."</td>";
                            if($sala[$i][$j][$k] == 0){
                                echo "<td>Sin Periodo</td>";
                            } else {
                                echo "<td>".$salaE3['Edificio']." ".$salaE3['Nombre_sala']."</td>";
                            }
                            echo "</tr>";
                        }
                        echo "<tr>";
                        echo "<td class='titulo_fila'>Profesor</td>";
                        echo "<td colspan='3'>".$profe[$i][$j]."</td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td class='titulo_fila'>Cantidad de Estudiantes</td>";
                        echo "<td colspan='3'>".$alumnos[$i][$j]."</td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "<br>";
                    }

                    echo "<br>";
                    echo "</th>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "</br></br>";
                echo "Recuerda:</br></br>";
                echo "- Si deseas agregar o cambiar asignaturas, puedes realizar nuevamente el proceso de Programaci&oacute;n Docente Final.</br>";
                echo "Debes seleccionar la opci&oacute;n Inscripci&oacute;n de Ramos. Al realizarlo, la &uacute;ltima solicitud ser&aacute; la v&aacute;lida</br>";
                echo "- Si deseas descargar un comprobante has clic ";
                $pdfArchivo = '<a href="comprobantePDF.php?depa='.$depto.'&carre='.$carrera.'&numPDF='.$ID_PDF.'&data='.$data;
                if($pdf_old != "") {
                    $pdfArchivo = $pdfArchivo.'&pdf_old='.$pdf_old;
                }
                $pdfArchivo = $pdfArchivo.'" target="_blank">aqu&iacute;.</a></br></br>';
                echo $pdfArchivo;

            } else {
                //hay que reversar

                //mensaje al usuario
                echo "Ha ocurrido un error al realizar la solicitud, esta pudo fallar por los siguientes motivos:<br>";
                echo "<ul><li>Las Salas indicadas ya han sido seleccionadas, favor intente nuevamente</li>";
                echo "<li>La Base de Datos est&aacute; abajo, favor contacte al Administrador de Base de Datos</li></ul>";
            }
            echo "</dd></div>";
        ?>
	</body>
</html>
