<?php
	class SenhaPerdida extends \HXPHP\System\Model{
		
		/**
		 * Configuração para a associação entre tabelas
		 * @var array
		 */
		static $belongs_to = array(
			array('usuario')
		);

		public static function validar($user_email)
		{
			$callback = new \stdClass;
			$callback->user = null;
			$callback->code = null;
			$callback->status = false;
			$email_exixt = Email::find_by_email($user_email);
			if(!is_null($email_exixt))
			{	
				$user_exist = Usuario::find_by_email_id($email_exixt->id);
				if(!is_null($user_exist))
				{
					$callback->status = true;
					$callback->user = $user_exist;

					self::delete_all(['conditions' => ['usuario_id = ?', $user_exist->id]]);

				}
				else
				{
					$callback->code = 'nenhum-usuario-encontrado';
				}
			}
			else
			{
				$callback->code = 'nenhum-usuario-encontrado';
			}
			
			return $callback;
		}
	}