<?php 
class HomeController extends \HXPHP\System\Controller{
	public function __construct($configs){
		parent::__construct($configs);
            $this->load(
                    'Services\Auth',
                    $configs->auth->after_login,
                    $configs->auth->after_logout,
                    true
                );
    		$this->auth->redirectCheck();
	}
	public function indexAction(){
		$this->view('funcoes-extensionista/EditarPerfil','',true,'','',[CSS.'index.css'],[JS.'Pesquisa/funcoes.js']);
	}
	public function searchAction($search){

		$search = new User;
		$data = $this->search($_GET['search'],$search);
		$data['endereco'] = BASE."home/search/";
		$this->view('Search',$data,true,'','',array(CSS.'index.css'),array(JS.'Pesquisa/funcoes.js'));
	}
}