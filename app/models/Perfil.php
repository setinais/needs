<?php
/**
* 
*/
class Perfil extends HXPHP\System\Model
{
	static $belongs_to = [
		['user']
	];
	static $has_many = [
		['profile_projetos']
		
	];
	/*static $validates_presence_of = [
			[
				'formacao',
				'message' => 'O <strong>Nome</strong> é um campo obrigatório.'
			],
			[
				'area',
				'message' => '<strong>Área</strong> é um campo obrigatório.'
			],
			[
				'palavra',
				'message' => '<strong>Senha</strong> é um campo obrigatório.'
			],
			[
				'lattes',
				'message' => '<strong>Telefone</strong> é um campo obrigatório.'
			],
			[
				'campus',
				'message' => '<strong>E-mail</strong> é um campo obrigatório.'
			]
		];
		static $validates_uniqueness_of  = [
			[
				['lattes'],
				'message' => 'Já existe um usuário com este Curriculo.'
			]
		];*/
	public static function cadastrarPerfil(array $post){
		$callback = new \stdClass;
		$callback->perfil = null;
		$callback->errors = [];
		$callback->status = false;

		var_dump($post);
	}

	public function searchResult($search){
			$final = $this->tratamentoSearch($search);
			if($final != false){
			foreach($final as $key => $val){
					$retorno = self::find('all',array('conditions'=> array('id = ?',$key)));
					if(Auth::login_check()){
						$url = 'informacoes("'.SITE.'home/Pesquisador_Extensionista/'.$retorno[0]->id.'");';
					}else{$url = "showLogin();";
					}
						$pesquisas[$key] = "
							Resposta para <mark><b>".$val[key($val)]."</b></mark><b>:</b> <div class='media'>
								<div class='media-left'>
									<button class='' onclick='".$url."'>
										<img class='perfil media-object img-circle' src='' alt='Foto do Extensinista'>
									</button>
								</div>
								<div class='media-body'>
									<h4 class='media-heading'>".$retorno[0]->user->full_name." ".$retorno[0]->user->second_name."</h4>
									<p>Aréa de Atuação: ".$retorno[0]->formacao."</p>
									<p>Instituição de Ensino: ".$retorno[0]->campus."</p>
								</div>
							</div>";
					
				
			}
			return $pesquisas;
		}else{
			return false;
		}
		}
	private function tratamentoSearch($search){
			$search = $this->strLowCase($search);
			$search_array = explode(" ",$search);
			//$tamanho_pesquisa_2 = intval(strlen($search) / 2);
			for($v=0;$v<count($search_array);$v++){
				$explode_searchPalavra[$v] = $this->searchNome($search_array[$v],"palavra");
				$explode_searchArea[$v] = $this->searchNome($search_array[$v],"area");
				$explode_searchFormacao[$v] = $this->searchNome($search_array[$v],"formacao");
				$explode_searchDescricao[$v] = $this->searchNome($search_array[$v],"descricao");
			}
			/*for($v=count($search_array)-1;$v>=0;$v--){
				$search -= str_replace($search_array[$v], "", $search);
				$inteiro_search_nome[$v] = $this->searchNome($search,"full_name");

			}*/
			$search_final = array_merge($explode_searchPalavra,$explode_searchArea,$explode_searchFormacao,$explode_searchDescricao);
			$search_final = array_filter($search_final);
			if(empty($search_final)){}else{
			$search_final = $this->returnArrayTratado($search_final);
			}
			
			if(empty($search_final)){}else{return $search_final;}
		}
		private function returnArrayTratado(array $array){
			$montado = array();
			foreach ($array as $key => $value) {
				foreach ($array[$key] as $id => $val) {
					$teste[] = $id;	
				}
			}
			$teste = array_unique($teste);
			foreach ($teste as $key => $value) {
				$montado[$value] = [];
			}
			
			foreach ($array as $key => $value) {
				foreach ($array[$key] as $id => $val) {
					foreach ($teste as $n => $ids) {
						foreach ($array[$key][$id] as $campo => $pes) {
						if($id === $ids){
							//Tomar cuidado com este IF, pois ele não deixara buscar em campos de textos grandes
							if(array_key_exists(key($val), $montado[$id]) && $val[key($val)] != $montado[$id][key($val)]){
								$imenda[$campo] =  $montado[$id][$campo]." ".$val[$campo];
								if(isset($montado[$id][$campo])){
									$montado[$id][$campo] = $imenda[$campo];
								}else{
										$montado[$id] += $imenda;
								}
								}else{
									$montado[$id] += $val;
								}
							}
						}
					}
				}
			}
			return	$montado;
		}
		private function searchNome($search,$campo){
			$ids = null;
			//$search = str_split($search);
			$result = self::all();
			$idss = [];
			foreach ($result as $key => $value) {
				$full_name[$value->id] = $this->strLowCase($value->$campo);
				$full_name[$value->id] = explode(" ",$full_name[$value->id]);
				//$full_name[$key+1] = str_split($full_name[$key+1]);
				$idss[] .= $value->id;
			}
			
			for($y=0;$y<count($search);$y++){
				for($j=0;$j<count($idss);$j++) {
					if(!empty($full_name[$idss[$j]])){
						for($v=0;$v<count($full_name[$idss[$j]]);$v++){
							if($full_name[$idss[$j]][$v] == $search){
								$ids[$idss[$j]][$campo] = $full_name[$idss[$j]][$v];
								
							}
						}
					}
				}
			}
			if(empty($ids)){
				
			}else{
				return $ids;
			}
		}
		private function filtroString($str){
			$str = preg_replace('/[áàãâä]/ui', 'a', $str);
			$str = preg_replace('/[éèêë]/ui', 'e', $str);
			$str = preg_replace('/[íìîï]/ui', 'i', $str);
			$str = preg_replace('/[óòõôö]/ui', 'o', $str);
			$str = preg_replace('/[úùûü]/ui', 'u', $str);
			$str = preg_replace('/[ç]/ui', 'c', $str);
    		// $str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '_', $str);
			//$str = preg_replace('/[^a-z0-9]/i', '_', $str);
    		//$str = preg_replace('/_+/', '_', $str); // ideia do Bacco :)
    		return $str;
		}
		private function strLowCase($search){
			$search = mb_strtolower($search,'UTF-8');
			$search = $this->filtroString($search);
			return $search;
		}
}