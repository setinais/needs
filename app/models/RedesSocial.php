<?php
/**
* 
*/
class RedesSocial extends HXPHP\System\Model
{
	
	static $has_many = [
		['usuarios']
		];

	public static function verificarGoogle($id){
		$verificar_exixts_facebook = self::find_by_id_facebook($ids);
		if(empty($verificar_exixts_google)){
			return true;
		}else{
			return false;
		}
		
	}
	public static function verificarFacebook($id){
		$verificar_exixts_facebook = self::find_by_id_google($ids);
		if(empty($verificar_exixts_facebook)){
			return true;
		}else{
			return false;
		}
	}
	public static function cadastroRedeSocial($ids){
		$redes_sociais = new RedesSocial($ids);
		if($redes_sociais->save()){$id = self::last(); return $id->id;}else{return false;}
	}
}