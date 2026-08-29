<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class modelPalavra extends CI_Model {

	public function __construct() {
        parent::__construct();
    }

    //Esta função deve receber o tipo do grafema,
    //buscar as palavras referentes ao grafema,
    //e sortear 5 palavras, que irão compor uma rodada
    public function sortearPalavras($tipoGrafema){

        $retorno = NULL;
        
            	
    	$codigo = $this->buscarCodigoPeloTipo($tipoGrafema);

        if ($codigo){
            $codGrafema = $codigo[0]->codGrafema;                           
            $listaPalavras = $this->buscarConjuntoPalavras($codGrafema);  
            
            //Se a consulta não for nula
            if ($listaPalavras) {
                $qtd = 0;
                $listaCodigos = array();
                unset($listaCodigos);

                //Guarda os códigos das palavras em $listaCódigos
                foreach ($listaPalavras as $list) {
                    $listaCodigos[] = $list->codPalavra;
                    $qtd++;
                }
            
                //Armazena os códigos das palavras e escolhe 5 aleatoreamente           
                $selecionados = array_rand($listaCodigos, 5);            
                $palavrasSorteadas = array();
                unset($palavrasSorteadas);

                //Armazena as palavras escolhidas em $palavrasSorteadas
                for ($i=0; $i < 5; $i++) { 
                    $numero = $listaCodigos[$selecionados[$i]];                            
                    $palavrasSorteadas[] = $this->buscarPalavraPeloCodigo($numero);                                        
                }
                


                $retorno[] = $palavrasSorteadas;
                $retorno[] = $codGrafema;
                return $retorno;
            } else {
                $retorno = FALSE;
            }
        } else {
            $retorno = FALSE;
        }		         	    

        return $retorno;
    }

    //Esta função deve receber as respostas digitadas pelo jogador
    //e o gabarito da rodada. Em seguida, deve verificar se as respostas
    //estão certas. Para cada palavra certa, o jogador recebe 10 pontos
    public function calcularPontuacao($inputJogador, $gabarito){
    	$pontuacao = 0;
    	for ($i = 0; $i < 5 ; $i++){
    		if (strcasecmp($inputJogador[$i], $gabarito[$i]) == 0){
    			$pontuacao = $pontuacao + 10;    		
    		}
    	}
    	return $pontuacao;
    }

    //Esta função recebe os dados de uma rodada e os armazena no banco de dados
    public function inserirRodadaPalavra($codGrafema, $codJogador, $duracao, $pontuacao){

    	if ($codGrafema != NULL && $codJogador != NULL && $duracao != NULL && $pontuacao != NULL){    		
            $dados = array(    			
    			'codJogador' => $codJogador,
    			'tipoRodada' => 'palavra',
    			'duracao' => $duracao,
    			'pontuacao' => $pontuacao,
    			);            

    		$this->db->insert('Rodada', $dados);
            $codRodada = $this->db->insert_id();
            $dadosRodadaGrafema = array('codGrafema'=>$codGrafema, 'codRodada'=>$codRodada); 
            $this->db->insert('RodadaGrafema', $dadosRodadaGrafema);  


            $this->adicionarTempo($codJogador, $duracao);
            return TRUE;
    	} else{
            return FALSE;
        }
    }

    public function adicionarTempo($codJogador, $tempo){
        $this->db->select('tempoTotal');
        $this->db->from('Jogador');
        $this->db->where('codJogador', $codJogador);
        
        $tempoAntigo = $this->db->get()->result();
        $tempoNovo = $tempoAntigo[0]->tempoTotal + $tempo;
        
        $this->db->set('tempoTotal', $tempoNovo);        
        $this->db->where('codJogador', $codJogador);
        $this->db->update('Jogador');
    }

    //Busca todos os códigos das palavras referentes ao código do grafema
    public function buscarConjuntoPalavras($codGrafema){             
        $this->db->select('codPalavra');
        $this->db->from('Palavra');
        $this->db->where('codGrafema', $codGrafema);                            
        $listaPalavras = $this->db->get()->result(); 
        return $listaPalavras;
    }

    //Busca uma palavra utilizando como parâmetro o seu código
    public function buscarPalavraPeloCodigo($codPalavra){
        $this->db->select('*');
        $this->db->from('Palavra');
        $this->db->where('codPalavra', $codPalavra);                            
        $palavra = $this->db->get()->result(); 
        return $palavra;
    }

    //Busca do código do grafema pelo tipo do Grafema
    public function buscarCodigoPeloTipo($tipoGrafema){
        $this->db->select('codGrafema');
        $this->db->from('Grafema');
        $this->db->where('tipoGrafema', $tipoGrafema);
        $this->db->limit(1);
        $codigo = $this->db->get()->result();
        return $codigo;
    }

    public function buscarRegraPeloTipo($tipoGrafema){
        $this->db->select('quadros');
        $this->db->from('Grafema');
        $this->db->where('tipoGrafema', $tipoGrafema);
        $this->db->limit(1);
        $grafema = $this->db->get()->result();
        return $grafema;
    }
}
