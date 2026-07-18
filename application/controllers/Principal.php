<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Principal extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->helper('array');
        $this->load->model('modelJogador');
        $this->load->model('modelHistoria');
    }


	public function index()
	{		
		if ($this->session->userdata('logged_in')) {			
            //$this->logoff();
            redirect('principal/menu');
        } else {        	
			$pagina = array('tela' => 'index', 'erro' => FALSE, 'abrirModalHistoria'=> FALSE, 'enviou' =>  TRUE, 'email'=>NULL, 'conquista' => 0);
			$this->load->view('construtor', $pagina);
		}
	}


	public function login()
	{

		if ($this->session->userdata('logged_in')) {
            redirect('principal/menu');
        } else{ 			       			
			$pagina = array('tela' => 'index', 'erro'=> FALSE, 'abrirModalHistoria'=> FALSE, 'enviou' =>  TRUE, 'email'=>NULL, 'conquista' => 0,);
			$this->load->view('construtor', $pagina);
		}
	}

	public function fazerLogin(){

		//Se o jogador já estiver logado, redireciona para o menu principal
		if ($this->session->userdata('logged_in')) {
                redirect('principal/menu');
        } else{ 
			//Salva o login e a senha digitados no array $dados
		    $dados = $this->input->post(array('login', 'senha'));

		    //tenta fazer o login
		    $login = $this->modelJogador->fazerLogin($dados['login'], $dados['senha']);
		    	        
		    if($login){	        	
		    	$this->session->set_userdata(array(
	                    'codJogador' => $login['codJogador'],
	                    'avatar' => $login['avatar'],
	                    'nome' => $login['nome'],	                    
	                    'logged_in' => TRUE,
	                    'nivel' => $login['nivel'],
	                    'experiencia' => $login['experiencia'],
	                ));
		    	redirect('principal/menu');
		    } else {
		    	$pagina = array('tela' => 'index', 'erro'=> TRUE, 'abrirModalHistoria'=> FALSE, 'enviou' => TRUE, 'email'=>NULL, 'conquista' => 0,);
				$this->load->view('construtor', $pagina);
		    }
		} 		
	}

	public function logoff() {

        $this->session->sess_destroy();
        redirect('principal/index');
    }

	public function recuperarSenha()
	{
		if ($this->session->userdata('logged_in')) {
            redirect('principal/menu');
        } else {	
        	$email = $this->input->post();

        	$existe = $this->modelJogador->verificarEmailCadastrado($email['email']);

        	if($existe){
	        	$senha = $this->modelJogador->recuperarSenha($email['email']);

	        	$this->load->library('email');		
	        	$this->email->from("novel.noreply@gmail.com", 'Novel');
				$this->email->subject("Esqueceu a senha?");			
				$this->email->to($email['email']); 
				$this->email->message("Reenvio de senha. <br/><br/>Sua nova senha é ".$senha.".<br/><br/>Você pode alterar a sua senha após fazer o login, clicando em Minha Conta.<br/><br/>Atenciosamente, <br/><br/>Equipe Novel.");
				$enviou  = $this->email->send();											
				$pagina = array('tela' => 'index', 'erro'=> FALSE, 'abrirModalHistoria'=> FALSE, 'enviou' => $enviou, 'email'=>$email['email'], 'conquista' => 0,);
					$this->load->view('construtor', $pagina);				
        	}
		}
	}

	public function menu()
	{				
		if ($this->session->userdata('logged_in')) {		
        	$pagina = array('tela' => 'menu', 'linkNovel'=> 'principal/menu', 'linkLogoff'=>'principal/logoff', 'abrirModalHistoria'=> FALSE, 'conquista' => 0);
			$this->load->view('construtor', $pagina);    
        } else {
			redirect('principal/index');
		}
	}

	public function controle()
	{
		$pagina = array('tela' => 'controle');
		$this->load->view('construtor', $pagina);
	}

	public function sobre()
	{

		$pagina = array('tela' => 'sobre',				
				'erro' => FALSE,
				'abrirModalHistoria'=> FALSE,
				'linkNovel'=> 'principal/menu', 
				'linkLogoff'=>'principal/logoff',
				'existe' => FALSE,
				'conquista' => 0,);
		$this->load->view('construtor', $pagina);
	}


	public function cadastrarJogador()
	{					
		if ($this->session->userdata('logged_in')) {
        	redirect('principal/menu');    
        } else {
			$pagina = array('tela' => 'cadastrar-jogador', 'erro' => FALSE, 'existe' => FALSE, 'conquista' => 0,  'abrirModalHistoria'=> FALSE,);
			$this->load->view('construtor', $pagina);
		}
	}

	public function realizarCadastro(){
		if ($this->session->userdata('logged_in')) {
        	redirect('principal/menu');    
        } else {
        	$dados = $this->input->post();
        	$existe = $this->modelJogador->verificarEmailCadastrado($dados['email']);


        	if ($existe){
        		$pagina = array('tela' => 'cadastrar-jogador', 'erro' => FALSE, 'existe'=> TRUE, 'abrirModalHistoria'=> FALSE, 'conquista' => 0,);
				$this->load->view('construtor', $pagina);
        	} else {
        		$retorno = $this->modelJogador->cadastrarJogador($dados);	        		
				if($retorno){					
					$this->loginCadastro($dados['login'], $dados['senha1']);					
				} else {
					$pagina = array('tela' => 'cadastrar-jogador', 'erro' => TRUE, 'existe' => FALSE, 'abrirModalHistoria'=> FALSE, 'conquista' => 0,);
					$this->load->view('construtor', $pagina);		
				}
        	}
		}		
	}

	public function loginCadastro($login, $senha){
		$logou = $this->modelJogador->fazerLogin($login, $senha);		
		    if($logou){	        	
		    	$this->session->set_userdata(array(
	                    'codJogador' => $logou['codJogador'],
	                    'avatar' => $logou['avatar'],
	                    'nome' => $logou['nome'],	                    
	                    'logged_in' => TRUE,
	                    'nivel' => $logou['nivel'],
	                    'experiencia' => $logou['experiencia'],
	                ));
		    	$this->modelJogador->conquistarEntrada($logou['codJogador']);
		    	redirect('principal/menu');
		    } else {
		    	$pagina = array('tela' => 'index', 'erro'=> TRUE, 'abrirModalHistoria'=> FALSE, 'conquista' => 0,);
				$this->load->view('construtor', $pagina);
		    }
	}

	public function meusPontos(){

		if ($this->session->userdata('logged_in')){
			
			$codJogador = $this->session->userdata('codJogador');
			$dadosPalavras = $this->modelJogador->buscarHistoricoPalavra($codJogador);
			$dadosTestes = $this->modelJogador->buscarHistoricoTeste($codJogador);
			$dadosTextos = $this->modelJogador->buscarHistoricoTexto($codJogador);
			$tempoTotal = $this->modelJogador->buscarTempoTotal($codJogador);
			$experiencia = $this->modelJogador->buscarExperienciaJogador($codJogador);
			$conquistas = $this->modelJogador->buscarConquistasJogador($codJogador);
			$avatar = $this->session->userdata('avatar');


			$pagina = array('tela' => 'meus-pontos', 
				'erro' => FALSE,
				'abrirModalHistoria'=> FALSE,
				'dadosPalavras' => $dadosPalavras,
				'dadosTestes' =>$dadosTestes,
				'dadosTextos' => $dadosTextos,
				'tempoTotal' =>$tempoTotal,
				'experiencia' =>$experiencia,
				'conquistas' =>$conquistas,
				'linkNovel'=> 'principal/menu', 
				'linkLogoff'=>'principal/logoff',
				'conquista' => 0,
				'avatar' => $avatar,	
				);
			
			$this->load->view('construtor', $pagina);
		} else {
			redirect('principal/index');
		}
	}

	public function minhaConta(){
		if ($this->session->userdata('logged_in')){
			$codJogador = $this->session->userdata('codJogador');
			$jogador = $this->modelJogador->buscarDadosJogador($codJogador);

			$pagina = array('tela' => 'minha-conta', 
				'erro' => FALSE,
				'abrirModalHistoria'=> FALSE,
				'jogador' => $jogador,
				'linkNovel'=> 'principal/menu', 
				'linkLogoff'=>'principal/logoff',
				'existe' => FALSE,
				'conquista' => 0,
				);
			
			$this->load->view('construtor', $pagina);
		} else {
			redirect('principal/index');
		}
	}

	public function editarCadastro(){

		$dados = $this->input->post();
		$codJogador = $this->session->userdata('codJogador');
		$editou = $this->modelJogador->editarCadastroJogador($dados, $codJogador);
		if ($editou){
			echo '<script>alert("Seus dados foram alterados!");';
			echo 'window.location="'.base_url('principal/menu').'";';
			echo '</script>';
			
		} else {
			echo '<script>alert("Seus dados não foram alterados. Por favor, tente novamente!");';
			echo 'window.location="'.base_url('principal/menu').'";';
			echo '</script>';			
		}
	}
	
	public function bonus(){
		$codJogador = $this->session->userdata('codJogador');						
		$experiencia = $this->session->userdata('experiencia');			

		$nivel = $this->uri->segment(3);
	    $nivel = urldecode($nivel);
		$bonus = $this->modelHistoria->buscarBonus($experiencia, $codJogador);						
	
		$pagina = array(
		'tela' => 'bonus', 
		'linkNovel'=> 'principal/menu', 
		'linkLogoff'=>'principal/logoff', 				
		'abrirModalHistoria'=> FALSE,												
		'conquista' => 0,
		'bonus'=>$bonus,
		'nivel'=>$nivel,
		);
		$this->load->view('construtor', $pagina);
	}

	public function inserirBonus(){
		$dados = $this->input->post();			
		$correcao = NULL;
		$tela = NULL;		
			switch ($dados['nivel']) {
				case '0':
					$tela = "principal/index";
				case '1':
					$tela = "principal/index";
					break;
				case '2':
					$tela = "principal/index";
				default:
					$tela = "principal/index";
					break;
			}
		if (count($dados) != 2){
			$correcao = $this->modelHistoria->corrigirBonus($dados);			
			unset($correcao[0]);			
		} 
		redirect($tela);
	}
}
