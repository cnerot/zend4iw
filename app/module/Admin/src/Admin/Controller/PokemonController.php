<?php
namespace Admin\Controller;

use Pokedex\Controller\PokedexController;
use Pokedex\Form\PokemonForm;
use Zend\View\Model\ViewModel;
use Zend\Validator\File\Size;
use Zend\Http\Request;
use Zend\Http\Client;
use Zend\Stdlib\Parameters;


class PokemonController extends PokedexController
{

    public function indexAction()
    {
        $types = $this->typesTable->fetchAll();
        $pokemons = $this->pokemonTable->getAllOrderByNationalId();
        $results[] = array(
            'types' => $types,
            'pokemons' => $pokemons,
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

    public function addAction()
    {
        $request = $this->getRequest();
        $formPokemon= new PokemonForm($this->typesTable, $this->pokemonTable);
        $formPokemon->get('submit')->setValue('Ajouter le Pokemon');
        if ($request->isPost()) {
            new PokemonAdder();
            $pokemon = $this->getServiceLocator()->get('Pokedex/Model/Pokemon');
            $formPokemon->setInputFilter($pokemon->getInputFilter());

            $nonFiles = $this->getRequest()->getPost()->toArray();
            $files = $this->getRequest()->getFiles()->toArray();

            $data = array_merge_recursive(
                $nonFiles,
                $files
            );
            $formPokemon->setData($data);
            $formPokemon->getInputFilter()
                ->get('name')
                ->getValidatorChain()
                ->attachByName(
                    'Db\NoRecordExists',
                    array(
                        'adapter' => $pokemon->getDbAdapter(),
                        'table' => 'pokemon',
                        'field' => 'name'
                    )
                );

            $formPokemon->getInputFilter()
                ->get('national_id')
                ->getValidatorChain()
                ->attachByName(
                    'Db\NoRecordExists',
                    array(
                        'adapter' => $pokemon->getDbAdapter(),
                        'table' => 'pokemon',
                        'field' => 'national_id'
                    )
                );
            if ($formPokemon->isValid()) {

                $size = new Size(array('max' => 716800));
                $adapter = new \Zend\File\Transfer\Adapter\Http();

                if (!$adapter->isValid()) {
                    $dataError = $adapter->getMessages();
                    $error = array();
                    foreach ($dataError as $key => $row) {
                        $error[] = $row;
                    }
                    $formPokemon->setMessages(array('avatar' => $error));
                } else {
                    $adapter->setDestination('./public/pokemon_img');

                    if ($adapter->receive($files['avatar']['name'])) {
                        $pokemon->exchangeArray($formPokemon->getData());
                        $pokemon->avatar = $files['avatar']['name'];

                        $this->pokemonTable->savePokemon($pokemon);
                        $this->flashMessenger()->addMessage(
                            array(
                                'success' => "le Pokemon '{$pokemon->name}' a été ajouté"
                            )
                        );
                        return $this->redirect()->toRoute('zfcadmin/pokemon');
                    }
                }
            }
        }
            return new ViewModel(
                array(
                    'form' => $formPokemon
                )
            );
    }

    public function editAction()
    {
        $request = $this->getRequest();
        $formPokemon= new PokemonForm($this->typesTable, $this->pokemonTable);
        $idPokemon = $this->params()->fromRoute('id', null);
        $formPokemon->get('submit')->setValue('Modifier le Pokemon');
        $pokeview = $this->pokemonTable->getPokemon($idPokemon);

        if ($request->isPost()) {
            $pokemon = $this->getServiceLocator()->get('Pokedex/Model/Pokemon');
            $formPokemon->setInputFilter($pokemon->getInputFilter());

            $nonFiles = $this->getRequest()->getPost()->toArray();
            $files = $this->getRequest()->getFiles()->toArray();

            $data = array_merge_recursive(
                $nonFiles,
                $files
            );
            $formPokemon->setData($data);
            if($data['name'] != $pokeview->name) {
                $formPokemon->getInputFilter()
                    ->get('name')
                    ->getValidatorChain()
                    ->attachByName(
                        'Db\NoRecordExists',
                        array(
                            'adapter' => $pokemon->getDbAdapter(),
                            'table' => 'pokemon',
                            'field' => 'name'
                        )
                    );
            }
            if($data['national_id'] != $pokeview->national_id) {
                $formPokemon->getInputFilter()
                    ->get('national_id')
                    ->getValidatorChain()
                    ->attachByName(
                        'Db\NoRecordExists',
                        array(
                            'adapter' => $pokemon->getDbAdapter(),
                            'table' => 'pokemon',
                            'field' => 'national_id'
                        )
                    );
            }

            if ($formPokemon->isValid()) {

                $size = new Size(array('max' => 716800));
                $adapter = new \Zend\File\Transfer\Adapter\Http();
                $adapter->setValidators(array($size), $files['avatar']);

                if (!$adapter->isValid() && $pokeview->avatar == null) {
                    $dataError = $adapter->getMessages();
                    $error = array();
                    foreach ($dataError as $key => $row) {
                        $error[] = $row;
                    }
                    $formPokemon->setMessages(array('avatar' => $error));

                } else {
                    $adapter->setDestination('./public/pokemon_img');

                    $pokemon->exchangeArray($formPokemon->getData());
                    if ($adapter->receive($files['avatar']['name'])) {

                        $pokemon->avatar = $files['avatar']['name'];
                    } else
                        $pokemon->avatar = $pokeview->avatar;
                    $this->pokemonTable->savePokemon($pokemon);

                        $this->flashMessenger()->addMessage(
                            array(
                                'success' => "Le pokémon '{$pokemon->name}' à été mis à jour"
                            )
                        );
                        return $this->redirect()->toRoute('zfcadmin/pokemon');
                }
            }
        }

        $formPokemon->setData($pokeview->getArrayCopy());
        return new ViewModel(
            array(
                'form' => $formPokemon,
                'pokeview' => $pokeview
            )
        );
    }

    public function deleteAction()
    {
        $flashMessenger = $this->getServiceLocator()->get('pokedex_flashmessenger');
        $idPokemon = $this->params()->fromRoute('id', null);
        $nbResult = $this->pokemonTable->deletePokemon($idPokemon);

        if ($nbResult >= 1) {
            $this->flashMessenger()->addMessage(
                array(
                    'success' => "Le Pokemon a été supprimé!"
                )
            );
        } else {
            $this->flashMessenger()->addMessage(
                array(
                    'error' => "Le pokémon ne peut pas être supprimé"
                )
            );
        }

        return $this->redirect()->toRoute('zfcadmin/pokemon');
    }
}
