<?php
	class Estado extends \HXPHP\System\Model{

		/**
		 * Configuração para a associação entre tabelas
		 * @var array
		 */
		static $has_many = array(
		 	array('usuarios')
		);

		/**
		 * Método responsável por retornar um array com as IDs e siglas dos estados
		 * @return array Array tratado para o PFBC
		 */
		public static function getSelectStates(){
			$all=self::all();
			return $all;
		}
	}