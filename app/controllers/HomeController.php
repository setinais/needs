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
		$this->view->setAssets('css',$this->configs->baseURI.'public/css/index.css')->setAssets('js',$this->configs->baseURI.'public/js/funcoes.js');
	}
	public function searchAction($search){

		$search = new User;
		$data = $this->search($_GET['search'],$search);
		$data['endereco'] = BASE."home/search/";
		$this->view('Search',$data,true,'','',array(CSS.'index.css'),array(JS.'Pesquisa/funcoes.js'));
	}
}