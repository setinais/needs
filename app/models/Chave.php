<?php
/**
* 
*/
class Chave extends \HXPHP\System\Model{
	
	static $belongs_to = [
		['email']
	];

    Static $validates_presence_of = [
    	[
    		'chave',
    		'message' => 'Erro de localização de time and date do servidor! Contate o Administrador do Sistema.  <strong>vynny.cg@gmail.com</strong>'
    	],
    	[
    		'email_id',
    		'message' => 'Email é um campo obrigatorio.'
    	]
    ];
	public static function cadastrarChave($email){
		Chave::atualizandoChaves();
		if(!empty($email)){
			$cadastrarChave = new \stdClass;
			$cadastrarChave->errors = [];
			$cadastrarChave->status = false;
			$cadastrarChave->chave = null;

			$data_atual = new DateTime();
			$data_solicitacao = $data_atual->format('Y-m-d H:i:s');
			$data_atual->add(new DateInterval("P2D"));
			$data_valida_ate = $data_atual->format('Y-m-d H:i:s'); //273591
			$status = "Liberada";

			$email_id = Email::pesquisarEmail($email);
			
			$token = Chave::newToken();
			(array) $att = [];
			$att =array_merge($att,['chave' => $token,'datatime' => $data_solicitacao,'validade' => $data_valida_ate,'status' => $status,'email_id' => $email_id->id]);
			
			if(empty(self::find_by_email_id($email_id->id))){
				$cad = self::create($att);
				if($cad->is_valid()){
					$cadastrarChave->status = true;
					$cadastrarChave->chave = $cad;
					return $cadastrarChave;
				}
			}else{
				$chave = self::find_by_email_id($email_id->id);
				$chave->chave = $att['chave'];
				$chave->datatime = $att['datatime'];
				$chave->validade = $att['validade'];
				$chave->status = $att['status'];
				$cad = $chave->save();
				if($cad){

					$cadastrarChave->status = true;
					$cadastrarChave->chave = self::find_by_email_id($email_id->id);
					return $cadastrarChave;
				}
			}
			$errors = $cad->errors->get_raw_errors();
			foreach ($errors as $campo => $message) {
				array_push($cadastrarChave->errors, $message[0]);
			}
			return $cadastrarChave;
		}
	}
	public static function getChave($token){
		Chave::atualizandoChaves();
		$select_token = self::find_by_chave($token);
		return $select_token;
	}
	public static function validarChave($token){
		if($token->status == "Liberada"){
			return true;
		}else{
			return false;
		}
	}
	public static function bloqueioDeUso($token){
		$Chave = self::find_by_token($token);
		$Chave->status = "Usada";
		$Chave->save();
	}
	private static function atualizandoChaves(){
		$diferenca = new DateTime();
		$tokens = self::all();
		foreach ($tokens as $value) {		
			$validade = new DateTime($value->validade);
			$diferenca_data = $validade->diff($diferenca);
			if($diferenca_data->invert == 0){
				$Chave = self::find($value->id);
				$Chave->status = "Bloqueada";
				$Chave->save();
			}
		}
	}
	public static function newToken(){
        return $token = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
    }
}