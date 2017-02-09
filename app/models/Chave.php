<?php
/**
* 
*/
class Chave extends \HXPHP\System\Model{
	
	static $belongs_to = [
		['email']
	];
	public static function cadastrarSolicitcaoToken($email,$token){
		Chave::atualizandoChaves();
		$data_atual = new DateTime();
		$data_solicitacao = $data_atual->format('Y-m-d H:i:s');
		$data_atual->add(new DateInterval("P2D"));
		$data_valida_ate = $data_atual->format('Y-m-d H:i:s'); //273591
		$status = "Liberada";
		$email_id = Email::cadastrarEmail($email);
		
		$attributes =    ['token' => $token,'datatime' => $data_solicitacao,'validade' => $data_valida_ate,'status' => $status,'email_id' => $email_id->id];
			$Token = new Chave($attributes);
			if($Token->save()){ return true;}else{ return false; }
	}
	public static function getChave($token){
		Chave::atualizandoChaves();
		$select_token = self::find_by_token($token);
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
}