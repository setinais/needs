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
	 /*static $validates_format_of = array(
 4     array('email', 'with' =>
 5       '/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/')
 8   );*/

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