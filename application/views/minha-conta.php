<!-- Aqui e a area do conteudo -->
<div class="novel-page novel-form-page">
	<div id="conteudo" class="novel-form-card novel-profile-card">
		<header class="novel-section-header">
			<p class="novel-eyebrow">Meu perfil</p>
			<h1 class="titulo-menu">Minha conta</h1>
			<p class="novel-lead">Mantenha seus dados atualizados para continuar sua jornada no Novel.</p>
		</header>

		<form id="form" role="form" method="post" action="<?php echo base_url('minha-conta/salvar');?>">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
			<div class="novel-form-grid">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" class="form-control" required id="nome" name="nome" value="<?php echo $jogador[0]->nome ?>">
				</div>
				<div class="form-group">
					<label for="email">E-mail</label>
					<input type="email" class="form-control" required id="email" name="email" value="<?php echo $jogador[0]->email ?>">
				</div>
				<div class="form-group">
					<label for="login">Login</label>
					<input type="text" class="form-control" required id="login" name="login" value="<?php echo $jogador[0]->login ?>">
				</div>
			</div>

			<div class="novel-password-box">
				<h2>Trocar senha</h2>
				<p>Preencha apenas se quiser definir uma nova senha.</p>
				<div class="novel-password-grid">
					<div class="form-group">
						<label for="senha1">Nova senha</label>
						<input type="password" class="form-control" id="senha1" name="senha1" placeholder="Nova senha">
					</div>
					<div class="form-group">
						<label for="senha2">Repita a nova senha</label>
						<input type="password" class="form-control" id="senha2" name="senha2" placeholder="Repita a nova senha">
					</div>
				</div>
			</div>

			<button type="button" onclick="verificarSenhas()" id="botaoEditar" class="btn btn-lg btn-success btn-block">Salvar conta</button>
			<button type="submit" id="enviarEdicao" hidden></button>
		</form>
	</div>
</div>

<?php
	if ($erro){
		echo '<script language="javascript">alert("Não foi possível editar sua conta. Por favor, tente novamente!");</script>';
	}

	if ($existe){
		echo '<script language="javascript">alert("Já existe um usuário cadastrado com esse email!");</script>';
	}
?>

<script type="text/javascript">
	function verificarSenhas(){
		var senha1 = document.getElementById("senha1").value;
		var senha2 = document.getElementById("senha2").value;
		if ((senha1 || senha2) && senha1 != senha2){
			alert("As senhas não coincidem! Por favor, digite senhas iguais.");
			document.getElementById("senha2").value = "";
			document.getElementById("senha2").focus();
		} else {
			document.getElementById("enviarEdicao").click();
		}
	}
</script>
