<?php
/**
* 
*/
class Email extends \HXPHP\System\Model
{
	static $belongs_to = array(
		array('usuario')
		//array('chave')
		);
	public static function verificarEmail($email){
			$user_exist = self::find_by_email($email);
			if(empty($user_exist)){
				return true;
			}else{
				return false;
		}
	}
	public static function cadastrarEmail($email){
			$att = ['email' => $email];
			$email = new Email($att);
			$email->save();
			$id = self::last();
			return $id;
	}
	public static function pesquisarEmail($email){
		$email = self::find_by_email($email);
		if(!empty($email)){
			return $email;
		}
	}
}