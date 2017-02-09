<?php
	class LostPassword extends \HXPHP\System\Model{
		
		/**
		 * Configuração para a associação entre tabelas
		 * @var array
		 */
		static $belongs_to = array(
			array('user')
		);
	}