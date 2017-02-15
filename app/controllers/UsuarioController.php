<?php
class UsuarioController extends \Hxphp\System\Controller{

    private $callback = null;
    private $chave = null;
    private $reloading = null;

    private function setChave($chave){
        $this->chave = Chave::getChave($chave);
        if(empty($this->chave)){
            $this->redirectTo($this->configs->baseURI);
        }
    }
    public function verificarEnderecoAction($pagina,$chave){
        if(!is_null($chave)){
           $this->setChave($chave);
       }
       if(empty($_REQUEST)){
            $this->callback = null;
            $this->redirectTo($this->configs->baseURI.'usuario'.DS.$pagina.DS.$this->chave->chave);
       }else{
            $this->callback = $_REQUEST;
            $this->redirectTo($this->configs->baseURI.'usuario'.DS.$pagina.DS.$this->chave->chave);
       }

    }
    public function cadastroDemandanteAction(){

         $this->request->setCustomFilters([
            'email_id' => FILTER_VALIDATE_EMAIL
            ]);
            $post = $this->request->post();
        if(!empty($post)){
            $post['funcoe_id'] = 'Demandante';
            $cadastrarUser = Usuario::cadastrarUsuario($post);
            if($cadastrarUser->status === false){
                $this->callback = $post;
                $this->reloading = '<script type="text/javascript" src="'.$this->configs->baseURI.'/public/js/IfReload.js"></script>';
                $this->load('Helpers\Alert',[
                    'danger',
                    'Ops! Não foi possivel efetuar seu cadastro. Verifique os erros abaixo',
                    $cadastrarUser->errors
                    ]);

            }else{
                $this->load('Helpers\Alert',[
                    'success',
                    'Cadastro realizado com sucesso!',
                    'Ir pra o <strong><a href='.$this->configs->baseURI.' >inicio</a></strong>'
                    ]);
            }
        }
        $estados = Estado::getSelectStates();
        $this->view->setFile('CadastroDemandante')->setHeader('HeaderGeneric')
        ->setAssets('css',[$this->configs->baseURI.'public/css/index.css',$this->configs->baseURI.'public/css/Cadastro.css'])
        ->setAssets('js',$this->configs->baseURI.'public/js/ValidacaoCadastro.js')->setVar('request' , $this->callback)->setVar('estados' , $estados)->setVar('reload', $this->reloading);
    }
    public function cadastroPEAction($chave){
        $this->setChave($chave);
        if($this->chave->status == 'Liberada'){
        $this->request->setCustomFilters([
            'email_id' => FILTER_VALIDATE_EMAIL
            ]);
            $post = $this->request->post();
        if(!empty($post)){
            $post_user = [];
            $post_perfil = [];
            $post_google_facebook = [];

            $post_user = array_merge($post_user, 
                [
                    'nome' => $post['nome'],
                    'usuario' => $post['usuario'],
                    'sobrenome' => $post['sobrenome'],
                    'senha' => $post['senha'],
                    'salt' => $post['salt'],
                    'telefone' => $post['telefone'],
                    'estado_id' => $post['estado_id'],
                    'funcoe_id' => $post['funcoe_id'],
                    'email_id' => $this->chave->email_id
                ]
            );
             $post_perfil = array_merge($post_perfil, 
                [
                    'formacao' => $post['formacao'],
                    'area' => $post['area'],
                    'descricao' => $post['descricao'],
                    'lattes' => $post['lattes'],
                    'grupo' => $post['grupo'],
                    'palavra' => $post['palavra'],
                    'campus'=> $post['campus']
                ]
            );
            $post_google_facebook = array_merge($post_google_facebook,
                [
                    'id_google' => $post['id_google'],
                    'id_facebook' => $post['id_facebook']
                ]
            );
            $cadastrarUser = Usuario::cadastrarUsuario($post_user);

            $cadastrarUser->status == false;
            if($cadastrarUser->status === false){
                $this->callback = $post;
                $this->reloading = '<script type="text/javascript" src="'.$this->configs->baseURI.'/public/js/IfReload.js"></script>';
                $this->load('Helpers\Alert',[
                    'danger',
                    'Ops! Não foi possivel efetuar seu cadastro. Verifique os erros abaixo',
                    $cadastrarUser->errors
                    ]);

            }else{
                $post_perfil = array_merge($post_perfil, ['user_id' => $cadastrarUser->user->id]);
                $cadastratPerfil = Perfil::cadastrarPerfil($post_perfil);
                $cadastrar_google_facebook = RedesSocial::cadastroRedeSocial($post_google_facebook);
                $this->load('Helpers\Alert',[
                    'success',
                    'Cadastro realizado com sucesso!',
                    'Ir pra o <strong><a href='.$this->configs->baseURI.' >inicio</a></strong>'
                    ]);
            }
           
            
        }
        $estados = Estado::getSelectStates();
        $this->view->setFile('CadastroPE')->setHeader('HeaderGeneric')
        ->setAssets('css',$this->configs->baseURI.'public/css/index.css')
        ->setAssets('js',$this->configs->baseURI.'public/js/ValidacaoCadastroPE-EP.js')->setVar('request' , $this->callback)->setVar('estados' , $estados)->setVar('roles', Funcoe::getRoles())->setVar('email',$this->chave->email->email)->setVar('token',$this->chave->chave);
        }else{
            //Chave invalida ow vencida;
        }
    }
    public function solicitarChaveAction(){
        $this->request->setCustomFilters([
            'email' => FILTER_VALIDATE_EMAIL
            ]);

        $post = $this->request->post();

        if(!empty($post)){
            $cad_chave = Chave::cadastrarChave($post['email'].'@ifto.edu.br');
            if($cad_chave->status == false){
                $this->load('Helpers\Alert',[
                    'danger',
                    'Ops! Não foi possivel efetuar sua solicitação. Verifique os erros abaixo!',
                    $cad_chave->errors
                    ]);
            }else{
                $email = Email::find_by_id($cad_chave->chave->email_id);
                $enviar_email = new \HXPHP\System\Services\Email;
                $status = $enviar_email->send($email->email,"Cadastro DemandaAi","\nFaça seu cadastro atraves do link:\n".$this->configs->siteURL.$this->configs->baseURI."usuario/CadastroPE/".$cad_chave->chave,$configs->env->development->mail->getFrom());
                if($status){
                $this->load('Helpers\Alert',[
                    'success',
                    'Cadastro realizado com sucesso!',
                    '<strong>Enviado com Sucesso.</strong> Esta chave tem validade de 48h para o email: <strong>'.$email->email.'</strong>. Por favor verifique sua Caixa de Entrada ou de Span! Ir pra o <strong><a href='.$this->configs->baseURI.' >inicio</a></strong>'
                    
                    ]);
                }else{
                    $this->load('Helpers\Alert',[
                    'danger',
                    'Ops! Não foi possivel enviar seu e-mail!',
                    '<strong>Aperte F5 para solicitar novamente!</strong>']);
                }

            }
        }
    }
    public function tokenAction($token){
        if(empty($token)){
            $this->redirectTo(BASE);
        }else{
            $info_token = Chave::getChave($token);
            if(!empty($info_token)){
                if(Chave::validarChave($info_token)){
                    $data['request'] = null;
                    $data['estados'] = State::getSelectStates();
                    $data['roles'] = Role::getRoles();
                    $data['token'] = $token;
                    $data['email'] = $info_token->email->email;
                    $this->view('CadastroPE',$data,true,'Generic','',[CSS.'index.css'],[JS.'Validacoes/ValidacaoCadastroPE-EP.js']);
                }else{
                    $data['erro'] = "<div class='alert alert-danger' role='alert'><strong>Chave Expirada!</strong> Solicitar uma nova <a href='".BASE."cadastro/solicitarNovoToken/' class='alert-link'>clique aqui!</a><div>";
                    $this->view('Errors',$data,true,'Generic','',[CSS.'index.css']);
                }
            }else{
                $data['erro'] = "<div class='alert alert-danger' role='alert'><strong>Chave Inválida!</strong> Ir para o <a href='".BASE."' class='alert-link'>Inicio</a><div>";
                    $this->view('Errors',$data,true,'Generic','',[CSS.'index.css']);
                //$this->redirectTo(BASE);
            }
        }
    }
    public function validandoTokenAction($token){
         if(empty($token)){
            $this->redirectTo(BASE);
        }else{
            $info_token = Chave::getChave($token);
            if(!empty($info_token)){
                var_dump($_REQUEST);
                if(Chave::validarChave($info_token)){
                    $senha = Tools::hashHX($_REQUEST['password']);
                    $password = $senha['password'];
                    $salt = $senha['salt'];
                    $status = 1;
                    $google_id = $_REQUEST['nid_google'];$facebook_id = $_REQUEST['nid_facebook'];
                    
                    $email_id = Email::pesquisarEmail($info_token->email->email);

                    if(empty($_REQUEST['nid_google'])){
                        $google_id = 'NULL';
                    }
                    if(empty($_REQUEST['nid_facebook'])){
                        $facebook_id = 'NULL';
                    }
                    $attributes_RS = ['id_facebook' => $facebook_id,'id_google' => $google_id];
                    $id_redes_social = RedesSocial::cadastroRedeSocial($attributes_RS);
                    $attributes = array('full_name' => $_REQUEST['Nome'],'second_name' => $_REQUEST['Sobrenome'], 'username' => $_REQUEST['Usuario'],'state_id' => $_REQUEST['Estado'], 'password' => $password, 'salt' => $salt, 'obs' => 'Null', 'status' => $status, 'role_id' => $_REQUEST['role'],'email_id' => $email_id->id,'telefone' => $_REQUEST['telefone'], 'redes_social_id' => $id_redes_social);
                    if(User::inserirUser($attributes)){
                        Chave::bloqueioDeUso($token);
                        //$this->redirectTo(BASE);
                    }else{
                        $texto=[
                            'danger',
                            'Conexão!',
                            'Sem conexão com a internet, por favor tente mais tarde!',
                        ];
                        $data['request'] = $_REQUEST;
                        $data['message'] = $texto;
                        $data['estados'] = State::getSelectStates();
                        $data['roles'] = Role::getRoles();
                        $data['token'] = $token;
                        //$data['email'] = $_REQUEST['Email'];
                        $this->view('CadastroPE',$data,true,'Generic','',[CSS.'index.css'],[JS.'Validacoes/ValidacaoCadastroPE-EP.js']);
                    }
                }else{
                    $data['erro'] = "<div class='alert alert-danger' role='alert'><strong>Chave Expirada!</strong> Solicitar uma nova <a href='".BASE."cadastro/solicitarNovoToken/' class='alert-link'>clique aqui!</a><div>";
                    $this->view('Errors',$data,true,'Generic','',[CSS.'index.css']);
                }
            }else{
                $data['erro'] = "<div class='alert alert-danger' role='alert'><strong>Chave Inválida!</strong> Ir para o <a href='".BASE."' class='alert-link'>Inicio</a><div>";
                    $this->view('Errors',$data,true,'Generic','',[CSS.'index.css']);
                //$this->redirectTo(BASE);
            }
        }
    }
    public function solicitarNovoTokenAction(){
        $data['erro'] = '<div class="row col-md-6 col-md-offset-3">
            <label for="email_institucional">Para Pesquisador/Extensionista, Solicite aqui seu cadastro!</label>
            <div class="input-group">
                <div class="input-group-btn">
                <button class="btn btn-success" onclick="solicitar();">Enviar</button>
                </div>
                <input id="email_institucional" type="text" name="email_institucional2" class="form-control" placeholder="Email Institucional" aria-describedby="basic-addon2" required="required">
                <span class="input-group-addon" id="basic-addon2">@ifto.edu.br</span>

            </div>
        <div id="errors" class="alert" role="alert" style="display: none;"></div>
    </div>
    <script>
        function solicitar(){
            endereco = "'.BASE.'cadastro/solicitarToken/";
            email = $("#email_institucional").val();
            email = email+"@ifto.edu.br";
    $.ajax({
        url: endereco+email,
        dataType: "html",
    })
    .done(function(res) {
        $("#errors").show("fast");
        if(res == "True"){
            $("#errors").attr("class", "alert alert-success");
            $("#errors").html("<strong>Enviado com Sucesso.</strong> Esta chave tem validade de 48h para o email: <strong>"+email+"</strong>. Por favor verifique sua Caixa de Entrada ou de Span!" );
        }else if(res == "False"){
            $("#errors").attr("class", "alert alert-danger");
            $("#errors").html("<strong>Email não enviado</strong>, verifique a conexão com a internet!");
        }else if(res == "JaExiste"){
            $("#errors").attr("class", "alert alert-warning");
            $("#errors").html("<strong>Este Email ja tem cadastro!</strong>");
        }
        
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
        }
    </script>';
        $this->view('Errors',$data,true,'Generic','',[CSS.'index.css']);
    }
    
}
