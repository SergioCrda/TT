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
        <script type="text/javascript" language="JavaScript">
			function confirmarForm(){
                return confirm('\u00bfEst\u00e1s seguro de resetear el Proceso PDI-PDF\u003f. \u000ASe eliminar\u00e1n todos los datos de la base de datos');
			}
		</script>
		<div id="fecha">
			<?php echo date("d/m/Y"). "<br/>" . date("H:i");?>
		</div>
		<h3>Configuraci&oacute;n<br><small>Reset de Tablas</small></h3>
        <?php
            $reversar = false;

            $link = mysqli_connect('localhost', 'dbttii', 'dbttii', "ttii2");
            if (mysqli_connect_errno()) echo "Falla al conectar con MySQL: " . mysqli_connect_error();

            //desactivar autocommit
            mysqli_autocommit($link, FALSE);

            /*$salas1 = "UPDATE `asignacion_salas` SET `ID_PDF_asignacion` = NULL, `ID_ramos_asignacion` = NULL, `ID_seccion_asignacion` = NULL";
            $salas2 = mysqli_query($link, $salas1) or die('Consulta fallida $salas2: ' . mysqli_error($link));
            if($salas2 === FALSE) $reversar = true;*/

            $seccionesPDF1 = "DELETE FROM `seccion_ramo_PDF`";
            $seccionesPDF2 = mysqli_query($link, $seccionesPDF1) or die('Consulta fallida $seccionesPDF2: ' . mysqli_error($link));
            if($seccionesPDF2 === FALSE) $reversar = true;

            $ramosPDF1 = "DELETE FROM `ramos_PDF`";
            $ramosPDF2 = mysqli_query($link, $ramosPDF1) or die('Consulta fallida $ramosPDF2: ' . mysqli_error($link));
            if($ramosPDF2 === FALSE) $reversar = true;

            $PDF1 = "DELETE FROM `PDF`";
            $PDF2 = mysqli_query($link, $PDF1) or die('Consulta fallida $PDF2: ' . mysqli_error($link));
            if($PDF2 === FALSE) $reversar = true;

            $seccionesPDI1 = "DELETE FROM `seccion_ramo_PDI`";
            $seccionesPDI2 = mysqli_query($link, $seccionesPDI1) or die('Consulta fallida $seccionesPDI2: ' . mysqli_error($link));
            if($seccionesPDI2 === FALSE) $reversar = true;

            $ramosPDI1 = "DELETE FROM `ramos_PDI`";
            $ramosPDI2 = mysqli_query($link, $ramosPDI1) or die('Consulta fallida $ramosPDI2: ' . mysqli_error($link));
            if($ramosPDI2 === FALSE) $reversar = true;

            $PDI1 = "DELETE FROM `PDI`";
            $PDI2 = mysqli_query($link, $PDI1) or die('Consulta fallida $PDI2: ' . mysqli_error($link));
            if($PDI2 === FALSE) $reversar = true;

            $incremento = "ALTER TABLE `seccion_ramo_PDF` AUTO_INCREMENT = 1;";
            $incremento .= "ALTER TABLE `ramos_PDF` AUTO_INCREMENT = 1;";
            $incremento .= "ALTER TABLE `PDF` AUTO_INCREMENT = 1;";
            $incremento .= "ALTER TABLE `seccion_ramo_PDI` AUTO_INCREMENT = 1;";
            $incremento .= "ALTER TABLE `ramos_PDI` AUTO_INCREMENT = 1;";
            $incremento .= "ALTER TABLE `PDI` AUTO_INCREMENT = 1;";
            $incremento1 = mysqli_multi_query($link, $incremento) or die('Consulta fallida $incremento1: ' . mysqli_error($link));
            if($incremento1 === FALSE) $reversar = true;

            echo "<div><dd>";
            echo "<strong>Estimado Director:</strong></br></br>";
            if($reversar === false) {
                echo "Se han reestablecido la tablas realacionadas a los procesos de Programaci&oacute;n Docente Inicial y Final<br>";

                //confirmar guardado
                mysqli_commit($link);
            } else {
                echo "Ha ocurrido un error al cambiar de estado, favor intente nuevamente. Si el error persiste, contacte al administrador de Base de Datos<br>";

                //hay que reversar
                mysqli_rollback($link);
            }
            echo "</dd></div><br/>";
            mysqli_close($link);

        ?>
	</body>
</html>
