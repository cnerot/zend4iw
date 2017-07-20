<?php
/**
 * Created by PhpStorm.
 * User: youcef
 * Date: 28/06/2017
 * Time: 16:17
 */

namespace Pokedex\Model;

use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Exception\InvalidQueryException;

class PositionTable
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

    public function getByNationalId($poke_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('poke_id' => $poke_id, 'date >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)'));
        $select->order('date DESC');
        $resultSet = $this->tableGateway->selectWith($select)->toArray();
        return $resultSet;
    }


    public function fetchAllByDate($idTypes)
    {

    }

    public function fetchByPosition()
    {
    }

    public function savePosition(Position $position)
    {
        $data = array(
            'poke_id' => $position->poke_id,
            'longitude' => $position->longitude,
            'latitude' => $position->latitude,
        );

        $id = (int)$position->id;

        if ($id == 0 || $id == null) {
            $this->tableGateway->insert($data);
            return (1);
        } else {
            return (0);
        }
    }
}