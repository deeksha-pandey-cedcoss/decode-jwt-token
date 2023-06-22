<?php

use Phalcon\Mvc\Controller;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;

// login controller
class LoginController extends Controller
{
    public function indexAction()
    {
        // default login view
    }
    // login action page
    public function loginAction()
    {
        if ($this->request->isPost()) {
            $password1 = $this->request->getPost("password");
            $email1 = $this->request->getPost("email");
        }

        $collection = $this->mongo->users;
        $m = $collection->findOne(["email" => $_POST['email'], "password" => $_POST['password']]);
        if ($m->email !== null) {

            $signer  = new Hmac();
            $builder = new Builder($signer);


            $now        = new DateTimeImmutable();
            $issued     = $now->getTimestamp();
            $notBefore  = $now->modify('-1 minute')->getTimestamp();
            $expires    = $now->modify('+1 day')->getTimestamp();
            $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';

            $builder
                ->setContentType('application/json')
                ->setExpirationTime($expires)
                ->setId('abcd123456789')
                ->setIssuedAt($issued)
                ->setNotBefore($notBefore)
                ->setSubject($m->name)
                ->setPassphrase($passphrase);
            $tokenObject = $builder->getToken();

            $this->session->set('token', $tokenObject->getToken());
            $this->view->message = "LOGIN SUCCESSFULLY";
            $this->response->redirect('index');
        } else {

            $this->view->message = "Not Login succesfully ";
            $this->response->redirect('login/index');
        }
    }
    public function logoutAction()
    {
        $this->session->remove('token');
        unset($this->session->token);
        echo "logout";
        $this->response->redirect("login");
    }
}
