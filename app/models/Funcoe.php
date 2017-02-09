<?php 
class Funcoe extends \HXPHP\System\Model{
	static $has_many = array(
		array('usuarios')
		);

	public static function getRoles(){
		return self::find(3,4,5);
	}

}