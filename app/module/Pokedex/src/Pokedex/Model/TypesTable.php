<?php
namespace Pokedex\Model;

use Zend\Db\TableGateway\TableGateway;

class TypesTable
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
    
    public function getAll()
    {
        $select = new \Zend\Db\Sql\Select();
        $select->from($this->tableGateway->getTable());
        return $select;
    }
    
    public function fetchAll()
    {
        $select = $this->tableGateway->getSql()->select();
        $resultSet = $this->tableGateway->selectWith($select)->toArray();

        return $resultSet;
    }

    public function getTypesByLabel($label)
    {
        $rowset = $this->tableGateway->select(array('label' => $label));
        $row = $rowset->current();

        if (!$row) {
            throw new \Exception("Could not find row $label");
        }

        return $row;
    }

    public function getTypesLabelById($id)
    {
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();

        if (!$row) {
            throw new \Exception("Could not find row with id $id");
        }

        return $row->label;
    }

    public function getTypesById($id)
    {
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        
        if (!$row) {
            throw new \Exception("Could not find row with id $id");
        }
        
        return $row;
    }
}
