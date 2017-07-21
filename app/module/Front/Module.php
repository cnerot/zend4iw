<?php
namespace Front;

use Front\Controller\TypesController;
use Front\Controller\IndexController;
use Front\Controller\PokemonController;
use Zend\EventManager\Event;
use Zend\EventManager\StaticEventManager;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\Controller\ControllerManager;
use Zend\Mvc\MvcEvent;
use ZfcBase\Module\AbstractModule;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\ModuleManager\ModuleManager;

class Module extends AbstractModule implements ConfigProviderInterface
{

    protected $serviceManager;

    public function init(ModuleManager $moduleManager)
    {
        $events = StaticEventManager::getInstance();
        $events->attach('bootstrap', 'bootstrap', array(
            $this,
            'onBootstrap'
        ));
    }

        public function onBootstrap(Event $e)
        {
            $application = $e->getParam('application');
            $this->serviceManager = $application->getServiceManager();
            $application->getEventManager()->attach('dispatch', array(
                $this,
                'onDispatch'
            ), - 100);
        }

    public function onDispatch(MvcEvent $e)
    {
        $matches = $e->getRouteMatch();
        $module = $matches->getParam('module');

        if (strpos($module, __NAMESPACE__) === 0) {
            $templatePathStack = $this->serviceManager->get('Zend\View\Resolver\TemplatePathStack');
            $templatePathStack->addPath(__DIR__ . '/view');
        }
    }

    public function getControllerConfig()
    {
        return array(
            'factories' => array(
                'Front\Controller\Index' => function (ControllerManager $cm)
                {
                    $sm = $cm->getServiceLocator();
                    $config = $sm->get('Config');
                    $config = isset($config['pokedex']) ? $config['pokedex'] : array();
                    $types = $sm->get('Pokedex\Model\TypesTable');
                    $pokemon = $sm->get('Pokedex\Model\PokemonTable');
                    $position = $sm->get('Pokedex\Model\PositionTable');
                    $controller = new IndexController($types, $pokemon, $position);
                    $controller->setConfig($config);
                    return $controller;
                }
            )
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'pokemonsessions' => 'Front\View\Helper\PokemonSessions',
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
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
