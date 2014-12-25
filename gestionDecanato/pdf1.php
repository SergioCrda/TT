<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
<html >
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Creaci&oacute;n de Programaci&oacute;n Docente Final</title>
		<link rel="StyleSheet" type="text/css" href="gestion.css">
		<script src="../general/ayuda.js" type="text/javascript" language="JavaScript"></script>
		<script src="../general/prototype.js" type="text/javascript" language="JavaScript"></script>
		<script src="../general/ajax.js" type="text/javascript" language="JavaScript"></script>
	</head>
	<body>
		<div id="fecha">
			<?php echo date("d/m/Y"). "<br/>" . date("H:i");?>
		</div>
		<h3>Programaci&oacute;n Docente Final<br><small>Selecci&oacute;n de Asignaturas</small></h3>
        <?php
            $estadoCambiar = $_GET['estado'];
            $idpdf = $_GET['id_pdf'];

            $link = mysql_connect('localhost', 'dbttii', 'dbttii') or die('No se pudo conectar: '.mysql_error());
            mysql_select_db('ttii') or die('No se pudo seleccionar la base de datos');

            $actualizarPDF1 = "UPDATE `PDF` SET `Estado`='".utf8_decode($estadoCambiar)."' WHERE `ID_PDF`=".$idpdf;
            $actualizarPDF2 = mysql_query($actualizarPDF1) or die('Consulta fallida: ' . mysql_error());

            $seleccionPDF1 = "SELECT * FROM `PDF` WHERE `ID_PDF` = " . $idpdf;
            $seleccionPDF2 = mysql_query($seleccionPDF1) or die('Consulta fallida: '.mysql_error());
            $seleccionPDF3 = mysql_fetch_assoc($seleccionPDF2);

            $ID_PDF = $seleccionPDF3['ID_PDF'];
            $estado = $seleccionPDF3['Estado'];
            $nombre_docente = $seleccionPDF3['Nombre_docente'];
            $ID_profesor = $seleccionPDF3['ID_profesor'];
            $ID_escuela = $seleccionPDF3['ID_escuela'];
            $fecha_PDF = $seleccionPDF3['Fecha_PDF'];

            $carreras_ID_carrera = $seleccionPDF3['carreras_ID_carrera'];
            $carrera1 = "SELECT `Nombre_carrera` FROM `carreras` WHERE `ID_carrera` = " . $carreras_ID_carrera;
            $carrera2 = mysql_query($carrera1) or die('Consulta fallida: '.mysql_error());
            $carrera3 = mysql_fetch_assoc($carrera2);
            $carrera4 = $carrera3['Nombre_carrera'];

            $departamentos_ID_depto = $seleccionPDF3['departamentos_ID_depto'];
            $departamento1 = "SELECT `Nombre_depto` FROM `departamentos` WHERE `ID_depto` = " . $departamentos_ID_depto;
            $departamento2 = mysql_query($departamento1) or die('Consulta fallida: '.mysql_error());
            $departamento3 = mysql_fetch_assoc($departamento2);
            $departamento4 = $departamento3['Nombre_depto'];
        ?>
        
         <div>
			<dd>
				<strong>Estimado Decano:</strong></br></br>
				Se ha cambiado de estado la <strong>Programaci&oacute;n Docente Final</strong> N&deg;<?php echo $idpdf; ?> <strong></strong> para la carrera de <strong><?php echo $carrera4; ?></strong> al Departamento de <strong><?php echo $departamento4; ?></strong>, en esta Programaci&oacute;n se solicitan las siguientes asignaturas: </br></br>
				<center><strong><ins>Listado de asignaturas</ins></strong></center></br>			
				<table align="center" cellspacing="0" cellpadding="3" class="pequena" width="80%">
					<tr class="titulo_fila">
						<td>C&oacute;digo</td>
						<td>Nombre Ramo</td>
						<td>Secciones</td>
					</tr>
					<?php
                        $seleccionRamoPDF1 = "SELECT * FROM `ramos_PDF` WHERE `PDF_id_PDF` = ".$idpdf;
                        $seleccionRamoPDF2 = mysql_query($seleccionRamoPDF1) or die('Consulta fallida: '.mysql_error());
                        $cuenta1=0;
                        while($seleccionRamoPDF3 = mysql_fetch_assoc($seleccionRamoPDF2)){
                            $codramo1 = "SELECT * FROM `ramos` WHERE `ID_ramo` = " . $seleccionRamoPDF3['ID_ramo'];
                            $codramo2 = mysql_query($codramo1) or die('Consulta fallida: '.mysql_error());
                            $codramo3 = mysql_fetch_assoc($codramo2);
                            $codramo = $codramo3['Codigo_ramo'];
                            $nomramo = $codramo3['Nombre_ramo'];
                            $canramo = $seleccionRamoPDF3['Cantidad_secciones'];
                            echo "<tr class='centro'>";
							echo "<td>".$codramo."</td>";
                            $codigoRamos[$cuenta1] = $codramo;
							echo "<td>".$nomramo."</td>";
                            $nombreRamos[$cuenta1] = $nomramo;
							echo "<td>".$canramo."</td>";
                            $cantidRamos[$cuenta1] = $canramo;
							echo "</tr>";
                            
                            $IDSeccionRamoPDF1 = "SELECT * FROM `seccion_ramo_PDF` WHERE `Ramos_PDF_id_Ramos_PDF` = " .$seleccionRamoPDF3['ID_ramos_PDF'];
                            //print_r($IDSeccionRamoPDF1);
                            $cuenta2 = 0;
                            $IDSeccionRamoPDF2 = mysql_query($IDSeccionRamoPDF1) or die('Consulta fallida: '.mysql_error());
                            while($IDSeccionRamoPDF3 = mysql_fetch_assoc($IDSeccionRamoPDF2)){
                                $noSeccion[$cuenta1][$cuenta2]  = $IDSeccionRamoPDF3['Numero_seccion'];
                                $horario[$cuenta1][$cuenta2][0] = $IDSeccionRamoPDF3['Horario_1'];
                                $horario[$cuenta1][$cuenta2][1] = $IDSeccionRamoPDF3['Horario_2'];
                                $horario[$cuenta1][$cuenta2][2] = $IDSeccionRamoPDF3['Horario_3'];
                                $profe[$cuenta1][$cuenta2]      = $IDSeccionRamoPDF3['Nombre_docente'];
                                $alumnos[$cuenta1][$cuenta2]    = $IDSeccionRamoPDF3['Cantidad_alumnos'];
                                $cuenta2++;
                            }
                            $cuenta1++;
                        }
                        $data = array($codigoRamos, $nombreRamos, $cantidRamos, $noSeccion, $horario, $profe, $alumnos);
                        function array_envia($array) { 
                            $tmp = serialize($array); 
                            $tmp = urlencode($tmp); 
                            return $tmp; 
                        }
                        $data = array_envia($data);
					?>
				</table>
				</br></br>
				Recuerda:</br></br>
				- Si la Programaci&oacute;n Docente Final fue aceptada est&aacute; pendiente por revisar en VRAC.</br>
				- Si la Programaci&oacute;n Docente Final fue rechazada, no contin&uacute;a el flujo.</br>
				- Si deseas descargar un comprobante has clic 
				<a href="comprobantePDF.php?depa=<?php echo $departamento4; ?>&carre=<?php echo $carrera4; ?>&numPDF=<?php echo $idpdf; ?>&data=<?php echo $data; ?>&estado=<?php echo $estadoCambiar ?>" target="_blank">aqu&iacute;.</a></br></br>
			</dd>
		</div>
        
		<br/>

	</body>
</html>