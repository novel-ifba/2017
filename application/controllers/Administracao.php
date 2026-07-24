<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administracao extends CI_Controller {

	public function __construct() {
	    parent::__construct();
	    $this->load->helper('array');
	    $this->load->model('modelAdministracao');
    }	

	public function index(){
		if ($this->session->userdata('logged_in')) {
                redirect('principal/menu');
            } else{
			$pagina = array('tela' => 'login-administrador', 'erro'=> FALSE,);
			$this->load->view('construtor', $pagina);
		}
	}


	public function fazerLogin(){

		//Se o jogador já estiver logado, redireciona para o menu principal
		if ($this->session->userdata('logged_in')) {
                redirect('administracao/menuAdministrador');
        } else{ 
			//Salva o login e a senha digitados no array $dados
		    $dados = $this->input->post(array('login', 'senha'));

		    //tenta fazer o login
		    $login = $this->modelAdministracao->fazerLogin($dados['login'], $dados['senha']);		   	
		    if($login){	        	
			    	$this->session->set_userdata(array(			    			
	                    'codAdministrador' => $login['codAdministrador'],	                    
	                    'nome' => $login['nome'],
	                    'logged_in' => TRUE,
	                ));
		    	//redirect('administracao/menuAdministrador');
		    } else {
		    	$pagina = array('tela' => 'login-administrador', 'erro'=> TRUE, );
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
			$pagina = array('tela' => 'recuperar-senha');
			$this->load->view('construtor', $pagina);
		}
	}

	public function menuAdministrador()
	{
		if ($this->session->userdata('logged_in')) {
            redirect('principal/menu');
        } else {
			$pagina = array('tela' => 'menu-administrador', 'linkNovel'=> 'menu-administrador', 'linkLogoff'=>'logoff',);
			$this->load->view('construtor', $pagina);
		}
	}

	public function cadastrarPalavra()
	{
		if ($this->session->userdata('logged_in')) {
            redirect('principal/menu');
        } else {
			$pagina = array('tela' => 'cadastrar-palavra', 'linkNovel'=> 'menu-administrador', 'linkLogoff'=>'logoff');
			$this->load->view('construtor', $pagina);
		}
	}

	public function editarPalavra()
	{
		if ($this->session->userdata('logged_in')) {
            redirect('principal/menu');
        } else {
			$pagina = array('tela' => 'editar-palavra', 'linkNovel'=> 'menu-administrador', 'linkLogoff'=>'logoff');
			$this->load->view('construtor', $pagina);
		}
	}

	public function cadastrarTexto()
	{
		if ($this->session->userdata('logged_in')) {
            redirect('principal/menu');
        } else {
			$pagina = array('tela' => 'cadastrar-texto', 'linkNovel'=> 'menu-administrador', 'linkLogoff'=>'logoff');
			$this->load->view('construtor', $pagina);
		}
	}

	public function editarTexto()
	{
		if ($this->session->userdata('logged_in')) {
            redirect('principal/menu');
        } else {
			$pagina = array('tela' => 'editar-texto', 'linkNovel'=> 'menu-administrador', 'linkLogoff'=>'logoff');
			$this->load->view('construtor', $pagina);
		}
	}
	
}
