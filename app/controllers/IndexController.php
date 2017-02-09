<?php 

class IndexController extends \HXPHP\System\Controller
{
	public function indexAction(){
                                       
            $this->view('Index','',true,'Generic','',array(
            	CSS.'animate.css',
            	CSS.'index.css'
            ),[
                //JS.'Validacoes/ApiGo.js'
            ]);
 
        }
        public function searchAction($search){
        	$search = new User;
            $data = $this->search($_GET['search'],$search);
            $data['endereco'] = SITE."index/search/";
            $this->view('Search',$data,true,'Generic','',array(CSS.'index.css'));
        }
}