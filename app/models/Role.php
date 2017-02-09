<?php 
class Role extends \HXPHP\System\Model{
	static $has_many = array(
		array('users')
		);

	public static function getRoles(){
		return self::find(3,4,5);
	}

}