<!-- Aqui é a área do conteúdo -->
<?php $temEstatisticas = $dadosPalavras || $dadosTextos || $dadosTestes; ?>
<div id="conteudo" class="novel-page">
	<div class="novel-stats-layout <?php echo $temEstatisticas ? '' : 'novel-stats-layout-empty'; ?>">
		<div class="coluna-tabelas">			
			<?php
				
				if($dadosPalavras){
					echo '<section>';
						echo '<h2 class="centered afastado-1pc">Estatísticas do nível Palavra</h2>';						
						echo '<div class="novel-chart-grid">';
							echo '<div class="novel-chart-card chart" id="graficoPalavras"></div>';
							echo '<div class="novel-chart-card chart" id="graficoPontuacaoPalavras"></div>';
						echo '</div>';						
						echo '<div class="dadosPalavras">';						
							$tamanhoPalavras = count($dadosPalavras);							
							echo '<input type="hidden" id="tamanhoPalavras" value="'.$tamanhoPalavras.'"/>';
							for ($i = 0; $i < $tamanhoPalavras; $i++){
								echo '<input type="hidden" id="grafemaPalavras'.$i.'" value="'.$dadosPalavras[$i]->tipoGrafema.'"/>';
								echo '<input type="hidden" id="pontuacaoPalavras'.$i.'" value="'.$dadosPalavras[$i]->pontuacao.'"/>';
								echo '<input type="hidden" id="duracaoPalavras'.$i.'" value="'.$dadosPalavras[$i]->duracao.'"/>';
							}
						echo '</div>';
					echo '</section>';
				}
				
				if($dadosTextos){
					echo '<section>';
						echo '<h2 class="centered afastado-1pc">Estatísticas do nível Texto</h2>';						
						echo '<div class="novel-chart-grid">';
							echo '<div class="novel-chart-card chart" id="graficoTextos"></div>';
							echo '<div class="novel-chart-card chart" id="graficoPontuacaoTextos"></div>';
						echo '</div>';										    
						echo '<div class="dadosTextos">';						
							$tamanhoTextos = count($dadosTextos);							
							echo '<input type="hidden" id="tamanhoTextos" value="'.$tamanhoTextos.'"/>';
							for ($i = 0; $i < $tamanhoTextos; $i++){
								echo '<input type="hidden" id="grafemaTextos'.$i.'" value="'.$dadosTextos[$i]->tipoGrafema.'"/>';
								echo '<input type="hidden" id="pontuacaoTextos'.$i.'" value="'.$dadosTextos[$i]->pontuacao.'"/>';
								echo '<input type="hidden" id="duracaoTextos'.$i.'" value="'.$dadosTextos[$i]->duracao.'"/>';
							}
						echo '</div>';
					echo '</section>';
				}

				if($dadosTestes){
					echo '<section>';
						echo '<h2 class="centered afastado-1pc">Estatísticas do nível Testes</h2>';						
						echo '<div class="novel-chart-grid">';
							echo '<div class="novel-chart-card chart" id="graficoTestes"></div>';
							echo '<div class="novel-chart-card chart" id="graficoPontuacaoTestes"></div>';
						echo '</div>';						
						echo '<div class="dadosTestes">';						
							$tamanhoTestes = count($dadosTestes);							
							echo '<input type="hidden" id="tamanhoTestes" value="'.$tamanhoTestes.'"/>';
							for ($i = 0; $i < $tamanhoTestes; $i++){
								echo '<input type="hidden" id="grafemaTestes'.$i.'" value="'.$dadosTestes[$i]->tipoGrafema.'"/>';
								echo '<input type="hidden" id="pontuacaoTestes'.$i.'" value="'.$dadosTestes[$i]->pontuacao.'"/>';
								echo '<input type="hidden" id="duracaoTestes'.$i.'" value="'.$dadosTestes[$i]->duracao.'"/>';
							}
						echo '</div>';
					echo '</section>';
				}
				


			?>
		</div>
		
		<aside class="coluna-conquistas imagem novel-player-panel">
			<?php
			$horas = 0;
			$segundos = $tempoTotal[0]->tempoTotal%60;
			$minutos = round($tempoTotal[0]->tempoTotal/60);
			if($minutos > 60){
				$horas = floor($minutos/60);
			}
			if($minutos < 10){
				$minutos = '0'.$minutos;
			}
			if($segundos < 10){
				$segundos = '0'.$segundos;
			}
			if($horas < 10){
				$horas = '0'.$horas;
			}

			echo '<div class="row">';
				echo '<h2 class="centered afastado-1pc">Dados do jogador</h2>';								
			echo '</div>';
			
			echo '<div class="row">';
				echo '<h5>Tempo de jogo: '.$horas.':'.$minutos.':'.$segundos.'</h5>';
			echo '</div>';

			echo '<div class="row">';						
				echo '<h5>Experiência: '.$experiencia.' pontos</h5>';
			echo '</div>';

			echo '<div class="row">';						
					echo '<h3 class="centered afastado-1pc conquistas">Conquistas do jogador</h3>';
				echo '</div>';
			$i = 0;
			foreach ($conquistas as $key) {				
				echo '<div x-data="{ aberta: false }">';
					echo '<h5 class="btn" @click="aberta = !aberta">'.$key->nomeConquista.'</h5>';
					echo '<img class="img-responsive" x-show="aberta" src="'.base_url('assets/img/conquistas/'.$avatar.'/conquista'.$key->codConquista).'.png" @click="aberta = !aberta">';
				echo '</div>';
				$i++;
			}
			?>
		</aside>
	</div>

	
