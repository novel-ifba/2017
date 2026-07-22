<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ModelJogador extends CI_Model {

	public function __construct() {
        parent::__construct();
    }

//LOGIN E FUNÇÕES ADMINISTRATIVAS

	public function fazerLogin($login, $senha)
	{	
		
		$senha = md5($senha);
		
		$this->db->select('*');
		$this->db->from('Jogador');
		$this->db->where('login', $login);
		$this->db->where('senha', $senha);
		$this->db->limit(1);
		$query = $this->db->get()->result();

		

		if ($query != NULL){

			//Se a consulta não for vazia, então é preciso setar os atributos de $jogador
			foreach ($query as $q) {
				$dados = array(
					'nome' => $q->nome,
					'avatar' => $q->avatar,
					'codJogador' => $q->codJogador,
					'nivel' => $q->nivel,
					'experiencia' => $q->experiencia,
				 );
				return $dados;
			}
		}else {
			return FALSE;
		} 
	}

	public function cadastrarJogador($dados){
		$nome = $dados['nome'];
		$email = $dados['email'];
		$login = $dados['login'];
		$senha = md5($dados['senha1']);
		$avatar = $dados['avatar'];

		
		$return = FALSE;
		if($dados != NULL){
			$string = array(
			'nome'=>$nome,
			'email'=>$email,
			'login'=>$login,
			'senha'=>$senha,
			'avatar'=>$avatar,			
			);

		$retorno = $this->db->insert('Jogador', $string);
		} 
		return $retorno;	
	}

	public function conquistarEntrada($codJogador){
		$string = array('codConquista'=>1, 'codJogador'=>$codJogador);
		$this->db->insert('ConquistaJogador', $string);
	}


	public function verificarEmailCadastrado($email){
		$this->db->select('codJogador');
		$this->db->from('Jogador');
		$this->db->where('email', $email);
		$retorno = $this->db->get()->result();
		return $retorno;
	}

	public function editarCadastroJogador($dados, $codJogador){
		$mudou = $this->verificarEmailCadastrado($dados['email']);		
		if($mudou == $codJogador){
			$query = array('nome' => $dados['nome'],
				'login'=> $dados['login'],
				'senha'=> md5($dados['senha1']),				
				);			
			$this->db->where('codJogador', $codJogador);
			$retorno = $this->db->update('Jogador', $query);
		} else {
			$query = array('nome' => $dados['nome'],
				'login'=> $dados['login'],
				'senha'=> md5($dados['senha1']),				
				'email' => $dados['email'],
			);
			$this->db->where('codJogador', $codJogador);
			$retorno = $this->db->update('Jogador', $query);	
		}
		return $retorno;
	}


	public function recuperarSenha($email){
		$senhaNova = $this->gerarNovaSenha();
		$sennhaNova = md5($senhaNova);
		$this->db->set('senha', $senhaNova);		
		$this->db->where('email', $email);
		$this->db->update('Jogador');		
		return $senhaNova;
	}

	function gerarNovaSenha(){
        
        $padrao = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        $retorno = "";

        for($i= 0; $i < 8; $i++){            
            $retorno.= $padrao[rand(0, strlen($padrao) - 1)];
        }
        return $retorno;
    }

//MUDANÇA DE NÍVEL E EXPERIÊNCIA


	public function buscarNivelJogador($codJogador){
		$this->db->select('nivel');
		$this->db->from('Jogador');
		$this->db->where('codJogador', $codJogador);
		$retorno = $this->db->get()->result();
		return $retorno;
	}

	public function subirNivelPalavra($codJogador, $pontuacao, $codGrafema){
		$retorno = NULL;						
		if ($pontuacao > 20 ){						
			$jogou = $this->verificarNivelAlcancadoPalavra($codGrafema, $codJogador);				
			$nivel = $this->buscarNivelJogador($codJogador);
			$nivel = $nivel[0]->nivel;				
			if ((!$jogou) || ($jogou[0]->pontuacao < 30)) {				
				$nivel++;				
				$this->db->set('nivel', $nivel);
				$this->db->where('codJogador', $codJogador);
				$this->db->update('Jogador');
				$retorno = TRUE;			
			} else {
				$retorno = FALSE;
			}
		} else {
			$retorno = FALSE;
		}
		return $retorno;
	}

	public function subirNivelTeste($codJogador, $pontuacao, $codigos, $quantidade){
		$retorno = NULL;
		$qtd = count($quantidade) * 18;
		if ($pontuacao > $qtd) {
			for ($i=0; $i < count($codigos); $i++) { 
				$grafemas[] = $this->buscarGrafema($codigos[$i]);
			}
			$jogou = $this->verificarNivelAlcancadoTeste($grafemas, $codJogador);			
			$nivel = $this->buscarNivelJogador($codJogador);
			$nivel = $nivel[0]->nivel;
			if ((!$jogou) || ($jogou[0]->pontuacao < $qtd)) {
				$nivel++;								
				$this->db->set('nivel', $nivel);
				$this->db->where('codJogador', $codJogador);
				$updetou= $this->db->update('Jogador');
				$retorno = TRUE;
			} else {
				$retorno = FALSE;
			} 
		}else {
			$retorno = FALSE;
		}
		return $retorno;
	}

	public function subirNivelTexto($codJogador, $pontuacao, $grafemas){
		$retorno = NULL;		
		if ($pontuacao > 120){
			$jogou = $this->verificarNivelAlcancadoTexto($grafemas, $codJogador);			
			$nivel = $this->buscarNivelJogador($codJogador);
			$nivel = $nivel[0]->nivel;
			if(!$jogou || $jogou[0]->pontuacao < 120){
				$nivel++;				
				$this->db->set('nivel', $nivel);
				$this->db->where('codJogador', $codJogador);
				$this->db->update('Jogador');
				$retorno = TRUE;
			} else {
				$retorno = FALSE;
			}
		} else {
			$retorno = FALSE;
		}
		return $retorno;
	}


	public function verificarNivelAlcancadoPalavra($codGrafema, $codJogador){		
			$this->db->select('MAX(pontuacao) AS pontuacao');
			$this->db->from('Rodada r');
			$this->db->join('RodadaGrafema rg', 'r.codRodada = rg.codRodada');
			$this->db->where('tipoRodada', 'palavra');
			$this->db->where('codJogador', $codJogador);
			$this->db->where('codGrafema', $codGrafema);
			$retorno = $this->db->get()->result();
			return $retorno;
	}

	//Esta função verifica se o jogador já jogou um texto que possui
    //determinados grafemas.
    public function verificarNivelAlcancadoTexto($grafemas, $codJogador){

    	
    	$tiposGrafemas = explode("&", $grafemas);
    	$where = '';
    	$tamanho = count($tiposGrafemas);

    	for($i = 0; $i < $tamanho; $i++) {
    		$where = $where.'g.tipoGrafema = "'. $tiposGrafemas[$i].'"';
    		if ($i < $tamanho -1){
    			$where = $where.' OR ';
    		}
    	} 
          
		$this->db->select('MAX(r.pontuacao) as pontuacao');
		$this->db->from('Rodada r');
		$this->db->join('RodadaGrafema rg', 'r.codRodada = rg.codRodada');
		$this->db->join('Grafema g', 'rg.codGrafema = g.codGrafema');		
		$this->db->where('r.tipoRodada', 'texto');
		$this->db->where('r.codJogador', $codJogador);
		$this->db->where($where);
		$this->db->group_by('r.codRodada');
		$this->db->limit(1);
		$this->db->having('COUNT(g.codGrafema)', $tamanho);

		$resultado = $this->db->get()->result();
        
    	if($resultado){
    		return $resultado;
    	} else {
    		return FALSE;
    	}
    }


	    public function verificarNivelAlcancadoTeste($grafemas, $codJogador){

    	$where = '';
    	$tamanho = count($grafemas);

    	for($i = 0; $i < $tamanho; $i++) {
    		$where = $where.'g.tipoGrafema = "'. $grafemas[$i][0]->tipoGrafema.'"';
    		if ($i < $tamanho -1){
    			$where = $where.' OR ';
    		}
    	} 
          
		$this->db->select('MAX(r.pontuacao) as pontuacao');
		$this->db->from('Rodada as r');
		$this->db->join('RodadaGrafema as rg', 'r.codRodada = rg.codRodada');
		$this->db->join('Grafema as g', 'g.codGrafema = g.codGrafema');		
		$this->db->where('r.tipoRodada', 'teste');
		$this->db->where('codJogador', $codJogador);
		$this->db->where($where);
		$this->db->group_by('r.codRodada');
		$this->db->having('COUNT(g.codGrafema)', $tamanho);
		$this->db->limit(1);
		$resultado = $this->db->get()->result();
        
    	if($resultado){
    		return $resultado;
    	} else {
    		return FALSE;
    	}
    }

	public function subirExperiencia($codJogador, $pontuacao){
		$experienciaAntiga = $this->buscarExperienciaJogador($codJogador);
		$experienciaNova = $experienciaAntiga + $pontuacao;		
		$this->db->set('experiencia', $experienciaNova);
		$this->db->where('codJogador', $codJogador);
		$this->db->update('Jogador');
	}

	//Esta função retorna todas as conquistas que exigem uma experiência
	//menor ou igual a que o jogador possui. Isso garante que só retorne
	//as conquistas que o jogador alcançou
	public function buscarConquistasJogador($codJogador){	
		$experiencia = $this->buscarExperienciaJogador($codJogador);
		$where = "experienciaDesbloqueio <= ".$experiencia;
		$this->db->select('nomeConquista, codConquista');
		$this->db->from('Conquista');
		$this->db->where($where);
		$retorno = $this->db->get()->result();		
		return $retorno;
	}


//ESTATÍSTICAS

	public function buscarGrafemasJogadosPalavra($codJogador){				
		$this->db->select('g.tipoGrafema, MAX(r.pontuacao) as pontuacao');
		$this->db->from('Rodada as r');
		$this->db->join('RodadaGrafema as rg', 'r.codRodada = rg.codRodada');
		$this->db->join('Grafema as g', 'rg.codGrafema = g.codGrafema');
		$this->db->where('r.tipoRodada', 'palavra');
		$this->db->where('codJogador', $codJogador);
		$this->db->group_by('rg.codGrafema');
		$this->db->order_by('rg.codgrafema');
		$retorno = $this->db->get()->result();
		return $retorno;
	}

	public function buscarListaGrafemas(){
		$this->db->select('tipoGrafema');
		$this->db->from('Grafema');
		$this->db->order_by('codgrafema');
		$retorno = $this->db->get()->result();
		return $retorno;
	}

	public function buscarListaTestes(){
		$this->db->select('grafemas');
		$this->db->from('GrupoTeste');
		$this->db->order_by('codGrupo');
		$listaGrupos = $this->db->get()->result();		
		$lista = NULL;
		foreach ($listaGrupos as $key) {
			if (($key->grafemas != "total") && ($key->grafemas != "-total")){
				$grupo = $this->separarGrupo($key->grafemas);				
			} else {
				$grupo = array($key->grafemas);				
			}				
			$grafemas = NULL;
			foreach ($grupo as $j) {				
				if(($j != "total") && ($j != "-total")){					
					$tipoGrafema = $this->buscarGrafema($j);									
					$grafemas[] = $tipoGrafema[0]->tipoGrafema;					
				} else {
					$grafemas[] = $j;
				}
			}

			$lista[] = $this->juntarGrupoGrafemas($grafemas);			
		}		
		return $lista;
	}

	public function buscarGrafema($codigo){
		$this->db->select('tipoGrafema');
		$this->db->from('Grafema');
		$this->db->where('codGrafema', $codigo);
		$retorno = $this->db->get()->result();			
		return $retorno;
	}

	public function separarGrupo($grupo){
		$separados = explode("_", $grupo);
		return $separados;
	}

	public function juntarGrupoGrafemas($grupo){		
		$retorno = "";
		$tamanho = count($grupo);				
		for ($i=0; $i < $tamanho ; $i++) { 
			if ($i != $tamanho -1){
				$retorno = $retorno.$grupo[$i]."&";
			} else {
				$retorno = $retorno.$grupo[$i];
			}
		}		
		return $retorno;
	}

	public function buscarHistoricoPalavra($codJogador){
		$this->db->select('g.tipoGrafema, SUM(r.duracao) as duracao, SUM(r.pontuacao) as pontuacao');
		$this->db->from('Rodada as r');
		$this->db->join('RodadaGrafema as rg', 'r.codRodada = rg.codRodada');
		$this->db->join('Grafema as g', 'rg.codGrafema = g.codGrafema');
		$this->db->where('r.tipoRodada', 'palavra');
		$this->db->where('r.codJogador', $codJogador);
		$this->db->group_by('g.tipoGrafema');
		$retorno = $this->db->get()->result();
		return $retorno;
	}

	public function buscarHistoricoTeste($codJogador){
		$this->db->select('g.tipoGrafema, SUM(r.duracao) as duracao, SUM(r.pontuacao) as pontuacao');
		$this->db->from('Rodada as r');
		$this->db->join('RodadaGrafema as rg', 'r.codRodada = rg.codRodada');
		$this->db->join('Grafema as g', 'rg.codGrafema = g.codGrafema');
		$this->db->where('r.tipoRodada', 'teste');
		$this->db->where('r.codJogador', $codJogador);
		$this->db->group_by('g.tipoGrafema');		
		$retorno = $this->db->get()->result();
		return $retorno;
	}

	public function buscarHistoricoTexto($codJogador){
		$this->db->select('g.tipoGrafema, SUM(r.duracao) as duracao, SUM(r.pontuacao) as pontuacao');
		$this->db->from('Rodada as r');
		$this->db->join('RodadaGrafema as rg', 'r.codRodada = rg.codRodada');
		$this->db->join('Grafema as g', 'rg.codGrafema = g.codGrafema');
		$this->db->where('r.tipoRodada', 'texto');
		$this->db->where('r.codJogador', $codJogador);
		$this->db->group_by('g.tipoGrafema');		
		$retorno = $this->db->get()->result();
		return $retorno;
	}


	public function buscarTempoTotal($codJogador){
		$this->db->select('tempoTotal');
    	$this->db->from('Jogador');    	
    	$this->db->where('codJogador', $codJogador);
    	$retorno = $this->db->get()->result();

    	return $retorno;
	}

	public function buscarExperienciaJogador($codJogador){			
		$this->db->select('experiencia');
		$this->db->from('Jogador');
		$this->db->where('codJogador', $codJogador);
		$this->db->limit(1);
		$retorno = $this->db->get()->result();		
		return $retorno[0]->experiencia;
	}

	public function buscarDadosJogador($codJogador){
		$this->db->select('nome, email, login');
		$this->db->from('Jogador');
		$this->db->where('codJogador', $codJogador);
		$retorno = $this->db->get()->result();
		return $retorno;
	}


	public function buscarGrafemasTexto($codTexto){
		$this->db->select('g.tipoGrafema');
		$this->db->from('Grafema g');
		$this->db->join('GrafemaTexto gt', 'g.codgrafema = gt.codgrafema');
		$this->db->where('gt.codTexto', $codTexto);
		$retorno = $this->db->get()->result();
	}
}


