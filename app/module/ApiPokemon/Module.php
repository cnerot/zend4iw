<?php
namespace ApiPokemon;

use ApiPokemon\Controller\PokemonController;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\Controller\ControllerManager;
use ZfcBase\Module\AbstractModule;


class Module extends AbstractModule implements ConfigProviderInterface
{

    public function getControllerConfig()
    {
        return array(
            'factories' => array(
                'ApiPokemon\Controller\Pokemon' => function (ControllerManager $cm)
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

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
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
