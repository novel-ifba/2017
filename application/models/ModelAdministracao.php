<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class modelAdministracao extends CI_Model {

	public function __construct() {
        parent::__construct();
    }


	public function fazerLogin($login, $senha)
	{
		
		$this->db->select('*');
		$this->db->from('Administrador');
		$this->db->where('login', $login);
		$this->db->limit(1);
		$query = $this->db->get()->result();

		// A senha era comparada em texto puro dentro do WHERE. Agora confere o
		// hash em PHP; linhas antigas sao regravadas no primeiro login.
		if ($query != NULL && ! $this->conferirSenha($senha, $query[0])) {
			$query = NULL;
		}

		if ($query != NULL){

			//Se a consulta não for vazia, então é preciso setar os atributos de $jogador
			foreach ($query as $q) {
				$dados = array(
					'nome' => $q->nome,
					'codAdministrador' => $q->codAdministrador,
				 );
				return $dados;
			}
		}else {
			return FALSE;
		} 
	}

	private function conferirSenha($senha, $admin)
	{
		if (password_verify($senha, $admin->senha)) {
			return TRUE;
		}

		if ($senha === $admin->senha OR md5($senha) === $admin->senha) {
			$this->db->where('codAdministrador', $admin->codAdministrador);
			$this->db->update('Administrador', array(
				'senha' => password_hash($senha, PASSWORD_DEFAULT),
			));
			return TRUE;
		}

		return FALSE;
	}
	
}
