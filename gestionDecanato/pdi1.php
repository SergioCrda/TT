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
		<h3>Programaci&oacute;n Docente Inicial<br><small>Selecci&oacute;n de Asignaturas</small></h3>
        <?php
            $estadoCambiar = $_GET['estado'];
            $idpdi = $_GET['id_pdi'];

            $reversar = false;

            $link = mysqli_connect('localhost', 'dbttii', 'dbttii', "ttii");
            if (mysqli_connect_errno()) echo "Falla al conectar con MySQL: " . mysqli_connect_error();

            //desactivar autocommit
            mysqli_autocommit($link, FALSE);

            $actualizarPDI1 = "UPDATE `PDI` SET `Estado_PDI`=".$estadoCambiar." WHERE `ID_PDI`=".$idpdi;
            $actualizarPDI2 = mysqli_query($link, $actualizarPDI1) or die('Consulta fallida $actualizarPDI2: ' . mysqli_error($link));
            if($actualizarPDI2 === FALSE) $reversar = true;

            $seleccionPDI1 = "SELECT * FROM `PDI` WHERE `ID_PDI` = ".$idpdi;
            $seleccionPDI2 = mysqli_query($link, $seleccionPDI1) or die('Consulta fallida $seleccionPDI2: '.mysqli_error($link));
            $seleccionPDI3 = mysqli_fetch_assoc($seleccionPDI2);

            $ID_PDI = $seleccionPDI3['ID_PDI'];
            $estado = $seleccionPDI3['Estado_PDI'];
            $nombre_docente = $seleccionPDI3['Nombre_docente'];
            $ID_profesor = $seleccionPDI3['ID_profesor'];
            $ID_escuela = $seleccionPDI3['ID_escuela'];
            $fecha_PDI = $seleccionPDI3['Fecha_PDI'];

            $carreras_ID_carrera = $seleccionPDI3['carreras_ID_carrera'];
            $carrera1 = "SELECT `Nombre_carrera` FROM `carreras` WHERE `ID_carrera` = " . $carreras_ID_carrera;
            $carrera2 = mysqli_query($link, $carrera1) or die('Consulta fallida $carrera2: '.mysqli_error($link));
            $carrera3 = mysqli_fetch_assoc($carrera2);
            $carrera4 = $carrera3['Nombre_carrera'];

            $departamentos_ID_depto = $seleccionPDI3['departamentos_ID_depto'];
            $departamento1 = "SELECT `Nombre_depto` FROM `departamentos` WHERE `ID_depto` = " . $departamentos_ID_depto;
            $departamento2 = mysqli_query($link, $departamento1) or die('Consulta fallida $departamento2: '.mysqli_error($link));
            $departamento3 = mysqli_fetch_assoc($departamento2);
            $departamento4 = $departamento3['Nombre_depto'];

            echo "<div><dd>";
            echo "<strong>Estimado Decano:</strong></br></br>";
            if($reversar === false) {
                echo "Se ha aprobado la <strong>Programaci&oacute;n Docente Inicial</strong> N&deg;".$idpdi;
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

                $seleccionRamoPDI1 = "SELECT * FROM `ramos_PDI` WHERE `PDI_id_PDI` = ".$idpdi;
                $seleccionRamoPDI2 = mysqli_query($link, $seleccionRamoPDI1) or die('Consulta fallida $seleccionRamoPDI2: '.mysqli_error($link));

                $cuenta1=0;
                while($seleccionRamoPDI3 = mysqli_fetch_assoc($seleccionRamoPDI2)){
                    $codramo1 = "SELECT * FROM `ramos` WHERE `ID_ramo` = " . $seleccionRamoPDI3['ID_ramo'];
                    $codramo2 = mysqli_query($link, $codramo1) or die('Consulta fallida $codramo2: '.mysqli_error($link));
                    $codramo3 = mysqli_fetch_assoc($codramo2);
                    $codramo = $codramo3['Codigo_ramo'];
                    $nomramo = $codramo3['Nombre_ramo'];
                    $canramo = $seleccionRamoPDI3['Cantidad_secciones'];
                    echo "<tr class='centro'>";
                    echo "<td>".$codramo."</td>";
                    $codigoRamos[$cuenta1] = $codramo;
                    echo "<td>".$nomramo."</td>";
                    $nombreRamos[$cuenta1] = $nomramo;
                    echo "<td>".$canramo."</td>";
                    $cantidRamos[$cuenta1] = $canramo;
                    echo "</tr>";
                    $cuenta1++;
                }
                $data = array($codigoRamos, $nombreRamos, $cantidRamos);
                function array_envia($array) {
                    $tmp = serialize($array);
                    $tmp = urlencode($tmp);
                    return $tmp;
                }
                $data = array_envia($data);

                echo "</table>";
                echo "</br></br>";
                echo "Recuerda:</br></br>";
                echo "- Si la Programaci&oacute;n Docente Inicial fue aceptada, puede comenzar el flujo de Programaci&oacute;n Docente Final.</br>";
                echo "- Si la Programaci&oacute;n Docente Inicial fue rechazada, no contin&uacute;a el flujo.</br>";
                echo "- Si deseas descargar un comprobante has clic ";
                echo "<a href='comprobantePDI.php?depa=".$departamento4."&carre=".$carrera4."&numPDI=".$idpdi."&data=".$data."&estado=".$estadoCambiar."' target='_blank'>aqu&iacute;.</a></br></br>";

                //confirmar guardado
                mysqli_commit($link);
            } else {
                //hay que reversar, borra el PDF creado, los ramos del PDF y las secciones de los ramos del PDF y las salas asignadas
                mysqli_rollback($link);

                //mensaje al usuario
                echo "Ha ocurrido un error al cambiar de estado, favor intente nuevamente. Si el error persiste, contacte al administrador de Base de Datos<br>";
            }

            echo "</dd></div><br/>";
            mysqli_close($link);

        ?>
	</body>
</html>
