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
        public function logarAction(){
    		$post = $this->request->post();
            
            $callback = Usuario::logar($post);
            if ($callback->status) 
            {
                 //$this->auth->login();
            }
            else
            {
                 $this->load('Helpers\Alert',[
                    'danger',
                    'Atenção!',
                    $callback->alert
                    ]);
                $this->view->setFile('index')->setHeader('HeaderGeneric')->setAssets('css',[$this->configs->baseURI . 'public/css/animate.css',$this->configs->baseURI . 'public/css/index.css']);    
            }
            
           
            
        }
        public function sairAction(){
        	Auth::logout();
        }
    }