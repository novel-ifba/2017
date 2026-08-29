<!-- Aqui e a area do conteudo -->
			<div class="row afastado-1pc vertical-center">
				<div id="conteudo" class="col-md-6 col-xs-12 centered" >
					<div class="row">
						<div id="painel-dados-usuario" class="col-md-12 col-xs-12">
							<div class="row">
								<h1 class="titulo-menu centered">Seus dados</h1>
							</div>

							<div class="row">
								<div class="form-group centered">
									<div class="col-md-12 col-xs-12 habilitar-edicao">
										<input class="centered" type="radio" name="edita" onclick="permitirEdicao()" />Habilitar Edição	
										</div>								    
								</div>
							</div>						
							
							<form class="form-vertical" id="form" role="form" method="post" action="<?php echo base_url('minha-conta/salvar');?>">
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
							  	<div class="row">
							  		<div class="form-group centered">
							  			<div class="col-md-4 col-xs-4">
							  				<label for="nome">Nome:</label>
							  			</div>
								    	<div class="col-md-8 col-xs-8">
								    		<input type="text" disabled class="form-control" required="" size="40" id="nome" name="nome" value="<?php echo $jogador[0]->nome ?>">
								    	</div>								    
							  		</div>
							  	</div>
							  	<div class="row">
							  		<div class="form-group centered">
							  			<div class="col-md-4 col-xs-4">
							  				<label for="email">E-mail:</label>
							  			</div>
								    	<div class="col-md-8 col-xs-8">
								    		<input type="email" disabled class="form-control" required="" size="40" id="email" name="email" value="<?php echo $jogador[0]->email ?>">
								    	</div>								    
							  		</div>
							  	</div>
							  	<div class="row">
								  	<div class="form-group centered">
								  		<div class="col-md-4 col-xs-4">
									    	<label for="login">Login:</label>
									    </div>
									    <div class="col-md-8 col-xs-8">
								   			<input type="text" disabled class="form-control" required="" size="40" id="login" name="login" value="<?php echo $jogador[0]->login ?>">
								   		</div>
								  	</div>
							  	</div>
							  	<div class="row" id="blocoSenha1">
								  	<div class="form-group centered">
								  		<div class="col-md-4 col-xs-4">
								    		<label for="senha1">Senha:</label>
								    	</div>
								   		<div class="col-md-8 col-xs-8">
								   			<input type="password" class="form-control" required=""  size="40" id="senha1" name="senha1">
								   		</div>
								  	</div>
							  	</div>
							  	<div class="row" id="blocoSenha2">
								  	<div class="form-group centered">
								  		<div class="col-md-4 col-xs-4">
								    		<label for="senha2">Repita a senha:</label>
								    	</div>
								   		<div class="col-md-8 col-xs-8">
								   			<input type="password" class="form-control" required="" size="40" id="senha2" name="senha2">							   			
								   		</div>
								  	</div>
							  	</div>							  	
								<div class="row centered afastado-1pc">
									<button type="button" onclick="verificarSenhas()" id="botaoEditar" class="btn btn-lg btn-success col-lg-7 pull-right botao_editar">Editar</button>
									<button type="submit" id="enviarEdicao" ></button>
								</div>
							</form>																				
						</div>
					</div>
				</div>

<!--Mostrar mensagem de erro se o login ou senha forem inválidos-->
<?php
	if ($erro){
		echo '<script language="javascript">';
			echo 'function mensagemErro(){';
				echo 'alert("Não foi possível editar sua conta. Por favor, tente novamente!");';
			echo '}';
			echo 'onload=mensagemErro();';
		echo '</script>';
	}

	if ($existe){
		echo '<script language="javascript">';
			echo 'function mensagemExiste(){';
				echo 'alert("Já existe um usuário cadastrado com esse email!");';
			echo '}';
			echo 'onload=mensagemExiste();';
		echo '</script>';
	}

?>

<script type="text/javascript">
	$("#enviarEdicao").hide();
	$("#botaoEditar").hide();
	$("#blocoSenha1").hide();
	$("#blocoSenha2").hide();

	function verificarSenhas(){
		var senha1 = document.getElementById("senha1").value;
		var senha2 = document.getElementById("senha2").value;	
		if (senha1 != senha2){
			alert("As senhas não coincidem! Por favor, digite senhas iguais.");
			document.getElementById("senha2").value = "";
			document.getElementById("senha2").focus();
		} else {						
			document.getElementById("enviarEdicao").click();								
		}
	}	

	function permitirEdicao(){
		$("#botaoEditar").show();
		$("#blocoSenha1").show();
		$("#blocoSenha2").show();
		document.getElementById("nome").disabled = false;
		document.getElementById("email").disabled = false;
		document.getElementById("login").disabled = false;
	}


</script>
