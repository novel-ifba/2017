<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Palavra extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->helper('array');
        $this->load->model('modelPalavra');
        $this->load->model('modelJogador');  
        $this->load->model('modelHistoria');     
    }

	public function index()	{
		
		if ($this->session->userdata('logged_in')) {
			$erro = $this->uri->segment(3);        	
            $inputJogador = array('null'=> 'nulo');
            $nivel = $this->session->userdata('nivel');            

            $codJogador = $this->session->userdata('codJogador');

			$grafemasJogados = $this->modelJogador->buscarGrafemasJogadosPalavra($codJogador);	
			$grafemasCadastrados = $this->modelJogador->buscarListaGrafemas();
            
			$cenas = $this->modelHistoria->buscarCenaPeloNivel($nivel);
			if($cenas){
				$abrirModalHistoria[] = $cenas[0]->nomeCena;
				$abrirModalHistoria[] = $cenas[0]->quadros;
			} else {
				$abrirModalHistoria = FALSE;
			}				
				$pagina = array(
				'tela' => 'menu-palavra', 
				'linkNovel'=> 'principal/menu', 
				'linkLogoff'=>'principal/logoff', 
				'abrirModalGabarito' => FALSE,
				'inputJogador' => $inputJogador,
				'abrirModalHistoria'=> $abrirModalHistoria,
				'gabarito' => NULL,
				'pontuacao' => NULL,
				'palavraCompleta' => NULL,
				'conquista' => 0,
				'erro' => $erro,
				'inseriu' => TRUE,
				'grafemasJogados' => $grafemasJogados,
				'grafemasCadastrados' =>$grafemasCadastrados,
				);
				$this->load->view('construtor', $pagina);	
        } else {
			redirect('principal/index');
		}
	}

	public function jogarPalavra() 	{
			
		if ($this->session->userdata('logged_in')) {

			$codJogador = $this->session->userdata('codJogador');
			$experiencia = $this->session->userdata('experiencia');
			$bonus = $this->modelHistoria->buscarBonus($experiencia, $codJogador);				
			
			if($bonus[0] != NULL){
				redirect('principal/bonus/0');
			} else {
	        	$grafema = $this->uri->segment(3);
	        	$grafema = urldecode($grafema);
				$retorno = $this->modelPalavra->sortearPalavras($grafema);
				$regra = $this->modelPalavra->buscarRegraPeloTipo($grafema);

				if($retorno){

					$palavrasSorteadas = $retorno[0];
					$codGrafema = $retorno[1];				

					foreach ($palavrasSorteadas as $key) {			 			
						$palavras[] = $key[0];					
					}

					$pagina = array('tela' => 'jogar-palavra', 
						'linkNovel'=> 'principal/menu', 
						'linkLogoff'=>'principal/logoff', 
						'palavras'=> $palavras,
						'grafema'=> $grafema, 
						'codGrafema' => $codGrafema,
						'abrirModalRegra' => TRUE,
						'abrirModalHistoria'=> FALSE,
						'conquista' => 0,
						'regra' => $regra,
						);

					$this->load->view('construtor', $pagina);
				} else {
					redirect('palavra/index/TRUE');
				}
			}
        } else {
			redirect('principal/index');
		}		
	}

	public function inserirRodadaPalavra(){
		if ($this->session->userdata('logged_in')) {

			$dados = $this->input->post();

			for ($i = 0; $i<5; $i++){
				$inputJogador[] = $dados['inputLetra'.$i];
			}

			for ($i = 0; $i<5; $i++){
				$gabarito[] = $dados['gabarito'.$i];
			}

			for ($i = 0; $i<5; $i++){
				$justificativa[] = $dados['justificativa'.$i];
			}

			for ($i = 0; $i<5; $i++){
				$palavraCompleta[] = $dados['palavraCompleta'.$i];
			}


			$codGrafema = $dados['codGrafema'];
			$pontuacao = $this->modelPalavra->calcularPontuacao($inputJogador, $gabarito);
			$nivelAntigo = $this->session->userdata('nivel');			
			$codJogador = $this->session->userdata('codJogador');						
			$nivelNovo = $this->modelJogador->subirNivelPalavra($codJogador, $pontuacao, $codGrafema);				
			if($nivelNovo){
				$this->session->set_userdata('nivel', $nivelAntigo + 1);
			}			

			$inseriu = $this->modelPalavra->inserirRodadaPalavra($dados['codGrafema'], $this->session->userdata('codJogador'), $dados['duracao'], $pontuacao);			
			$this->modelJogador->subirExperiencia($codJogador, $pontuacao);

			$cenas = $this->modelHistoria->buscarCenaPeloNivel($this->session->userdata('nivel'));
			if($cenas){
				$abrirModalHistoria[] = $cenas[0]->nomeCena;
				$abrirModalHistoria[] = $cenas[0]->quadros;
			} else {
				$abrirModalHistoria = FALSE;
			}
		
			$experiencia = $this->modelJogador->buscarExperienciaJogador($codJogador);

			$this->session->set_userdata('experiencia', $experiencia);			
			$conquista = $this->modelHistoria->buscarNovaConquista($experiencia, $codJogador);	
			$nomeConquista = $this->modelHistoria->buscarNomeConquista($conquista);			

			$grafemasJogados = $this->modelJogador->buscarGrafemasJogadosPalavra($codJogador);	
			$grafemasCadastrados = $this->modelJogador->buscarListaGrafemas();
				

				$pagina = array(
					'tela' => 'menu-palavra',
					'linkNovel'=> 'principal/menu', 
					'linkLogoff'=>'principal/logoff', 
					'inputJogador' => $inputJogador,
					'gabarito' => $gabarito,
					'pontuacao' => $pontuacao,
					'justificativa' => $justificativa,
					'palavraCompleta' => $palavraCompleta,
					'abrirModalGabarito' => TRUE,
					'abrirModalHistoria'=> $abrirModalHistoria,
					'inseriu' => $inseriu,
					'erro' => NULL,
					'conquista' => $conquista,
					'nomeConquista'=> $nomeConquista,
					'grafemasJogados' => $grafemasJogados,
					'grafemasCadastrados' => $grafemasCadastrados,
					);
				$this->load->view('construtor', $pagina);		
        } else {
        	redirect('principal/index');
		}	
	}
}
