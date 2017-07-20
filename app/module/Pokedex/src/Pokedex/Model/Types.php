<?php
namespace Pokedex\Model;

use Zend\Db\Adapter\AdapterInterface;

class Types
{
    public $id;
    public $label;
    protected $inputFilter;
    protected $dbAdapter;

    public function exchangeArray($data)
    {

        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->label = (isset($data['label'])) ? $data['label'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
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
