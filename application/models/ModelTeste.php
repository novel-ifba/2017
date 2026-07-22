<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ModelTeste extends CI_Model {

	public function __construct() {
        parent::__construct();
    }

    //Esta função deve receber o tipo do grafema,
    //buscar as palavras referentes ao grafema,
    //e sortear 5 palavras, que irão compor uma rodada
    public function sortearTestes($grafemas){


        $testesSorteados = array();
        unset($testesSorteados);
        $alternativas = array();
        unset($alternativas);
        $retorno = NULL;

        if (($grafemas == "total") || ($grafemas == "-total")){
            $tiposGrafemas = $this->sortearTodosTestes();
        } else {
            $tiposGrafemas = $this->separarGrafemas($grafemas);  
        }      

        $jogadorApto = $this->verificarJogadorGrafemas($tiposGrafemas);

        if ($jogadorApto){
            $tamanho = count($tiposGrafemas);
            foreach($tiposGrafemas as $key) {
                $codigo = $this->buscarCodigoPeloTipo($key);                
                $codigoGrafema[] = $codigo[0];
                if ($codigo){
                    $codGrafema = $codigo[0]->codGrafema;                           
                    $listaTestes = $this->buscarConjuntoTestes($codGrafema);                      
                    
                    //Se a consulta não for nula
                    if ($listaTestes) {
                        $qtd = 0;
                        $listaCodigos = array();
                        unset($listaCodigos);

                        //Guarda os códigos dos testes em $listaCódigos
                        foreach ($listaTestes as $list) {
                            $listaCodigos[] = $list->codTeste;
                            $qtd++;
                        }                        
                    
                        //Armazena os códigos das palavras e escolhe 2 aleatoreamente           
                        $selecionados = array_rand($listaCodigos, 2);   


                        //Armazena as palavras escolhidas em $testesSorteados
                        for ($i=0; $i < 2; $i++) { 
                            $numero = $listaCodigos[$selecionados[$i]];                            
                            $testesSorteados[] = $this->buscarTestePeloCodigo($numero);
                            $alternativas[] = $this-> buscarAlternativasPeloCodigoTeste($numero);
                        }                                                                                        
                    } else {
                        $retorno = FALSE;
                    }
                } else {
                    $retorno = FALSE;
                }                       
            }
            $retorno[] = $testesSorteados;                         
            $retorno[] = $codigoGrafema;
            $retorno[] = $alternativas; 
        } else {
            $retorno = FALSE;
        }            
        return $retorno;
    }

    public function sortearTodosTestes(){
        $this->db->select('tipoGrafema');
        $this->db->from('Grafema');
        $resultado = $this->db->get()->result();
        $retorno = NULL;
        foreach ($resultado as $key) {
            $retorno[] = $key->tipoGrafema;
        }
        return $retorno;
    }

    public function separarGrafemas($grafemas){
        $tiposGrafemas = explode("&", $grafemas);
        return $tiposGrafemas;
    }

    //Esta função deve receber as respostas escolhidas pelo jogador
    //e o gabarito da rodada. Em seguida, deve verificar se as respostas
    //estão certas. Para cada palavra certa, o jogador recebe 30 pontos
    public function calcularPontuacao($inputJogador, $gabarito, $qtdTestes){
    	$pontuacao = 0;
    	for ($i = 0; $i < $qtdTestes ; $i++){
    		if (strcasecmp($inputJogador[$i], $gabarito[$i]) == 0){
    			$pontuacao = $pontuacao + 30;    		
    		}
    	}
    	return $pontuacao;
    }

    //Esta função recebe os dados de uma rodada e os armazena no banco de dados
    public function inserirRodadaTeste($codigos, $codJogador, $duracao, $pontuacao){

    	if ($codigos != NULL && $codJogador != NULL && $duracao != NULL && $pontuacao != NULL){

            for ($i=0; $i < count($codigos); $i++) { 
                $separados[] = $this->buscarGrafema($codigos[$i]);
            }                    
            
            $dadosRodada = array(                   
                'codJogador' => $codJogador,
                'tipoRodada' => 'teste',
                'duracao' => $duracao,
                'pontuacao' => $pontuacao,
            );                    

            $this->db->insert('rodada', $dadosRodada); 
            $codRodada = $this->db->insert_id();
            
            foreach ($separados as $key) {                
        		$codGrafema = $this->buscarCodigoPeloTipo($key[0]->tipoGrafema);                
                $dadosRodadaGrafema = array('codGrafema'=>$codGrafema[0]->codGrafema, 'codRodada'=> $codRodada);            
                $inseriu = $this->db->insert('RodadaGrafema', $dadosRodadaGrafema);                 
            }
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

    //Busca todos os códigos dos testes referentes ao código do grafema
    public function buscarConjuntoTestes($codGrafema){             
        $this->db->select('codTeste');
        $this->db->from('Teste');
        $this->db->where('codGrafema', $codGrafema);                            
        $listaTestes = $this->db->get()->result(); 
        return $listaTestes;
    }

    //Busca uma palavra utilizando como parâmetro o seu código
    public function buscarTestePeloCodigo($codTeste){
        $this->db->select('*');
        $this->db->from('Teste');
        $this->db->where('codTeste', $codTeste);                            
        $teste = $this->db->get()->result(); 
        return $teste;
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

    public function buscarAlternativasPeloCodigoTeste($codTeste){
        $this->db->select('alternativa');
        $this->db->from('Alternativas');
        $this->db->where('codTeste', $codTeste);        
        $alternativas = $this->db->get()->result();
        return $alternativas;
    }

    //Esta função verifica se o jogador já passou pelos grafemas
    //que são pré-requisitos para um determinado teste.
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

    public function buscarGrafema($codigo){
        $this->db->select('tipoGrafema');
        $this->db->from('Grafema');
        $this->db->where('codGrafema', $codigo);
        $retorno = $this->db->get()->result();          
        return $retorno;
    }

    public function buscarGrafemasJogadosTeste($codJogador){        
        $this->db->select('codRodada, pontuacao');
        $this->db->from('Rodada');
        $this->db->where('codJogador', $codJogador);
        $this->db->where('tipoRodada', 'teste');    
        $listaCodigos = $this->db->get()->result();    
        $juntos = NULL; 
        $pontuacao = NULL;     

        foreach ($listaCodigos as $key) {    
            $grafemas = $this->buscarGrafemasPeloCodigoRodada($key->codRodada);               
            $qtd = count($grafemas) * 2;             
            if ($qtd != 0){
                $passou = $this->verificarPontuacaoSuperiorTeste($key->pontuacao, $qtd);      
            } else {
                $passou = FALSE;
            }                

            if($passou){                                            
                $juntos[] = $this->juntarGrafemas($grafemas);            
            }            
        }

        $unicos = $this->coletarGrafemasUnicosTeste($juntos);              
        return $unicos;   
    }

    public function buscarGrafemasPeloCodigoRodada($codRodada){
        $this->db->select('g.tipoGrafema');
        $this->db->from('RodadaGrafema rg');
        $this->db->join('Grafema g', 'g.codGrafema = rg.codGrafema');
        $this->db->where('rg.codRodada', $codRodada);
        $this->db->order_by('g.codgrafema');
        $retorno = $this->db->get()->result();
        return $retorno;
    }

    public function verificarPontuacaoSuperiorTeste($pontuacao, $qtd){
        $retorno = FALSE;
        $media = $qtd*18;//18 é 0,6*30 (60% de 30 pontos)  
        if ($pontuacao > $media){
                $retorno = TRUE;
        }
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

    public function coletarGrafemasUnicosTeste($listaGrafemas){
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
