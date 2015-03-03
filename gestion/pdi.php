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
                var depLista = document.getElementById("departamento_id");
                var depSelec = depLista.selectedIndex;
                var opcDepto = depLista.options[depSelec];
                var texDepto = opcDepto.text;
                var valDepto = opcDepto.value;

                var carLista = document.getElementById("carrera_id");
                var carSelec = carLista.selectedIndex;
                var opcCarre = carLista.options[carSelec];
                var texCarre = opcCarre.text;
                var valCarre = opcCarre.value;


                if (valDepto=='0' && valCarre=='0'){
                    alert('Seleccione Departamento y Carrera');
                    return false;
                }else{
                    if(valDepto!='0' && valCarre=='0'){
						alert('Seleccione Carrera');
						return false;
					}
					if(valDepto=='0' && valCarre!='0'){
						alert('Seleccione Departamento');
						return false;
					}else{
						//codigo para validar que no este repetido la seleccion depto-carrera
                        var consulta =
                        <?php
                            $link = mysql_connect('localhost', 'dbttii', 'dbttii') or die('No se pudo conectar: ' . mysql_error());
                            mysql_select_db('ttii') or die('No se pudo seleccionar la base de datos');

                            $PDIConsulta1 = "SELECT * FROM `PDI` WHERE `ID_profesor` = '1' AND `Estado_PDI` <> 7";
                            $PDIConsulta2 = mysql_query($PDIConsulta1) or die('Consulta fallida: '.mysql_error());
                            $i = 0;
                            while($PDIConsulta3 = mysql_fetch_assoc($PDIConsulta2)) {
                                $par[$i][0] = $PDIConsulta3['departamentos_ID_depto'];
                                $par[$i][1] = $PDIConsulta3['carreras_ID_carrera'];
                                $par[$i][2] = $PDIConsulta3['ID_PDI'];
                                $i++;
                            }
                            echo json_encode($par);
                        ?>;
                        console.log(consulta);
                        for(var i=0;i<consulta.length;i++) {
                            if(consulta[i][0] == valDepto && consulta[i][1] == valCarre){
                                var modificar = confirm("Se va crear una Programaci\u00f3n Docente Inicial para departamento "+texDepto+" y carrera "+texCarre+", se sobreescribir\u00e1 PDI N\u00ba "+consulta[i][2]+". \u00bfDesea eliminar la solicitud anterior y crear una nueva\u003f");
                                if(modificar){
                                    var repetido = document.getElementById("estaRepetido");
                                    var PDIrepet = document.getElementById("PDIRepetido");
                                    repetido.value = "1";
                                    PDIrepet.value = consulta[i][2];
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        }
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
			<form name="PDI" method="post" id="PDI" action="pdi1.php" onSubmit="return validarForm();">
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

                            $depa1 = "SELECT * FROM `departamentos` ORDER BY `ID_depto` ASC";
							$depa2 = mysql_query($depa1) or die('Consulta fallida: ' . mysql_error());

                            echo "<select id='departamento_id' name='departamento_name_id' required autofocus>";
							echo "<option value='0'>Seleccione el departamento.</option>";
                            while($depa3 = mysql_fetch_array($depa2)) {
                                echo "<option value='".$depa3['ID_depto']."'>".$depa3['Nombre_depto']."</option>";
                            }
                            echo "</select>";
						?>
					</td>
                    <input type="hidden" name="estaRepetido" id="estaRepetido" value="0">
                    <input type="hidden" name="PDIRepetido" id="PDIRepetido" value="">
					<td>
						<?php 
							$carre1 = "SELECT * FROM `carreras`";
                            $carre2 = mysql_query($carre1) or die('Consulta fallida: ' . mysql_error());

							echo "<select id='carrera_id' name='carrera_name_id' required>";
							echo "<option value='0' >Seleccione la carrera.</option>";
                            while($carre3 = mysql_fetch_array($carre2)) {
                                echo "<option value='".$carre3['ID_carrera']."'>".$carre3['Nombre_carrera']."</option>";
                            }
							echo "</select>";
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
                    while($PDI3 = mysql_fetch_assoc($PDI2)){
                        $cuenta++;
                        if($cuenta == 1){
                            echo '<table align="center" width="90%" border="1" cellpadding="3" cellspacing="0" class="pequena" id="pdiTabla"><tr class="titulo_fila"><td>Folio PDI</td><td>Fecha</td><td>Departamento</td><td>Carrera</td><td>Estado Actual</td><td>PDI Cancelado</td></tr>';
                        }

                        $carrera1 = "SELECT `Nombre_carrera` FROM `carreras` WHERE `ID_carrera` = ".$PDI3['carreras_ID_carrera'] ;
                        $carrera2 = mysql_query($carrera1) or die('Consulta fallida: ' . mysql_error());
                        $carrera3 = mysql_fetch_assoc($carrera2);

                        $depto1 = "SELECT `Nombre_depto` FROM `departamentos` WHERE `ID_depto` = ".$PDI3['departamentos_ID_depto'] ;
                        $depto2 = mysql_query($depto1) or die('Consulta fallida: '.mysql_error());
                        $depto3 = mysql_fetch_assoc($depto2);
                        
                        $estado01 = "SELECT `Nombre` FROM `estados_pdi_pdf` WHERE `ID_estado` = ".$PDI3['Estado_PDI'];
                        $estado02 = mysql_query($estado01) or die('Consulta fallida: '.mysql_error());
                        $estado03 = mysql_fetch_assoc($estado02);
                        
                        echo '<tr class="centro">';
                        echo '<td onClick=mostrar("detalle'.$cuenta.'")>'.$PDI3['ID_PDI'].' [<a href="#" class="no_linea">ver detalle</a>]</td>';
                        echo '<td>'.$PDI3['Fecha_PDI'].'</td>';
                        echo '<td>'.$carrera3['Nombre_carrera'].'</td>';
                        echo '<td>'.$depto3['Nombre_depto'].'</td>';
                        echo '<td>'.$estado03['Nombre'].'</td>';
                        if($PDI3['PDI_cancelado']!=null) {
                            echo '<td>'.$PDI3['PDI_cancelado'].'</td>';
                        } else {
                            echo '<td>No</td>';
                        }
                        echo '</tr>';

                        $seleccionRamoPDI1 = "SELECT * FROM `ramos_PDI` WHERE `PDI_id_PDI` = ".$PDI3['ID_PDI'];
                        $seleccionRamoPDI2 = mysql_query($seleccionRamoPDI1) or die('Consulta fallida: '.mysql_error());

                        echo '<tr id="detalle'.$cuenta.'" style="display: none"><th colspan="6">';
                        $cuenta1 = 0;
                        while($seleccionRamoPDI3 = mysql_fetch_assoc($seleccionRamoPDI2)){
                            $cuenta1++;

                            $codramo1 = "SELECT * FROM `ramos` WHERE `ID_ramo` = " . $seleccionRamoPDI3['ID_ramo'];
                            $codramo2 = mysql_query($codramo1) or die('Consulta fallida: '.mysql_error());
                            $codramo3 = mysql_fetch_assoc($codramo2);
                            $codramo = $codramo3['Codigo_ramo'];
                            $nomramo = $codramo3['Nombre_ramo'];

                            echo "<br>";
                            echo '<table align="center" width="800px" border="1" cellpadding="3" cellspacing="0" id="pdfTablaRamos'.$cuenta1.'"><tr class="titulo_fila"><td>N&deg; Ramo</td><td>C&oacute;d. de Ramo</td><td>Nombre del Ramo</td><td>Cantidad de Secciones</td></tr>';
                            echo "<tr class='centro'>";
                            echo "<td width='80px'>" .$cuenta1. "</td>";
                            echo "<td width='150px'>".$codramo."</td>";
                            echo "<td width='400px'>".$nomramo."</td>";
                            echo "<td>".$seleccionRamoPDI3['Cantidad_secciones']."</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<th colspan='4'>";

                            $seleccionSeccionRamoPDI1 = "SELECT * FROM `seccion_ramo_PDI` WHERE `Ramos_PDI_id_Ramos_PDI`= ".$seleccionRamoPDI3['ID_ramos_PDI'];
                            $seleccionSeccionRamoPDI2 = mysql_query($seleccionSeccionRamoPDI1) or die('Consulta fallida: '.mysql_error());
                            $cuenta2 = 1;
                            while($seleccionSeccionRamoPDI3 = mysql_fetch_assoc($seleccionSeccionRamoPDI2)){
                                if($cuenta2==1){
                                    echo "<br>";
                                    echo '<table align="center" border="1" cellpadding="3" cellspacing="0" id="pdfTablaSeccion'.$cuenta1.$cuenta2.'"><tr class="titulo_fila"><td>N&deg; Secci&oacute;n </td><td>Horarios</td></tr>';
                                }
                                $horario11 = "SELECT `Periodo` FROM `periodos` WHERE `ID_periodo` = " . $seleccionSeccionRamoPDI3['Horario_1'];
                                $horario12 = mysql_query($horario11) or die('Consulta fallida: '.mysql_error());
                                $horario13 = mysql_fetch_assoc($horario12);

                                $horario21 = "SELECT `Periodo` FROM `periodos` WHERE `ID_periodo` = " . $seleccionSeccionRamoPDI3['Horario_2'];
                                $horario22 = mysql_query($horario21) or die('Consulta fallida: '.mysql_error());
                                $horario23 = mysql_fetch_assoc($horario22);

                                $horario31 = "SELECT * FROM `periodos` WHERE `ID_periodo` = " . $seleccionSeccionRamoPDI3['Horario_3'];
                                $horario32 = mysql_query($horario31) or die('Consulta fallida: '.mysql_error());
                                $horario33 = mysql_fetch_assoc($horario32);

                                echo "<tr class='centro'>";
                                echo "<td width='110px'>" .$cuenta2. "</td>";
                                if($horario33['ID_periodo'] == 0){
                                    echo "<td width='200px'>".$horario13['Periodo']."<br>".$horario23['Periodo']."</td>";
                                } else {
                                    echo "<td width='200px'>".$horario13['Periodo']."<br>".$horario23['Periodo']."<br>".$horario33['Periodo']."</td>";
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
