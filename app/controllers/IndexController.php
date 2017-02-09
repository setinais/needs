<?php 

class IndexController extends \HXPHP\System\Controller
{
	public function indexAction(){
                                       
            $this->view->setFile('index')->setHeader('HeaderGeneric')->setAssets('css',[$this->configs->baseURI . 'public/css/animate.css',$this->configs->baseURI . 'public/css/index.css'])->setAssets('js',$this->configs->baseURI.'public/js/main.js'); //JS.'Validacoes/ApiGo.js'
 
        }
        public function searchAction($search){
        	$search = new Usuario;
            $data = $this->search($_GET['search'],$search);
            $data['endereco'] = BASE."index/search/";
            $this->view('Search',$data,true,'Generic','',array(CSS.'index.css'));
        }
}