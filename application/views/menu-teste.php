	<!-- Aqui é a área do conteúdo -->
	<div id="conteudo" class="col-md-12 col-xs-12">
		<div class="row">
				<h3 class="titulo-menu">Nível Teste</h3>
		</div>
		<div id="imagens-menu" class="centered" >		
				
				<?php
				$tamGrafemasBD = count($grafemasCadastrados);
				$tamGrafemasJogados = count($grafemasJogados);
				
				if(in_array("g_j&ch_x&s_z_x&c_ç_s_ss_sc_sç_xc&m_n&r_rr&e_i&o_u_l", $grafemasJogados)){
					$cod = array_search("g_j&ch_x&s_z_x&c_ç_s_ss_sc_sç_xc&m_n&r_rr&e_i&o_u_l", $grafemasJogados);
					$grafemasJogados[$cod] = "total";
				}

				if(in_array("g_j&ch_x&s_z_x&c_ç_s_ss_sc_sç_xc&m_n&r_rr&e_i&o_u_l", $grafemasJogados)){
					$cod = array_search("g_j&ch_x&s_z_x&c_ç_s_ss_sc_sç_xc&m_n&r_rr&e_i&o_u_l", $grafemasJogados);
					$grafemasJogados[$cod] = "-total";
				}

				echo '<div class="row afastado-1pc">';

				for($i = 0; $i<$tamGrafemasBD; $i++) {
					if (($i%4)==0){
						echo '</div>';
						echo '<div class="row afastado-1pc">';
					}					
					echo '<div class="col-md-3 col-xs-3">';
						echo '<div class="row">';
							echo '<div class="col-md-12 col-xs-12">';
								$url = 'teste/jogarTeste/'.$grafemasCadastrados[$i];
								echo '<a href="'.base_url($url).'">';
									$url = 'assets/img/grafemas/teste/'.$grafemasCadastrados[$i].'.png';
									echo '<img  class="img img-responsive" src="'.base_url($url).'">';		
								echo '</a>';
							echo '</div>';
						echo '</div>';
						echo '<div class="row">';
							for ($j=0; $j < $tamGrafemasJogados; $j++) { 

								if ($grafemasJogados[$j] == $grafemasCadastrados[$i]){
									echo '<span class="glyphicon glyphicon-check" aria-hidden="true"/>';
								}								
							}
							
						echo '</div>';
					echo '</div>';
				}
				echo '</div>';
			?>

		<input type="hidden" id="abrirModalGabarito" value="<?php echo ($abrirModalGabarito); ?>">		
		<input type="hidden" id="abrirModalHistoria" value="<?php echo ($abrirModalHistoria[0]); ?>">		

<!-- Modal -->
  	<div class="modal fade" id="modalGabarito" role="dialog">
	    <div class="modal-dialog modal-lg">
	      <div class="modal-content">
	        <div class="modal-header">
	          	<button type="submit" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Veja seu desempenho!</h4>
	        </div>
	        <input type="hidden" id="inputs" name="inputs" value=""/>
	        <div class="modal-body">          
					<?php
						echo '<div class="table-responsive col-md-12 col-xs-12">';								
							echo '<table class="centered tabela-gabarito table-bordered table-striped">';
								echo '<tr>';
									echo '<th class="titulo"> Sua resposta </th>';
									echo '<th class="titulo"> Gabarito </th>';
								echo '<tr>';
								$i = 0;
								foreach ($inputJogador as $input => $value) {
									echo '<tr>';
										echo '<td>'.$value.'</td>';	
										echo '<td>'.$gabarito[$i].'</td>';
									echo '</tr>';
								$i++;
								}
							echo '</table>';
						echo '</div>';
						echo '<p class="centered" > Sua pontuação foi '.$pontuacao. ' pontos!</p>';						
					?>			
	        </div>
		    <div class="row centered">
		    	<div class="modal-footer">
		    			<button type="button" id="sairGabarito" data-dismiss="modal" class="btn btn-default">Fechar</button>		    		
		        </div>
		    </div>
	      </div>
	    </div>
	  </div>
	</div>

<?php
	if ($erro){
		echo '<script language="javascript">';
			echo 'function mensagemErro(){';
				echo 'alert("Você ainda não está apto! \nPara jogar este teste você precisa ter jogado os grafemas mostrados nos ícones de cada fase.");';
			echo '}';
			echo 'onload=mensagemErro();';
		echo '</script>';
	}
?>

<script type="text/javascript">

var abrir = document.getElementById("abrirModalGabarito").value;
	if(abrir){
			mostrarGabarito();	
	} 

	function mostrarGabarito(){		
		$("#modalGabarito").modal();		
	}

</script>