<?php 
    class EsqueciASenhaController extends Controller{
        public function __construct(){
            parent::__construct();
            Auth::redirectCheck(true);
        }
        public function indexAction(){
            $this->view('EsqueciASenha','',true,'Generic');
        }
        public function enviarAction(){
            $validator=new Validator($_REQUEST);
            $validator->field_username('username');
            if(!$validator->valid){
                $data['message']=$validator->getErrors();
            }else{
                $username=trim($_REQUEST['username']);
                if(!is_null(User::find_by_username($username))){
                    $lost_password=new Lost_Password($username,SITE.'esqueci-a-senha/redefinir/');
                    $data['message']=$lost_password->results();
                }else{
                    $data['message']=array(
                        'danger',
                        'Oops, usuário não encontrado!',
                        'Por favor, verifique o nome de usuário informado e tente novamente.'
                    );
                }
            }
            $this->view('EsqueciASenha',$data,true,'Generic');
        }
        public function redefinirAction(){
            $token=is_null($this->getParam(0)) ? '' : $this->getParam(0);
            $data=array(
                'token'=>$token
            );
            $this->view('RedefinirASenha',$data,true,'Generic');
        }
        public function confirmarAction(){
            $token=$this->getParam(0);
            $data=array(
                'token'=>$token
            );
            if(!is_null($token)){
                $validator=new Validator($_REQUEST);
                $validator->field_filledin('password');
                if($validator->valid){
                    $password=trim($_REQUEST['password']);
                    $lost_password=new Lost_Password('','',$token);
                    $save=$lost_password->save($password);
                    $data['message']=($save !== true) ? $lost_password->errors : $lost_password->success_save_msg;
                }else{
                    $data['message']=$validator->getErrors();
                }
            }else{
                $data['message']=array(
                    'danger',
                    'Código inválido ou expirado!',
                    'Por favor, verifique o link de redefinição de senha e tente novamente.'
                );
            }
            $this->view('RedefinirASenha',$data,true,'Generic');
        }
    }