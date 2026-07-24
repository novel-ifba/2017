<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class modelTexto extends CI_Model {

	public function __construct() {
        parent::__construct();
    }


    //Esta função deve receber o tipo do grafema,
    //buscar os textos referentes ao grafema,
    //e sortear 1 texto, que irão compor uma rodada
    public function sortearTexto($grafemas){

    	$tiposGrafemas = $this->separarGrafemas($grafemas);
        

        if(($tiposGrafemas[0] == "total") || ($tiposGrafemas[0] == "_total")){
            $tiposGrafemas = array("g_j", "ch_x", "s_z_x", "c_ç_s_ss_sc_sç_xc", "m_n", "r_rr", "e_i", "o_u_l");
        }        

    	$jogadorApto = $this->verificarJogadorGrafemas($tiposGrafemas);

    	if ($jogadorApto){

	    	$textos = $this->buscarCodigoTextoPelosGrafemas($tiposGrafemas);
            

			//Se a consulta não for nula
			if ($textos) {

		    	$listaCodigos = array();
		    	unset($listaCodigos);

                $tamanho = count($tiposGrafemas);                
                //Guarda os códigos dos textos em $listaCódigos
				foreach ($textos as $lista) {
                    $resposta = $this->garimparTextos($lista->codTexto, $tamanho);                    
                    if($resposta){
                        $listaCodigos[] = $lista->codTexto;
                    }					
				}                
	    		
	            //Armazena os códigos dos textos e escolhe 1 aleatoreamente			
	            $selecionados = array_rand($listaCodigos, 1);            

	            //Armazena o código sorteado em $numero e pesquisa pelo 
	            //texto cujo código é igual a $numero

	            $numero = $listaCodigos[$selecionados];
	            $this->db->select('*');
	            $this->db->from('Texto');
	            $this->db->where('codTexto', $numero);                            
	            $textoSorteado = $this->db->get()->result();              	              
				return $textoSorteado;
	    	} else {
	    		return FALSE;
	    	}
	    }  else {
	    	return FALSE;
	    }         	    	        
    }

    public function buscarCodigoTextoPelosGrafemas($tiposGrafemas){
        $where = '';
        $tamanho = count($tiposGrafemas);

        for($i = 0; $i < $tamanho; $i++) {
            $where = $where.'g.tipoGrafema = "'. $tiposGrafemas[$i].'"';
            if ($i < $tamanho -1){
                $where = $where.' OR ';
            }
        }               

        $this->db->select('gt.codTexto');
        $this->db->from('GrafemaTexto as gt');
        $this->db->join('Grafema as g', 'gt.codGrafema = g.codGrafema');
        $this->db->where($where);
        $this->db->group_by('gt.codTexto');
        $this->db->having('COUNT(gt.codGrafema)', $tamanho);
        $retorno = $this->db->get()->result();
        return $retorno;
    }

    //Essa funcao garante que somente farao parte do sorteio
    //os textos que possuem somente os grafemas correspondentes
    //ja que a consulta pode trazer textos a mais
    public function garimparTextos($texto, $tamanho){        
            $this->db->select("codTexto");
            $this->db->from("GrafemaTexto");
            $this->db->where("codTexto", $texto);
            $this->db->group_by("codTexto");
            $this->db->having("count(codGrafema)", $tamanho);
            $retorno = $this->db->get()->result();
        return $retorno;
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

    //Esta função verifica se o jogador já passou pelos grafemas
    //que são pré-requisitos para um determinado texto.
    //Os parâmetros de entrada são os grafemas pertencentes ao texto
    public function verificarJogadorGrafemas($tiposGrafemas){

    	$where = '';
    	$tamanho = count($tiposGrafemas);

    	for($i = 0; $i < $tamanho; $i++) {
    		$where = $where.'tipoGrafema = "'. $tiposGrafemas[$i].'"';
    		if ($i < $tamanho -1){
    			$where = $where.' OR ';
    		}
    	} 
    	
    	$codJogador = $this->session->userdata('codJogador');

    	$this->db->select('COUNT(DISTINCT rg.codGrafema) as qtd');
    	$this->db->from('Rodada as r');
        $this->db->join('RodadaGrafema rg', 'r.codRodada = rg.codRodada');
    	$this->db->join('Grafema as g', 'rg.codGrafema = g.codGrafema');
    	$this->db->where('r.tipoRodada = "palavra"');
    	$this->db->where($where);
    	$this->db->where('codJogador', $codJogador);
    	$this->db->limit(1);
    	$resultado = $this->db->get()->result();        
        
    	if($resultado[0]->qtd == $tamanho){
    		return TRUE;
    	} else {
    		return FALSE;
    	}
    }

    //Esta função recebe um codTexto e encontra seu gabarito
    public function encontrarGabarito($codTexto){
    	$this->db->select('letraGabarito, posicao');
    	$this->db->from('Gabarito');
    	$this->db->where('codTexto', $codTexto);
    	$gabarito = $this->db->get()->result();
    	return $gabarito;
    }

    //Esta função deve receber as respostas digitadas pelo jogador
    //e o gabarito da rodada. Em seguida, deve verificar se as respostas
    //estão certas. Para cada palavra certa, o jogador recebe 10 pontos
    public function calcularPontuacao($inputJogador, $gabarito){    	
    	$pontuacao = 0;
    	$tamanho = count($gabarito);
    	for ($i = 0; $i < $tamanho ; $i++){
    		$posicao = $gabarito[$i]->posicao;
    		$letraGabarito = $gabarito[$i]->letraGabarito;
    		if ((strcasecmp($inputJogador[$i], $letraGabarito) == 0) && ($posicao == $i +1)){
    			$pontuacao = $pontuacao + 20;    		
    		}
    	}  
        return $pontuacao;  	    	
    }

    public function inserirRodadaTexto($grafemas, $codJogador, $duracao, $pontuacao, $codTexto){
    	if ($grafemas != NULL && $codJogador != NULL && $duracao != NULL && $pontuacao != NULL){
    		    	    		
    					
			$dadosRodada = array(
			'codJogador' => $codJogador,
			'tipoRodada' => 'texto',
			'duracao' => $duracao,
			'pontuacao' => $pontuacao,
			);

            $this->db->insert('Rodada', $dadosRodada);

            $codRodada = $this->db->insert_id();            
            $grafemaSeparado = $this->separarGrafemas($grafemas);
    		                                
            foreach ($grafemaSeparado as $gr => $valor) {
                $codGrafema = $this->encontrarCodigoGrafema($valor);

                $dadosRodadaGrafema = array('codGrafema' => $codGrafema[0]->codGrafema, 'codRodada'=>$codRodada); 
                $inseriu = $this->db->insert('RodadaGrafema', $dadosRodadaGrafema);                  
    		} 
            $this->adicionarTempo($codJogador, $duracao);
            return TRUE;         
    	} else{
            return FALSE;
        }
    }


    public function separarGrafemas($grafemas){
    	$tiposGrafemas = explode("&", $grafemas);
    	return $tiposGrafemas;
    }

    public function encontrarCodigoGrafema($grafema){
    	$this->db->select('codGrafema');
    	$this->db->from('Grafema');
    	$this->db->where('tipoGrafema', $grafema);
    	$codGrafema = $this->db->get()->result();
    	return $codGrafema;
    }    

    public function buscarListaGrafemasTexto(){        
        $retorno = NULL;
        $codigos = $this->buscarListaCodigosTexto();         
        foreach ($codigos as $key) { 
           $separados = $this->buscarGrafemaPeloCodigoTexto($key->codTexto);           
           $retorno[] = $this->juntarGrafemas($separados);                   
        }        
        return $retorno;
    }

    public function buscarGrafemasJogadosTexto($codJogador){
        $this->db->select('codRodada, pontuacao');
        $this->db->from('Rodada');
        $this->db->where('codJogador', $codJogador);
        $this->db->where('tipoRodada', 'texto');
        $listaCodigos = $this->db->get()->result();   

        $juntos = NULL;      
        $pontuacao = NULL;
        foreach ($listaCodigos as $key) {          
            $grafemas = $this->buscarGrafemasPeloCodigoRodada($key->codRodada);           
            $passou = $this->verificarPontuacaoSuperiorTexto($key->pontuacao);                                                     
            if($passou){                                            
                $juntos[] = $this->juntarGrafemas($grafemas);                
            }    
        }        
        $unicos = $this->coletarGrafemasUnicosTexto($juntos);

        
        return $unicos;        
    }

    public function buscarGrafemasPeloCodigoRodada($codRodada){
        $this->db->select('g.tipoGrafema');
        $this->db->from('RodadaGrafema rg');
        $this->db->join('Grafema g', 'g.codGrafema = rg.codGrafema');
        $this->db->join('Rodada r', 'r.codRodada = rg.codRodada');
        $this->db->where('r.codRodada', $codRodada);
        $this->db->order_by('g.codGrafema');
        $retorno = $this->db->get()->result();
        return $retorno;
    }

    public function verificarPontuacaoSuperiorTexto($pontuacao){
        $retorno = FALSE;        
        if($pontuacao > 120){
            $retorno = TRUE;            
        }
        return $retorno;
    } 

    public function buscarGrafemaPeloCodigoTexto($codTexto){        
        $this->db->select('g.tipoGrafema');
        $this->db->from('Grafema g');
        $this->db->join('GrafemaTexto gt', 'g.codGrafema = gt.codGrafema');        
        $this->db->where('gt.codTexto', $codTexto);
        $this->db->order_by('g.codGrafema', 'ASC');
        $retorno= $this->db->get()->result();

        return $retorno;
    }

    public function buscarListaCodigosTexto(){
        $this->db->select('codTexto');
        $this->db->from('Texto');
        $retorno = $this->db->get()->result();
        return $retorno;
    }

    public function juntarGrafemas($separados){
        $retorno = NULL;
        $tamanho = count($separados);
        for ($i=0; $i < $tamanho; $i++) { 
            if($i == 0){
                $retorno = $retorno.$separados[$i]->tipoGrafema; 
            } else{
                $retorno = $retorno.'&'.$separados[$i]->tipoGrafema; 
            }
        }        

        return $retorno;
    }  

    public function coletarGrafemasUnicosTexto($listaGrafemas){
        $retorno = array();        
        $repetidos = FALSE;
        if($listaGrafemas){
            $listaGrafemas = array_filter($listaGrafemas);
            $tamListaGrafemas = count($listaGrafemas);
            $qtd = array_count_values($listaGrafemas);        
        
            if(in_array("g_j&ch_x&s_z_x&c_ç_s_ss_sc_sç_xc&m_n&r_rr&e_i&o_u_l", $listaGrafemas)){
                if ($qtd["g_j&ch_x&s_z_x&c_ç_s_ss_sc_sç_xc&m_n&r_rr&e_i&o_u_l"] > 1){
                    $repetidos = TRUE;
                }
            }          

            foreach ($listaGrafemas as $key) {
                if(!in_array($key, $retorno)){
                    $retorno[] = $key;
                }  
            }
        }             

        if($repetidos){
           $retorno[] = "g_j&ch_x&s_z_x&c_ç_s_ss_sc_sç_xc&m_n&r_rr&e_i&o_u_l";
        }
        return $retorno;
    }

}
