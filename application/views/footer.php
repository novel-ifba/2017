</div>

<?php if ( ! empty($abrirModalHistoria)): ?>
<!--MODAL DA HISTORIA-->

<div class="modal fade novel-story-screen" id="modalHistoria" role="dialog" data-backdrop="static" data-keyboard="false">		
	    <div class="modal-dialog modal-lg novel-story-dialog">
	      <div class="modal-content novel-story-modal" id="conteudoModalHistoria">
	        <div class="modal-header novel-story-header">
	          <div>
	          	<p class="novel-eyebrow">História</p>
	          	<h4 class="modal-title">Acompanhe a aventura</h4>
	          </div>
	        </div>
	        
	        <div id="corpoModal" class="modal-body novel-story-body">          
							
	        	<div id="carrosselHistoria" class="carousel slide novel-story-carousel"  data-ride="carousel" data-interval="false">
	        		
				    <!-- Wrapper for slides -->
				    <div class="carousel-inner" role="listbox" >	
				    	<?php $avatar = $this->session->userdata('avatar'); ?>
				    	<div class="item active historia">
	        				<img class="historia centered img-responsive novel-story-image" src="<?php echo base_url('assets/img/historia/'.$avatar.'/'.$abrirModalHistoria[0].'-1.png'); ?>">
	        			</div>			    
					    <?php 				    	
					    	
					    	for ($i=2; $i <= $abrirModalHistoria[1]; $i++) { 					    					    
					    		echo '<div class="item historia">';
	        					echo 	'<img class="historia centered img-responsive novel-story-image" src="'.base_url('assets/img/historia/'.$avatar.'/'.$abrirModalHistoria[0].'-'.$i.'.png').'">';	        					
	        					echo '</div>';
					    	}  
					    ?>		    
				    </div>

				    <!-- Left and right controls -->
				    <a class="left carousel-control novel-story-control novel-story-control-prev" onclick="i--" href="#carrosselHistoria" role="button" data-slide="prev" aria-label="Cena anterior">
				      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
				      <span class="sr-only">Previous</span>
				    </a>
				    <a class="right carousel-control novel-story-control novel-story-control-next" onclick="fecharHistoria()" href="#carrosselHistoria" role="button" data-slide="next" aria-label="Próxima cena">
				      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
				      <span class="sr-only">Next</span>
				    </a>
				  </div>

	        </div>
		    <div class="modal-footer novel-story-footer">
		    	<button type="button" id="sairHistoria" data-dismiss="modal" class="btn btn-default">Fechar história</button>		    		
		    </div>
	      </div>
	    </div>
	  </div>
	<input type="hidden" name="qtd" id="qtd" value="<?php echo $abrirModalHistoria[1]; ?>">
<?php endif; ?>

<?php if ( ! empty($conquista)): ?>
<!-- MODAL DA CONQUISTA -->

 <!-- Modal -->
  <div class="modal fade" id="modalConquista" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title titulo">Nova conquista alcançada:</h4>
        </div>
        <div class="modal-body">
         <?php  
         		$avatar = $this->session->userdata('avatar');
         		echo '<div class="row conquista">';
         			echo '<h4 class="titulo">'.$conquista.'/20</h4>';
				echo '<h4 class="titulo">'.(isset($nomeConquista[0]) ? $nomeConquista[0]->nomeConquista : '').'</h4>';
				echo '</div>';				    		    	
	    		echo '<div class="row conquista">';
					echo 	'<img class="historia centered img-responsive" src="'.base_url('assets/img/conquistas/'.$avatar.'/conquista'.$conquista.'.png').'">';	        					
				echo '</div>';	   
	    ?>		
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>



<footer class="novel-footer">
	<div id="footer">
		<h5>Novel - Um Software Educativo para a Aprendizagem Autônoma de Ortografia - 2017</h5>	
	</div>
</footer>
	

<!-- Fechamentos das divs principais. Não apagar!-->
	</div>
</div>
<!-- Fim do conteudo -->

</body>
</html>

<?php	

	if ( ! empty($conquista)){
		echo '<script language="javascript">';			
				echo '$("#modalConquista").modal();';
		echo '</script>';
	}

	if ( ! empty($abrirModalHistoria)){
		echo '<script language="javascript">';			
				echo '$("#modalHistoria").modal();';
		echo '</script>';
	}

?>

<script type="text/javascript">
	i = 0;
	function fecharHistoria(){
		// O campo #qtd so existe quando o modal da historia foi renderizado.
		var qtd = document.getElementById("qtd");
		if (!qtd) { return; }
		i++;
		if(i == qtd.value){
			$("#modalHistoria").modal("hide");
		}		
	}
</script>
