<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
<html >
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Creaci&oacute;n de Programaci&oacute;n Docente Inicial</title>
		<link rel="StyleSheet" type="text/css" href="gestion.css">
		<script src="../general/ayuda.js" type="text/javascript" language="JavaScript"></script>
		<script src="../general/prototype.js" type="text/javascript" language="JavaScript"></script>
		<script src="../general/ajax.js" type="text/javascript" language="JavaScript"></script>
	</head>
	<body>
		<div id="fecha">
			<?php echo date("d/m/Y"). "<br/>" . date("H:i");?>
		</div>
		<h3>Programaci&oacute;n Docente Inicial<br><small>Comprobante</small></h3>
		<?php
            header('Content-Type: text/html; charset=utf-8');
			$departamento = $_POST['depar'];
			$carrera = $_POST['carre'];
			$cod_ramo = $_POST['codigoRamo'];
			$ramo = $_POST['ramo'];
			$seccion = $_POST['seccion'];
            $repetido = $_POST['estaRepetido'];
			$PDIRepetido = $_POST['PDIRepetido'];
            for($i = 0; $i < count($ramo); $i++){
                $hora_seccion[$i] = $_POST['horario_dia_id'.$i];
            }
			$data = array($cod_ramo, $ramo, $seccion);
			function array_envia($array) { 
				$tmp = serialize($array); 
				$tmp = urlencode($tmp); 
				return $tmp; 
			}
			$data = array_envia($data);
            
            //conexion a base de datos
            $link = mysql_connect('localhost', 'dbttii', 'dbttii') or die('No se pudo conectar: ' . mysql_error());
            mysql_select_db('ttii') or die('No se pudo seleccionar la base de datos');

            if($repetido == "1"){
                //actualiza estado PDI
                $cancelarPDI1 = "UPDATE `PDI` SET `Estado_PDI`= 7 WHERE `ID_PDI`= ".$PDIRepetido;
                $cancelarPDI2 = mysql_query($cancelarPDI1) or die('Consulta fallida $cancelarPDI1: ' . mysql_error());
            }

            //inserta datos en PDI
            $fechaHora = date('Y-m-j H:i:s');
            if($repetido == "1"){
                $nuevaPDI1 = "INSERT INTO `PDI`(`Estado_PDI`,`Nombre_docente`,`ID_profesor`,`ID_escuela`,`Fecha_PDI`,`carreras_ID_carrera`,`departamentos_ID_depto`, `PDI_cancelado`) VALUES (1,'NOMBRE_PRUEBA',1,1,'".$fechaHora."','".$carrera."','".$departamento."',".$PDIRepetido.")";
            } else {
                $nuevaPDI1 = "INSERT INTO `PDI`(`Estado_PDI`,`Nombre_docente`,`ID_profesor`,`ID_escuela`,`Fecha_PDI`,`carreras_ID_carrera`,`departamentos_ID_depto`) VALUES (1,'NOMBRE_PRUEBA',1,1,'".$fechaHora."','".$carrera."','".$departamento."')";
            }
            $nuevaPDI2 = mysql_query($nuevaPDI1) or die('Consulta fallida $nuevaPDI2: ' . mysql_error());
            
            //obtiene el ID_PDI
            $conocePDI1 = "SELECT `ID_PDI` FROM `PDI` WHERE `Nombre_docente` = 'NOMBRE_PRUEBA' AND `ID_profesor` = 1 AND `ID_escuela` = 1 AND `Fecha_PDI` = '".$fechaHora."' AND `carreras_ID_carrera` = '".$carrera."' AND `departamentos_ID_depto` = '".$departamento."'";
            $conocePDI2 = mysql_query($conocePDI1) or die('Consulta fallida $conocePDI2: ' . mysql_error());
            $conocePDI3 = mysql_fetch_assoc($conocePDI2);
            $ID_PDI = $conocePDI3['ID_PDI'];
        
            //inserta los ramos en PDI
            for($i = 0; $i < count($ramo); $i++){
                //obtiene el ID_ramo
                $conoceIDRAMO1 = "SELECT `ID_ramo` FROM `ramos` WHERE `Nombre_ramo` = '".$ramo[$i]."' AND `Codigo_ramo`= '".$cod_ramo[$i]."'";
                $conoceIDRAMO2 = mysql_query($conoceIDRAMO1) or die('Consulta fallida $conoceIDRAMO2: ' . mysql_error());
                $conoceIDRAMO3 = mysql_fetch_assoc($conoceIDRAMO2);
                $ID_ramo = $conoceIDRAMO3['ID_ramo'];
                
                //inserta los ramos
                $nuevoRamo1 = "INSERT INTO `ramos_PDI`(`ID_ramo`, `Cantidad_secciones`, `PDI_ID_PDI`) VALUES (".$ID_ramo.",".$seccion[$i].",".$ID_PDI.")";
                $nuevoRamo2 = mysql_query($nuevoRamo1) or die('Consulta fallida $nuevoRamo2: ' . mysql_error());
                
                //obtiene el ID_ramo_PDI
                $conoceIDRAMOPDI1 = "SELECT `ID_ramos_PDI` FROM `ramos_PDI` WHERE `Cantidad_secciones` = '".$seccion[$i]."' AND `ID_ramo` = '".$ID_ramo."' AND `PDI_ID_PDI` = '".$ID_PDI."'";
                $conoceIDRAMOPDI2 = mysql_query($conoceIDRAMOPDI1) or die('Consulta fallida $conoceIDRAMOPDI2: ' . mysql_error());
                $conoceIDRAMOPDI3 = mysql_fetch_assoc($conoceIDRAMOPDI2);
                $ID_ramo_PDI = $conoceIDRAMOPDI3['ID_ramos_PDI'];

                //inserta las secciones de un ramo
                for($j = 0; $j < count($hora_seccion[$i]); $j++){
                    if($hora_seccion[$i][$j][2]==-1){
                        $nuevaSeccion1 = "INSERT INTO `seccion_ramo_PDI`(`Numero_seccion`, `Ramos_PDI_id_Ramos_PDI`, `Horario_1`, `Horario_2`, `Horario_3`) VALUES (".($j+1).", '".$ID_ramo_PDI."', ".$hora_seccion[$i][$j][0].", ".$hora_seccion[$i][$j][1].", 0)";
                    } else {
                        $nuevaSeccion1 = "INSERT INTO `seccion_ramo_PDI`(`Numero_seccion`, `Ramos_PDI_id_Ramos_PDI`, `Horario_1`, `Horario_2`, `Horario_3`) VALUES (".($j+1).", '".$ID_ramo_PDI."', ".$hora_seccion[$i][$j][0].", ".$hora_seccion[$i][$j][1].", ".$hora_seccion[$i][$j][2].")";
                    }
                    $nuevaSeccion2 = mysql_query($nuevaSeccion1) or die('Consulta fallida $nuevaSeccion2: ' . mysql_error());
                }
            }
            //obtiene el ID_depto
            $consultaID_depto1 = "SELECT * FROM `departamentos` WHERE `ID_depto` = ".$departamento;
            $consultaID_depto2 = mysql_query($consultaID_depto1) or die('Consulta fallida $consultaID_depto2: ' . mysql_error());
            $consultaID_depto3 = mysql_fetch_assoc($consultaID_depto2);
            //$consultaID_depto3['Nombre_depto'];

            //obtiene el ID_carrera
            $consultaID_carrera1 = "SELECT * FROM `carreras` WHERE `ID_carrera` = ".$carrera;
            $consultaID_carrera2 = mysql_query($consultaID_carrera1) or die('Consulta fallida $consultaID_carrera2: ' . mysql_error());
            $consultaID_carrera3 = mysql_fetch_assoc($consultaID_carrera2);
            //$consultaID_carrera3['Nombre_carrera'];
		?>
		<div>
			<dd>
				<strong>Estimado Director de Escuela:</strong></br></br>
				Se ha realizado la siguiente <strong>Programaci&oacute;n Docente Inicial</strong> N&deg;<?php echo $ID_PDI; ?> <strong></strong> para la carrera de <strong><?php echo $consultaID_carrera3['Nombre_carrera']; ?></strong>
				al Departamento de <strong><?php echo $consultaID_depto3['Nombre_depto']; ?></strong><?php
                    if($repetido == "1"){
                        $PDI_cancelado = ' y se ha cancelado la Programaci&oacute;n Docente Inicial N&deg; '.$PDIRepetido;
                        echo $PDI_cancelado;
                    }
                ?>, esta solicitud contiene las siguientes asignaturas: </br></br>
				<center><strong><ins>Listado de asignaturas</ins></strong></center></br>			
				<table align="center" cellspacing="0" cellpadding="3" class="pequena" width="80%">
					<tr class="titulo_fila">
						<td>C&oacute;digo</td>
						<td>Nombre Ramo</td>
						<td>Secciones</td>
					</tr>
					<?php
						$max = count($ramo);
						for($i = 0; $i < $max; $i++){
							echo "<tr class='centro'>\n";
							echo "<td>".$cod_ramo[$i]."</td>";
							echo "<td>".$ramo[$i]."</td>\n";
							echo "<td>".$seccion[$i]."</td>";
							echo "</tr>";
						}
					?>
				</table>
				</br></br>
				Recuerda:</br></br>
				- Si deseas agregar o cambiar asignaturas, puedes realizar nuevamente el proceso de Programaci&oacute;n Docente Inicial.</br>
				Debes seleccionar la opci&oacute;n Inscripci&oacute;n de Ramos. Al realizarlo, la &uacute;ltima solicitud ser&aacute; la v&aacute;lida</br>
				- Si deseas descargar un comprobante has clic 
                <?php
                    $pdfArchivo = '<a href="comprobantePDI.php?depa='.$consultaID_depto3['Nombre_depto'].'&carre='.$consultaID_carrera3['Nombre_carrera'].'&numPDI='.$ID_PDI.'&data='.$data;
                    if($repetido == "1"){
                        $pdfArchivo = $pdfArchivo.'&repe='.$PDIRepetido;
                    }
                    $pdfArchivo = $pdfArchivo.'" target="_blank">aqu&iacute;.</a></br></br>';
                    echo $pdfArchivo;
                ?>
			</dd>
		</div>
	
	</body>
</html>
