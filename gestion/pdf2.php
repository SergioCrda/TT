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
                $alumnos[$i] = $_POST['cant_alumnos'.$i];
            }
            $data = array($codramos, $ramos, $cantidadSecciones, $noSeccion, $horario, $profe, $alumnos);
            function array_envia($array) {
				$tmp = serialize($array); 
				$tmp = urlencode($tmp); 
				return $tmp; 
			}
			$data = array_envia($data);

            //conexion a base de datos
            $link = mysql_connect('localhost', 'dbttii', 'dbttii') or die('No se pudo conectar: ' . mysql_error());
            mysql_select_db('ttii') or die('No se pudo seleccionar la base de datos');

            //obtiene el ID_depto
            $consultaID_depto1 = "SELECT `ID_depto` FROM `departamentos` WHERE `Nombre_depto` = '".$depto."'";
            $consultaID_depto2 = mysql_query($consultaID_depto1) or die('Consulta fallida: ' . mysql_error());
            $filaDepto = mysql_fetch_assoc($consultaID_depto2);
            $ID_depto = $filaDepto['ID_depto'];

            //obtiene el ID_carrera
            $consultaID_carrera1 = "SELECT `ID_carrera` FROM `carreras` WHERE `Nombre_carrera` = '".$carrera."'";
            $consultaID_carrera2 = mysql_query($consultaID_carrera1) or die('Consulta fallida: ' . mysql_error());
            $filaCarrera = mysql_fetch_assoc($consultaID_carrera2);
            $ID_carrera = $filaCarrera['ID_carrera'];

            //inserta datos en PDF
            $fechaHora = date('Y-m-j H:i:s');
            $nuevaPDF1 = "INSERT INTO `PDF`(`Estado`, `Nombre_docente`, `ID_profesor`, `ID_escuela`, `Fecha_PDF` , `carreras_ID_carrera`, `departamentos_ID_depto` , `ID_PDI`) VALUES ('".utf8_decode('Revisión Decanato')."' ,'NOMBRE_PRUEBA', 1, 1, '".$fechaHora."', '".$ID_carrera."','".$ID_depto."' ,'".$idPDI."')";
            $nuevaPDF2 = mysql_query($nuevaPDF1) or die('Consulta fallida: ' . mysql_error());

            //obtiene el ID_PDF
            $conocePDF = "SELECT `ID_PDF` FROM `PDF` WHERE `Nombre_docente` = 'NOMBRE_PRUEBA' AND `ID_profesor` = 1 AND `ID_escuela` = 1 AND `Fecha_PDF` = '".$fechaHora."' AND `carreras_ID_carrera` = '".$ID_carrera."' AND `departamentos_ID_depto` = '".$ID_depto."' ";
            $consultaPDF = mysql_query($conocePDF) or die('Consulta fallida: ' . mysql_error());
            $fila1 = mysql_fetch_assoc($consultaPDF);
            $ID_PDF = $fila1['ID_PDF'];

            //inserta los ramos en PDF
            for($i = 0; $i < count($ramos); $i++){
                //obtiene el ID_ramo
                $conoceIDRAMO = "SELECT `ID_ramo` FROM `ramos` WHERE `Nombre_ramo` = '".$ramos[$i]."'";
                $consultaRAMO = mysql_query($conoceIDRAMO) or die('Consulta fallida: ' . mysql_error());
                $fila2 = mysql_fetch_assoc($consultaRAMO);
                $ID_ramo = $fila2['ID_ramo'];

                //inserta los ramos
                $nuevoRamo1 = "INSERT INTO `ramos_PDF`(`ID_ramo`, `Cantidad_secciones`, `PDF_ID_PDF`) VALUES (".$ID_ramo.",".$cantidadSecciones[$i].",".$ID_PDF.")";
                $nuevoRamo2 = mysql_query($nuevoRamo1) or die('Consulta fallida: ' . mysql_error());

                //obtiene el ID_ramo_PDI
                $conoceIDRAMOPDF = "SELECT `ID_ramos_PDF` FROM `ramos_PDF` WHERE `Cantidad_secciones` = '".$cantidadSecciones[$i]."' AND `ID_ramo` = '".$ID_ramo."' AND `PDF_ID_PDF` = '".$ID_PDF."'";
                $consultaRAMOPDF = mysql_query($conoceIDRAMOPDF) or die('Consulta fallida: ' . mysql_error());
                $fila3 = mysql_fetch_assoc($consultaRAMOPDF);
                $ID_ramo_PDF = $fila3['ID_ramos_PDF'];

                //inserta las secciones de un ramo
                for($j = 0; $j < count($horario[$i]); $j++){
                    if($horario[$i][$j][2]==""){
                        $nuevaSeccion1 = "INSERT INTO `seccion_ramo_PDF`(`Numero_seccion`, `Ramos_PDF_id_Ramos_PDF`, `Horario_1`, `Horario_2`, `Horario_3`, `Nombre_docente`, `Cantidad_alumnos`) VALUES (".($j+1).", '".$ID_ramo_PDF."', ".$horario[$i][$j][0].", ".$horario[$i][$j][1].", 0, ".$profe[$i][$j].",".$alumnos[$i][$j].")";
                    } else {
                        $nuevaSeccion1 = "INSERT INTO `seccion_ramo_PDF`(`Numero_seccion`, `Ramos_PDF_id_Ramos_PDF`, `Horario_1`, `Horario_2`, `Horario_3`, `Nombre_docente`, `Cantidad_alumnos`) VALUES (".($j+1).", '".$ID_ramo_PDF."', ".$horario[$i][$j][0].", ".$horario[$i][$j][1].", ".$horario[$i][$j][2].", ".$profe[$i][$j].",".$alumnos[$i][$j].")";
                    }
                    $nuevaSeccion2 = mysql_query($nuevaSeccion1) or die('Consulta fallida: ' . mysql_error());
                }
            }
            
            //actualiza estado PDI 
            $actualizarPDI1 = "UPDATE `PDI` SET `Estado`='".utf8_decode('En Proceso PDF')."' WHERE `ID_PDI`= ".$idPDI;
            $actualizarPDI2 = mysql_query($actualizarPDI1) or die('Consulta fallida: ' . mysql_error());
        ?>
        <div>
			<dd>
				<strong>Estimado Director de Escuela:</strong></br></br>
				Se ha realizado la siguiente <strong>Programaci&oacute;n Docente Final</strong> N&deg;<?php echo $ID_PDF; ?> <strong></strong> para la carrera de <strong><?php echo $carrera; ?></strong> 
				al Departamento de <strong><?php echo $depto; ?></strong>, en esta Programaci&oacute;n se solicitan las siguientes asignaturas: </br></br>
				<center><strong><ins>Listado de asignaturas</ins></strong></center></br>			
				<table align="center" border="1" cellspacing="0" cellpadding="3" class="pequena" width="80%">
					<?php
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
                            echo "<table align='center' border='1' cellspacing='0' cellpadding='3' width='90%'>";
                            echo "<tr class='titulo_fila'>";
                            echo "<td>N&deg; de Secci&oacute;n</td>";
                            echo "<td width='150px'>Horario 1</td>";
                            echo "<td width='150px'>Horario 2</td>";
                            echo "<td width='150px'>Horario 3</td>";
                            echo "<td width='200px'>Profesor</td>";
                            echo "<td>Cantidad de Estudiantes</td>";
                            echo "</tr>";
                            $max2 = $cantidadSecciones[$i];
                            for($j = 0; $j < $max2; $j++){
                                echo "<tr class='centro'>";
                                echo "<td>".$noSeccion[$i][$j]."</td>";
                                for($k = 0; $k < 3; $k++) {
                                    $horarioE1 = "SELECT `Periodo` FROM `periodos` WHERE `ID_periodo` = ".$horario[$i][$j][$k];
                                    $horarioE2 = mysql_query($horarioE1) or die('Consulta fallida: '.mysql_error());
                                    $horarioE3 = mysql_fetch_assoc($horarioE2);
                                    $horarioE4 = $horarioE3['Periodo'];
                                    echo "<td>".$horarioE4."</td>";
                                }
                                echo "<td>".$profe[$i][$j]."</td>";
                                echo "<td>".$alumnos[$i][$j]."</td>";
                                echo "</tr>";
                            }
                            echo "</table>";
                            echo "<br>";
                            echo "</th>";
                            echo "</tr>";
						}
                        //1. ID_profesor debe ir rut o id de profesor segun la tabla y relacionarlos
                        //2. ID_escuela debe ir id de escuela segùn el profesor y relacionarlos
                        //3. validar profesores
                        //4. agregar sala
                        //5. validar capacidad de sala con cantidad ingresada en la seccion
					?>
				</table>
				</br></br>
				Recuerda:</br></br>
				- Si deseas agregar o cambiar asignaturas, puedes realizar nuevamente el proceso de Programaci&oacute;n Docente Final.</br>
				Debes seleccionar la opci&oacute;n Inscripci&oacute;n de Ramos. Al realizarlo, la &uacute;ltima solicitud ser&aacute; la v&aacute;lida</br>
				- Si deseas descargar un comprobante has clic 
				<a href="comprobantePDF.php?depa=<?php echo $depto; ?>&carre=<?php echo $carrera; ?>&numPDF=<?php echo $ID_PDF; ?>&data=<?php echo $data; ?>" target="_blank">aqu&iacute;.</a></br></br>
			</dd>
		</div>
	</body>
</html>