<?php
namespace Pokeapi\Controller;

use Zend\EventManager\EventManagerInterface;
use Pokedex\Controller\PokedexController;
use Zend\View\Model\ViewModel;
class PokemonController  extends PokedexController
{
    public function getAction()
    {
        $this->layout('layout/pokelayout');
        $params = $this->params()->fromQuery();
        if ($params['id'])
        {
            $pokemon = $this->pokemonTable->getPokemonArray($params['id']);
            $pokemonsArray = $pokemon;
        }
        else
            $pokemonsArray = $this->pokemonTable->getAllOrderByNationalId();
        $this->typesTable->fetchAll();
        $pokemons = array();
        foreach ($pokemonsArray as $pokemonArray)
        {
            $pokemonArray['type_principal'] = $this->typesTable->getTypesLabelById($pokemonArray['type_principal']);
            if ($pokemonArray['type_secondaire'] != null)
                $pokemonArray['type_secondaire'] = $this->typesTable->getTypesLabelById($pokemonArray['type_secondaire']);
            $pokemonArray['evolution'] = $this->pokemonTable->getPokemonName($pokemonArray['evolution']);
            unset($pokemonArray['avatar']);
            unset($pokemonArray['inputFilter']);
            unset($pokemonArray['dbAdapter']);
            $pokemons[] = $pokemonArray;
        }

        return new ViewModel(
            array(
                'pokemons' => $pokemons
            )
        );
    }

    public function updateAction()
    {
        $this->layout('layout/pokelayout');
        $params = $this->params()->fromQuery();
        $pokemon = $this->getServiceLocator()->get('Pokedex/Model/Pokemon');
        if ($params['id'])
        {
            if(!is_numeric($params['id']))
                return new ViewModel(
                    array(
                        'result' => 0
                    )
                );
        }
        if ($params['avatar'])
        {
            unset($params['avatar']);
        }
        if ($params['national_id'])
        {
            if(!is_numeric($params['national_id']))
                return new ViewModel(
                    array(
                        'result' => 0
                    )
                );
            $test = $this->pokemonTable->getByNationalId($params['national_id']);
            if ($test != null)
                return new ViewModel(
                    array(
                        'result' => 0
                    )
                );
        }
        if(!is_numeric($params['type_principal']))
            return new ViewModel(
                array(
                    'result' => 0
                )
            );
        if ($params['type_secondaire'])
        {
            if(!is_numeric($params['type_secondaire']))
                return new ViewModel(
                    array(
                        'result' => 0
                    )
                );
        }
        if(!is_numeric($params['evolution']))
            return new ViewModel(
                array(
                    'result' => 0
                )
            );
        $pokemon->exchangeArray($params);
        $result = $this->pokemonTable->savePokemon($pokemon);
        return new ViewModel(
            array(
                'result' => $result
            )
        );
    }

    public function createAction()
    {
        $this->layout('layout/pokelayout');
        $params = $this->params()->fromQuery();
        $pokemon = $this->getServiceLocator()->get('Pokedex/Model/Pokemon');
        if ($params['id'])
        {
            unset($params['id']);
        }
        if ($params['avatar'])
        {
            unset($params['avatar']);
        }
        if ($params['national_id'])
        {
            if(!is_numeric($params['national_id']))
                return new ViewModel(
                    array(
                        'result' => 0
                    )
                );
            $test = $this->pokemonTable->getByNationalId($params['national_id']);
            if ($test != null)
                return new ViewModel(
                    array(
                        'result' => 0
                    )
                );
        }
        if(!is_numeric($params['type_principal']))
            return new ViewModel(
                array(
                    'result' => 0
                )
            );
        if ($params['type_secondaire'])
        {
            if(!is_numeric($params['type_secondaire']))
                return new ViewModel(
                    array(
                        'result' => 0
                    )
                );
        }
        if(!is_numeric($params['evolution']))
            return new ViewModel(
                array(
                    'result' => 0
                )
            );
        $pokemon->exchangeArray($params);
        $result = $this->pokemonTable->savePokemon($pokemon);
        return new ViewModel(
            array(
                'result' => $result
            )
        );
    }

    public function geolocAction()
    {
        $this->layout('layout/pokelayout');
        $params = $this->params()->fromQuery();
        $position = $this->getServiceLocator()->get('Pokedex/Model/Position');
        $position->exchangeArray($params);
        $result = $this->positionTable->savePosition($position);
        return new ViewModel(
            array(
                'result' => $result
            )
        );
    }

    public function deleteAction(){
        $this->layout('layout/pokelayout');
        $params = $this->params()->fromQuery();
        if ($params['id'])
        {
            $result = $this->pokemonTable->deletePokemon($params['id']);
        }
        return new ViewModel(
            array(
                'result' => $result
            )
        );
    }

}