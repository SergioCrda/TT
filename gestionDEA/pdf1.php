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

            $reversar = false;

            $link = mysqli_connect('localhost', 'dbttii', 'dbttii', "ttii");
            if (mysqli_connect_errno()) echo "Falla al conectar con MySQL: " . mysqli_connect_error();

            //desactivar autocommit
            mysqli_autocommit($link, FALSE);

            $actualizarPDF1 = "UPDATE `PDF` SET `Estado_PDF`='".$estadoCambiar."' WHERE `ID_PDF`=".$idpdf;
            $actualizarPDF2 = mysqli_query($link, $actualizarPDF1) or die('Consulta fallida $actualizarPDF2: ' . mysqli_error($link));
            if($actualizarPDF2 === FALSE) $reversar = true;

            $seleccionPDF1 = "SELECT * FROM `PDF` WHERE `ID_PDF` = " . $idpdf;
            $seleccionPDF2 = mysqli_query($link, $seleccionPDF1) or die('Consulta fallida $seleccionPDF2: '.mysqli_error($link));
            $seleccionPDF3 = mysqli_fetch_assoc($seleccionPDF2);

            $ID_PDF = $seleccionPDF3['ID_PDF'];
            $estado = $seleccionPDF3['Estado_PDF'];
            $nombre_docente = $seleccionPDF3['Nombre_docente'];
            $ID_profesor = $seleccionPDF3['ID_profesor'];
            $ID_escuela = $seleccionPDF3['ID_escuela'];
            $fecha_PDF = $seleccionPDF3['Fecha_PDF'];

            $carreras_ID_carrera = $seleccionPDF3['carreras_ID_carrera'];
            $carrera1 = "SELECT `Nombre_carrera` FROM `carreras` WHERE `ID_carrera` = " . $carreras_ID_carrera;
            $carrera2 = mysqli_query($link, $carrera1) or die('Consulta fallida $carrera2: '.mysqli_error($link));
            $carrera3 = mysqli_fetch_assoc($carrera2);
            $carrera4 = $carrera3['Nombre_carrera'];

            $departamentos_ID_depto = $seleccionPDF3['departamentos_ID_depto'];
            $departamento1 = "SELECT `Nombre_depto` FROM `departamentos` WHERE `ID_depto` = " . $departamentos_ID_depto;
            $departamento2 = mysqli_query($link, $departamento1) or die('Consulta fallida $departamento2: '.mysqli_error($link));
            $departamento3 = mysqli_fetch_assoc($departamento2);
            $departamento4 = $departamento3['Nombre_depto'];

            echo "<div><dd>";
            echo "<strong>Estimado Decano:</strong></br></br>";
            if($reversar === false) {
                echo "Se ha cambiado de estado la <strong>Programaci&oacute;n Docente Final</strong> N&deg;".$idpdf;
                echo " para la carrera de <strong>".$carrera4."</strong>";
                echo " al Departamento de <strong>".$departamento4."</strong>";
                echo ", en esta Programaci&oacute;n se solicitan las siguientes asignaturas: </br></br>";
                echo "<center><strong><ins>Listado de asignaturas</ins></strong></center></br>";
                echo "<table align='center' cellspacing='0' cellpadding='3' class='pequena' width='80%'>";
                echo "<tr class='titulo_fila'>";
                echo "<td>C&oacute;digo</td>";
                echo "<td>Nombre Ramo</td>";
                echo "<td>Secciones</td>";
                echo "</tr>";

                $seleccionRamoPDF1 = "SELECT * FROM `ramos_PDF` WHERE `PDF_id_PDF` = ".$idpdf;
                $seleccionRamoPDF2 = mysqli_query($link, $seleccionRamoPDF1) or die('Consulta fallida $seleccionRamoPDF2: '.mysqli_error($link));
                $cuenta1=0;
                while($seleccionRamoPDF3 = mysqli_fetch_assoc($seleccionRamoPDF2)){
                    $codramo1 = "SELECT * FROM `ramos` WHERE `ID_ramo` = " . $seleccionRamoPDF3['ID_ramo'];
                    $codramo2 = mysqli_query($link, $codramo1) or die('Consulta fallida $codramo2: '.mysqli_error($link));
                    $codramo3 = mysqli_fetch_assoc($codramo2);
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
                    $IDSeccionRamoPDF2 = mysqli_query($link, $IDSeccionRamoPDF1) or die('Consulta fallida $IDSeccionRamoPDF2: '.mysqli_error($link));

                    $cuenta2 = 0;
                    while($IDSeccionRamoPDF3 = mysqli_fetch_assoc($IDSeccionRamoPDF2)){
                        $noSeccion[$cuenta1][$cuenta2]  = $IDSeccionRamoPDF3['Numero_seccion'];
                        $horario[$cuenta1][$cuenta2][0] = $IDSeccionRamoPDF3['Horario_1'];
                        $horario[$cuenta1][$cuenta2][1] = $IDSeccionRamoPDF3['Horario_2'];
                        $horario[$cuenta1][$cuenta2][2] = $IDSeccionRamoPDF3['Horario_3'];
                        $sala[$cuenta1][$cuenta2][0]    = $IDSeccionRamoPDF3['Sala_1'];
                        $sala[$cuenta1][$cuenta2][1]    = $IDSeccionRamoPDF3['Sala_2'];
                        $sala[$cuenta1][$cuenta2][2]    = $IDSeccionRamoPDF3['Sala_3'];
                        $profe[$cuenta1][$cuenta2]      = $IDSeccionRamoPDF3['Nombre_docente'];
                        $alumnos[$cuenta1][$cuenta2]    = $IDSeccionRamoPDF3['Cantidad_alumnos'];
                        $cuenta2++;
                    }
                    $cuenta1++;
                }
                $data = array($codigoRamos, $nombreRamos, $cantidRamos, $noSeccion, $horario, $profe, $alumnos, $sala);
                function array_envia($array) {
                    $tmp = serialize($array);
                    $tmp = urlencode($tmp);
                    return $tmp;
                }
                $data = array_envia($data);

                echo "</table>";
                echo "</br></br>";
                echo "Recuerda:</br></br>";
                echo "- Si la Programaci&oacute;n Docente Final fue aceptada est&aacute; pendiente por revisar en la DEA.</br>";
                echo "- Si la Programaci&oacute;n Docente Final fue rechazada, no contin&uacute;a el flujo.</br>";
                echo "- Si deseas descargar un comprobante has clic";
                echo "<a href='comprobantePDF.php?depa=".$departamento4."&carre=".$carrera4."&numPDF=".$idpdf."&data=".$data."&estado=".$estadoCambiar."' target='_blank'>aqu&iacute;.</a></br></br>";

                //confirmar guardado
                mysqli_commit($link);
            } else {
                //hay que reversar, borra el PDF creado, los ramos del PDF y las secciones de los ramos del PDF y las salas asignadas
                mysqli_rollback($link);

                //mensaje al usuario
                echo "Ha ocurrido un error al cambiar de estado, favor intente nuevamente. Si el error persiste contacte al administrador de Base de Datos<br>";
            }

            echo "</dd></div><br/>";
            mysqli_close($link);
        ?>
	</body>
</html>
