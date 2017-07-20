<?php
namespace Pokedex\Model;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilter;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\AdapterInterface;

class Position implements InputFilterAwareInterface
{
    public $id;
    public $poke_id;
    public $longitude;
    public $latitude;
    public $date;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->poke_id = (isset($data['poke_id'])) ? $data['poke_id'] : null;
        $this->longitude = (isset($data['longitude'])) ? $data['longitude'] : null;
        $this->latitude = (isset($data['latitude'])) ? $data['latitude'] : null;
        $this->date = (isset($data['date'])) ? $data['date'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(\Zend\InputFilter\InputFilterInterface $inputFilter)
    {
        $this->inputFilter = $inputFilter;
    }

    public function getInputFilter()
    {
    }

    public function setDbAdapter(AdapterInterface $adapter)
    {
        $this->dbAdapter = $adapter;
    }

    public function getDbAdapter()
    {
        return $this->dbAdapter;
    }
}
