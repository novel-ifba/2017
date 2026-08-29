<div id="conteudo" class="col-md-12 col-xs-12">
	<form id="form" class="form-inline" role="form" method="post" action="<?php echo base_url('texto/inserirRodadaTexto');?>">
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">	
		<div class="row">
			<button type="button" id="botaoSair" class="btn btn-danger" onclick="mostrarSair()">Sair do treinamento</button>
			<div class="col-md-8 col-xs-12 centered">									
				<p><span class="glyphicon glyphicon-time relogio"></span>
				<input type="text" id="tempo" name="tempo" disabled=""></p>
				<input type="hidden" id="duracao" name="duracao" value="0">
				<?php
					if(($grafemas[0]=="total") || ($grafemas[0]=="_total")){
						$grafemas = "g_j&ch_x&s_z_x&c_ç_s_ss_sc_sç_xc&m_n&r_rr&e_i&o_u_l";
					}
					echo '<div class="row">';
						echo '<h1 class="title"> Preencha os campos com ';
							$grafemasUnderline = explode('&', $grafemas);
							$tamanhoUnderline = count($grafemasUnderline);							
							for ($i = 0; $i < $tamanhoUnderline; $i++) {
								$grafemaSeparado = explode("_", $grafemasUnderline[$i]);
								$tamanhoSeparado = count($grafemaSeparado);
								for($j = 0; $j < $tamanhoSeparado; $j++) {
									if (($i == $tamanhoUnderline - 1) && ($j == $tamanhoSeparado - 1) ){
										echo $grafemaSeparado[$j].':';									
									} elseif (($i == $tamanhoUnderline - 1) && ($j == $tamanhoSeparado - 2)) {
										echo $grafemaSeparado[$j].', ou ';
									} else {
										echo $grafemaSeparado[$j].', ';
									}
								}
							}
						echo'</h1>';
					echo '</div>';
					echo '<div class="row">';
						echo '<h2 class="enunciado formatacao">';	
							$enunciado = explode("_", $texto[0]->textoIncompleto);
							$tamanho = count($enunciado);
							for ($i = 0; $i < $tamanho; $i++){
								$tamanhoString = strlen($enunciado[$i]);
								$string = $enunciado[$i];
								
								for ($j = 0; $j < $tamanhoString; $j++){
									if ((strcmp($string[$j],"-") == 0) && (strcmp($string[$j+1]," ") == 0)){
										echo '<br />';
									}							
									echo $string[$j];
								}
								if ($i != $tamanho -1){
									echo '<input type="text" id="inputLetra'.$i.'" required="required" class="input-sm input-texto" name="inputLetra'.$i.'" maxlength="2">';	
								}
							}
						echo '</h2>';
					echo '</div>';
					echo '<input type="hidden" name="grafemas" value="'.$grafemas.'">';								
					echo '<input type="hidden" name="codTexto" value="'.$texto[0]->codTexto.'">';
					echo '<input type="hidden" id="url" value="'.base_url().'">';
				?>	
				<div class="row">
					<div class="col-md-12 col-xs-12">
						<button type="button" class="btn btn-success" onclick="pegarTempoFinal()" name="responder">Responder</button>
					</div>
				</div>
																			
			</div>
		</div>
	</form>


	<script language="JavaScript">

	var timeCrono; 
	var hor = 0;
	var min = 0;
	var seg = 0;
	var segFinal = 0;
	var segInicio = 0;
	var startTime = new Date(); 
	var start = startTime.getSeconds();

	iniciarCronometro();

	function iniciarCronometro() {
		if (seg + 1 > 59) { 
			min+= 1;
		}
		if (min > 59) {
			min = 0;
		hor+= 1;
		}
		var time = new Date(); 
		if (time.getSeconds() >= start) {
			seg = time.getSeconds() - start;
		} 
		else {
			seg = 60 + (time.getSeconds() - start);
		}
		timeCrono= (hor < 10) ? "0" + hor : hor;
		timeCrono+= ((min < 10) ? ":0" : ":") + min;
		timeCrono+= ((seg < 10) ? ":0" : ":") + seg;
		document.getElementById("tempo").value = timeCrono;
		setTimeout("iniciarCronometro()",1000);
	} 

	function pegarTempoFinal() {
		
		var finalTime = new Date(); 

		segInicio = (((startTime.getHours()*60) + startTime.getMinutes())*60) + startTime.getSeconds();
		segFinal = (((finalTime.getHours()*60) + finalTime.getMinutes())*60) + finalTime.getSeconds();

		var duracao = segFinal - segInicio;
		document.getElementById("duracao").value = duracao - 1;		
		document.getElementById("form").submit();
	}	

	function mostrarSair(){
		var confirma  =  confirm("Deseja mesmo sair? Sua pontuação não será computada.");
		if (confirma){
			url = document.getElementById("url").value;
			string = url+"/principal/menu";
			window.location.href = string;
		}
	}
	
</script>