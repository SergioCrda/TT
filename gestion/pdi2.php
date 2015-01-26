<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
<html >
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Creaci&oacute;n de Programaci&oacute;n Docente Inicial</title>
		<link rel="StyleSheet" type="text/css" href="gestion.css">
		<script src="../general/ayuda.js" type="text/javascript" language="JavaScript"></script>
		<script src="../general/prototype.js" type="text/javascript" language="JavaScript"></script>
        <script type="text/javascript" languaje="JavaScript">
            function desactivar1(id1,id2) {
                var final = document.getElementById(id1.id).value;
                for(var i=1; i<=final; i++){
                    document.getElementById(id2.id).options[i].disabled = true;
                }
                for(var j=parseInt(final)+1; j<=54; j++){
                    document.getElementById(id2.id).options[j].disabled = false;
                }
            }
            function desactivar2(id1,id2) {
                var final = document.getElementById(id1.id).value;
                for(var i=1; i<=parseInt(final)+1; i++){
                    document.getElementById(id2.id).options[i+1].disabled = true;
                }
                for(var j=parseInt(final)+2; j<=54; j++){
                    document.getElementById(id2.id).options[j].disabled = false;
                }
            }
        </script>
 	</head>
	<body>
		<div id="fecha">
			<?php echo date("d/m/Y"). "<br/>" . date("H:i");?>
		</div>
		<h3>Programaci&oacute;n Docente Inicial<br><small>Selecci&oacute;n de horario por secci&oacute;n</small></h3>
		<?php
			$departamento = $_POST['depar'];
			$carrera = $_POST['carre'];
			$cod_ramo = $_POST['codigoRamo'];
			$ramo = $_POST['ramo'];
			$seccion = $_POST['seccion'];
			$data = array($cod_ramo, $ramo, $seccion);
			function array_envia($array) { 
				$tmp = serialize($array); 
				$tmp = urlencode($tmp); 
				return $tmp; 
			}
			$data = array_envia($data);
		?>
		<br/>
		<h4>Seleccione Horario de las siguientes asignaturas</h4>
		<form name="PDI" method="post" action="pdi1.php">
		    <?php
				echo "<input type='hidden' id='departa' name='depar' value='$departamento'/>\n\t\t\t";
				echo "<input type='hidden' id='carrera' name='carre' value='$carrera'/>\n";
			?>
			<table align="center" width="80%" border="1" cellpadding="3" cellspacing="0" class="pequena">			
				<tr class="titulo_fila">
					<td>Ramo</td>
					<td>Seccion(es)</td>
					<td>Horario seccion(es)</td>
				</tr>
				<?php
					for($j = 0; $j < count($ramo); $j++){
						echo "<tr class=centro>";
						echo "<td>";
						echo "[".$cod_ramo[$j]."]<br>".$ramo[$j];
                        echo "<input type='hidden' id='codigosRamo' name='codigoRamo[]' value='$cod_ramo[$j]'/>\n";
                        echo "<input type='hidden' id='ramos' name='ramo[]' value='$ramo[$j]'/>\n";
						echo "</td>";
						echo "<td>";
						echo $seccion[$j]."<input type='hidden' id='secciones' name='seccion[]' value='$seccion[$j]'/>\n";
						echo "</td>";
						echo "<td>";
						for($i = 0; $i < $seccion[$j]; $i++){
							echo "Secci&oacute;n ".($i+1).": ";
							$link = mysql_connect('localhost', 'dbttii', 'dbttii') or die('No se pudo conectar: ' . mysql_error());
							mysql_select_db('ttii') or die('No se pudo seleccionar la base de datos');	
							$hora1 = "SELECT `periodo` FROM  `periodos` ";
							$ID_hora1 = "SELECT  `id_periodo` FROM  `periodos`";
							$hora2 = mysql_query($hora1) or die('Consulta fallida: ' . mysql_error());
							$ID_hora2 = mysql_query($ID_hora1) or die('Consulta fallida: ' . mysql_error());
							echo "<select name='horario_dia_id".$j."[$i][0]' id='horario".$j.$i."0' onchange='desactivar1(horario".$j.$i."0,horario".$j.$i."1)'>\n";
							echo "<option value='-1'>Seleccione el horario</option>\n";
							while (($hora3 = mysql_fetch_array($hora2, MYSQL_ASSOC)) and ($ID_hora3 = mysql_fetch_array($ID_hora2, MYSQL_ASSOC))) {
								foreach (array_combine($hora3,$ID_hora3) as $hora4=>$ID_hora4) {
                                    if($ID_hora4 != 0){
									   echo "<option value='$ID_hora4'>$hora4</option>\n";
                                    }
								}
							}
							echo "</select>\n";

							$link = mysql_connect('localhost', 'dbttii', 'dbttii') or die('No se pudo conectar: ' . mysql_error());
							mysql_select_db('ttii') or die('No se pudo seleccionar la base de datos');	
							$hora1 = "SELECT `periodo` FROM  `periodos` ";
							$ID_hora1 = "SELECT  `id_periodo` FROM  `periodos`";
							$hora2 = mysql_query($hora1) or die('Consulta fallida: ' . mysql_error());
							$ID_hora2 = mysql_query($ID_hora1) or die('Consulta fallida: ' . mysql_error());
							echo "<select name='horario_dia_id".$j."[$i][1]' id='horario".$j.$i."1' onchange='desactivar2(horario".$j.$i."1,horario".$j.$i."2)'>\n";
							echo "<option value='-1'>Seleccione el horario</option>\n";
							while (($hora3 = mysql_fetch_array($hora2, MYSQL_ASSOC)) and ($ID_hora3 = mysql_fetch_array($ID_hora2, MYSQL_ASSOC))) {
								foreach (array_combine($hora3,$ID_hora3) as $hora4=>$ID_hora4) {
                                    if($ID_hora4 != 0){
									   echo "<option value='$ID_hora4'>$hora4</option>\n";
                                    }
								}
							}
							echo "</select>\n";

							$link = mysql_connect('localhost', 'dbttii', 'dbttii') or die('No se pudo conectar: ' . mysql_error());
							mysql_select_db('ttii') or die('No se pudo seleccionar la base de datos');	
							$hora1 = "SELECT `periodo` FROM  `periodos` ";
							$ID_hora1 = "SELECT  `id_periodo` FROM  `periodos`";
							$hora2 = mysql_query($hora1) or die('Consulta periodos: ' . mysql_error());
							$ID_hora2 = mysql_query($ID_hora1) or die('Consulta fallida: ' . mysql_error());
							echo "<select name='horario_dia_id".$j."[$i][2]' id='horario".$j.$i."2'>\n";
							echo "<option value='-1'>Seleccione el horario (opcional)</option>\n";
							while (($hora3 = mysql_fetch_array($hora2, MYSQL_ASSOC)) and ($ID_hora3 = mysql_fetch_array($ID_hora2, MYSQL_ASSOC))) {
								foreach (array_combine($hora3,$ID_hora3) as $hora4=>$ID_hora4) {
									   echo "<option value='$ID_hora4'>$hora4</option>\n";
								}
							}
							echo "</select><br>\n";
						}
						echo "</td>";
						echo "</tr>";
					}
				?>
			</table>
			<center>
			    <br/>
			    <input type="submit" name="solicitar" formmethod="post" formaction="pdi3.php" value="Solicitar" />
			</center>
		</form>
	</body>
</html>