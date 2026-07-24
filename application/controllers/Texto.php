<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Texto extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->helper('array');
        $this->load->model('modelTexto');
           $this->load->model('modelJogador');
           $this->load->model('modelHistoria');
    }

	public function index()
	{		
		if ($this->session->userdata('logged_in')) {
			
			
			$nivel = $this->session->userdata('nivel');
            $cenas = $this->modelHistoria->buscarCenaPeloNivel($nivel);
			if($cenas){
				$abrirModalHistoria[] = $cenas[0]->nomeCena;
				$abrirModalHistoria[] = $cenas[0]->quadros;
			} else {
				$abrirModalHistoria = FALSE;
			}
			$codJogador = $this->session->userdata('codJogador');
			$grafemasTextos = $this->modelTexto->buscarListaGrafemasTexto();			
			$grafemasJogados = $this->modelTexto->buscarGrafemasJogadosTexto($codJogador);			
						
				$pagina = array('tela' => 'menu-texto', 
					'linkNovel'=> 'principal/menu', 
					'linkLogoff'=>'principal/logoff', 
					'abrirModalHistoria'=> $abrirModalHistoria, 
					'abrirModalGabarito' => FALSE, 
					'erro' =>FALSE,
					'conquista' => 0,
					'grafemasTextos' => $grafemasTextos,
					'grafemasJogados' => $grafemasJogados
					);

				$this->load->view('construtor', $pagina);
        } else {
			redirect('principal/menu');
		}
	}

	public function jogarTexto()
	{
		if ($this->session->userdata('logged_in')) {
			
			$codJogador = $this->session->userdata('codJogador');
			$experiencia = $this->session->userdata('experiencia');
			$bonus = $this->modelHistoria->buscarBonus($experiencia, $codJogador);	

			if($bonus[0] != NULL){
					redirect('principal/bonus/1');
			} else {
				$grafemas = $this->uri->segment(3);
				$grafemas = urldecode($grafemas);

				$texto = $this->modelTexto->sortearTexto($grafemas);
				$grafemasTextos = $this->modelTexto->buscarListaGrafemasTexto();
				$codJogador = $this->session->userdata('codJogador');

				if($texto){
					$codTexto = $texto[0]->codTexto;				

					$pagina = array('tela' => 'jogar-texto', 
					'linkNovel'=> 'principal/menu', 
					'linkLogoff'=>'principal/logoff',
					'texto' => $texto,
					'grafemas' => $grafemas,
					'conquista' => 0,
					'abrirModalHistoria' => FALSE,					
					);
				
				$this->load->view('construtor', $pagina); 
				} else{				
					$grafemasJogados = $this->modelTexto->buscarGrafemasJogadosTexto($codJogador);

					$pagina = array('tela' => 'menu-texto', 
						'linkNovel'=> 'principal/menu', 
						'linkLogoff'=>'principal/logoff', 
						'abrirModalHistoria'=> FALSE, 
						'abrirModalGabarito' => FALSE,	
						'erro' => TRUE,
						'conquista' => 0,
						'grafemasTextos' => $grafemasTextos,
						'grafemasJogados' => $grafemasJogados
					);
				$this->load->view('construtor', $pagina); 
				}
			}			          
        } else {	
			redirect('principal/index');
		}
	}

	public function inserirRodadaTexto(){		
		if ($this->session->userdata('logged_in')) {
			$dados = $this->input->post();			

			$tamanho = count($dados);

			$inputJogador = NULL;


			foreach ($dados as $d => $valor) {
				$indice = $d;
				$tamanho = count($dados);				
				for ($i=0; $i < $tamanho; $i++){
					$string = 'inputLetra'.$i;
					if(strcmp($indice, $string) == 0){
						$inputJogador[] = $valor;
					}
				}			
			}					
			$grafemas = $dados['grafemas']; 

			if (($grafemas == "total")|| ($grafemas == "_total")) {
				$grafemas = "g_j&ch_x&s_z_x&c_ç_s_ss_sc_sç_xc&m_n&r_rr&e_i&o_u_l";
			}
			$duracao = $dados['duracao'];
			$codTexto = $dados['codTexto'];
			$gabarito = $this->modelTexto->encontrarGabarito($codTexto);
			$quantidade = count($gabarito);
			
			$codJogador = $this->session->userdata('codJogador');							
			$pontuacao = $this->modelTexto->calcularPontuacao($inputJogador, $gabarito);	

			$this->modelJogador->subirExperiencia($codJogador, $pontuacao);
			$experiencia = $this->modelJogador->buscarExperienciaJogador($codJogador);
			$this->session->set_userdata('experiencia', $experiencia);	
			$conquista = $this->modelHistoria->buscarNovaConquista($experiencia, $codJogador);
			$nomeConquista = $this->modelHistoria->buscarNomeConquista($conquista);	
					
			$nivelAntigo = $this->session->userdata('nivel');								
			$nivelNovo = $this->modelJogador->subirNivelTexto($codJogador, $pontuacao, $grafemas, $quantidade);				
			if($nivelNovo){
				$this->session->set_userdata('nivel', $nivelAntigo + 1);
			}
			
			$inseriu = $this->modelTexto->inserirRodadaTexto($grafemas, $codJogador, $duracao, $pontuacao, $codTexto);

			$cenas = $this->modelHistoria->buscarCenaPeloNivel($this->session->userdata('nivel'));
			if($cenas){
				$abrirModalHistoria[] = $cenas[0]->nomeCena;
				$abrirModalHistoria[] = $cenas[0]->quadros;
			} else {
				$abrirModalHistoria = FALSE;
			}

			$grafemasTextos = $this->modelTexto->buscarListaGrafemasTexto();
			$grafemasJogados = $this->modelTexto->buscarGrafemasJogadosTexto($codJogador);
			
				$pagina = array(
					'tela' => 'menu-texto',
					'linkNovel'=> 'principal/menu', 
					'linkLogoff'=>'principal/logoff', 
					'inputJogador' => $inputJogador,
					'gabarito' => $gabarito,
					'pontuacao' => $pontuacao,
					'abrirModalGabarito' => TRUE,
					'inseriu' => $inseriu,
					'conquista' => $conquista,
					'nomeConquista'=> $nomeConquista,
					'abrirModalHistoria' => $abrirModalHistoria,
					'erro' => FALSE,
					'grafemasTextos' => $grafemasTextos,
					'grafemasJogados' => $grafemasJogados			
					);
				$this->load->view('construtor', $pagina);	
		} else {
			redirect('principal/index');
		}
	}
}