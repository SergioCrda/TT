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
		<h3>Programaci&oacute;n Docente Final<br><small>Asignaci&oacute;n de Docente</small></h3>
        <form name="PDF" method="post" action="pdf2.php">
            <?php
                $link = mysql_connect('localhost', 'dbttii', 'dbttii') or die('No se pudo conectar: '.mysql_error());
                mysql_select_db('ttii') or die('No se pudo seleccionar la base de datos');
                $idpdi = $_GET['id_pdi'];
                $seleccionPDI1 = "SELECT * FROM `PDI` WHERE `ID_PDI` = " . $idpdi;
                $seleccionPDI2 = mysql_query($seleccionPDI1) or die('Consulta fallida: '.mysql_error());
                $seleccionPDI3 = mysql_fetch_assoc($seleccionPDI2);

                $ID_PDI = $seleccionPDI3['ID_PDI'];
                $estado = $seleccionPDI3['Estado'];
                $nombre_docente = $seleccionPDI3['Nombre_docente'];
                $ID_profesor = $seleccionPDI3['ID_profesor'];
                $ID_escuela = $seleccionPDI3['ID_escuela'];
                $fecha_PDI = $seleccionPDI3['Fecha_PDI'];

                $carreras_ID_carrera = $seleccionPDI3['carreras_ID_carrera'];
                $carrera1 = "SELECT `Nombre_carrera` FROM `carreras` WHERE `ID_carrera` = " . $carreras_ID_carrera;
                $carrera2 = mysql_query($carrera1) or die('Consulta fallida: '.mysql_error());
                $carrera3 = mysql_fetch_assoc($carrera2);
                $carrera4 = $carrera3['Nombre_carrera'];

                $departamentos_ID_depto = $seleccionPDI3['departamentos_ID_depto'];
                $departamento1 = "SELECT `Nombre_depto` FROM `departamentos` WHERE `ID_depto` = " . $departamentos_ID_depto;
                $departamento2 = mysql_query($departamento1) or die('Consulta fallida: '.mysql_error());
                $departamento3 = mysql_fetch_assoc($departamento2);
                $departamento4 = $departamento3['Nombre_depto'];

                echo '<table align="center" width="75%" border="1" cellpadding="3" cellspacing="0" class="pequena" id="pdfTabla"><tr class="titulo_fila"><td>Folio</td><td>Fecha</td><td>Nombre Docente</td><td>Escuela</td><td>Departamento</td><td>Carrera</td><td>Estado</td></tr>';
                echo "<tr class='centro'>";
                echo "<td>".$ID_PDI."<input type='hidden' id='idPDI' name='id_pdi' value='$ID_PDI'/></td>";
                echo "<td>".$fecha_PDI."<input type='hidden' id='fechaPDI' name='fecha_pdi' value='$fecha_PDI'/></td>";
                echo "<td>".$nombre_docente."<input type='hidden' id='nombreDocente' name='nombre_docente' value='$nombre_docente'/></td>";
                echo "<td>".$ID_escuela."<input type='hidden' id='IDEscuela' name='ID_Escuela' value='$ID_escuela'/></td>";
                echo "<td>".$departamento4."<input type='hidden' id='depa' name='depto' value='$departamento4'/></td>";
                echo "<td>".$carrera4."<input type='hidden' id='carre' name='carrera' value='$carrera4'/></td>";
                echo "<td>".$estado."</td>";
                //echo "ID Profesor : ".$ID_profesor;
                echo "</tr>";
                echo "<tr>";
                echo "<th colspan='7'>";
                $seleccionRamoPDI1 = "SELECT * FROM `ramos_PDI` WHERE `PDI_id_PDI` = ".$idpdi;
                $seleccionRamoPDI2 = mysql_query($seleccionRamoPDI1) or die('Consulta fallida: '.mysql_error());
                $cuenta1 = 1;
                while($seleccionRamoPDI3 = mysql_fetch_assoc($seleccionRamoPDI2)){
                    echo "<br>";
                    echo '<table align="center" width="800px" border="1" cellpadding="3" cellspacing="0" id="pdfTablaRamos'.$cuenta1.'"><tr class="titulo_fila"><td>N&deg; Ramo</td><td>C&oacute;d. de Ramo</td><td>Nombre del Ramo</td><td>Cantidad de Secciones</td></tr>';
                    echo "<tr class='centro'>";
                    echo "<td width='80px'>" .$cuenta1. "</td>";
                    $codramo1 = "SELECT `Codigo_ramo` FROM `ramos` WHERE `ID_ramo` = " . $seleccionRamoPDI3['ID_ramo'];
                    $codramo2 = mysql_query($codramo1) or die('Consulta fallida: '.mysql_error());
                    $codramo3 = mysql_fetch_assoc($codramo2);
                    $codramo4 = $codramo3['Codigo_ramo'];
                    echo "<td width='150px'>".$codramo4."<input type='hidden' id='ramo' name='codramos[]' value='$codramo4'/></td>";
                    $ramo1 = "SELECT `Nombre_ramo` FROM `ramos` WHERE `ID_ramo` = " . $seleccionRamoPDI3['ID_ramo'];
                    $ramo2 = mysql_query($ramo1) or die('Consulta fallida: '.mysql_error());
                    $ramo3 = mysql_fetch_assoc($ramo2);
                    $ramo4 = $ramo3['Nombre_ramo'];
                    echo "<td width='400px'>".$ramo4."<input type='hidden' id='ramo' name='ramos[]' value='$ramo4'/></td>";
                    $seleccionRamoPDI4 = $seleccionRamoPDI3['Cantidad_secciones'];
                    echo "<td>".$seleccionRamoPDI4."<input type='hidden' id='cantidadSecciones' name='cantidad_secciones[]' value='$seleccionRamoPDI4'/></td>";
                    //echo "ID PDI : ".$seleccionRamoPDI3['PDI_id_PDI'];
                    //$ramoPDI  = $seleccionRamoPDI3['ID_ramos_PDI'];
                    //echo "ID Ramo PDI : ".$ramoPDI;
                    echo "</tr>";
                    echo "<tr>";
                    echo "<th colspan='4'>";
                    $ramoPDI  = $seleccionRamoPDI3['ID_ramos_PDI'];
                    $seleccionSeccionRamoPDI1 = "SELECT * FROM `seccion_ramo_PDI` WHERE `Ramos_PDI_id_Ramos_PDI`= " . $ramoPDI;
                    $seleccionSeccionRamoPDI2 = mysql_query($seleccionSeccionRamoPDI1) or die('Consulta fallida: '.mysql_error());
                    $cuenta2 = 1;
                    while($seleccionSeccionRamoPDI3 = mysql_fetch_assoc($seleccionSeccionRamoPDI2)){
                        if($cuenta2==1){
                            echo "<br>";
                            echo '<table align="center" width="752px" border="1" cellpadding="3" cellspacing="0" id="pdfTablaSeccion'.$cuenta1.$cuenta2.'"><tr class="titulo_fila"><td>N&deg; Secci&oacute;n </td><td>Horarios</td><td>Asignar Docente</td><td>Cantidad Estudiantes</td></tr>';
                        }
                        echo "<tr class='centro'>"
                            ;
                        echo "<td width='110px'>" .$cuenta2. "<input type='hidden' id='noSeccion' name='no_seccion".($cuenta1-1)."[]' value='$cuenta2'/></td>";
                        
                        //echo "ID Seccion Ramo PDI : ".$seleccionSeccionRamoPDI3['ID_seccion_ramo_PDI'];
                        //echo "Numero de Seccion : ".$seleccionSeccionRamoPDI3['Numero_seccion'];
                        //echo "ID Ramo PDI : ".$seleccionSeccionRamoPDI3['Ramos_PDI_id_Ramos_PDI'];
                        $horario11 = "SELECT `Periodo` FROM `periodos` WHERE `ID_periodo` = " . $seleccionSeccionRamoPDI3['Horario_1'];
                        $horario12 = mysql_query($horario11) or die('Consulta fallida: '.mysql_error());
                        $horario13 = mysql_fetch_assoc($horario12);
                        $horario14 = $horario13['Periodo'];
                        //echo "<td width='120px'>".$horario14."<input type='hidden' name='horario".($cuenta1-1)."[".($cuenta2-1)."][0]' value='".$seleccionSeccionRamoPDI3['Horario_1']."'/></td>";
                        $horario21 = "SELECT `Periodo` FROM `periodos` WHERE `ID_periodo` = " . $seleccionSeccionRamoPDI3['Horario_2'];
                        $horario22 = mysql_query($horario21) or die('Consulta fallida: '.mysql_error());
                        $horario23 = mysql_fetch_assoc($horario22);
                        $horario24 = $horario23['Periodo'];
                        //echo "<td width='120px'>".$horario24."<input type='hidden' name='horario".($cuenta1-1)."[".($cuenta2-1)."][1]' value='".$seleccionSeccionRamoPDI3['Horario_2']."'/></td>";
                        $horario31 = "SELECT `Periodo` FROM `periodos` WHERE `ID_periodo` = " . $seleccionSeccionRamoPDI3['Horario_3'];
                        $horario32 = mysql_query($horario31) or die('Consulta fallida: '.mysql_error());
                        $horario33 = mysql_fetch_assoc($horario32);
                        $horario34 = $horario33['Periodo'];
                        echo "<td width='200px'>".$horario14."<br>".$horario24."<br>".$horario34;
                        echo "<input type='hidden' name='horario".($cuenta1-1)."[".($cuenta2-1)."][0]' value='".$seleccionSeccionRamoPDI3['Horario_1']."'/></td>";
                        echo "<input type='hidden' name='horario".($cuenta1-1)."[".($cuenta2-1)."][1]' value='".$seleccionSeccionRamoPDI3['Horario_2']."'/>";
                        echo "<input type='hidden' name='horario".($cuenta1-1)."[".($cuenta2-1)."][2]' value='".$seleccionSeccionRamoPDI3['Horario_3']."'/>";
                        echo "</td>";
                        
                        echo "<td width='230px'>";
                        echo "<select id='profe".$cuenta1.$cuenta2."' name='profe_id".($cuenta1-1)."[]'>";
                        echo "<option value='0'>Seleccione Profesor</option>";
                        echo "<option value='1'>NOMBRE DE PROFESOR 1</option>";
                        echo "<option value='2'>NOMBRE DE PROFESOR 2</option>";
                        echo "<option value='3'>NOMBRE DE PROFESOR 3</option>";
                        echo "<option value='4'>NOMBRE DE PROFESOR 4</option>";
                        echo "</select>";
                        echo "</td>";
                        
                        echo "<td><input type='number' id='cantAlumnos".$cuenta1.$cuenta2."' name='cant_alumnos".($cuenta1-1)."[]' min='1' max='100'></td>";
                        
                        echo "</tr>";
                        $cuenta2++;
                    }
                    echo "</table>";
                    echo "<br>";
                    echo "</th>";
                    echo "</tr>";
                    echo "</table>";
                    echo "<br>";
                    $cuenta1++;
                }
                echo "</th>";
                echo "</tr>";
                echo "</table>";
            ?>
            <br>
            <center>     
                <input type="submit" name="asignar" formmethod="post" formaction="pdf2.php" value="Asignar Docentes" />
            </center>
        </form>
	</body>
</html>