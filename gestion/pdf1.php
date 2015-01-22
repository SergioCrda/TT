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
                $estado = $seleccionPDI3['Estado_PDI'];

                $estado01 = "SELECT `Nombre` FROM `estados_pdi_pdf` WHERE `ID_estado` = ".$estado;
                $estado02 = mysql_query($estado01) or die('Consulta fallida: '.mysql_error());
                $estado03 = mysql_fetch_assoc($estado02);
                $estado04 = $estado03['Nombre'];

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
                echo "<td>".$estado04."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<th colspan='7'>";
                $seleccionRamoPDI1 = "SELECT * FROM `ramos_PDI` WHERE `PDI_id_PDI` = ".$idpdi;
                $seleccionRamoPDI2 = mysql_query($seleccionRamoPDI1) or die('Consulta fallida: '.mysql_error());
                $cuenta1 = 1;
                while($seleccionRamoPDI3 = mysql_fetch_assoc($seleccionRamoPDI2)){
                    echo "<br>";
                    echo '<table align="center" width="95%" border="1" cellpadding="3" cellspacing="0" id="pdfTablaRamos'.$cuenta1.'"><tr class="titulo_fila"><td>N&deg; Ramo</td><td>C&oacute;d. de Ramo</td><td>Nombre del Ramo</td><td>Cantidad de Secciones</td></tr>';
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
                            echo '<table align="center" width="95%" border="1" cellpadding="3" cellspacing="0" id="pdfTablaSeccion'.$cuenta1.$cuenta2.'"><tr class="titulo_fila"><td>N&deg; Secci&oacute;n </td><td>Horarios</td><td>Salas</td><td>Asignar Docente</td><td>Cantidad Estudiantes</td></tr>';
                        }
                        echo "<tr class='centro'>";
                        echo "<td width='95px'>" .$cuenta2. "<input type='hidden' id='noSeccion' name='no_seccion".($cuenta1-1)."[]' value='$cuenta2'/></td>";

                        $horario11 = "SELECT `Periodo` FROM `periodos` WHERE `ID_periodo` = " . $seleccionSeccionRamoPDI3['Horario_1'];
                        $horario12 = mysql_query($horario11) or die('Consulta fallida: '.mysql_error());
                        $horario13 = mysql_fetch_assoc($horario12);
                        $horario14 = $horario13['Periodo'];
                        
                        $horario21 = "SELECT `Periodo` FROM `periodos` WHERE `ID_periodo` = " . $seleccionSeccionRamoPDI3['Horario_2'];
                        $horario22 = mysql_query($horario21) or die('Consulta fallida: '.mysql_error());
                        $horario23 = mysql_fetch_assoc($horario22);
                        $horario24 = $horario23['Periodo'];
                        
                        $horario31 = "SELECT `Periodo` FROM `periodos` WHERE `ID_periodo` = " . $seleccionSeccionRamoPDI3['Horario_3'];
                        $horario32 = mysql_query($horario31) or die('Consulta fallida: '.mysql_error());
                        $horario33 = mysql_fetch_assoc($horario32);
                        $horario34 = $horario33['Periodo'];
                        if($horario34 == "Sin Periodo"){
                            echo "<td width='200px'>".$horario14."<br>".$horario24."</td>";
                        } else {
                            echo "<td width='200px'>".$horario14."<br>".$horario24."<br>".$horario34."</td>";
                        }
                        echo "<input type='hidden' name='horario".($cuenta1-1)."[".($cuenta2-1)."][0]' value='".$seleccionSeccionRamoPDI3['Horario_1']."'/>";
                        echo "<input type='hidden' name='horario".($cuenta1-1)."[".($cuenta2-1)."][1]' value='".$seleccionSeccionRamoPDI3['Horario_2']."'/>";
                        echo "<input type='hidden' name='horario".($cuenta1-1)."[".($cuenta2-1)."][2]' value='".$seleccionSeccionRamoPDI3['Horario_3']."'/>";
                        
                        $asignacion11 = "SELECT `ID_sala_asignacion` FROM `asignacion_salas` WHERE `ID_periodo_asignacion` = " . $seleccionSeccionRamoPDI3['Horario_1'];
                        $asignacion12 = mysql_query($asignacion11) or die('Consulta fallida: '.mysql_error());
                        $asignacion10 = mysql_num_rows($asignacion12);  
                        if($asignacion10 == 0){
                            $salas11 = "SELECT * FROM `salas` WHERE `ID_sala` <> 0";
                            $salas12 = mysql_query($salas11) or die('Consulta fallida: '.mysql_error());
                        } else {
                            $salas11 = "SELECT * FROM `salas` WHERE `ID_sala` <> ( SELECT `ID_sala_asignacion` FROM `asignacion_salas` WHERE `ID_periodo_asignacion` = " . $seleccionSeccionRamoPDI3['Horario_1'].") AND `ID_sala` <> 0";
                            $salas12 = mysql_query($salas11) or die('Consulta fallida: '.mysql_error());
                        }

                        $asignacion21 = "SELECT `ID_sala_asignacion` FROM `asignacion_salas` WHERE `ID_periodo_asignacion` = " . $seleccionSeccionRamoPDI3['Horario_2'];
                        $asignacion22 = mysql_query($asignacion21) or die('Consulta fallida: '.mysql_error());
                        $asignacion20 = mysql_num_rows($asignacion22);  
                        if($asignacion20 == 0){
                            $salas21 = "SELECT * FROM `salas` WHERE `ID_sala` <> 0";
                            $salas22 = mysql_query($salas21) or die('Consulta fallida: '.mysql_error());
                        } else {
                            $salas21 = "SELECT * FROM `salas` WHERE `ID_sala` <> ( SELECT `ID_sala_asignacion` FROM `asignacion_salas` WHERE `ID_periodo_asignacion` = " . $seleccionSeccionRamoPDI3['Horario_2'].") AND `ID_sala` <> 0";
                            $salas22 = mysql_query($salas21) or die('Consulta fallida: '.mysql_error());
                        }
                            
                        echo "<td width='100px'>";
                        echo "<select id='salas".($cuenta1-1).($cuenta2-1)."0' name='salas_id".($cuenta1-1)."[".($cuenta2-1)."][0]'>";
                        echo "<option value='0'>Seleccione Sala</option>";
                        while($salas13 = mysql_fetch_assoc($salas12)){
                            echo "<option value='".$salas13['ID_sala']."'>".$salas13['Edificio']." ".$salas13['Nombre_sala']."</option>";
                        }
                        echo "</select><br>";
                        echo "<select id='salas".($cuenta1-1).($cuenta2-1)."1' name='salas_id".($cuenta1-1)."[".($cuenta2-1)."][1]'>";
                        echo "<option value='0'>Seleccione Sala</option>";
                        while($salas23 = mysql_fetch_assoc($salas22)){
                            echo "<option value='".$salas23['ID_sala']."'>".$salas23['Edificio']." ".$salas23['Nombre_sala']."</option>";
                        }
                        echo "</select><br>";
                        if($horario34 == "Sin Periodo"){
                            echo "<select id='salas".($cuenta1-1).($cuenta2-1)."2' name='salas_id".($cuenta1-1)."[".($cuenta2-1)."][2]' style='display:none'>";
                            echo "<option value='0' selected='selected'></option></select>";
                        } else{
                            echo "<select id='salas".($cuenta1-1).($cuenta2-1)."2' name='salas_id".($cuenta1-1)."[".($cuenta2-1)."][2]'>";
                            echo "<option value='' selected='selected'>Seleccione Sala</option>";
                            $asignacion31 = "SELECT `ID_sala_asignacion` FROM `asignacion_salas` WHERE `ID_periodo_asignacion` = " . $seleccionSeccionRamoPDI3['Horario_3'];
                            $asignacion32 = mysql_query($asignacion31) or die('Consulta fallida: '.mysql_error());
                            $asignacion30 = mysql_num_rows($asignacion32);  
                            if($asignacion30 == 0){
                                $salas31 = "SELECT * FROM `salas` WHERE `ID_sala` <> 0";
                                $salas32 = mysql_query($salas31) or die('Consulta fallida: '.mysql_error());
                            } else {
                                $salas31 = "SELECT * FROM `salas` WHERE `ID_sala` <> ( SELECT `ID_sala_asignacion` FROM `asignacion_salas` WHERE `ID_periodo_asignacion` = " . $seleccionSeccionRamoPDI3['Horario_3'].") AND `ID_sala` <> 0";
                                $salas32 = mysql_query($salas31) or die('Consulta fallida: '.mysql_error());
                            }
                            while($salas33 = mysql_fetch_assoc($salas32)){
                                echo "<option value='".$salas33['ID_sala']."'>".$salas33['Edificio']." ".$salas33['Nombre_sala']."</option>";
                            }
                            echo "</select>";
                        }
                        
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
                        
                        echo "<td width='200px'><input type='number' id='cantAlumnos".$cuenta1.$cuenta2."' name='cant_alumnos".($cuenta1-1)."[]' min='1' max='100'></td>";
                        
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