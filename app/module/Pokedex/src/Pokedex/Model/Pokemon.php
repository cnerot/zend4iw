<?php
namespace Pokedex\Model;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilter;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\AdapterInterface;

class Pokemon implements InputFilterAwareInterface
{
    public $id;
    public $name;
    public $national_id;
    public $type_principal;
    public $type_secondaire;
    public $description;
    public $evolution;
    public $avatar;
    protected $inputFilter;
    protected $dbAdapter;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->national_id = (isset($data['national_id'])) ? $data['national_id'] : null;
        $this->type_principal = (isset($data['type_principal'])) ? $data['type_principal'] : null;
        $this->type_secondaire = (isset($data['type_secondaire'])) ? $data['type_secondaire'] : null;
        $this->evolution = (isset($data['evolution'])) ? $data['evolution'] : null;
        $this->avatar = (isset($data['avatar'])) ? $data['avatar'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
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
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(
                array(
                    'name'     => 'national_id',
                    'required' => true,
                )
            );

            $inputFilter->add(
                array(
                    'name' => 'evolution',
                    'required' => true,
                )
            );

            $inputFilter->add(
                array(
                    'name' => 'type_principal',
                    'required' => true,
                )
            );

            $inputFilter->add(
                array(
                    'name' => 'type_secondaire',
                    'required' => false,
                )
            );

            $inputFilter->add(
                array(
                    'name' => 'avatar',
                    'required' => false,
                )
            );

            $inputFilter->add(
                array(
                    'name' => 'name',
                    'required' => true,
                    'filters'  => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                        array(
                            'name'    => 'StringLength',
                            'options' => array(
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 12,
                            )
                        )
                    )
                )
            );

            $inputFilter->add(
                array(
                    'name' => 'description',
                    'required' => true,
                    'filters'  => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                        array(
                            'name'    => 'StringLength',
                            'options' => array(
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 255,
                            )
                        )
                    )
                )
            );

            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
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
