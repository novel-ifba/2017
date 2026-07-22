<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ModelHistoria extends CI_Model {

	public function __construct() {
        parent::__construct();
    }

    public function buscarCenaPeloNivel($nivel){
    	$this->db->select('nomeCena, quadros');
    	$this->db->from('Cenas');
    	$this->db->where('nivelDesbloqueio', $nivel);
    	$this->db->limit(1);
    	$retorno = $this->db->get()->result();
    	return $retorno;
    }

    public function buscarNovaConquista($experiencia, $codJogador){        
        $ultimaConquista = $this->buscarUltimaConquistaJogador($codJogador);        
        $novaConquista = $ultimaConquista[0]->codConquista + 1;        
        $experienciaNecessaria = $this->buscarExperienciaNecessaria($novaConquista);         
        if ($experienciaNecessaria){
            if ($experiencia >= $experienciaNecessaria[0]->experienciaDesbloqueio){
                $string = array('codConquista'=> $novaConquista, 'codJogador'=>$codJogador);
                $this->db->insert('ConquistaJogador', $string);            
                return $novaConquista;
            } else {
                return 0;
            } 
        } else {
            return 0;
        }
               
    }

    public function buscarUltimaConquistaJogador($codJogador){
        $this->db->select('MAX(codConquista) as codConquista');
        $this->db->from('ConquistaJogador');
        $this->db->where('codJogador', $codJogador);
        $retorno = $this->db->get()->result();
        return $retorno;
    }

    public function buscarExperienciaNecessaria($codConquista){
        $this->db->select('experienciaDesbloqueio');
        $this->db->from('Conquista');
        $this->db->where('codConquista', $codConquista);
        $retorno = $this->db->get()->result();
        return $retorno;
    }

    public function buscarNomeConquista($codConquista){
        $this->db->select('nomeConquista');
        $this->db->from('Conquista');
        $this->db->where('codConquista', $codConquista);
        $retorno = $this->db->get()->result();
        return $retorno;
    }
	
	public function buscarBonus($experiencia, $codJogador){
        $retorno = FALSE;
        $this->db->select('MAX(codBonus) as codBonus');
        $this->db->from('Bonus');
        $this->db->where('experienciaNecessaria <=', $experiencia);
        $codBonus = $this->db->get()->result();

        $jogou = $this->verificarJogadorBonus($codJogador, $codBonus[0]->codBonus);        

        if(!$jogou){
            $retorno[] = $this->buscarTextoBonus($codBonus[0]->codBonus);
            $retorno[] = $this->buscarPalavraBonus($codBonus[0]->codBonus);
            $retorno[] = $codBonus;
        }

        return $retorno;
    }

    public function buscarTextoBonus($codBonus){
        $this->db->select('textoBonus');
        $this->db->from('Bonus');
        $this->db->where('codBonus', $codBonus);
        $retorno = $this->db->get()->result();        
        return $retorno;
    }

    public function buscarPalavraBonus($codBonus){
        $this->db->select('palavra, inicio, fim');
        $this->db->from('PalavraBonus');
        $this->db->where('codBonus', $codBonus);
        $retorno = $this->db->get()->result();

        return $retorno;
    }

    public function verificarJogadorBonus($codJogador, $codBonus){
        $this->db->select('codBonus');
        $this->db->from('JogadorBonus');
        $this->db->where('codJogador', $codJogador);
        $this->db->where('codBonus', $codBonus);
        $retorno = $this->db->get()->result();        
        return $retorno;
    }

    public function corrigirBonus($dados){
        $codBonus = $dados['codBonus'];            
        unset($dados['codBonus']);
        $nivel = $dados['nivel'];
        unset($dados['nivel']);
        $palavras = $this->buscarPalavraBonus($codBonus);
        $posicoes = $this->separarPosicoesBonus($palavras);
        $cont = 0;
        foreach ($dados as $key) {
            $acertou = array_search($key, $posicoes);
            if($acertou){
                $cont++;
                $posicoesCertas[] = $key;
            }
        }  
        $palavrasCertas = NULL;        
        $pontuacao = $this->calcularPontuacaoBonus($cont);
        if ($pontuacao > 0){
            $palavrasCertas = $this->buscarPalavraInicioFim($codBonus, $posicoesCertas);            
        } 

        $this->inserirRodadaBonus($codBonus, $pontuacao);

        $retorno[] = $nivel;
        $retorno[] = $pontuacao;
        $retorno[] = $palavrasCertas;
        return $retorno;
    }   

    public function inserirRodadaBonus($codBonus, $pontuacao){
        $codJogador = $this->session->userdata('codJogador');
        $experiencia = $this->buscarExperienciaJogador() +  $pontuacao;
        
        $string = array('codJogador'=>$codJogador, 'codBonus'=>$codBonus);
        $this->db->insert('JogadorBonus', $string);
        
        $string = array('experiencia'=>$experiencia); 
        $this->db->where('codJogador', $codJogador);
        $this->db->update('Jogador', $string);
    }

    public function buscarExperienciaJogador(){
        $codJogador = $this->session->userdata('codJogador');
        $this->db->select('experiencia');
        $this->db->from('Jogador');
        $this->db->where('codJogador', $codJogador);
        $retorno = $this->db->get()->result();
        return $retorno[0]->experiencia;
    }

    public function separarPosicoesBonus($palavras){
        $retorno = NULL;
        foreach ($palavras as $key) {
            $retorno[] = $key->inicio;
            $retorno[] = $key->fim;
        }
        return $retorno;
    }

    public function calcularPontuacaoBonus($cont){
        if($cont%2 != 0){
            $cont--;
        }
        return ($cont/2)*50;
    }

    public function buscarPalavraInicioFim($codBonus, $posicoes){
        $retorno = array();
        foreach ($posicoes as $pos) {
            $this->db->select('palavra');
            $this->db->from('palavrabonus');
            $this->db->where('codBonus', $codBonus);
            $this->db->where('inicio', $pos);
            $this->db->or_where('fim', $pos);            
            $palavra = $this->db->get()->result();
            $se = array_search($palavra[0]->palavra, $retorno);
            if($se == FALSE){
                $retorno[] = $palavra[0]->palavra;
            }
        }
        if(!empty($retorno)){
            unset($retorno[0]);
        }        
        return $retorno;
    }
}
