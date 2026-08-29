	<!-- Aqui é a área do conteúdo -->
	<div id="conteudo" class="col-md-12 col-xs-12">
		<div class="row">
				<h3 class="titulo-menu">Nível Texto</h3>
		</div>
		<div id="imagens-menu" class="centered" >
			<?php					

					if(in_array("g_j&ch_x&s_z_x&c_ç_s_ss_sc_sç_xc&m_n&r_rr&e_i&o_u_l", $grafemasJogados)){
					$cod = array_search("g_j&ch_x&s_z_x&c_ç_s_ss_sc_sç_xc&m_n&r_rr&e_i&o_u_l", $grafemasJogados);
					$grafemasJogados[$cod] = "total";
				}

				if(in_array("g_j&ch_x&s_z_x&c_ç_s_ss_sc_sç_xc&m_n&r_rr&e_i&o_u_l", $grafemasJogados)){
					$cod = array_search("g_j&ch_x&s_z_x&c_ç_s_ss_sc_sç_xc&m_n&r_rr&e_i&o_u_l", $grafemasJogados);
					$grafemasJogados[$cod] = "_total";
				}
					
					$tamGrafemasBD = count($grafemasTextos);
					$grafemas = array();									
					for ($i=1; $i < $tamGrafemasBD; $i++) { 
						if(!in_array($grafemasTextos[$i], $grafemas)){							
							$grafemas[] = $grafemasTextos[$i];
						}																
					}										
					echo '<div class="row afastado-1pc">';
						array_pop($grafemas);
						array_push($grafemas, "_total", "total");
						$tamGrafemasUnicos = count($grafemas);
						for($i = 0; $i<$tamGrafemasUnicos; $i++) {
							if ($i==4){
								echo '</div>';
								echo '<div class="row afastado-1pc">';
							}

							echo '<div class="col-md-3 col-xs-3">';
								echo '<div class="row centered">';
									echo '<div class="col-md-12 col-xs-12">';
										$url = 'texto/jogarTexto/'.$grafemas[$i];
										echo '<a href="'.base_url($url).'">';
											$url = 'assets/img/grafemas/texto/'.$grafemas[$i].'.png';
											echo '<img class="img-texto img-responsive centered aa" src="'.base_url($url).'">';
										echo '</a>';
									echo '</div>';
								echo '</div>';												
							echo '<div class="row">';
								$tamGrafemasJogados = count($grafemasJogados);
								for ($j=0; $j < $tamGrafemasJogados; $j++) { 
									if (($grafemasJogados[$j] == $grafemas[$i])){
										echo '<span class="glyphicon glyphicon-check" aria-hidden="true"/>';
									}								
								}							
							echo '</div>';		
						echo '</div>';
						}
					echo '</div>';									
			?>
		</div>

		<input type="hidden" id="abrirModalGabarito" value="<?php echo ($abrirModalGabarito); ?>">		
		<input type="hidden" id="erro" value="<?php echo ($erro); ?>">			
		<input type="hidden" id="abrirModalHistoria" value="<?php echo (isset($abrirModalHistoria[0]) ? $abrirModalHistoria[0] : ''); ?>">

<!-- Modal -->
  	<div class="modal fade" id="modalGabarito" role="dialog">
	    <div class="modal-dialog modal-lg">
	      <div class="modal-content">
	        <div class="modal-header">
	          	<button type="submit" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Veja seu desempenho!</h4>
	        </div>	        
	        <div class="modal-body">          
					<?php		
						if ( ! empty($abrirModalGabarito)) {
						echo '<div class="table-responsive">';															
							echo '<table class="centered tabela-gabarito table-bordered table-striped">';
								echo '<tr>';
									echo '<th class="titulo"> Sua resposta </th>';
									echo '<th class="titulo"> Gabarito </th>';
								echo '<tr>';
								$i = 0;
								foreach ($inputJogador as $input => $value) {
									echo '<tr>';
										echo '<td>'.$value.'</td>';	
										echo '<td>'.$gabarito[$i]->letraGabarito.'</td>';	
									echo '</tr>';
								$i++;
								}
							echo '</table>';
						echo '</div>';
						echo '<p class="centered" > Sua pontuação foi '.$pontuacao. ' pontos!</p>';						
						}
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


<!--Mostrar mensagem de erro se o o jogador ainda não estiver apto a jogar um texto-->
<?php	
	if ($erro){							
		echo '<script language="javascript">';
		echo  'alert("Você ainda não está apto! \nPara jogar este texto você precisa ter jogado os grafemas mostrados nos ícones de cada fase.")';	
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