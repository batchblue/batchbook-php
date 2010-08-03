
<?php
/**
 * 
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */

/**
 * Person
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook 
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 * @author      Chris Kohlhardt <chrisk@gliffy.com>
 */



class Batchblue_Service_BatchBook_Location {



    /**
     * integer $_id of location
     */ 
    private $_id;




    /**
     * string $_label of location
     */ 
    private $_label;


    /**
     * string $_email of location
     */ 
    private $_email;


    /**
     * string $_website of location
     */ 
    private $_website;

    /**
     * string $_phone of location
     */ 
    private $_phone;

    /**
     * string $_cell of location
     */ 
    private $_cell;

    /**
     * string $_fax of location
     */ 
    private $_fax;

    /**
     * string $_street_1 of location
     */ 
    private $_street_1;

    /**
     * string $_street_2 of location
     */ 
    private $_street_2;

    /**
     * string $_city of location
     */ 
    private $_city;

    /**
     * string $_state of location
     */ 
    private $_state;

    /**
     * string $_postal_code of location
     */ 
    private $_postalCode;

    /**
     * string $_country of location
     */ 
    private $_country;


    /**
     * constructor
     *
     * If no label is specified, default to 'work'
     *
     * @param int $id optional id of deal
     */ 
    public function __construct($label = null)
    {
        if ( empty($label) ) {
            $this->setLabel('work');
        } 
    }


    public function getId()
    {
        return $this->_id;
    }

    public function setId($value)
    {
        $this->_id = (integer) $value;

        return $this;
    }



    public function getLabel()
    {
        return $this->_label;
    }

    public function setLabel($value)
    {
        $this->_label = (string) $value;

        return $this;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setEmail($value)
    {
        $this->_email = (string) $value;

        return $this;
    }



    public function getWebsite()
    {
        return $this->_website;
    }

    public function setWebsite($value)
    {
        $this->_website = (string) $value;

        return $this;
    }

    public function getPhone()
    {
        return $this->_phone;
    }

    public function setPhone($value)
    {
        $this->_phone = (string) $value;

        return $this;
    }

    public function getCell()
    {
        return $this->_cell;
    }

    public function setCell($value)
    {
        $this->_cell = (string) $value;

        return $this;
    }

    public function getFax()
    {
        return $this->_fax;
    }

    public function setFax($value)
    {
        $this->_fax = (string) $value;

        return $this;
    }


    public function getStreet1()
    {
        return $this->_street_1;
    }

    public function setStreet1($value)
    {
        $this->_street_1 = (string) $value;

        return $this;
    }




    public function getStreet2()
    {
        return $this->_street_2;
    }

    public function setStreet2($value)
    {
        $this->_street_2 = (string) $value;

        return $this;
    }



    public function getCity()
    {
        return $this->_city;
    }

    public function setCity($value)
    {
        $this->_city = (string) $value;

        return $this;
    }




    public function getState()
    {
        return $this->_state;
    }

    public function setState($value)
    {
        $this->_state = (string) $value;

        return $this;
    }



    public function getPostalCode()
    {
        return $this->_postalCode;
    }

    public function setPostalCode($value)
    {
        $this->_postalCode = (string) $value;

        return $this;
    }


    public function getCountry()
    {
        return $this->_country;
    }

    public function setCountry($value)
    {
        $this->_country = (string) $value;

        return $this;
    } 


}
