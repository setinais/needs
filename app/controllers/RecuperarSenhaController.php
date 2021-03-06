<?php 
 /**
 * 
 */
 class RecuperarSenhaController extends \HXPHP\System\Controller
 {
     
    function __construct($configs)
    {
            parent::__construct($configs);
            $this->load(
                    'Services\Auth',
                    $configs->auth->after_login,
                    $configs->auth->after_logout,
                    true
                );
            $this->auth->redirectCheck(true);
    }

    public function solicitarAction()
    {
        $this->view->setFile('index');

        $this->load('Modules\Messages', 'password-recovery');
        $this->messages->setBlock('alerts');

        $this->request->setCustomFilters(['email' => FILTER_VALIDATE_EMAIL]);

        $email = $this->request->post('email');

        $error = null;

        if(!is_null($email) && $email !== false)
        {
            $validar = SenhaPerdida::validar($email);

            if(!$validar->status)
            {
                $error = $this->messages->getByCode($validar->code);
            }
            else
            {
                $this->load('Services\PasswordRecovery', 
                            $this->configs->site->url.$this->configs->baseURI.'recuperarsenha/redefinir/'
                    );
                
                SenhaPerdida::create([
                        'usuario_id' => $validar->user->id,
                        'chave' => $this->passwordrecovery->token,
                        'IP' => $_SERVER['REMOTE_ADDR'],
                        'status' => 0 
                    ]);
                $message = $this->messages->messages->getByCode('link-enviado',[
                    'message' => [
                            $validar->user->nome,
                            $this->passwordrecovery->link,
                            $this->passwordrecovery->link
                    ]
                    ]);
                $this->load('Services\Email');
                $envio_do_email = $this->email->send($validar->user->email->email,'NEEDS' . $message['subject'], $message['message'] . ' Needs', 
                    [
                        'email' => $this->configs->mail->from_mail,
                        'remetente' => $this->configs->mail->from
                    ]);
                if($envio_do_email === false)
                {
                    $error = $this->messages->getByCode('email-nao-enviado');
                }
            }
        }
        else
        {
            $error = $this->messages->getByCode('nenhum-usuario-encontrado');
        }
        if(!is_null($error))
        {
            $this->load('Helpers\Alert',$error);
        }
        else
        {
            $success = $this->messages->getByCode('link-enviado');
            $this->view->setFile('message');
            $this->load('Helpers\Alert', $success);
        }
    }

    public function redefinirAction($chave)
    {

    }

    public function alterarSenhaAction($chave)
    {

    }
 }