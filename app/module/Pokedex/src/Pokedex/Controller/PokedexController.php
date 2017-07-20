<?php
namespace Pokedex\Controller;

use RuntimeException;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Stdlib\ArrayUtils;

class   PokedexController extends AbstractRestfulController
{

    protected $config = array();

    protected $pokemonTable;

    protected $typesTable;

    protected $positionTable;

    public function __construct($types, $pokemon, $position)
    {
        $this->typesTable = $types;
        $this->pokemonTable = $pokemon;
        $this->positionTable = $position;
    }

    public function setConfig($config)
    {
        if ($config instanceof \Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }
        
        if (! is_array($config)) {
            throw new RuntimeException(sprintf('Expected array or Pokedex configuration; received %s', (is_object($config) ? get_class($config) : gettype($config))));
        }
        $this->config = $config;
    }

    public function setTypesTable($types)
    {
        $this->typesTable = $types;
    }

    public function setPokemonTable($pokemon)
    {
        $this->pokemonTable = $pokemon;
    }

    public function setPositionTable($position)
    {
        $this->positionTable = $position;
    }
}
