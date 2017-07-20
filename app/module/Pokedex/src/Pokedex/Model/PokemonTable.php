<?php
namespace Pokedex\Model;

use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Exception\InvalidQueryException;

class PokemonTable
{
    /**
     * @var TableGateway
     */
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getAdapter()
    {
        return $this->tableGateway->getAdapter();
    }
    
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        
        return $resultSet;
    }

    
    public function fetchAllByTypePrincipal($idTypes)
    {
        $resultSet = $this->tableGateway->select(
            array(
                'type_principal = ?' => (int)$idTypes,
            )
        );
        return $resultSet;
    }

    public function getAllOrderByNationalId()
    {
        $select = $this->tableGateway->getSql()->select();
        $select->order('national_id ASC');
        $resultSet = $this->tableGateway->selectWith($select)->toArray();

        return $resultSet;
    }

    public function getByEvolution($evolution)
    {
        $evolution  = (int)$evolution;
        $rowset = $this->tableGateway->select(array('evolution' => $evolution));
        $row = $rowset->current();

        return $row;
    }

    public function getPokemon($id)
    {
        $id  = (int)$id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();

        return $row;
    }
    public function getPokemonArray($id)
    {
        $id  = (int)$id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->toArray();
        return $row;
    }


    public function getPokemonName($id)
    {
        $id  = (int)$id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();

        return $row->name;
    }

    public function getByNationalId($id)
    {
        $rowset = $this->tableGateway->select(array('national_id' => $id));
        $row = $rowset->current();

        return $row;
    }

    public function getByName($name)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('name' => $name));
        $resultSet = $this->tableGateway->selectWith($select)->toArray();

        return $resultSet;
    }

    public function savePokemon(Pokemon $pokemon)
    {
        $data = array(
            'id' => $pokemon->id,
            'name' => $pokemon->name,
            'national_id' => $pokemon->national_id,
            'type_principal' => $pokemon->type_principal,
            'type_secondaire' => $pokemon->type_secondaire,
            'avatar' => $pokemon->avatar,
            'description' => $pokemon->description,
            'evolution' => $pokemon->evolution
        );

        $id = (int)$pokemon->id;

        if ($id == 0 || $id == null) {
            $this->tableGateway->insert($data);
            return 1;
        } elseif ($this->getPokemon($id)) {
           if ($data['avatar'] == NULL) {
               $tmp = $this->getPokemon($id);
               $data['avatar'] = $tmp->avatar;
           }
            $this->tableGateway->update(
                $data,
                array(
                    'id' => $id,
                )
            );
            return 1;
        } else {
            return 0;
        }
    }

    public function deletePokemon($id)
    {
        try {
            return $this->tableGateway->delete(array('id' => $id));
        } catch (InvalidQueryException $e) {
            return 0;
        }
    }
}
