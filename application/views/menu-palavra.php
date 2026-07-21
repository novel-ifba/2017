	<!-- Aqui é a área do conteúdo -->
	<div id="conteudo" class="col-md-12 col-xs-12">
		<div class="row">
				<h3 class="titulo-menu">Nível Palavra</h3>
		</div>		
		<div id="imagens-menu" class="centered" >
	
			<?php
				$tamGrafemasBD = count($grafemasCadastrados);
				$tamGrafemasJogados = count($grafemasJogados);				

				echo '<div class="row afastado-1pc">';

				for($i = 0; $i<$tamGrafemasBD; $i++) {
					if ($i == 4){
						echo '</div>';
						echo '<div class="row afastado-1pc">';
					}

					echo '<div class="col-md-3 col-xs-3">';
						echo '<div class="row">';
							echo '<div class="col-md-12 col-xs-12">';								 
								$url = 'palavra/jogarPalavra/'.$grafemasCadastrados[$i]->tipoGrafema;
								echo '<a href="'.base_url($url).'">';
									$url = 'assets/img/grafemas/palavra/'.$grafemasCadastrados[$i]->tipoGrafema.'.png';
									echo '<img class="img-responsive aa" src="'.base_url($url).'">';		
								echo '</a>';
							echo '</div>';
						echo '</div>';
						echo '<div class="row">';
							echo '<div class="col-md-12 col-xs-12">';
								for ($j=0; $j < $tamGrafemasJogados; $j++) { 
									if (($grafemasJogados[$j]->tipoGrafema == $grafemasCadastrados[$i]->tipoGrafema) && $grafemasJogados[$j]->pontuacao > 20){
										echo '<span class="glyphicon glyphicon-check branco" aria-hidden="true"/>';			
									}								
								}
							echo '</div>';	
						echo '</div>';
					echo '</div>';
				}
				echo '</div>';
			?>
		
		<input type="hidden" id="abrirModalGabarito" value="<?php echo ($abrirModalGabarito); ?>">
		<input type="hidden" id="abrirModalHistoria" value="<?php echo ($abrirModalHistoria[0]); ?>">
		<input type="hidden" id="inseriu" value="<?php echo ($inseriu); ?>">
		<input type="hidden" id="erro" value="<?php echo ($erro); ?>">
		

  <!-- Modal -->
  	<div class="modal fade" id="modalGabarito" role="dialog">
	    <div class="modal-dialog modal-lg">
	      <div class="modal-content">
	        <div class="modal-header" style="background-color: #3498db;">
	          	<button type="submit" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title title" style="font-size: 40px;">Veja seu desempenho!</h4>
	        </div>
	        <input type="hidden" id="inputs" name="inputs" value=""/>
	        <div class="modal-body">          
					<?php
						echo '<div class="table-responsive">';									
							echo '<table class="centered tabela-gabarito table table-striped">';
								echo '<tr>';
									echo '<th class="titulo"> Sua resposta </th>';
									echo '<th class="titulo"> Gabarito </th>';
									echo '<th class="titulo"> Resposta correta</th>';									
									echo '<th class="titulo"> Explicação </th>';
								echo '<tr>';
								$i = 0;
								foreach ($inputJogador as $input => $value) {
									echo '<tr>';
										echo '<td>'.$value.'</td>';	
										echo '<td>'.$gabarito[$i].'</td>';
										echo '<td>'.$palavraCompleta[$i].'</td>';											
										echo '<td>'.$justificativa[$i].'</td>';	
									echo '</tr>';
								$i++;
								}
							echo '</table>';
						echo '</div>';
						echo '<h4 class="centered afastado-1pc" > Sua pontuação foi '.$pontuacao. ' pontos!</h4>';						
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
				echo 'alert("Ops! Moncoso provocou um distúrbio nas palavras... \nTente novamente!");';
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