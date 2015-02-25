<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
<html >
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Creaci&oacute;n de Programaci&oacute;n Docente Inicial</title>
		<link rel="StyleSheet" type="text/css" href="gestion.css">
		<script src="../general/ayuda.js" type="text/javascript" language="JavaScript"></script>
		<script src="../general/prototype.js" type="text/javascript" language="JavaScript"></script>
		<script src="../general/ajax.js" type="text/javascript" language="JavaScript"></script>
        <script type="text/javascript" language="JavaScript">
            function checkSalas(opt1,opt2,opt3,inpt,inptSala) {
                var sala1 = parseInt(opt1.options[opt1.selectedIndex].getAttribute("cap"));
                var sala2 = parseInt(opt2.options[opt2.selectedIndex].getAttribute("cap"));
                var sala3 = parseInt(opt3.options[opt3.selectedIndex].getAttribute("cap"));
                var sala = parseInt(inptSala.value);
                var cantEst = parseInt(inpt.value);
                if(inpt.value == "" || inpt.value == null) {
                    alert("Por favor, ingrese cantidad de Estudiantes");
                } else {
                    if(sala != 0) {
                        if((sala1 < cantEst) || (sala2 < cantEst) || (sala3 < cantEst)) {
                            alert("Por favor, ingrese cantidad de Estudiantes de acuerdo a la Capacidad de la Sala");
                        }
                    } else {
                        if((sala1 < cantEst) || (sala2 < cantEst)) {
                            alert("Por favor, ingrese cantidad de Estudiantes de acuerdo a la Capacidad de la Sala");
                        }
                    }
                }
            }
            function validarForm(formulario) {
                var salas = formulario.getElementsByClassName("sala");
                var profe = formulario.getElementsByClassName("profe");
                var est = formulario.getElementsByClassName("est");
                for(var i = 0; i < salas.length; i++) {
                    if(salas[i].value == 0) {
                        alert("Ingrese todas las Salas correspondientes");
                        return false;
                    }
                }
                for(var j = 0; j < profe.length; j++) {
                    if(profe[j].value == 0) {
                        alert("Ingrese todos los Profesores correspondientes");
                        return false;
                    }
                }
                for(var k = 0; k < est.length; k++) {
                    if(est[k].value == 0) {
                        alert("Ingrese todas las Cantidades de Estudiantes correspondientes");
                        return false;
                    }
                }
                return true;
            }
        </script>
	</head>
	<body>
		<div id="fecha">
			<?php echo date("d/m/Y"). "<br/>" . date("H:i");?>
		</div>
		<h3>Programaci&oacute;n Docente Final<br><small>Asignaci&oacute;n de Docente</small></h3>
        <form name="PDF" method="post" action="pdf2.php" onsubmit="return validarForm(this)">
            <?php
                $link = mysql_connect('localhost', 'dbttii', 'dbttii') or die('No se pudo conectar: '.mysql_error());
                mysql_select_db('ttii') or die('No se pudo seleccionar la base de datos');

                $idpdi = $_GET['id_pdi'];
                $seleccionPDI1 = "SELECT * FROM `PDI` WHERE `ID_PDI` = " . $idpdi;
                $seleccionPDI2 = mysql_query($seleccionPDI1) or die('Consulta fallida: '.mysql_error());
                $seleccionPDI3 = mysql_fetch_assoc($seleccionPDI2);

                $estado01 = "SELECT `Nombre` FROM `estados_pdi_pdf` WHERE `ID_estado` = ".$seleccionPDI3['Estado_PDI'];
                $estado02 = mysql_query($estado01) or die('Consulta fallida: '.mysql_error());
                $estado03 = mysql_fetch_assoc($estado02);

                $ID_profesor = $seleccionPDI3['ID_profesor'];

                $carrera1 = "SELECT `Nombre_carrera` FROM `carreras` WHERE `ID_carrera` = ".$seleccionPDI3['carreras_ID_carrera'];
                $carrera2 = mysql_query($carrera1) or die('Consulta fallida: '.mysql_error());
                $carrera3 = mysql_fetch_assoc($carrera2);

                $departamento1 = "SELECT `Nombre_depto` FROM `departamentos` WHERE `ID_depto` = ".$seleccionPDI3['departamentos_ID_depto'];
                $departamento2 = mysql_query($departamento1) or die('Consulta fallida: '.mysql_error());
                $departamento3 = mysql_fetch_assoc($departamento2);

                echo '<table align="center" width="75%" border="1" cellpadding="3" cellspacing="0" class="pequena" id="pdfTabla" name="pdfRamos">';
                echo '<tr class="titulo_fila"><td>Folio</td><td>Fecha</td><td>Nombre Docente</td><td>Escuela</td><td>Departamento</td><td>Carrera</td><td>Estado</td></tr>';
                echo "<tr class='centro'>";
                echo "<td>".$seleccionPDI3['ID_PDI']."<input type='hidden' id='idPDI' name='id_pdi' value='".$seleccionPDI3['ID_PDI']."'/></td>";
                echo "<td>".$seleccionPDI3['Fecha_PDI']."<input type='hidden' id='fechaPDI' name='fecha_pdi' value='".$seleccionPDI3['Fecha_PDI']."'/></td>";
                echo "<td>".$seleccionPDI3['Nombre_docente']."<input type='hidden' id='nombreDocente' name='nombre_docente' value='".$seleccionPDI3['Nombre_docente']."'/></td>";
                echo "<td>".$seleccionPDI3['ID_escuela']."<input type='hidden' id='IDEscuela' name='ID_Escuela' value='".$seleccionPDI3['ID_escuela']."'/></td>";
                echo "<td>".$departamento3['Nombre_depto']."<input type='hidden' id='depa' name='depto' value='".$departamento3['Nombre_depto']."'/></td>";
                echo "<td>".$carrera3['Nombre_carrera']."<input type='hidden' id='carre' name='carrera' value='".$carrera3['Nombre_carrera']."'/></td>";
                echo "<td>".$estado03['Nombre']."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<th colspan='7'>";

                $seleccionRamoPDI1 = "SELECT * FROM `ramos_PDI` WHERE `PDI_id_PDI` = ".$idpdi;
                $seleccionRamoPDI2 = mysql_query($seleccionRamoPDI1) or die('Consulta fallida: '.mysql_error());

                $cuenta1 = 1;
                while($seleccionRamoPDI3 = mysql_fetch_assoc($seleccionRamoPDI2)){
                    $ramo1 = "SELECT * FROM `ramos` WHERE `ID_ramo` = ". $seleccionRamoPDI3['ID_ramo'];
                    $ramo2 = mysql_query($ramo1) or die('Consulta fallida: '.mysql_error());
                    $ramo3 = mysql_fetch_assoc($ramo2);

                    echo "<br>";
                    echo '<table align="center" width="95%" border="1" cellpadding="3" cellspacing="0" id="pdfTablaRamos'.$cuenta1.'">';
                    echo '<tr class="titulo_fila"><td>N&deg; Ramo</td><td>C&oacute;d. de Ramo</td><td>Nombre del Ramo</td><td>Cantidad de Secciones</td></tr>';
                    echo "<tr class='centro'>";
                    echo "<td width='80px'>".$cuenta1."</td>";
                    echo "<td width='150px'>".$ramo3['Codigo_ramo']."<input type='hidden' id='codramo' name='codramos[]' value='".$ramo3['Codigo_ramo']."'/></td>";
                    echo "<td width='400px'>".$ramo3['Nombre_ramo']."<input type='hidden' id='nomramo' name='ramos[]' value='".$ramo3['Nombre_ramo']."'/></td>";
                    echo "<td>".$seleccionRamoPDI3['Cantidad_secciones']."<input type='hidden' id='cantidadSecciones' name='cantidad_secciones[]' value='".$seleccionRamoPDI3['Cantidad_secciones']."'/></td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<th colspan='4'>";

                    $seleccionSeccionRamoPDI1 = "SELECT * FROM `seccion_ramo_PDI` WHERE `Ramos_PDI_id_Ramos_PDI`= ".$seleccionRamoPDI3['ID_ramos_PDI'];
                    $seleccionSeccionRamoPDI2 = mysql_query($seleccionSeccionRamoPDI1) or die('Consulta fallida: '.mysql_error());

                    $cuenta2 = 1;
                    while($seleccionSeccionRamoPDI3 = mysql_fetch_assoc($seleccionSeccionRamoPDI2)){
                        $horario11 = "SELECT `Periodo` FROM `periodos` WHERE `ID_periodo` = " . $seleccionSeccionRamoPDI3['Horario_1'];
                        $horario12 = mysql_query($horario11) or die('Consulta fallida: '.mysql_error());
                        $horario13 = mysql_fetch_assoc($horario12);
                        
                        $horario21 = "SELECT `Periodo` FROM `periodos` WHERE `ID_periodo` = " . $seleccionSeccionRamoPDI3['Horario_2'];
                        $horario22 = mysql_query($horario21) or die('Consulta fallida: '.mysql_error());
                        $horario23 = mysql_fetch_assoc($horario22);
                        
                        $horario31 = "SELECT * FROM `periodos` WHERE `ID_periodo` = " . $seleccionSeccionRamoPDI3['Horario_3'];
                        $horario32 = mysql_query($horario31) or die('Consulta fallida: '.mysql_error());
                        $horario33 = mysql_fetch_assoc($horario32);

                        if($cuenta2==1){
                            echo "<br>";
                            echo '<table align="center" width="95%" border="1" cellpadding="3" cellspacing="0" id="pdfTablaSeccion'.$cuenta1.$cuenta2.'"><tr class="titulo_fila"><td>N&deg; Secci&oacute;n </td><td>Horarios</td><td>Salas</td><td>Asignar Docente</td><td>Cantidad Estudiantes</td></tr>';
                        }
                        echo "<tr class='centro'>";
                        echo "<td width='95px'>" .$cuenta2. "<input type='hidden' id='noSeccion' name='no_seccion".($cuenta1-1)."[]' value='$cuenta2'/></td>";
                        if($horario33['ID_periodo'] == 0){
                            echo "<td width='200px'>".$horario13['Periodo']."<br>".$horario23['Periodo']."</td>";
                        } else {
                            echo "<td width='200px'>".$horario13['Periodo']."<br>".$horario23['Periodo']."<br>".$horario33['Periodo']."</td>";
                        }
                        echo "<input type='hidden' id='horario".($cuenta1-1).($cuenta2-1)."0' name='horario".($cuenta1-1)."[".($cuenta2-1)."][0]' value='".$seleccionSeccionRamoPDI3['Horario_1']."'/>";
                        echo "<input type='hidden' id='horario".($cuenta1-1).($cuenta2-1)."1' name='horario".($cuenta1-1)."[".($cuenta2-1)."][1]' value='".$seleccionSeccionRamoPDI3['Horario_2']."'/>";
                        echo "<input type='hidden' id='horario".($cuenta1-1).($cuenta2-1)."2' name='horario".($cuenta1-1)."[".($cuenta2-1)."][2]' value='".$seleccionSeccionRamoPDI3['Horario_3']."'/>";
                        
                        $asignacion11 = "SELECT `ID_sala_asignacion` FROM `asignacion_salas` WHERE `ID_periodo_asignacion` = ".$seleccionSeccionRamoPDI3['Horario_1'];
                        $asignacion12 = mysql_query($asignacion11) or die('Consulta fallida: '.mysql_error());
                        $asignacion10 = mysql_num_rows($asignacion12);
                        if($asignacion10 == 0){
                            $salas11 = "SELECT * FROM `salas` WHERE `ID_sala` <> 0";
                            $salas12 = mysql_query($salas11) or die('Consulta fallida: '.mysql_error());
                        } else {
                            $salas11 = "SELECT * FROM `salas` WHERE `ID_sala` <> ( SELECT `ID_sala_asignacion` FROM `asignacion_salas` WHERE `ID_periodo_asignacion` = " . $seleccionSeccionRamoPDI3['Horario_1'].") AND `ID_sala` <> 0";
                            $salas12 = mysql_query($salas11) or die('Consulta fallida: '.mysql_error());
                        }

                        $asignacion21 = "SELECT `ID_sala_asignacion` FROM `asignacion_salas` WHERE `ID_periodo_asignacion` = ".$seleccionSeccionRamoPDI3['Horario_2'];
                        $asignacion22 = mysql_query($asignacion21) or die('Consulta fallida: '.mysql_error());
                        $asignacion20 = mysql_num_rows($asignacion22);  
                        if($asignacion20 == 0){
                            $salas21 = "SELECT * FROM `salas` WHERE `ID_sala` <> 0";
                            $salas22 = mysql_query($salas21) or die('Consulta fallida: '.mysql_error());
                        } else {
                            $salas21 = "SELECT * FROM `salas` WHERE `ID_sala` <> ( SELECT `ID_sala_asignacion` FROM `asignacion_salas` WHERE `ID_periodo_asignacion` = ".$seleccionSeccionRamoPDI3['Horario_2'].") AND `ID_sala` <> 0";
                            $salas22 = mysql_query($salas21) or die('Consulta fallida: '.mysql_error());
                        }
                            
                        echo "<td width='100px'>";
                        echo "<select id='salas".($cuenta1-1).($cuenta2-1)."0' name='salas_id".($cuenta1-1)."[".($cuenta2-1)."][0]' class='sala' required>";
                        echo "<option value='0' cap='0'>Seleccione Sala</option>";
                        while($salas13 = mysql_fetch_assoc($salas12)){
                            echo "<option value='".$salas13['ID_sala']."' cap='".$salas13['Capacidad']."'>".$salas13['Edificio']." ".$salas13['Nombre_sala']." (".$salas13['Capacidad']." alumnos)</option>";
                        }
                        echo "</select><br>";
                        echo "<select id='salas".($cuenta1-1).($cuenta2-1)."1' name='salas_id".($cuenta1-1)."[".($cuenta2-1)."][1]' class='sala' required>";
                        echo "<option value='0' cap='0'>Seleccione Sala</option>";
                        while($salas23 = mysql_fetch_assoc($salas22)){
                            echo "<option value='".$salas23['ID_sala']."' cap='".$salas23['Capacidad']."'>".$salas23['Edificio']." ".$salas23['Nombre_sala']." (".$salas23['Capacidad']." alumnos)</option>";
                        }
                        echo "</select><br>";
                        if($horario34 == "Sin Periodo"){
                            echo "<select id='salas".($cuenta1-1).($cuenta2-1)."2' name='salas_id".($cuenta1-1)."[".($cuenta2-1)."][2]' style='display:none'>";
                            echo "<option value='0' cap='0' selected='selected'></option></select>";
                        } else{
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
                            echo "<select id='salas".($cuenta1-1).($cuenta2-1)."2' name='salas_id".($cuenta1-1)."[".($cuenta2-1)."][2]' class='sala' required>";
                            echo "<option value='0' cap='0' selected='selected'>Seleccione Sala</option>";
                            while($salas33 = mysql_fetch_assoc($salas32)){
                                echo "<option value='".$salas33['ID_sala']."' cap='".$salas33['Capacidad']."'>".$salas33['Edificio']." ".$salas33['Nombre_sala']." (".$salas33['Capacidad']." alumnos)</option>";
                            }
                            echo "</select>";
                        }
                        echo "</td>";
                        
                        echo "<td width='230px'>";
                        echo "<select id='profe".$cuenta1.$cuenta2."' name='profe_id".($cuenta1-1)."[]' class='profe' required>";
                        echo "<option value='0'>Seleccione Profesor</option>";
                        echo "<option value='1'>NOMBRE DE PROFESOR 1</option>";
                        echo "<option value='2'>NOMBRE DE PROFESOR 2</option>";
                        echo "<option value='3'>NOMBRE DE PROFESOR 3</option>";
                        echo "<option value='4'>NOMBRE DE PROFESOR 4</option>";
                        echo "</select>";
                        echo "</td>";
                        
                        echo "<td width='200px'><input type='number' id='cantAlumnos".$cuenta1.$cuenta2."' name='cant_alumnos".($cuenta1-1)."[]' class='est' min='1' max='100' onblur='checkSalas(salas".($cuenta1-1).($cuenta2-1)."0,salas".($cuenta1-1).($cuenta2-1)."1,salas".($cuenta1-1).($cuenta2-1)."2,cantAlumnos".$cuenta1.$cuenta2.",horario".($cuenta1-1).($cuenta2-1)."2)'></td>";
                        
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
