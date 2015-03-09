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
            $reversar = false;
            //conexion a base de datos
            $link = mysqli_connect('localhost', 'dbttii', 'dbttii', "ttii");
            if (mysqli_connect_errno()) echo "Falla al conectar con MySQL: " . mysqli_connect_error();

            //desactivar autocommit
            mysqli_autocommit($link, FALSE);

            if($repetido == "1"){
                //actualiza estado PDI
                $cancelarPDI1 = "UPDATE `PDI` SET `Estado_PDI`= 7 WHERE `ID_PDI`= ".$PDIRepetido;
                $cancelarPDI2 = mysqli_query($link, $cancelarPDI1) or die('Consulta fallida $cancelarPDI1: ' . mysqli_error($link));
                if($cancelarPDI2 === FALSE) $reversar = true;
            }

            //inserta datos en PDI
            $fechaHora = date('Y-m-j H:i:s');
            if($repetido == "1"){
                $nuevaPDI1 = "INSERT INTO `PDI`(`Estado_PDI`,`Nombre_docente`,`ID_profesor`,`ID_escuela`,`Fecha_PDI`,`carreras_ID_carrera`,`departamentos_ID_depto`, `PDI_cancelado`) VALUES (1,'NOMBRE_PRUEBA',1,1,'".$fechaHora."','".$carrera."','".$departamento."',".$PDIRepetido.")";
            } else {
                $nuevaPDI1 = "INSERT INTO `PDI`(`Estado_PDI`,`Nombre_docente`,`ID_profesor`,`ID_escuela`,`Fecha_PDI`,`carreras_ID_carrera`,`departamentos_ID_depto`) VALUES (1,'NOMBRE_PRUEBA',1,1,'".$fechaHora."','".$carrera."','".$departamento."')";
            }
            $nuevaPDI2 = mysqli_query($link, $nuevaPDI1) or die('Consulta fallida $nuevaPDI2: ' . mysqli_error($link));
            if($nuevaPDI2 === FALSE) $reversar = true;

            //obtiene el ID_PDI
            $conocePDI1 = "SELECT `ID_PDI` FROM `PDI` WHERE `Nombre_docente` = 'NOMBRE_PRUEBA' AND `ID_profesor` = 1 AND `ID_escuela` = 1 AND `Fecha_PDI` = '".$fechaHora."' AND `carreras_ID_carrera` = '".$carrera."' AND `departamentos_ID_depto` = '".$departamento."'";
            $conocePDI2 = mysqli_query($link, $conocePDI1) or die('Consulta fallida $conocePDI2: ' . mysqli_error($link));
            $conocePDI3 = mysqli_fetch_assoc($conocePDI2);
            $ID_PDI = $conocePDI3['ID_PDI'];
        
            //inserta los ramos en PDI
            for($i = 0; $i < count($ramo); $i++){
                //obtiene el ID_ramo
                $conoceIDRAMO1 = "SELECT `ID_ramo` FROM `ramos` WHERE `Nombre_ramo` = '".$ramo[$i]."' AND `Codigo_ramo`= '".$cod_ramo[$i]."'";
                $conoceIDRAMO2 = mysqli_query($link, $conoceIDRAMO1) or die('Consulta fallida $conoceIDRAMO2: ' . mysqli_error($link));
                $conoceIDRAMO3 = mysqli_fetch_assoc($conoceIDRAMO2);
                $ID_ramo = $conoceIDRAMO3['ID_ramo'];
                
                //inserta los ramos
                $nuevoRamo1 = "INSERT INTO `ramos_PDI`(`ID_ramo`, `Cantidad_secciones`, `PDI_ID_PDI`) VALUES (".$ID_ramo.",".$seccion[$i].",".$ID_PDI.")";
                $nuevoRamo2 = mysqli_query($link, $nuevoRamo1) or die('Consulta fallida $nuevoRamo2: ' . mysqli_error($link));
                if($nuevoRamo2 === FALSE) $reversar = true;
                
                //obtiene el ID_ramo_PDI
                $conoceIDRAMOPDI1 = "SELECT `ID_ramos_PDI` FROM `ramos_PDI` WHERE `Cantidad_secciones` = '".$seccion[$i]."' AND `ID_ramo` = '".$ID_ramo."' AND `PDI_ID_PDI` = '".$ID_PDI."'";
                $conoceIDRAMOPDI2 = mysqli_query($link, $conoceIDRAMOPDI1) or die('Consulta fallida $conoceIDRAMOPDI2: ' . mysqli_error($link));
                $conoceIDRAMOPDI3 = mysqli_fetch_assoc($conoceIDRAMOPDI2);
                $ID_ramo_PDI = $conoceIDRAMOPDI3['ID_ramos_PDI'];

                //inserta las secciones de un ramo
                for($j = 0; $j < count($hora_seccion[$i]); $j++){
                    if($hora_seccion[$i][$j][2]==-1){
                        $nuevaSeccion1 = "INSERT INTO `seccion_ramo_PDI`(`Numero_seccion`, `Ramos_PDI_id_Ramos_PDI`, `Horario_1`, `Horario_2`, `Horario_3`) VALUES (".($j+1).", '".$ID_ramo_PDI."', ".$hora_seccion[$i][$j][0].", ".$hora_seccion[$i][$j][1].", 0)";
                    } else {
                        $nuevaSeccion1 = "INSERT INTO `seccion_ramo_PDI`(`Numero_seccion`, `Ramos_PDI_id_Ramos_PDI`, `Horario_1`, `Horario_2`, `Horario_3`) VALUES (".($j+1).", '".$ID_ramo_PDI."', ".$hora_seccion[$i][$j][0].", ".$hora_seccion[$i][$j][1].", ".$hora_seccion[$i][$j][2].")";
                    }
                    $nuevaSeccion2 = mysqli_query($link, $nuevaSeccion1) or die('Consulta fallida $nuevaSeccion2: ' . mysqli_error($link));
                    if($nuevaSeccion2 === FALSE) $reversar = true;
                }
            }
            //obtiene el ID_depto
            $consultaID_depto1 = "SELECT * FROM `departamentos` WHERE `ID_depto` = ".$departamento;
            $consultaID_depto2 = mysqli_query($link, $consultaID_depto1) or die('Consulta fallida $consultaID_depto2: ' . mysqli_error($link));
            $consultaID_depto3 = mysqli_fetch_assoc($consultaID_depto2);
            //$consultaID_depto3['Nombre_depto'];

            //obtiene el ID_carrera
            $consultaID_carrera1 = "SELECT * FROM `carreras` WHERE `ID_carrera` = ".$carrera;
            $consultaID_carrera2 = mysqli_query($link, $consultaID_carrera1) or die('Consulta fallida $consultaID_carrera2: ' . mysqli_error($link));
            $consultaID_carrera3 = mysqli_fetch_assoc($consultaID_carrera2);
            //$consultaID_carrera3['Nombre_carrera'];

            echo "<div><dd>";
            echo "<strong>Estimado Director de Escuela:</strong></br></br>";
            if($reversar === false) {
                echo "Se ha realizado la siguiente <strong>Programaci&oacute;n Docente Inicial</strong> N&deg;".$ID_PDI;
                echo " para la carrera de <strong>".$consultaID_carrera3['Nombre_carrera']."</strong>";
                if($repetido == "1"){
                    $PDI_cancelado = ' y se ha cancelado la Programaci&oacute;n Docente Inicial N&deg; '.$PDIRepetido;
                    echo $PDI_cancelado;
                }
                echo ", esta solicitud contiene las siguientes asignaturas: </br></br>";
                echo "<center><strong><ins>Listado de asignaturas</ins></strong></center></br>";
                echo "<table align='center' cellspacing='0' cellpadding='3' class='pequena' width='80%'>";
                echo "<tr class='titulo_fila'>";
                echo "<td>C&oacute;digo</td>";
                echo "<td>Nombre Ramo</td>";
                echo "<td>Secciones</td>";
                echo "</tr>";
                $max = count($ramo);
                for($i = 0; $i < $max; $i++){
                    echo "<tr class='centro'>\n";
                    echo "<td>".$cod_ramo[$i]."</td>";
                    echo "<td>".$ramo[$i]."</td>\n";
                    echo "<td>".$seccion[$i]."</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "</br></br>";
                echo "Recuerda:</br></br>";
                echo "- Si deseas agregar o cambiar asignaturas, puedes realizar nuevamente el proceso de Programaci&oacute;n Docente Inicial.</br>";
                echo "Debes seleccionar la opci&oacute;n Inscripci&oacute;n de Ramos. Al realizarlo, la &uacute;ltima solicitud ser&aacute; la v&aacute;lida</br>";
                echo "- Si deseas descargar un comprobante has clic ";
                $pdfArchivo = '<a href="comprobantePDI.php?depa='.$consultaID_depto3['Nombre_depto'].'&carre='.$consultaID_carrera3['Nombre_carrera'].'&numPDI='.$ID_PDI.'&data='.$data;
                if($repetido == "1"){
                    $pdfArchivo = $pdfArchivo.'&repe='.$PDIRepetido;
                }
                $pdfArchivo = $pdfArchivo.'" target="_blank">aqu&iacute;.</a></br></br>';
                echo $pdfArchivo;

                //confirmar guardado
                mysqli_commit($link);
            } else {
                //hay que reversar, borra el PDF creado, los ramos del PDF y las secciones de los ramos del PDF y las salas asignadas
                mysqli_rollback($link);

                //mensaje al usuario
                echo "Ha ocurrido un error al realizar la solicitud, esta pudo fallar por los siguientes motivos:<br>";
                echo "<ul><li>Las Salas indicadas ya han sido seleccionadas, favor intente nuevamente</li>";
                echo "<li>La Base de Datos est&aacute; abajo, favor contacte al Administrador de Base de Datos</li></ul>";
            }
            echo "</dd></div>";
            mysqli_close($link);
		?>
	</body>
</html>
