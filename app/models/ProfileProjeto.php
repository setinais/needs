<?php
/**
* 
*/
class TiposProjeto extends \HXPHP\System\Model
{
	static $belongs_to = [
		['profile'],
		['projeto']
	];	
	static $has_many = [
		['tipos']
	];
}