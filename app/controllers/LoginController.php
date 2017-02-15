<?php 
    class LoginController extends \HXPHP\System\Controller{
    	public function __construct($configs){
    		parent::__construct($configs);
            $this->load(
                    'Services\Auth',
                    $configs->auth->after_login,
                    $configs->auth->after_logout,
                    true
                );
    		$this->auth->redirectCheck(true);
    	}
        public function indexAction(){
            $this->view('index','',true,'Generic','',array(CSS.'animate.css'));
        }
        public function logarAction(){
    		$post = $this->request->post();
            var_dump($post);
            //$this->auth->login();
        }
        public function sairAction(){
        	Auth::logout();
        }
    }