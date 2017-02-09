<?php 
class HomeController extends Controller{
	public function __construct(){
		parent::__construct();
		Auth::redirectCheck();
	}
	public function indexAction(){
		$this->view('funcoes-extensionista/EditarPerfil','',true,'','',[CSS.'index.css'],[JS.'Pesquisa/funcoes.js']);
	}
	public function searchAction($search){

		$search = new User;
		$data = $this->search($_GET['search'],$search);
		$data['endereco'] = SITE."home/search/";
		$this->view('Search',$data,true,'','',array(CSS.'index.css'),array(JS.'Pesquisa/funcoes.js'));
	}
}