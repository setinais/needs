<?php
	class LoginAttempt extends \HXPHP\System\Model{
		
		/**
		 * Configuração para a associação entre tabelas
		 * @var array
		 */
		static $belongs_to = array(
			array('user')
		);

		/**
		 * Número de tentativas registradas
		 * @var integer
		 */
		public static $attempts;
		
		/**
		 * Retorna a quantidade de tentativas de login mal-sucedidas de determinado usuário
		 * @param integer $user_id ID do usuário
		 */
		public static function CountTentativa($user_id){
			$now = time();
			$valid_attempts = $now - (2 * 60 * 60);
			if(User::find($user_id)->role == 'administrator'){
				$attempts=self::find('all',array('conditions'=>array('user_id = ? AND IP = ? AND time > ?',$user_id,$_SERVER['REMOTE_ADDR'],$valid_attempts)));
			}
			else{
				$attempts=self::find('all',array('conditions'=>array('user_id = ? AND time > ?',$user_id,$valid_attempts)));
			}
			self::$attempts=count($attempts);
			return (int) self::$attempts;
		}

		/**
		 * Verifica se o número de tentativas excedeu o limite de 5
		 * @param integer $user_id ID do usuário
		 */
		public static function CheckTentativa($user_id) {
			self::CountTentativa($user_id);
			return (bool) ((self::$attempts > 5) ? true : false);
		}

		/**
		 * Registra uma tentativa de login mal-sucedida para determinado usuário
		 * @param integer $user_id ID do usuário
		 */
		public static function ArmazenarTentativa($user_id){
			return (bool) self::create(array('user_id'=>$user_id,'IP'=>$_SERVER['REMOTE_ADDR']));
		}

		/**
		 * Exclui todas as tentativas após login bem sucedido, caso o usuário não seja bloqueado
		 * @param integer $user_id ID do usuário
		 */
		public static function LimparTentativas($user_id){
			self::delete_all(array('conditions' => array('user_id = ?', $user_id)));
		}
	}