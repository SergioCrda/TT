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
            $PDI1 = "SELECT * FROM `PDI` WHERE `ID_profesor` = '1' AND `Estado` = 'PDI Aprobado'";
            $PDI2 = mysql_query($PDI1) or die('Consulta fallida: '.mysql_error());
            $cuenta = 0;
            while($fila = mysql_fetch_assoc($PDI2)){
                $cuenta++;
                if($cuenta == 1){
                    echo '<table align="center" width="75%" border="1" cellpadding="3" cellspacing="0" class="pequena" id="pdiTabla"><tr class="titulo_fila"><td>Folio</td><td>Fecha</td><td>Departamento</td><td>Carrera</td><td>Estado</td><td>Solicitar <br>Programaci&oacute;n Docente Final</td></tr>';
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
                echo '<td>'.$fila['Estado'].'</td>';
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
                            echo '<table align="center" width="90%" border="1" cellpadding="3" cellspacing="0" class="pequena" id="pdfTabla"><tr class="titulo_fila"><td>Folio</td><td>Fecha</td><td>Departamento</td><td>Carrera</td><td>Estado Actual</td></tr>';
                        }
                        $carrera21 = "SELECT `Nombre_carrera` FROM `carreras` WHERE `ID_carrera` = ".$fila2['carreras_ID_carrera'] ;
                        $carrera22 = mysql_query($carrera21) or die('Consulta fallida: ' . mysql_error());
                        $carrera23 = mysql_fetch_assoc($carrera22);
                        $depto21 = "SELECT `Nombre_depto` FROM `departamentos` WHERE `ID_depto` = ".$fila2['departamentos_ID_depto'] ;
                        $depto22 = mysql_query($depto21) or die('Consulta fallida: '.mysql_error());
                        $depto23 = mysql_fetch_assoc($depto22);
                        echo '<tr class="centro">';
                        echo '<td onClick=mostrar("detalle'.$cuenta0.'")>'.$fila2['ID_PDF'].' [<a href="#" class="no_linea">ver detalle</a>]</td>';
                        echo '<td>'.$fila2['Fecha_PDF'].'</td>';
                        echo '<td>'.$carrera23['Nombre_carrera'].'</td>';
                        echo '<td>'.$depto23['Nombre_depto'].'</td>';
                        echo '<td>'.$fila2['Estado'].'</td>';
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
                            $ramoPDF  = $seleccionRamoPDF3['ID_ramos_PDF'];
                            $seleccionSeccionRamoPDF1 = "SELECT * FROM `seccion_ramo_PDF` WHERE `Ramos_PDF_id_Ramos_PDF`= " . $ramoPDF;
                            $seleccionSeccionRamoPDF2 = mysql_query($seleccionSeccionRamoPDF1) or die('Consulta fallida: '.mysql_error());
                            $cuenta2 = 1;
                            while($seleccionSeccionRamoPDF3 = mysql_fetch_assoc($seleccionSeccionRamoPDF2)){
                                if($cuenta2==1){
                                    echo "<br>";
                                    echo '<table align="center" border="1" cellpadding="3" cellspacing="0" id="pdfTablaSeccion'.$cuenta1.$cuenta2.'"><tr class="titulo_fila"><td>N&deg; Secci&oacute;n </td><td>Horarios</td></tr>';
                                }
                                echo "<tr class='centro'>";
                                echo "<td width='110px'>" .$cuenta2. "</td>";
                                $horario11 = "SELECT `Periodo` FROM `periodos` WHERE `ID_periodo` = " . $seleccionSeccionRamoPDF3['Horario_1'];
                                $horario12 = mysql_query($horario11) or die('Consulta fallida: '.mysql_error());
                                $horario13 = mysql_fetch_assoc($horario12);
                                $horario14 = $horario13['Periodo'];
                                $horario21 = "SELECT `Periodo` FROM `periodos` WHERE `ID_periodo` = " . $seleccionSeccionRamoPDF3['Horario_2'];
                                $horario22 = mysql_query($horario21) or die('Consulta fallida: '.mysql_error());
                                $horario23 = mysql_fetch_assoc($horario22);
                                $horario24 = $horario23['Periodo'];
                                $horario31 = "SELECT `Periodo` FROM `periodos` WHERE `ID_periodo` = " . $seleccionSeccionRamoPDF3['Horario_3'];
                                $horario32 = mysql_query($horario31) or die('Consulta fallida: '.mysql_error());
                                $horario33 = mysql_fetch_assoc($horario32);
                                $horario34 = $horario33['Periodo'];
                                if($horario34 == "Sin Periodo"){
                                    echo "<td width='200px'>".$horario14."<br>".$horario24."</td>";
                                } else {
                                    echo "<td width='200px'>".$horario14."<br>".$horario24."<br>".$horario34."</td>";
                                }
                                echo "</tr>";
                                $cuenta2++;
                            }
                            echo "</table>";
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
