<?php
namespace Pokedex\Form;
use Zend\Form\Form;
use Pokedex\Model\TypesTable;
use Pokedex\Model\PokemonTable;

class PokemonForm extends Form
{
    protected $typesTable = null;
    protected $pokemonTable = null;

    public function __construct(TypesTable $table, PokemonTable $tablePokemon)
    {
        parent::__construct('Pokemon');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        $this->typesTable = $table;
        $this->pokemonTable = $tablePokemon;

        $this->add(
            array(
                'name' => 'id',
                'type' => 'Hidden',
            )
        );

        $this->add(
            array(
                'name' => 'name',
                'type' => 'Text',
                'attributes' => array(
                    'id'    => 'name'
                ),
                'options' => array(
                    'label' => 'Nom du Pokemon',
                ),
            )
        );


        $this->add(
            array(
                'name' => 'national_id',
                'type' => 'Text',
                'attributes' => array(
                    'id'    => 'national_id'
                ),
                'options' => array(
                    'label' => 'ID National du Pokemon',
                ),
            )
        );

        $this->add(
            array(
                'name' => 'description',
                'type' => 'Textarea',
                'attributes' => array(
                    'id'    => 'description',
                    'rows' => 4,
                    'cols' => 30,
                ),
                'options' => array(
                    'label' => 'Description du Pokemon'
                )
            )
        );

        $this->add(
            array(
                'name' => 'type_principal',
                'type' => 'Select',
                'attributes' => array(
                    'id'    => 'type_principal'
                ),
                'options' => array(
                    'label' => 'Type Principale',
                    'value_options' => $this->getTypes("type_principal"),
                    'empty_option'  => '--- Sélectionner un type ---'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'type_secondaire',
                'type' => 'Select',
                'attributes' => array(
                    'id'    => 'type_secondaire'
                ),
                'options' => array(
                    'label' => 'Type Secondaire',
                    'value_options' => $this->getTypes("type_secondaire"),
                    'empty_option'  => '--- Sélectionner un type ---'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'evolution',
                'type' => 'Select',
                'attributes' => array(
                    'id'    => 'evolution'
                ),
                'options' => array(
                    'label' => 'Evolution directe du Pokemon',
                    'value_options' => $this->getPokemonName(),
                    'empty_option'  => '--- Sélectionner une Evolution ---'
                ),
            )
        );


        $this->add(
            array(
                'name' => 'avatar',
                'type' => 'File',
                'attributes' => array(
                    'id'    => 'avatar'
                ),
                'options' => array(
                    'label' => 'Photo du Pokemon'
                )
            )
        );

        $this->add(
            array(
                'name' => 'submit',
                'type' => 'Submit',
                'attributes' => array(
                    'value' => 'Sauvegarder le Pokémon',
                    'class' => 'btn',
                    'id' => 'submit',
                ),
            )
        );
    }

    public function getTypes()
    {
        $types = $this->typesTable->fetchAll();
        $selectData = array();
        $x = 1;
        foreach ($types as $key => $selectOption) {
            $selectData[$x++] = $selectOption["label"];
        }

        return $selectData;
    }
    public function getPokemonName()
    {
        $pokemons = $this->pokemonTable->getAllOrderByNationalId();
        $selectData = array();
        $x = 1;
        foreach ($pokemons as $key => $selectOption) {
            $selectData[$x++] = $selectOption["name"];
        }

        return $selectData;
    }

}
