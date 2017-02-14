<?php
/**
* 
*/
class Email extends \HXPHP\System\Model
{
	static $belongs_to = array(
		array('usuario'),
		array('chave')
		);
	public static function verificarEmail($email){
			$user_exist = self::find_by_email($email);
			if(empty($user_exist)){
				return true;
			}else{
				return false;
		}
	}
	public static function cadastrarEmail($email3){
			$att = ['email' => $email3];
			$email = new Email($att);
			$email->save();
			$id = self::find_by_email($email3);
			return $id;
	}
	public static function pesquisarEmail($email2){
		$email = self::find_by_email($email2);
		if(!empty($email)){
			return $email;
		}else{
			$email = Email::cadastrarEmail($email2);
			return $email;
		}
	}
}