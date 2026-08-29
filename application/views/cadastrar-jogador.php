<!-- Aqui e a area do conteudo -->
			<div class="row afastado-1pc vertical-center">
				<div id="conteudo" class="col-md-6 col-xs-12 centered" >
					<div class="row">
						<div id="painel-login" class="col-md-12 col-xs-12">
							<div class="row">
								<h1 class="titulo-menu centered" style="color: #FFFFFF; text-transform: uppercase;">Cadastre-se para jogar!</h1><br><br>
							</div>							
							<form class="form-vertical col-md-12 col-xs-12 centered" id="form" role="form" method="post" action="<?php echo base_url('cadastro/salvar');?>">
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
							  <div class="col-md-offset-3 col-xs-offset-3">
							  	<div class="row">
							  		<div class="form-group centered">
								    	<div class="col-md-8 col-xs-8">
								    		<label for="nome" style="text-align: left!important; width: 100%; color: #FFFFFF;">Nome:</label>
								    		<input type="text" class="form-control" required="" size="40" id="nome" name="nome" placeholder="Nome">
								    	</div>								    
							  		</div>
							  	</div>
							  	<div class="row">
							  		<div class="form-group centered">
								    	<div class="col-md-8 col-xs-8">
								    		<label for="email" style="text-align: left!important; width: 100%; color: #FFFFFF;">E-mail:</label>
								    		<input type="email" class="form-control" required="" size="40" id="email" name="email" placeholder="E-mail">
								    	</div>								    
							  		</div>
							  	</div>
							  	<div class="row">
								  	<div class="form-group centered">
									    <div class="col-md-8 col-xs-8">
									    	<label for="login" style="text-align: left!important; width: 100%; color: #FFFFFF;">Login:</label>
								   			<input type="text" class="form-control" required="" size="40" id="login" name="login" placeholder="Login">
								   		</div>
								  	</div>
							  	</div>
							  	<div class="row">
								  	<div class="form-group centered">
								   		<div class="col-md-8 col-xs-8">
								   			<label for="senha1" style="text-align: left!important; width: 100%; color: #FFFFFF;">Senha:</label>
								   			<input type="password" class="form-control" required=""  size="40" id="senha1" name="senha1" placeholder="Senha">
								   		</div>
								  	</div>
							  	</div>
							  	<div class="row">
								  	<div class="form-group centered">
								   		<div class="col-md-8 col-xs-8">
								   			<label for="senha2" style="text-align: left!important; width: 100%; color: #FFFFFF;">Repita a senha:</label>
								   			<input type="password" class="form-control" required="" size="40" id="senha2" name="senha2" placeholder="Repita a Senha">							   			
								   		</div>
								  	</div>
							  	</div>
							  </div><br><br>
							  	<div class="row">
								  	<div class="form-group centered">
								  		<div class="col-md-12 col-xs-12">
								  			<label for="avatar" style="text-align: left!important; width: 100%; color: #FFFFFF; padding-left: 50px;">Escolha seu avatar:</label>
								    	</div>
								    	<br><br>
								   		<div class="col-md-6 col-xs-3">
								   			<label class="centered">
								   				<div class="row">
								   					<img class="centered img-responsive ii" style="border-radius: 50%; border: 4px solid #3A17E8;" src="<?php echo base_url('assets/img/cecilia.png'); ?>">
								   				</div>
								   				<div class="row" style="color: #FFF; text-transform: uppercase;">
								   					<input class="centered" required type="radio" name="avatar" value="cecilia"/>Cecília
								   				</div>								   											   	
	   							 			</label>					
								   		</div>
								   		<div class="col-md-6 col-xs-3">
								   			<label class="centered">
								   				<div class="row">
								   					<img class="centered img-responsive ii" style="border-radius: 50%; border: 4px solid #3A17E8;" src="<?php echo base_url('assets/img/graciliano.png'); ?>">
								   				</div>
								   				<div class="row" style="color: #FFF; text-transform: uppercase;">
								   					<input class="centered" type="radio" name="avatar" value="graciliano"/>Graciliano
								   				</div>								   											   	
	   							 			</label>	
								   		</div>
								   		<div class="col-md-6 col-xs-3">
								   			<label class="centered">
								   				<div class="row">
								   					<img class="centered img-responsive ii" style="border-radius: 50%; border: 4px solid #3A17E8;" src="<?php echo base_url('assets/img/clarice.png'); ?>">
								   				</div>
								   				<div class="row" style="color: #FFF; text-transform: uppercase;">
								   					<input class="centered" type="radio" name="avatar" value="clarice"/>Clarice
								   				</div>								   											   	
	   							 			</label>						
								   		</div>
								   		<div class="col-md-6 col-xs-3">
								   			<label class="centered">
								   				<div class="row">
								   					<img class="centered img-responsive ii" style="border-radius: 50%; border: 4px solid #3A17E8;" src="<?php echo base_url('assets/img/verissimo.png'); ?>">
								   				</div>
								   				<div class="row" style="color: #FFF; text-transform: uppercase;">
								   					<input class="centered" type="radio" name="avatar" value="verissimo"/>Veríssimo
								   				</div>								   											   	
	   							 			</label>								
								   		</div>
								  	</div>
							  	</div>
							  	<br><br>
								<div class="row centered c">
									<button type="button" onclick="verificarSenhas()" class="btn btn-lg btn-success">CADASTRAR</button>
									<button type="submit" id="enviarCadastro" ></button>
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
									echo 'alert("Não foi possível realizar o cadastro. Por favor, tente novamente!");';
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
						$("#enviarCadastro").hide();
						function verificarSenhas(){
							var senha1 = document.getElementById("senha1").value;
							var senha2 = document.getElementById("senha2").value;	
							if (senha1 != senha2){
								alert("As senhas não coincidem! Por favor, digite senhas iguais.");
								document.getElementById("senha2").value = "";
								document.getElementById("senha2").focus();
							} else {						
								document.getElementById("enviarCadastro").click();								
							}
						}						

					</script>
