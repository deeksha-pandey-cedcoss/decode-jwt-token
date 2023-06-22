<?php

namespace MyApp\Handlers;

use DateTime;
use Phalcon\Di\Injectable;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;
use Phalcon\Events\Event;


class Listner extends Injectable
{
    public function beforeHandleRequestt()
    {
        $di = $this->getDI();

        $token = $this->session->get("token");

        $tokenReceived = $token;
        $now           = new DateTime();
        $issued        = $now->getTimestamp();
        $notBefore     = $now->modify('-1 minute')->getTimestamp();
        $expires       = $now->getTimestamp();
        $id            = 'abcd123456789';
        $signer     = new Hmac();
        $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';


        $parser      = new Parser();
        $tokenObject = $parser->parse($tokenReceived);

        $validator = new Validator($tokenObject, 100);

        $validator
            ->validateExpiration($expires)
            ->validateId($id)
            ->validateIssuedAt($issued)
            ->validateNotBefore($notBefore)
            ->validateSignature($signer, $passphrase);



        $rolejwt = $tokenObject->getClaims()->getPayload();
        $expiration = $expires;
        $is_token_expired = ($expiration - time()) < 0;
        if ($rolejwt['sub'] !== "") {
            print_r($rolejwt['sub']);
            echo "<br>";
        } else {
            echo "Invalid Token details";
            die;
        }
        if ($is_token_expired) {
            print_r("Valid Time");
            echo "<br>";
        } else {
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
                ->setSubject($rolejwt['sub'])
                ->setPassphrase($passphrase);
            $tokenObject = $builder->getToken();

            $this->session->set('token', $tokenObject->getToken());
            echo "Invalid Token details";
            die;
        }
    }
}
