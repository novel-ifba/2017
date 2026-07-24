<!-- Aqui e a area do conteudo -->
			<div class="col-md-12 col-xs-12 vertical-center">
				<div id="conteudo" class="col-md-4 col-xs-8 centered" >
					<div class="row">
						<div id="painel-login" class="centered col-md-12 col-xs-12">
							<h1>Login do administrador</h1>
							<form class="form-inline" role="form" method="post" action="<?php echo base_url('administracao/fazerLogin');?>">
							  	<div class="form-group">
							    	<label for="login">Login:</label>
							    	<input type="text" id="login" name="login" required class="form-control" id="login">
							  	</div>
							  	<div class="form-group">
							    	<label for="pwd">Senha:</label>
							   		<input type="password" id="senha" name="senha" required class="form-control" id="senha">
							  	</div>
								<div class="col-md-10 col-xs-12 centered">
									<button type="submit" class="btn btn-block btn-success">Entrar</button>
								</div>
								<div id="botao-esqueciSenha">
									<a href="<?php echo base_url('administracao/recuperarSenha'); ?>" class="btn btn-link" role="button">Esqueci a senha</a>
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
									echo 'alert("Login ou senha inválidos. Tente novamente!");';
								echo '}';
								echo 'onload=mensagemErro();';
							echo '</script>';
						}
					?>		