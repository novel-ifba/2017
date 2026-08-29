<nav class="navbar navbar-default cc novel-navbar" x-data="{ aberto: false }">
	 <div class="navbar-header">
	    <button type="button" class="navbar-toggle" @click="aberto = !aberto" :aria-expanded="aberto.toString()" aria-controls="myNavbar">
	    	<span class="icon-bar"></span>
	    	<span class="icon-bar"></span>
	    	<span class="icon-bar"></span>
	    </button>
		<a class="navbar-brand" href="<?php echo base_url(isset($linkNovel) ? $linkNovel : 'menu'); ?>">Novel</a>
		<img class="img-responsive" style="border-radius: 50%;" width="100px;" id="avatarNav" src="<?php echo base_url('assets/img/'.$this->session->userdata('avatar').'.png'); ?>">
    </div>
    <div class="navbar-collapse novel-navbar-menu" id="myNavbar" :class="{ 'is-open': aberto }">
		<ul class="nav navbar-nav navbar-right">
			<li>
		    	<a href="#"><?php  echo("Olá, " . $this->session->userdata('nome') . "!"); ?></a>
		    </li>
		    <li>
		    	<a href="<?php echo base_url('minha-conta'); ?>">
		    		<span class="glyphicon glyphicon-user" aria-hidden="true"></span> Minha conta
		    	</a>
		    </li>
			<li>
				<a href="<?php echo base_url('meus-pontos'); ?>">
					<span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Meus pontos
				</a>
			</li> 
			<li>
				<a href="<?php echo base_url('sobre-o-novel');?>">
					<span class="glyphicons glyphicons-puzzle"></span>Sobre o Novel
				</a>
			</li>
			<li>
				<a href="<?php echo base_url(isset($linkLogoff) ? $linkLogoff : 'sair');?>">
					<span class="glyphicon glyphicon-log-out"></span> Sair
				</a>
			</li>
	    </ul> 
	</div> 
</nav>
