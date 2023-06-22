<?php

use Phalcon\Mvc\Controller;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;


class SignupController extends Controller
{

    public function IndexAction()
    {
        // defalut action
    }

    public function registerAction()
    {


        $data = array(
            "name" => $this->escaper->escapeHtml($this->request->getPost("name")),
            "email" => $this->escaper->escapeHtml($this->request->getPost("email")),
            "password" => $this->escaper->escapeHtml($this->request->getPost("password")),

        );

        $collection = $this->mongo->users;
        $status = $collection->insertOne($data);


        if ($status->name !== null) {
            $this->response->redirect("login");
        } else {
            echo "<h3>Invalid entries </h3>";
            die;
        }
    }
}
