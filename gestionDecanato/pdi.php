<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
<html>
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
		<h3>Programaci&oacute;n Docente Inicial<br><small>Selecci&oacute;n de Departamento</small></h3>
		<br />
		<?php
            $link = mysql_connect('localhost', 'dbttii', 'dbttii') or die('No se pudo conectar: '.mysql_error());
            mysql_select_db('ttii') or die('No se pudo seleccionar la base de datos');
            $PDI1 = "SELECT * FROM `PDI` WHERE `ID_profesor` = '1' AND `Estado` = 'Revision Decanato'";
            $PDI2 = mysql_query($PDI1) or die('Consulta fallida: '.mysql_error());
            $cuenta = 0;
            while($fila = mysql_fetch_assoc($PDI2)){
                $cuenta++;
                if($cuenta == 1){
                    echo '<table align="center" width="75%" border="1" cellpadding="3" cellspacing="0" class="pequena" id="pdiTabla"><tr class="titulo_fila"><td>Folio</td><td>Fecha</td><td>Departamento</td><td>Carrera</td><td>Estado</td><td>Cambiar Estado</td></tr>';
                }
                $carrera1 = "SELECT `Nombre_carrera` FROM `carreras` WHERE `ID_carrera` = ".$fila['carreras_ID_carrera'] ;
                $carrera2 = mysql_query($carrera1) or die('Consulta fallida: ' . mysql_error());
                $carrera3 = mysql_fetch_assoc($carrera2);
                $depto1 = "SELECT `Nombre_depto` FROM `departamentos` WHERE `ID_depto` = ".$fila['departamentos_ID_depto'] ;
                $depto2 = mysql_query($depto1) or die('Consulta fallida: '.mysql_error());
                $depto3 = mysql_fetch_assoc($depto2);
                echo '<tr class="centro">';
                echo '<td onClick=mostrar("detalle'.$cuenta.'")>'.$fila['ID_PDI'].' [<a href="#" class="no_linea">ver detalle</a>]</td>';
                echo '<td>'.$fila['Fecha_PDI'].'</td>';
                echo '<td>'.$carrera3['Nombre_carrera'].'</td>';
                echo '<td>'.$depto3['Nombre_depto'].'</td>';
                echo '<td>';
                echo "<select id='id_estado".$cuenta."' name='estado".$cuenta."' onChange=changeText('id_estado".$cuenta."','estado".$cuenta."');validarSelect('id_estado".$cuenta."','estado".$cuenta."')>";
                echo "<option value='0'>Seleccione el Estado</option>";
                echo "<option value='Cerrado'>Cerrar PDI</option>";
                echo "<option value='Revision VRAC'>Aprobar (Enviar a VRAC)</option>";
				echo "</select>";
                echo '</td>';
                echo '<td><center><a id="estado'.$cuenta.'" href="pdi1.php?id_pdi='.$fila['ID_PDI'].'&estado=" style="display:none">Enviar</a></center></td>';
                echo '</tr>';
                
                $seleccionRamoPDI1 = "SELECT * FROM `ramos_PDI` WHERE `PDI_id_PDI` = ".$fila['ID_PDI'];
                $seleccionRamoPDI2 = mysql_query($seleccionRamoPDI1) or die('Consulta fallida: '.mysql_error());
                
                echo '<tr id="detalle'.$cuenta.'" style="display: none"><th colspan="6">';
                $cuenta1 = 0;
                while($seleccionRamoPDI3 = mysql_fetch_assoc($seleccionRamoPDI2)){
                    $cuenta1++;
                    echo "<br>";
                    echo '<table align="center" width="800px" border="1" cellpadding="3" cellspacing="0" id="pdfTablaRamos'.$cuenta1.'"><tr class="titulo_fila"><td>N&deg; Ramo</td><td>C&oacute;d. de Ramo</td><td>Nombre del Ramo</td><td>Cantidad de Secciones</td></tr>';
                    echo "<tr class='centro'>";
                    echo "<td width='80px'>" .$cuenta1. "</td>";
                    
                    $codramo1 = "SELECT * FROM `ramos` WHERE `ID_ramo` = " . $seleccionRamoPDI3['ID_ramo'];
                    $codramo2 = mysql_query($codramo1) or die('Consulta fallida: '.mysql_error());
                    $codramo3 = mysql_fetch_assoc($codramo2);
                    $codramo = $codramo3['Codigo_ramo'];
                    $nomramo = $codramo3['Nombre_ramo'];
                    $seleccionRamoPDI4 = $seleccionRamoPDI3['Cantidad_secciones'];
                    
                    echo "<td width='150px'>".$codramo."</td>";
                    echo "<td width='400px'>".$nomramo."</td>";
                    echo "<td>".$seleccionRamoPDI4."</td>";
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
                            echo '<table align="center" border="1" cellpadding="3" cellspacing="0" id="pdfTablaSeccion'.$cuenta1.$cuenta2.'"><tr class="titulo_fila"><td>N&deg; Secci&oacute;n </td><td>Horarios</td></tr>';
                        }
                        echo "<tr class='centro'>"
                            ;
                        echo "<td width='110px'>" .$cuenta2. "</td>";
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
                        echo "<td width='200px'>".$horario14."<br>".$horario24."<br>".$horario34."</td>";
                        echo "</td>";

                        echo "</tr>";
                        $cuenta2++;
                    }
                    echo "</table>";
                    echo "<br>";
                    echo "</th>";
                    echo "</tr>";
                    echo "</table>";
                    echo "<br>";
                }
                echo '</th></tr>';     
            }
            if($cuenta == 0) {
                echo '<div class="ayuda" style="text-align: left">
<div style="float:right"><img src="../images/icons/status_unknown.png" border="0" alt="·Atención!" title="·Atención!" width="32"></div>
<b>Estimado Docente:</b><br><p>No existe(n) Programaci&oacute;n(es) Docente Inicial para revisar en Decanato o no es fecha para realizar solicitudes.</p>
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
		<!--h4>Solicitudes anteriores en este semestre</h4>
		<table align="center" width="75%" border="1" cellpadding="3" cellspacing="0" class="pequena">
			<tr class="titulo_fila">
				<td width="5%">N&uacute;mero</td>
				<td width="15%">Fecha</td>
				<td width="25%">Estado</td>
				<td colspan="2">{ Acci&oacute;n }</td>
			</tr>
				<tr class="centro">
				<td colspan="5">
					<img src="../images/mini/icons-mini-icon_alert.gif" alt="" align="middle"/><br/>
					No has solicitado matr&iacute;culas de excepci&oacute;n en este semestre
				</td>
			</tr>
		</table-->
	</body>
</html>
