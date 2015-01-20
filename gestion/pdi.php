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
			function validarForm(){
				 var dep=document.getElementById("departamento_id").value;
				 var car=document.getElementById("carrera_id").value;
				 if (dep=='0' && car=='0'){
				   alert('Seleccione departamento y carrera');
				   return false;
				 }else{
					if(dep!='0' && car=='0'){
						alert('Seleccione carrera');
						return false;
					}
					if(dep=='0' && car!='0'){
						alert('Seleccione departamento');
						return false;
					}else{
						return true;
					}
				 }
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
		<table align="center" width="75%" border="1" cellpadding="3" cellspacing="0" class="pequena">
			<form name="PDI" method="post" action="pdi1.php" onSubmit="return validarForm()">			
				<tr class="titulo_fila">
					<td>Departamento</td>
					<td>Carrera</td>
					<td>Solicitar <br>Programaci&oacute;n Docente Inicial</td>
				</tr>
				<tr class="centro">
					<td>
						<?php 
							$link = mysql_connect('localhost', 'dbttii', 'dbttii') or die('No se pudo conectar: ' . mysql_error());
							mysql_select_db('ttii') or die('No se pudo seleccionar la base de datos');
							$depa1 = "SELECT `Nombre_depto` FROM `departamentos` ORDER BY `ID_depto` ASC";
							$ID_depa1 = "SELECT `ID_depto` FROM `departamentos` ORDER BY `ID_depto` ASC";
							$depa2 = mysql_query($depa1) or die('Consulta fallida: ' . mysql_error());
							$ID_depa2 = mysql_query($ID_depa1) or die('Consulta fallida: ' . mysql_error());
							echo "<select id='departamento_id' name='departamento_name_id'>\n";
							echo "<option value='0'>Seleccione el departamento.</option>\n";
							while (($depa3 = mysql_fetch_array($depa2, MYSQL_ASSOC)) and ($ID_depa3 = mysql_fetch_array($ID_depa2, MYSQL_ASSOC))) {
								foreach (array_combine($depa3,$ID_depa3) as $depa4=>$ID_depa4) {
									echo "<option value='$ID_depa4'>$depa4</option>\n";
								}
							}
							echo "</select>\n";
						?>
					</td>
					<td>
						<?php 
							$carrera1 = "SELECT `Nombre_carrera` FROM `carreras`";
							$ID_carrera1 = "SELECT `ID_carrera` FROM `carreras`";
							$carrera2 = mysql_query($carrera1) or die('Consulta fallida: ' . mysql_error());
							$ID_carrera2 = mysql_query($ID_carrera1) or die('Consulta fallida: ' . mysql_error());
							echo "</select>\n";
							echo "<select id='carrera_id' name='carrera_name_id' >\n";
							echo "<option value='0' >Seleccione la carrera.</option>\n";
							while (($carrera3 = mysql_fetch_array($carrera2, MYSQL_ASSOC)) and ($ID_carrera3 = mysql_fetch_array($ID_carrera2, MYSQL_ASSOC))) {
								foreach (array_combine($carrera3,$ID_carrera3) as $carrera4=>$ID_carrera4) {
									echo "<option value='$ID_carrera4'>$carrera4</option>\n";
								}
							}
							echo "</select>\n";
						?>
					</td>
					<td>
						<center><input type="submit" name="solicitar" formmethod="post" formaction="pdi1.php" value="Elegir Ramos"></center>
					</td>
				</tr>
			</form>
		</table>
		<br>

		<br>
		<div class="cita" style="width:80%">
			<b>Estado de las solicitudes (PDI) <small>[<a href="javascript:ver_oculta('msg')" class="no_linea">click para ver</a>]</small></b>
            <div id="msg" style="display: none">
                <ol>
                    <li>Si quieres modificar alguna solicitud, s&oacute;lo realiza la solicitud para el mismo departamento y la misma carrera</li>
                    <li>S&oacute;lo se pueden modificar la que est&aacute;n en estado Revision Decanato</li>
                </ol>
                <br>
                <?php
                    $PDI1 = "SELECT * FROM `PDI` WHERE `ID_profesor` = '1'";
                    $PDI2 = mysql_query($PDI1) or die('Consulta fallida: '.mysql_error());
                    $cuenta = 0;
                    while($fila = mysql_fetch_assoc($PDI2)){
                        $cuenta++;
                        if($cuenta == 1){
                            echo '<table align="center" width="90%" border="1" cellpadding="3" cellspacing="0" class="pequena" id="pdiTabla"><tr class="titulo_fila"><td>Folio</td><td>Fecha</td><td>Departamento</td><td>Carrera</td><td>Estado Actual</td></tr>';
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
                        echo '<td>'.$fila['Estado'].'</td>';
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
                                if($horario34 == "Sin Periodo"){
                                    echo "<td width='200px'>".$horario14."<br>".$horario24."</td>";
                                } else {
                                    echo "<td width='200px'>".$horario14."<br>".$horario24."<br>".$horario34."</td>";
                                }
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
        <b>Estimado Docente:</b><br><p>No existe(n) Programaci&oacute;n(es) Docente Inicial para revisar o no es fecha para realizar solicitudes.</p>
        </div>';
                    } else {
                        echo '</table>';
                    }
                ?>
                <br>
				<!--li>La comisi&oacute;n verificar&aacute; la veracidad de lo que registras.</li> 
				<li>El resultado de tu petici&oacute;n podr&iacute;a estar desde el 12 de Marzo de 2014, dependiendo de la facultad en que te encuentres.</li>
				<li>Puedes hacer llegar antecedentes en tu favor, por intermedio del representante del tu centro de alumnos o mediante tu jefe de carrera.</li>
				<li>Si incurres en causal de eliminaci&oacute;n y no solicitas matr&iacute;cula de excepci&oacute;n quedar&aacute;s eliminado acad&eacute;micamente. </li>
				<li><b>NO PUEDES</b> solicitar matr&iacute;cula de excepci&oacute;n si ya lo has hecho dos veces y a&uacute;n no tienes el 70 % de tus asignaturas aprobadas.</li>
				<li>Si crees que te has equivocado, puedes eliminar esta solicitud y hacer otra.</li-->
			</div>
		</div>
	</body>
</html>
