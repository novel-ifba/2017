<!DOCTYPE html>
<html>
<head>
	<title>Novel</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrap.css"); ?>" />
	<link rel="stylesheet" href="<?php echo base_url("assets/css/estilo.css"); ?>" />
	<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body id="pagina-inicial">
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.7.1.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>

<main class="novel-auth-page" x-data="{ recuperar: false }">
	<section class="novel-auth-hero">
		<div>
			<p class="novel-eyebrow">Ortografia gamificada</p>
			<h1>Novel</h1>
			<p>Aprenda regras ortográficas com fases curtas, personagens literários e acompanhamento de progresso.</p>
		</div>
		<img src="<?php echo base_url('assets/img/graciliano.png'); ?>" alt="Personagem do Novel">
	</section>

	<section class="novel-auth-card">
		<div x-show="!recuperar">
			<h2>Entrar na aventura</h2>
			<form role="form" method="post" action="<?php echo base_url('autenticar');?>">
				<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
				<div class="form-group">
					<label for="login">Login</label>
					<input type="text" required name="login" class="form-control" id="login" placeholder="Seu login">
				</div>
				<div class="form-group">
					<label for="senha">Senha</label>
					<input type="password" required name="senha" class="form-control" id="senha" placeholder="Sua senha">
				</div>
				<button type="submit" name="btn-login" class="btn btn-lg btn-block btn-success">Começar</button>
			</form>

			<div class="novel-auth-actions">
				<a href="<?php echo base_url('cadastro'); ?>">Criar conta</a>
				<button type="button" class="btn btn-link" @click="recuperar = true">Esqueci a senha</button>
			</div>
		</div>

		<div x-show="recuperar">
			<h2>Recuperar senha</h2>
			<form role="form" method="post" action="<?php echo base_url('recuperar-senha');?>">
				<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
				<div class="form-group">
					<label for="email">E-mail</label>
					<input type="email" class="form-control" id="email" name="email" placeholder="email@exemplo.com">
				</div>
				<button type="submit" class="btn btn-lg btn-block btn-success">Enviar nova senha</button>
				<button type="button" class="btn btn-link btn-block" @click="recuperar = false">Voltar ao login</button>
			</form>
		</div>
	</section>
</main>

<?php					
	if ($erro){
		echo '<script language="javascript">alert("Login ou senha inválidos. Tente novamente!");</script>';						
	}						
	if ($email == TRUE){
		echo '<script language="javascript">alert("Sua nova senha foi enviada para o email '.$email.'");</script>';						
	}
	if ($enviou == FALSE){
		echo '<script language="javascript">alert("Senha não enviada. Tente novamente!");</script>';						
	}
?>	

</body>
</html>
