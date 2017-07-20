<?php
namespace Front\Controller;

use Pokedex\Controller\PokedexController;
use Zend\View\Model\ViewModel;

class IndexController extends PokedexController
{

    public function indexAction()
    {
        $types = $this->typesTable->fetchAll();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $args = $this->getRequest()->getPost()->toArray();
            $pokemons = $this->pokemonTable->getByName($args['keywords']);
        } else {
            $pokemons = $this->pokemonTable->getAllOrderByNationalId();
        }
        $results[] = array(
            'types' => $types,
            'pokemons' => $pokemons
        );
        return new ViewModel(
            array(
                'results' => $results,

            )
        );
    }

    public function getAction()
    {
        $pokemon = $this->pokemonTable->getPokemon($this->params()->fromRoute('id', null));

        if ($pokemon == false) {
            return $this->redirect()->toRoute('home');
        }
        $pokemons = $this->pokemonTable->getAllOrderByNationalId();
        $positions = $this->positionTable->getByNationalId($pokemon->national_id);
        $types = $this->typesTable->fetchAll();
        $evolutions = array();
        $pre = $this->pokemonTable->getByEvolution($pokemon->id);
        if ($pre == false) {
            $evolutions[] = $pokemon;
            $evo = $this->pokemonTable->getPokemon($pokemon->evolution);
            if ($evo != false) {
                $evolutions[] = $evo;
                $lastEvo = $this->pokemonTable->getPokemon($evo->evolution);
                if ($lastEvo != false) {
                    $evolutions[] = $lastEvo;
                }
            }
        } else {
            $tmpPre = $this->pokemonTable->getByEvolution($pre->id);
            if ($tmpPre == false) {
                $evolutions[] = $pre;
                $secondEvo = $this->pokemonTable->getPokemon($pre->evolution);
                $evolutions[] = $secondEvo;
                $lastEvo = $this->pokemonTable->getPokemon($secondEvo->evolution);
                if ($lastEvo != false) {
                    $evolutions[] = $lastEvo;
                }
            } else {
                $evolutions[] = $tmpPre;
                $middle = $this->pokemonTable->getPokemon($tmpPre->evolution);
                $evolutions[] = $middle;
                $lastEvo = $this->pokemonTable->getPokemon($middle->evolution);
                $evolutions[] = $lastEvo;
            }
        }


        return new ViewModel(
            array(
                'pokemon' => $pokemon,
                'types' => $types,
                'evolutions' => $evolutions,
                'pokemons' => $pokemons,
                'positions' => $positions

            )
        );
    }
}