<script type="text/javascript">

 	//Para desenhar os gráficos, utiliza-se aqui
 	//a ferramenta Google Charts

     // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(desenharTabelaPalavras); 
    google.charts.setOnLoadCallback(desenharTabelaPontuacaoPalavras);   	
	google.charts.setOnLoadCallback(desenharTabelaTextos);
	google.charts.setOnLoadCallback(desenharTabelaPontuacaoTextos);
	google.charts.setOnLoadCallback(desenharTabelaTestes);              
    google.charts.setOnLoadCallback(desenharTabelaPontuacaoTestes);     

	function opcoesGrafico(titulo) {
		return {
			title: titulo,
			width: '100%',
			height: 400,
			chartArea: { width: '78%', height: '72%' },
			colors: ['#f1c40f', '#e74c3c', '#2ecc71', '#9b59b6', '#34495e', '#95a5a6', '#ecf0f1'],
			backgroundColor: 'transparent'
		};
	}

	var redesenharGraficos;
	window.addEventListener('resize', function() {
		clearTimeout(redesenharGraficos);
		redesenharGraficos = setTimeout(function() {
			desenharTabelaPalavras();
			desenharTabelaPontuacaoPalavras();
			desenharTabelaTextos();
			desenharTabelaPontuacaoTextos();
			desenharTabelaTestes();
			desenharTabelaPontuacaoTestes();
		}, 200);
	});
      
          

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
	function desenharTabelaPalavras() {
		if (!document.getElementById('tamanhoPalavras')) { return; }

		// Create the data table.
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Grafema');
		data.addColumn('number', 'Duração');
		data.addColumn('number', 'Pontuação');
		var tamanho = document.getElementById('tamanhoPalavras').value;        
		for (var i = 0; i < tamanho; i++) {
			var string = "grafemaPalavras"+i;
		  	var grafema = document.getElementById(string).value;	    	      		      	
		  	string = "pontuacaoPalavras"+i;
		  	var pontuacao = parseInt(document.getElementById(string).value);
		  	string = "duracaoPalavras"+i;	      	
		  	var duracao = parseInt(document.getElementById(string).value);		      	
		  	data.addRow([''+grafema+'', duracao, pontuacao]);         

		}

		// Set chart options
		var options = opcoesGrafico('Pontuação e duração por grafema jogado');

		// Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.BarChart(document.getElementById('graficoPalavras'));
		chart.draw(data, options);                

	}

	function desenharTabelaPontuacaoPalavras() {
		if (!document.getElementById('tamanhoPalavras')) { return; }

		// Create the data table.
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Grafema');
		data.addColumn('number', 'Pontuação');
		var tamanho = document.getElementById('tamanhoPalavras').value;        
		for (var i = 0; i < tamanho; i++) {
			var string = "grafemaPalavras"+i;
		  	var grafema = document.getElementById(string).value;	    	      		      	
		  	string = "pontuacaoPalavras"+i;
		  	var pontuacao = parseInt(document.getElementById(string).value);
		  	data.addRow([''+grafema+'', pontuacao]);         
		}

		// Set chart options
		var options = opcoesGrafico('Pontuação por grafema jogado');

		// Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.PieChart(document.getElementById('graficoPontuacaoPalavras'));
		chart.draw(data, options);                

	}
	
	//GRÁFICOS DOS TEXTOS
      
    function desenharTabelaTextos() {
		if (!document.getElementById('tamanhoTextos')) { return; }

		// Create the data table.
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Grafema');
		data.addColumn('number', 'Duração');
		data.addColumn('number', 'Pontuação');		
		var tamanho = document.getElementById('tamanhoTextos').value;        		
		for (var i = 0; i < tamanho; i++) {
			var string = "grafemaTextos"+i;
		  	var grafema = document.getElementById(string).value;	    	      		      	
		  	string = "pontuacaoTextos"+i;
		  	var pontuacao = parseInt(document.getElementById(string).value);
		  	string = "duracaoTextos"+i;	      	
		  	var duracao = parseInt(document.getElementById(string).value);		      	
		  	data.addRow([''+grafema+'', duracao, pontuacao]);         		  	
		}

		// Set chart options
		var options = opcoesGrafico('Pontuação e duração por grafema jogado');

		// Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.BarChart(document.getElementById('graficoTextos'));
		chart.draw(data, options);                

	}

	function desenharTabelaPontuacaoTextos() {
		if (!document.getElementById('tamanhoTextos')) { return; }

		// Create the data table.
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Grafema');
		data.addColumn('number', 'Pontuação');
		var tamanho = document.getElementById('tamanhoTextos').value;        
		for (var i = 0; i < tamanho; i++) {
			var string = "grafemaTextos"+i;
		  	var grafema = document.getElementById(string).value;	    	      		      	
		  	string = "pontuacaoTextos"+i;     
		  	var pontuacao = parseInt(document.getElementById(string).value); 	
		  	data.addRow([''+grafema+'', pontuacao]);         
		}

		// Set chart options
		var options = opcoesGrafico('Pontuação por grafema jogado');

		// Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.PieChart(document.getElementById('graficoPontuacaoTextos'));
		chart.draw(data, options);                

	}

	//GRÁFICOS DOS TESTES

	function desenharTabelaTestes() {
		if (!document.getElementById('tamanhoTestes')) { return; }

		// Create the data table.
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Grafema');
		data.addColumn('number', 'Duração');
		data.addColumn('number', 'Pontuação');
		var tamanho = document.getElementById('tamanhoTestes').value;        
		for (var i = 0; i < tamanho; i++) {
			var string = "grafemaTestes"+i;
		  	var grafema = document.getElementById(string).value;	    	      		      	
		  	string = "pontuacaoTestes"+i;
		  	var pontuacao = parseInt(document.getElementById(string).value);
		  	string = "duracaoTestes"+i;	      	
		  	var duracao = parseInt(document.getElementById(string).value);		      	
		  	data.addRow([''+grafema+'', duracao, pontuacao]);         

		}

		// Set chart options
		var options = opcoesGrafico('Pontuação e duração por grafema jogado');

		// Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.BarChart(document.getElementById('graficoTestes'));
		chart.draw(data, options);                

	}

	function desenharTabelaPontuacaoTestes() {
		if (!document.getElementById('tamanhoTestes')) { return; }

		// Create the data table.
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Grafema');
		data.addColumn('number', 'Pontuação');
		var tamanho = document.getElementById('tamanhoTestes').value;        
		for (var i = 0; i < tamanho; i++) {
			var string = "grafemaTestes"+i;
		  	var grafema = document.getElementById(string).value;	    	      		      	
		  	string = "pontuacaoTestes"+i;
		  	var pontuacao = parseInt(document.getElementById(string).value);	      	
		  	data.addRow([''+grafema+'', pontuacao]);         

		}

		// Set chart options
		var options = opcoesGrafico('Pontuação por grafema jogado');

		// Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.PieChart(document.getElementById('graficoPontuacaoTestes'));
		chart.draw(data, options);                

	}

</script>
