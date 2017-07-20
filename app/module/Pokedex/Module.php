<?php
namespace Pokedex;

use Pokedex\Model\Types;
use Pokedex\Model\TypesTable;
use Pokedex\Model\Pokemon;
use Pokedex\Model\PokemonTable;
use Pokedex\Model\Position;
use Pokedex\Model\PositionTable;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ArraySerializable as ArraySerializableHydrator;
use ZfcBase\Module\AbstractModule;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Pokedex\View\UnauthorizedStrategy;

class Module extends AbstractModule implements ConfigProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Pokedex\Model\TypesTable' => function ($sm) {
                    $tableGateway = $sm->get('TypesTableGateway');
                    $table = new TypesTable($tableGateway);
                    return $table;
                },
                'TypesTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new HydratingResultSet(new ArraySerializableHydrator(), new Types());
                    
                    return new TableGateway('types', $dbAdapter, null, $resultSetPrototype);
                },
                'Pokedex\Model\PositionTable' => function ($sm) {
                    $tableGateway = $sm->get('PositionTableGateway');
                    $table = new PositionTable($tableGateway);
                    return $table;
                },
                'PositionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new HydratingResultSet(new ArraySerializableHydrator(), new Position());

                    return new TableGateway('position', $dbAdapter, null, $resultSetPrototype);
                },
                'Pokedex\Model\PokemonTable' => function ($sm) {
                    $tableGateway = $sm->get('PokemonTableGateway');
                    $table = new PokemonTable($tableGateway);
                    return $table;
                },
                'PokemonTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new HydratingResultSet(new ArraySerializableHydrator(), new Pokemon());
                    return new TableGateway('pokemon', $dbAdapter, null, $resultSetPrototype);
                },
                'Pokedex\View\UnauthorizedStrategy' => function ($sm) {
                    $strategy = new UnauthorizedStrategy();
                    return $strategy;
                },
                'Pokedex/Model/Pokemon' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $pokemon = new \Pokedex\Model\Pokemon();
                    $pokemon->setDbAdapter($dbAdapter);
                    return $pokemon;
                },
                'Pokedex/Model/Position' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $position = new \Pokedex\Model\Position();
                    $position->setDbAdapter($dbAdapter);
                    return $position;
                },
                'Pokedex/Model/Types' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $types = new \Pokedex\Model\Types();
                    $types->setDbAdapter($dbAdapter);
                    return $types;
                }
            )
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
