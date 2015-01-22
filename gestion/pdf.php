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
                } else {
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
		<h3>Programaci&oacute;n Docente Final<br><small>Selecci&oacute;n de Programaci&oacute;n Docente Inicial</small></h3>
		<br />		
        <?php
            $link = mysql_connect('localhost', 'dbttii', 'dbttii') or die('No se pudo conectar: '.mysql_error());
            mysql_select_db('ttii') or die('No se pudo seleccionar la base de datos');
            $PDI1 = "SELECT * FROM `PDI` WHERE `ID_profesor` = '1' AND `Estado_PDI` = 4";
            $PDI2 = mysql_query($PDI1) or die('Consulta fallida: '.mysql_error());
            $cuenta = 0;
            while($fila = mysql_fetch_assoc($PDI2)){
                $cuenta++;
                if($cuenta == 1){
                    echo '<table align="center" width="75%" border="1" cellpadding="3" cellspacing="0" class="pequena" id="pdiTabla"><tr class="titulo_fila"><td>Folio PDI</td><td>Fecha</td><td>Departamento</td><td>Carrera</td><td>Estado</td><td>Solicitar <br>Programaci&oacute;n Docente Final</td></tr>';
                }
                $carrera1 = "SELECT `Nombre_carrera` FROM `carreras` WHERE `ID_carrera` = ".$fila['carreras_ID_carrera'] ;
                $carrera2 = mysql_query($carrera1) or die('Consulta fallida: ' . mysql_error());
                $carrera3 = mysql_fetch_assoc($carrera2);
                $depto1 = "SELECT `Nombre_depto` FROM `departamentos` WHERE `ID_depto` = ".$fila['departamentos_ID_depto'] ;
                $depto2 = mysql_query($depto1) or die('Consulta fallida: '.mysql_error());
                $depto3 = mysql_fetch_assoc($depto2);
                echo '<tr class="centro">';
                echo '<td>'.$fila['ID_PDI'].'</td>';
                echo '<td>'.$fila['Fecha_PDI'].'</td>';
                echo '<td>'.$carrera3['Nombre_carrera'].'</td>';
                echo '<td>'.$depto3['Nombre_depto'].'</td>';

                $estado01 = "SELECT `Nombre` FROM `estados_pdi_pdf` WHERE `ID_estado` = ".$fila['Estado_PDI'];
                $estado02 = mysql_query($estado01) or die('Consulta fallida: '.mysql_error());
                $estado03 = mysql_fetch_assoc($estado02);
                $estado04 = $estado03['Nombre'];
                
                echo '<td>'.$estado04.'</td>';
                echo '<td><center><a href="pdf1.php?id_pdi='.$fila['ID_PDI'].'">Enviar</a></center></td>';
                echo '</tr>';     
            }
            if($cuenta == 0) {
                echo '<div class="ayuda" style="text-align: left">
<div style="float:right"><img src="../images/icons/status_unknown.png" border="0" alt="·Atención!" title="·Atención!" width="32"></div>
<b>Estimado Docente:</b><br><p>No existe(n) Programaci&oacute;n(es) Docente Inicial creada o no es fecha para realizar solicitudes.</p>
</div>';
            } else {
                echo '</table>';
            }
        ?>
		<br>
		<div class="cita" style="width:80%">
			<b>Estado de las solicitudes (PDF) <small>[<a href="javascript:ver_oculta('msg')" class="no_linea">click para ver</a>]</small></b>
			<div id="msg" style="display: none">
                <ol>
                    <li></li> 
                    <li></li>
                </ol>
                <?php
                    $PDF21 = "SELECT * FROM `PDF` WHERE `ID_profesor` = '1'";
                    $PDF22 = mysql_query($PDF21) or die('Consulta fallida: '.mysql_error());
                    $cuenta0 = 0;
                    while($fila2 = mysql_fetch_assoc($PDF22)){
                        $cuenta0++;
                        if($cuenta0 == 1){
                            echo '<table align="center" width="90%" border="1" cellpadding="3" cellspacing="0" class="pequena" id="pdfTabla"><tr class="titulo_fila"><td>Folio PDF</td><td>Fecha</td><td>Departamento</td><td>Carrera</td><td>Estado Actual</td><td>Folio PDI<br>Relacionado</td></tr>';
                        }
                        $carrera21 = "SELECT `Nombre_carrera` FROM `carreras` WHERE `ID_carrera` = ".$fila2['carreras_ID_carrera'] ;
                        $carrera22 = mysql_query($carrera21) or die('Consulta fallida: ' . mysql_error());
                        $carrera23 = mysql_fetch_assoc($carrera22);
                        $depto21 = "SELECT `Nombre_depto` FROM `departamentos` WHERE `ID_depto` = ".$fila2['departamentos_ID_depto'] ;
                        $depto22 = mysql_query($depto21) or die('Consulta fallida: '.mysql_error());
                        $depto23 = mysql_fetch_assoc($depto22);
                        $estado11 = "SELECT `Nombre` FROM `estados_pdi_pdf` WHERE `ID_estado` = ".$fila2['Estado_PDF'];
                        $estado12 = mysql_query($estado11) or die('Consulta fallida: '.mysql_error());
                        $estado13 = mysql_fetch_assoc($estado12);
                        $estado14 = $estado13['Nombre'];
                        echo '<tr class="centro">';
                        echo '<td onClick=mostrar("detalle'.$cuenta0.'")>'.$fila2['ID_PDF'].' [<a href="#" class="no_linea">ver detalle</a>]</td>';
                        echo '<td>'.$fila2['Fecha_PDF'].'</td>';
                        echo '<td>'.$carrera23['Nombre_carrera'].'</td>';
                        echo '<td>'.$depto23['Nombre_depto'].'</td>';
                        echo '<td>'.$estado14.'</td>';
                        echo '<td>'.$fila2['ID_PDI'].'</td>';
                        echo '</tr>';

                        $seleccionRamoPDF1 = "SELECT * FROM `ramos_PDF` WHERE `PDF_id_PDF` = ".$fila2['ID_PDF'];
                        $seleccionRamoPDF2 = mysql_query($seleccionRamoPDF1) or die('Consulta fallida: '.mysql_error());

                        echo '<tr id="detalle'.$cuenta0.'" style="display: none"><th colspan="6">';
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
                    if($cuenta0 == 0) {
                        echo '<div class="ayuda" style="text-align: left">
        <div style="float:right"><img src="../images/icons/status_unknown.png" border="0" alt="·Atención!" title="·Atención!" width="32"></div>
        <b>Estimado Docente:</b><br><p>No existe(n) Programaci&oacute;n(es) Docente Final para revisar en Decanato o no es fecha para realizar solicitudes.</p>
        </div>';
                    } else {
                        echo '</table>';
                    }
                ?>
            </div>
		</div>
	</body>
</html>
