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

            //conexion a base de datos
            $link = mysql_connect('localhost', 'dbttii', 'dbttii') or die('No se pudo conectar: ' . mysql_error());
            mysql_select_db('ttii') or die('No se pudo seleccionar la base de datos');

            //obtiene el ID_depto
            $consultaID_depto1 = "SELECT `ID_depto` FROM `departamentos` WHERE `Nombre_depto` = '".$depto."'";
            $consultaID_depto2 = mysql_query($consultaID_depto1) or die('Consulta fallida: ' . mysql_error());
            $consultaID_depto3 = mysql_fetch_assoc($consultaID_depto2);

            //obtiene el ID_carrera
            $consultaID_carrera1 = "SELECT `ID_carrera` FROM `carreras` WHERE `Nombre_carrera` = '".$carrera."'";
            $consultaID_carrera2 = mysql_query($consultaID_carrera1) or die('Consulta fallida: ' . mysql_error());
            $consultaID_carrera3 = mysql_fetch_assoc($consultaID_carrera2);

            //inserta datos en PDF
            $fechaHora = date('Y-m-j H:i:s');
            $nuevaPDF1 = "INSERT INTO `PDF`(`Estado_PDF`,`Nombre_docente`,`ID_profesor`,`ID_escuela`,`Fecha_PDF`,`carreras_ID_carrera`,`departamentos_ID_depto`,`ID_PDI`) VALUES (12,'NOMBRE_PRUEBA',1,1,'".$fechaHora."','".$consultaID_carrera3['ID_carrera']."','".$consultaID_depto3['ID_depto']."','".$idPDI."')";
            $nuevaPDF2 = mysql_query($nuevaPDF1) or die('Consulta fallida: ' . mysql_error());

            //obtiene el ID_PDF
            $conocePDF1 = "SELECT `ID_PDF` FROM `PDF` WHERE `Nombre_docente` = 'NOMBRE_PRUEBA' AND `ID_profesor` = 1 AND `ID_escuela` = 1 AND `Fecha_PDF` = '".$fechaHora."' AND `carreras_ID_carrera` = '".$consultaID_carrera3['ID_carrera']."' AND `departamentos_ID_depto` = '".$consultaID_depto3['ID_depto']."' ";
            $conocePDF2 = mysql_query($conocePDF1) or die('Consulta fallida: ' . mysql_error());
            $conocePDF3 = mysql_fetch_assoc($conocePDF2);
            $ID_PDF = $conocePDF3['ID_PDF'];

            //inserta los ramos en PDF
            for($i = 0; $i < count($ramos); $i++){
                //obtiene el ID_ramo
                $conoceIDRAMO1 = "SELECT `ID_ramo` FROM `ramos` WHERE `Nombre_ramo` = '".$ramos[$i]."'";
                $conoceIDRAMO2 = mysql_query($conoceIDRAMO1) or die('Consulta fallida: ' . mysql_error());
                $conoceIDRAMO3 = mysql_fetch_assoc($conoceIDRAMO2);
                $ID_ramo = $conoceIDRAMO3['ID_ramo'];

                //inserta los ramos
                $nuevoRamo1 = "INSERT INTO `ramos_PDF`(`ID_ramo`, `Cantidad_secciones`, `PDF_ID_PDF`) VALUES (".$ID_ramo.",".$cantidadSecciones[$i].",".$ID_PDF.")";
                $nuevoRamo2 = mysql_query($nuevoRamo1) or die('Consulta fallida: ' . mysql_error());

                //obtiene el ID_ramo_PDI
                $conoceIDRAMOPDF1 = "SELECT `ID_ramos_PDF` FROM `ramos_PDF` WHERE `Cantidad_secciones` = '".$cantidadSecciones[$i]."' AND `ID_ramo` = '".$ID_ramo."' AND `PDF_ID_PDF` = '".$ID_PDF."'";
                $conoceIDRAMOPDF2 = mysql_query($conoceIDRAMOPDF1) or die('Consulta fallida: ' . mysql_error());
                $conoceIDRAMOPDF3 = mysql_fetch_assoc($conoceIDRAMOPDF2);
                $ID_ramo_PDF = $conoceIDRAMOPDF3['ID_ramos_PDF'];

                //inserta las secciones de un ramo
                for($j = 0; $j < count($horario[$i]); $j++){
                    if($horario[$i][$j][2]==""){
                        $nuevaSeccion1 = "INSERT INTO `seccion_ramo_PDF`(`Numero_seccion`, `Ramos_PDF_id_Ramos_PDF`, `Horario_1`, `Horario_2`, `Horario_3`, `Sala_1`, `Sala_2`, `Sala_3`, `Nombre_docente`, `Cantidad_alumnos`) 
                                        VALUES (".($j+1).", '".$ID_ramo_PDF."', ".$horario[$i][$j][0].", ".$horario[$i][$j][1].", 0, ".$sala[$i][$j][0].", ".$sala[$i][$j][1].", 0, " .$profe[$i][$j].", ".$alumnos[$i][$j].")";
                    } else {
                        $nuevaSeccion1 = "INSERT INTO `seccion_ramo_PDF`(`Numero_seccion`, `Ramos_PDF_id_Ramos_PDF`, `Horario_1`, `Horario_2`, `Horario_3`, `Sala_1`, `Sala_2`, `Sala_3`, `Nombre_docente`, `Cantidad_alumnos`) 
                                        VALUES (".($j+1).", '".$ID_ramo_PDF."', ".$horario[$i][$j][0].", ".$horario[$i][$j][1].", ".$horario[$i][$j][2].", ".$sala[$i][$j][0].", ".$sala[$i][$j][1].", ".$sala[$i][$j][2].", ".$profe[$i][$j].", ".$alumnos[$i][$j].")";
                    }
                    $nuevaSeccion2 = mysql_query($nuevaSeccion1) or die('Consulta fallida: ' . mysql_error());
                }
            }
            
            //actualiza estado PDI 
            $actualizarPDI1 = "UPDATE `PDI` SET `Estado_PDI`= 5 WHERE `ID_PDI`= ".$idPDI;
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
                            $max2 = $cantidadSecciones[$i];
                            for($j = 0; $j < $max2; $j++){
                                echo "<table align='center' border='1' cellspacing='0' cellpadding='3' width='700px' class='media'>";
                                echo "<tr><td class='titulo_fila media' colspan='4'>Secci&oacute;n N&uacute;mero ".$noSeccion[$i][$j]."</td></tr>";
                                for($k = 0; $k < 3; $k++) {
                                    $horarioE1 = "SELECT `Periodo` FROM `periodos` WHERE `ID_periodo` = ".$horario[$i][$j][$k];
                                    $horarioE2 = mysql_query($horarioE1) or die('Consulta fallida: '.mysql_error());
                                    $horarioE3 = mysql_fetch_assoc($horarioE2);

                                    $salaE1 = "SELECT * FROM `salas` WHERE `ID_sala` = ".$sala[$i][$j][$k];
                                    $salaE2 = mysql_query($salaE1) or die('Consulta fallida: '.mysql_error());
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
