<!-- Aqui e a area do conteudo -->
	<div class="row afastado-1pc vertical-center">
		<div class="col-md-9 col-xs-12 centered">
			<?php $temBonus = ! empty($bonus[0][0]) && ! empty($bonus[1]) && ! empty($bonus[2][0]); ?>
			<?php if ($temBonus): ?>
			<div class="row centered col-md-2 col-md-offset-4">
				<p><span class="glyphicon glyphicon-time relogio"></span>
				<input type="text" id="tempo" name="tempo" disabled=""></p>
			</div>
			<?php
					echo '<h2 class="titulo">Você ganhou um bônus!</h2>';
					echo '<h3> Encontre as palavras antes que o tempo acabe:</h3>';
					echo '<table id="tabela-palavras-bonus" class="table" style="border: none!important;">';
						echo '<tr>';
						for ($i=0; $i < 5; $i++) { 
							echo '<td id="palavraBonus'.$i.'"><h4>'.$bonus[1][$i]->palavra.'</h4></td>';
						}
						echo '</tr>';
					echo '</table>';
					
					echo '<form id="form" role="form" method="post" action="'.base_url('principal/inserirBonus').'">';
					echo '<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">';
						echo '<div class="row">';
						echo '<table id="tabela-bonus" class="centered table table-bordered" style="margin-bottom: 10px!important;">';
							
							
							echo '<input type="hidden" name="codBonus" value="'.$bonus[2][0]->codBonus.'">';
							echo '<input type="hidden" name="nivel" value="'.$nivel.'">';


							$j = 1;						
							for ($i=0; $i <= 224; $i++) {						
								if($i==0){
									echo '<tr>';									
										echo '<td id="td'.$i.'" onclick="clicou('.$i.')">
											<input type="checkbox" id="'.$i.'" name="'.$i.'" class="checkbox-bonus" value="'.$i.'"><button type="button" class="btn-bonus" for="'.$i.'">'.$bonus[0][0]->textoBonus[$i].'</button></td>';
									$j++;
								} elseif($j == 1){
									echo '</tr>';
									echo '<tr>';									
										echo '<td id="td'.$i.'" onclick="clicou('.$i.')">
											<input type="checkbox" id="'.$i.'" name="'.$i.'" class="checkbox-bonus"  value="'.$i.'"><button type="button" class="btn-bonus" for="'.$i.'">'.$bonus[0][0]->textoBonus[$i].'</button></td>';		
									$j++;
								}elseif ($j == 15){																
										echo '<td id="td'.$i.'" onclick="clicou('.$i.')">
											<input type="checkbox" id="'.$i.'" name="'.$i.'" class="checkbox-bonus" value="'.$i.'"><button type="button" class="btn-bonus" for="'.$i.'">'.$bonus[0][0]->textoBonus[$i].'</button></td>';
									$j = 1;
								} else {
									if ($i == 224){
										echo '<td id="td'.$i.'" onclick="clicou('.$i.')">
											<input type="checkbox" id="'.$i.'" name="'.$i.'" class="checkbox-bonus" value="'.$i.'"><button type="button" class="btn-bonus" for="'.$i.'">'.$bonus[0][0]->textoBonus[$i].'</button></td>';
										$j++;
										echo '</tr>';
									}else {
										echo '<td id="td'.$i.'" onclick="clicou('.$i.')">
											<input type="checkbox" id="'.$i.'" name="'.$i.'" class="checkbox-bonus" value="'.$i.'"><button type="button" class="btn-bonus" for="'.$i.'">'.$bonus[0][0]->textoBonus[$i].'</button></td>';
										$j++;
									}								
								}							
							}
						?>
					</table>
				</div>
				<div class="row">
					<div class="col-xs-2 col-md-2"></div>
					<div class="col-xs-8 col-md-4 col-md-offset-2">
						<button class="btn btn-block btn-success centered" id="enviar" type="submit">Responder</button>
					</div>
					<div class="col-xs-2 col-md-2"></div>
				</div>
			</form>
			<?php else: ?>
			<h2 class="titulo">Nenhum b&ocirc;nus liberado ainda</h2>
			<p>Os b&ocirc;nus s&atilde;o desbloqueados por experi&ecirc;ncia. Continue jogando
			palavras, textos e testes para liberar o primeiro.</p>
			<a class="btn btn-success" href="<?php echo base_url('menu'); ?>">Voltar ao menu</a>
			<?php endif; ?>
		</div>	

<?php if ($temBonus): ?>
<script type="text/javascript">
	desaparecer();
	function desaparecer(){
		$(".checkbox-bonus").hide();
	}
	function clicou(id){
		td = "td"+id;		
		if (document.getElementById(td).style.background == "dimgray"){
			document.getElementById(td).style.background = "";
			document.getElementById(id).checked = false;
		} else{
			document.getElementById(td).style.background = "dimgray";
			document.getElementById(id).checked = true;		
		}		
	}

	var timeCrono; 
	var hor = 0;
	var min = 0;
	var seg = 60;
	var startTime = new Date(); 
	var start = startTime.getSeconds();

	iniciarCronometro();

	function iniciarCronometro() {
		if (seg - 1 <= 0) { 
			document.getElementById("enviar").click();
		}

		var time = new Date(); 
		if (time.getSeconds() >= start) {
			seg--;
		} 
		/*else {
			seg = 60 + (time.getSeconds() - start);
		}*/
		timeCrono= (hor < 10) ? "0" + hor : hor;
		timeCrono+= ((min < 10) ? ":0" : ":") + min;
		timeCrono+= ((seg < 10) ? ":0" : ":") + seg;
		document.getElementById("tempo").value = timeCrono;
		setTimeout("iniciarCronometro()",1000);
	} 
</script>
<?php endif; ?>