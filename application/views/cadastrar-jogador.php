<!-- Aqui e a area do conteudo -->
<div class="novel-page novel-form-page">
	<div id="conteudo" class="novel-form-card">
		<header class="novel-section-header">
			<p class="novel-eyebrow">Nova conta</p>
			<h1 class="titulo-menu">Cadastre-se para jogar</h1>
			<p class="novel-lead">Crie seu perfil, escolha um avatar literário e comece sua trilha de ortografia.</p>
		</header>

		<form id="form" role="form" method="post" action="<?php echo base_url('cadastro/salvar');?>">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
			<div class="novel-form-grid">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" class="form-control" required id="nome" name="nome" placeholder="Nome">
				</div>
				<div class="form-group">
					<label for="email">E-mail</label>
					<input type="email" class="form-control" required id="email" name="email" placeholder="E-mail">
				</div>
				<div class="form-group">
					<label for="login">Login</label>
					<input type="text" class="form-control" required id="login" name="login" placeholder="Login">
				</div>
				<div class="form-group">
					<label for="senha1">Senha</label>
					<input type="password" class="form-control" required id="senha1" name="senha1" placeholder="Senha">
				</div>
				<div class="form-group">
					<label for="senha2">Repita a senha</label>
					<input type="password" class="form-control" required id="senha2" name="senha2" placeholder="Repita a senha">
				</div>
			</div>

			<div class="novel-avatar-field">
				<label>Escolha seu avatar</label>
				<div class="novel-avatar-grid">
					<label>
						<input required type="radio" name="avatar" value="cecilia">
						<img src="<?php echo base_url('assets/img/cecilia.png'); ?>" alt="Cecília">
						<span>Cecília</span>
					</label>
					<label>
						<input type="radio" name="avatar" value="graciliano">
						<img src="<?php echo base_url('assets/img/graciliano.png'); ?>" alt="Graciliano">
						<span>Graciliano</span>
					</label>
					<label>
						<input type="radio" name="avatar" value="clarice">
						<img src="<?php echo base_url('assets/img/clarice.png'); ?>" alt="Clarice">
						<span>Clarice</span>
					</label>
					<label>
						<input type="radio" name="avatar" value="verissimo">
						<img src="<?php echo base_url('assets/img/verissimo.png'); ?>" alt="Veríssimo">
						<span>Veríssimo</span>
					</label>
				</div>
			</div>

			<button type="button" onclick="verificarSenhas()" class="btn btn-lg btn-success btn-block">Cadastrar</button>
			<button type="submit" id="enviarCadastro" hidden></button>
		</form>
	</div>
</div>

<?php
	if ($erro){
		echo '<script language="javascript">alert("Não foi possível realizar o cadastro. Por favor, tente novamente!");</script>';
	}

	if ($existe){
		echo '<script language="javascript">alert("Já existe um usuário cadastrado com esse email!");</script>';
	}
?>

<script type="text/javascript">
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
