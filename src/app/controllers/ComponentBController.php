<?php

use Phalcon\Mvc\Controller;
use Phalcon\Events\Manager as EventsManager;
use MyApp\Handlers\Aware;
use MyApp\Handlers\Listner;

// login controller
class ComponentBController extends Controller
{
    public function indexAction()
    {

        $token = $this->session->get("token");
        $eventsManager = new EventsManager();

        $component = new Aware();

        $component->setEventsManager($eventsManager);

        $eventsManager->attach(
            'test',
            new Listner()
        );
        $component->process();
        echo "Hello component B";

        die;
    }
}
