<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Creaci&oacute;n de Programaci&oacute;n Docente Final</title>
		<link rel="StyleSheet" type="text/css" href="gestion.css">
		<script src="../general/ayuda.js" type="text/javascript" language="JavaScript"></script>
		<script src="../general/prototype.js" type="text/javascript" language="JavaScript"></script>
		<script src="../general/ajax.js" type="text/javascript" language="JavaScript"></script>
	</head>
	<body>
        <script type="text/javascript" language="JavaScript">
			function anula(id){
				if(confirm('¿Est&aacute;s seguro de anular esta solicitud? \En caso que te encuentres en causal de eliminaci&oacute;n y no llenes otra solicitud, quedar&aacute;s eliminado de tu carrera')) 
					window.location.replace(id);
				return;
			}
			//funcion para validar los datos
			function validarSelect(id, link){
				 var dep=document.getElementById(id).value;
				 if (dep!='0'){
                    document.getElementById(link).style.display="";
				 }else{
                    document.getElementById(link).style.display="none";
				 }
			}
            function changeText(id, link) { 
                var x = document.getElementById(id);
                var y = document.getElementById(link);
                var base = y.href.indexOf("estado");
                var esta = link.length;
                console.log(base);
                console.log(esta);
                var todo = base + esta;
                console.log(todo);
                var cambioURL = y.href.substring(0,todo);
                console.log(cambioURL);
                cambioURL = cambioURL + x.value;
                y.href = cambioURL;
            } 
            function mostrar(detalle) {
                var style2 = document.getElementById(detalle).style;
		        style2.display = style2.display? "":"none";
            }
		</script>
		<div id="fecha">
			<?php echo date("d/m/Y"). "<br/>" . date("H:i");?>
		</div>
		<h3>Programaci&oacute;n Docente Final<br><small>Selecci&oacute;n de Departamento</small></h3>
		<br />
		<?php
            $link = mysql_connect('localhost', 'dbttii', 'dbttii') or die('No se pudo conectar: '.mysql_error());
            mysql_select_db('ttii') or die('No se pudo seleccionar la base de datos');
            $PDF1 = "SELECT * FROM `PDF` WHERE `ID_profesor` = '1' AND `Estado_PDF` = 13";
            $PDF2 = mysql_query($PDF1) or die('Consulta fallida: '.mysql_error());
            $cuenta = 0;
            while($fila = mysql_fetch_assoc($PDF2)){
                $cuenta++;
                if($cuenta == 1){
                    echo '<table align="center" width="75%" border="1" cellpadding="3" cellspacing="0" class="pequena" id="pdfTabla"><tr class="titulo_fila"><td>Folio</td><td>Fecha</td><td>Departamento</td><td>Carrera</td><td>Estado</td><td>Cambiar Estado</td></tr>';
                }
                $carrera1 = "SELECT `Nombre_carrera` FROM `carreras` WHERE `ID_carrera` = ".$fila['carreras_ID_carrera'] ;
                $carrera2 = mysql_query($carrera1) or die('Consulta fallida: ' . mysql_error());
                $carrera3 = mysql_fetch_assoc($carrera2);
                $depto1 = "SELECT `Nombre_depto` FROM `departamentos` WHERE `ID_depto` = ".$fila['departamentos_ID_depto'] ;
                $depto2 = mysql_query($depto1) or die('Consulta fallida: '.mysql_error());
                $depto3 = mysql_fetch_assoc($depto2);
                echo '<tr class="centro">';
                echo '<td onClick=mostrar("detalle'.$cuenta.'")>'.$fila['ID_PDF'].' [<a href="#" class="no_linea">ver detalle</a>]</td>';
                echo '<td>'.$fila['Fecha_PDF'].'</td>';
                echo '<td>'.$carrera3['Nombre_carrera'].'</td>';
                echo '<td>'.$depto3['Nombre_depto'].'</td>';
                echo '<td>';
                echo "<select id='id_estado".$cuenta."' name='estado".$cuenta."' onChange=changeText('id_estado".$cuenta."','estado".$cuenta."');validarSelect('id_estado".$cuenta."','estado".$cuenta."')>";
                echo "<option value='0'>Seleccione el Estado</option>";
                echo "<option value='16'>Cerrar PDF</option>";
                echo "<option value='14'>Aprobar (Enviar a DEA)</option>";
				echo "</select>";
                echo '</td>';
                echo '<td><center><a id="estado'.$cuenta.'" href="pdf1.php?id_pdf='.$fila['ID_PDF'].'&estado=" style="display:none">Enviar</a></center></td>';
                echo '</tr>';
                
                $seleccionRamoPDF1 = "SELECT * FROM `ramos_PDF` WHERE `PDF_id_PDF` = ".$fila['ID_PDF'];
                $seleccionRamoPDF2 = mysql_query($seleccionRamoPDF1) or die('Consulta fallida: '.mysql_error());
                
                echo '<tr id="detalle'.$cuenta.'" style="display: none"><th colspan="6">';
                $cuenta1 = 0;
                while($seleccionRamoPDF3 = mysql_fetch_assoc($seleccionRamoPDF2)){
                    $cuenta1++;
                    echo "<br>";
                    echo '<table align="center" width="800px" border="1" cellpadding="3" cellspacing="0" id="pdfTablaRamos'.$cuenta1.'"><tr class="titulo_fila"><td>N&deg; Ramo</td><td>C&oacute;d. de Ramo</td><td>Nombre del Ramo</td><td>Cantidad de Secciones</td></tr>';
                    echo "<tr class='centro'>";
                    echo "<td width='80px'>" .$cuenta1. "</td>";
                    
                    $codramo1 = "SELECT * FROM `ramos` WHERE `ID_ramo` = " . $seleccionRamoPDF3['ID_ramo'];
                    $codramo2 = mysql_query($codramo1) or die('Consulta fallida: '.mysql_error());
                    $codramo3 = mysql_fetch_assoc($codramo2);
                    $codramo = $codramo3['Codigo_ramo'];
                    $nomramo = $codramo3['Nombre_ramo'];
                    $seleccionRamoPDF4 = $seleccionRamoPDF3['Cantidad_secciones'];
                    
                    echo "<td width='150px'>".$codramo."</td>";
                    echo "<td width='400px'>".$nomramo."</td>";
                    echo "<td>".$seleccionRamoPDF4."</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<th colspan='4'>";
                    
                    echo "<br>";
                    $ramoPDF  = $seleccionRamoPDF3['ID_ramos_PDF'];
                    $seleccionSeccionRamoPDF1 = "SELECT * FROM `seccion_ramo_PDF` WHERE `Ramos_PDF_id_Ramos_PDF`= " . $ramoPDF;
                    $seleccionSeccionRamoPDF2 = mysql_query($seleccionSeccionRamoPDF1) or die('Consulta fallida: '.mysql_error());
                    while($seleccionSeccionRamoPDF3 = mysql_fetch_assoc($seleccionSeccionRamoPDF2)){
                        echo "<table align='center' border='1' cellspacing='0' cellpadding='3' width='700px' class='media'>";
                        echo "<tr><td class='titulo_fila media' colspan='4'>Secci&oacute;n N&uacute;mero ".$seleccionSeccionRamoPDF3['Numero_seccion']."</td></tr>";
                        for($k = 0; $k < 3; $k++) {
                            echo "<tr>";
                            echo "<td class='titulo_fila' width='35%'>Horario ".($k+1)."</td>";
                            if($k == 0){
                                $auxHorario = $seleccionSeccionRamoPDF3['Horario_1'];
                            } else if ($k == 1){
                                $auxHorario = $seleccionSeccionRamoPDF3['Horario_2'];
                            } else if ($k == 2){
                                $auxHorario = $seleccionSeccionRamoPDF3['Horario_3'];
                            }
                            $horarioE1 = "SELECT `Periodo` FROM `periodos` WHERE `ID_periodo` = ". $auxHorario;
                            $horarioE2 = mysql_query($horarioE1) or die('Consulta fallida: '.mysql_error());
                            $horarioE3 = mysql_fetch_assoc($horarioE2);
                            $horarioE4 = $horarioE3['Periodo'];
                            echo "<td>".$horarioE4."</td>";
                            echo "<td class='titulo_fila' width='25%'>Sala ".($k+1)."</td>";
                            if($k == 0){
                                $auxSala = $seleccionSeccionRamoPDF3['Sala_1'];
                            } else if ($k == 1){
                                $auxSala = $seleccionSeccionRamoPDF3['Sala_2'];
                            } else if ($k == 2){
                                $auxSala = $seleccionSeccionRamoPDF3['Sala_3'];
                            }
                            $salaE1 = "SELECT * FROM `salas` WHERE `ID_sala` = ". $auxSala ;
                            $salaE2 = mysql_query($salaE1) or die('Consulta fallida: '.mysql_error());
                            $salaE3 = mysql_fetch_assoc($salaE2);
                            $salaE4 = $salaE3['Nombre_sala'];
                            $salaE5 = $salaE3['Edificio'];
                            if($auxSala == 0){
                                echo "<td>Sin Periodo</td>";
                            } else {
                                echo "<td>".$salaE5." ".$salaE4."</td>";
                            }
                            echo "</tr>";
                        }
                        echo "<tr>";
                        echo "<td class='titulo_fila'>Profesor</td>";
                        echo "<td colspan='3'>".$seleccionSeccionRamoPDF3['Nombre_docente']."</td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td class='titulo_fila'>Cantidad de Estudiantes</td>";
                        echo "<td colspan='3'>".$seleccionSeccionRamoPDF3['Cantidad_alumnos']."</td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "<br>";
                    }
                    echo "<br>";
                    
                    echo "</th></tr>";
                    echo "</table>";
                    echo "<br>";
                }
                echo '</th></tr>';     
            }
            if($cuenta == 0) {
                echo '<div class="ayuda" style="text-align: left">
<div style="float:right"><img src="../images/icons/status_unknown.png" border="0" alt="·Atención!" title="·Atención!" width="32"></div>
<b>Estimado Docente:</b><br><p>No existe(n) Programaci&oacute;n(es) Docente Final para revisar en Vicerrector&iacute;a Acad&eacute;mica o no es fecha para realizar solicitudes.</p>
</div>';
            } else {
                echo '</table>';
            }
        ?>
        <br>
		<div class="cita" style="width:75%">
			<b>Te solicitamos recordar que <small>[<a href="javascript:ver_oculta('msg')" class="no_linea">ver</a>]</small></b>
			<ol id="msg" style="display: none">
				<li>La comisi&oacute;n verificar&aacute; la veracidad de lo que registras.</li> 
				<li>El resultado de tu petici&oacute;n podr&iacute;a estar desde el 12 de Marzo de 2014, dependiendo de la facultad en que te encuentres.</li>
				<li>Puedes hacer llegar antecedentes en tu favor, por intermedio del representante del tu centro de alumnos o mediante tu jefe de carrera.</li>
				<li>Si incurres en causal de eliminaci&oacute;n y no solicitas matr&iacute;cula de excepci&oacute;n quedar&aacute;s eliminado acad&eacute;micamente. </li>
				<li><b>NO PUEDES</b> solicitar matr&iacute;cula de excepci&oacute;n si ya lo has hecho dos veces y a&uacute;n no tienes el 70 % de tus asignaturas aprobadas.</li>
				<li>Si crees que te has equivocado, puedes eliminar esta solicitud y hacer otra.</li>
			</ol>
		</div>
	</body>
</html>
