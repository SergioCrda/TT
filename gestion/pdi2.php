<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
<html >
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Creaci&oacute;n de Programaci&oacute;n Docente Inicial</title>
		<link rel="StyleSheet" type="text/css" href="gestion.css">
		<script src="../general/ayuda.js" type="text/javascript" language="JavaScript"></script>
		<script src="../general/prototype.js" type="text/javascript" language="JavaScript"></script>
        <script type="text/javascript" languaje="JavaScript">
            function desactivar1(id1,id2,id3) {
                var final = document.getElementById(id1.id).value;
                document.getElementById(id2.id).disabled = false;
                document.getElementById(id3.id).disabled = true;
                document.getElementById(id2.id).options[0].selected = true;
                document.getElementById(id3.id).options[0].selected = true;
                for(var i=1; i<=final; i++){
                    document.getElementById(id2.id).options[i].disabled = true;
                }
                for(var j=parseInt(final)+1; j<=54; j++){
                    document.getElementById(id2.id).options[j].disabled = false;
                }
            }
            function desactivar2(id1,id2) {
                var final = document.getElementById(id1.id).value;
                document.getElementById(id2.id).disabled = false;
                document.getElementById(id2.id).options[0].selected = true;
                for(var i=1; i<=parseInt(final)+1; i++){
                    document.getElementById(id2.id).options[i+1].disabled = true;
                }
                for(var j=parseInt(final)+2; j<=54; j++){
                    document.getElementById(id2.id).options[j].disabled = false;
                }
            }
            function validarForm(formulario) {
                var horario = formulario.getElementsByClassName("hora");
                for(var i = 0; i < horario.length; i++) {
                    if(horario[i].value == -1) {
                        alert("Ingrese todos los Periodos correspondientes");
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
		<form name="PDI" method="post" action="pdi1.php" onsubmit="return validarForm(this)">
		    <?php
				echo "<input type='hidden' id='departa' name='depar' value='".$departamento."'/>";
				echo "<input type='hidden' id='carrera' name='carre' value='".$carrera."'/>";
                echo "<input type='hidden' id='estaRepetido' name='estaRepetido' value='".$_POST['estaRepetido']."'/>";
				echo "<input type='hidden' id='PDIRepetido' name='PDIRepetido' value='".$_POST['PDIRepetido']."'/>";
			?>
			<table align="center" width="80%" border="1" cellpadding="3" cellspacing="0" class="pequena">			
				<tr class="titulo_fila">
					<td>Ramo</td>
					<td>Seccion(es)</td>
					<td>Horario seccion(es)</td>
				</tr>
				<?php
                    $link = mysqli_connect('localhost', 'dbttii', 'dbttii', "ttii");
                    if (mysqli_connect_errno()) echo "Falla al conectar con MySQL: " . mysqli_connect_error();
					for($j = 0; $j < count($ramo); $j++){
						echo "<tr class=centro>";
						echo "<td>";
						echo "[".$cod_ramo[$j]."]<br>".$ramo[$j];
                        echo "<input type='hidden' id='codigosRamo' name='codigoRamo[]' value='".$cod_ramo[$j]."'/>\n";
                        echo "<input type='hidden' id='ramos' name='ramo[]' value='".$ramo[$j]."'/>\n";
						echo "</td>";
						echo "<td>";
						echo $seccion[$j]."<input type='hidden' id='secciones' name='seccion[]' value='".$seccion[$j]."'/>\n";
						echo "</td>";
						echo "<td>";
						for($i = 0; $i < $seccion[$j]; $i++){
							echo "Secci&oacute;n ".($i+1).": ";

							$hora11 = "SELECT * FROM  `periodos` ";
							$hora12 = mysqli_query($link, $hora11) or die('Consulta fallida $hora12: ' . mysqli_error($link));

							echo "<select name='horario_dia_id".$j."[".$i."][0]' id='horario".$j.$i."0' class='hora' onchange='desactivar1(horario".$j.$i."0,horario".$j.$i."1,horario".$j.$i."2)' required>";
							echo "<option value='-1'>Seleccione el horario</option>";
							while ($hora13 = mysqli_fetch_array($hora12)) {
                                if($hora13['ID_periodo'] != 0){
                                   echo "<option value='".$hora13['ID_periodo']."'>".$hora13['Periodo']."</option>";
                                }
							}
							echo "</select>";

							$hora21 = "SELECT * FROM  `periodos` ";
							$hora22 = mysqli_query($link, $hora21) or die('Consulta fallida $hora22: ' . mysqli_error($link));

							echo "<select name='horario_dia_id".$j."[".$i."][1]' id='horario".$j.$i."1' class='hora' onchange='desactivar2(horario".$j.$i."1,horario".$j.$i."2)' disabled required>";
							echo "<option value='-1'>Seleccione el horario</option>";
							while ($hora23 = mysqli_fetch_array($hora22)) {
                                if($hora23['ID_periodo'] != 0){
                                   echo "<option value='".$hora23['ID_periodo']."'>".$hora23['Periodo']."</option>";
                                }
							}
							echo "</select>";

							$hora31 = "SELECT * FROM  `periodos` ";
							$hora32 = mysqli_query($link, $hora31) or die('Consulta fallida $hora32: ' . mysqli_error($link));

							echo "<select name='horario_dia_id".$j."[".$i."][2]' id='horario".$j.$i."2' disabled required>";
							echo "<option value='-1'>Seleccione el horario (opcional)</option>";
							while ($hora33 = mysqli_fetch_array($hora32)) {
                                if($hora33['ID_periodo'] != 0){
                                   echo "<option value='".$hora33['ID_periodo']."'>".$hora33['Periodo']."</option>";
                                }
							}
							echo "</select><br>";
						}
						echo "</td>";
						echo "</tr>";
					}
                    mysqli_close($link);
				?>
			</table>
			<center>
			    <br/>
			    <input type="submit" name="solicitar" formmethod="post" formaction="pdi3.php" value="Solicitar" />
			</center>
		</form>
	</body>
</html>
