<?php
	$linkInicio = isset($linkNovel) ? $linkNovel : 'menu';
	$linkSair = isset($linkLogoff) ? $linkLogoff : 'sair';
?>

<nav class="navbar navbar-default cc novel-navbar" x-data="{ menuAberto: false, perfilAberto: false }">
	<div class="navbar-header novel-navbar-branding">
		<a class="navbar-brand" href="<?php echo base_url($linkInicio); ?>">Novel</a>
		<a class="novel-avatar-link" href="<?php echo base_url($linkInicio); ?>" aria-label="Ir para o início">
			<img class="img-responsive" id="avatarNav" src="<?php echo base_url('assets/img/'.$this->session->userdata('avatar').'.png'); ?>" alt="Avatar do jogador">
		</a>
		<button type="button" class="navbar-toggle" @click="menuAberto = !menuAberto" :aria-expanded="menuAberto.toString()" aria-controls="myNavbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</div>

	<div class="navbar-collapse novel-navbar-menu" id="myNavbar" :class="{ 'is-open': menuAberto }">
		<ul class="nav navbar-nav navbar-right novel-main-nav">
			<li>
				<a href="<?php echo base_url($linkInicio); ?>">
					<span class="glyphicon glyphicon-home" aria-hidden="true"></span> Início
				</a>
			</li>
			<li>
				<a href="<?php echo base_url('meus-pontos'); ?>">
					<span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Meus pontos
				</a>
			</li>
			<li>
				<a href="<?php echo base_url('sobre-o-novel'); ?>">
					<span class="glyphicon glyphicon-book" aria-hidden="true"></span> Sobre
				</a>
			</li>
			<li class="novel-user-menu" @click.away="perfilAberto = false">
				<button type="button" class="novel-user-trigger" @click="perfilAberto = !perfilAberto" :aria-expanded="perfilAberto.toString()">
					<span class="novel-user-name"><?php echo $this->session->userdata('nome'); ?></span>
					<span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
				</button>
				<div class="novel-user-dropdown" x-show="perfilAberto" x-transition x-cloak>
					<a href="<?php echo base_url('minha-conta'); ?>">
						<span class="glyphicon glyphicon-user" aria-hidden="true"></span> Perfil
					</a>
					<a href="<?php echo base_url($linkSair); ?>">
						<span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Sair
					</a>
				</div>
			</li>
		</ul>
	</div>
</nav>
