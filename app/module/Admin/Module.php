<?php
namespace Admin;

use Admin\Controller\PokemonController;
use Zend\Mvc\Controller\ControllerManager;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use ZfcBase\Module\AbstractModule;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\Router\RouteMatch;

class Module extends AbstractModule implements ConfigProviderInterface
{
    protected $whitelist = array('zfcadmin/login', 'home', 'pokeapi', 'home/action', 'pokeapi/action');

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $sm  = $e->getApplication()->getServiceManager();
        $auth = $sm->get('zfcuser_auth_service');
        $list = $this->whitelist;
        $eventManager->attach(MvcEvent::EVENT_ROUTE, function($e) use ($list, $auth) {
            $match = $e->getRouteMatch();

            // No route match, this is a 404
            if (!$match instanceof RouteMatch) {
                return;
            }

            // Route is whitelisted
            $name = $match->getMatchedRouteName();
            if (in_array($name, $list)) {
                return;
            }

            // User is authenticated
            if ($auth->hasIdentity()) {
                return;
            }

            // Redirect to the user login page, as an example
            $router   = $e->getRouter();
            $url      = $router->assemble(array(), array(
                'name' => 'zfcadmin/login'
            ));

            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);

            return $response;
        }, -100);
        $eventManager->attach(MvcEvent::EVENT_RENDER, function ($e)
        {
            $flashMessenger = new FlashMessenger();

            $messages = array_merge($flashMessenger->getSuccessMessages(), $flashMessenger->getInfoMessages(), $flashMessenger->getErrorMessages(), $flashMessenger->getMessages());

            if ($flashMessenger->hasMessages()) {
                $e->getViewModel()->setVariable('flashMessages', $messages);
            }
        });

        $eventManager->attach('route', array($this, 'onPreRoute'), 100);
        $moduleRouteListener->attach($eventManager);
    }

    public function onPreRoute($e){
        $app      = $e->getTarget();
        $serviceManager       = $app->getServiceManager();
        $serviceManager->get('router')->setTranslator($serviceManager->get('translator'));
    }

    public function getControllerConfig()
    {
        return array(
            'factories' => array(
                'Admin\Controller\Pokemon' => function (ControllerManager $cm)
                {
                    $sm = $cm->getServiceLocator();
                    $config = $sm->get('Config');
                    $config = isset($config['pokedex']) ? $config['pokedex'] : array();
                    $types = $sm->get('Pokedex\Model\TypesTable');
                    $pokemon = $sm->get('Pokedex\Model\PokemonTable');
                    $position = $sm->get('Pokedex\Model\PositionTable');
                    $controller = new PokemonController($types, $pokemon, $position);
                    $controller->setConfig($config);
                    return $controller;
                }
            )
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'pokedex_flashmessenger' => function ($sm) {
                    $viewModel = $sm->get('view_manager')->getViewModel();
                    $controller = $sm->get('ControllerPluginManager');
                    $flashMessenger = $controller->get('FlashMessenger');

                    return $viewModel->setVariable('messages', $flashMessenger->getMessages());
                }
            )
        );
    }

    public function getDir()
    {
        return __DIR__;
    }

    public function getNamespace()
    {
        return __NAMESPACE__;
    }
}
