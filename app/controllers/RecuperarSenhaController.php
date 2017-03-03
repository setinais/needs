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