<?php 
    class LoginController extends Controller{
    	public function __construct(){
    		parent::__construct();
    		Auth::redirectCheck(true);
    	}
        public function indexAction(){
            $this->view('index','',true,'Generic','',array(CSS.'animate.css'));
        }
        public function logarAction(){
    		$data=array();
    		//Validação
    		$validator=new Validator($_REQUEST);
    		$validator->field_filledIn($_REQUEST);
            

    		if(!$validator->valid){
    			$data["message"]=$validator->getErrors();
    		}else{
    			//Tratamento
    			$super_global=DataHelper::tratamento($_REQUEST,INPUT_POST);

    			//Autenticação
    			$auth=new Auth;
    			$auth->login($super_global["usuario"],$super_global["senha"]);
    			if($auth->check()){
                    if(empty($_REQUEST['pesquisa'])){
    				    $this->redirectTo(SITE."home");
                    }else{
                        $this->redirectTo(SITE."home/search/?search=".$_REQUEST['pesquisa']);
                    }
    			}else{
    				$data["message"]=$auth->getErrors();
    			}
    		}
    		$this->view('index',$data,true,'Generic','',array(
                CSS.'animate.css',
                CSS.'index.css'
            ));
        }
        public function loginGoogleAction($chave,$id){
                $data['dados'] = "<script>validarToken('".$chave."','".SITE."');</script>";
                $this->view('Xrhauth',$data,true,'Generic','',[CSS.'animate.css',CSS.'index.css'],[JS.'Validacoes/ValidarLogonGoogle.js']);
            
        }
        public function criandoSessaoUserGoogleAction($id_google){
            var_dump($id_google);
            $user_data = User::find_by_id($id);
                if(count($user_data) == 1 && !empty($user_data)){
                    if($user_data->role->id != 2){
                        $profile_data = Profile::find_by_user_id($user_data->id);
                        $_SESSION['profile_id'] = preg_replace("/[^0-9]+/", "", $profile_data->id);
                    }
                    $_SESSION['user_id'] = preg_replace("/[^0-9]+/", "", $user_data->id);
                    $_SESSION['username'] = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $user_data->username);
                    $_SESSION['login_string'] = hash('sha512', $_SESSION['username'].$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
                }
        }
        public function sairAction(){
        	Auth::logout();
        }
    }