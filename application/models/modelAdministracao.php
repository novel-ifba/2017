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
		$this->db->where('senha', $senha);
		$this->db->limit(1);
		$query = $this->db->get()->result();

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
	
}
