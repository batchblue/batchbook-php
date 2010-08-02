<?php
/**
 * Big Yellow
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
 * @copyright   Copyright (c) 2010 Big Yellow Technologies, LLC
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 * @author      Rob Riggen <rob@bigyellowtech.com>
 */
class Batchblue_Service_BatchBook_Person
{
    /**
     * int $_id id of person
     */
    private $_id;

    /**
     * string $_firstName first name of person
     */
    private $_firstName;

    /**
     * string $_lastName last name of person
     */
    private $_lastName;

    /**
     * string $_title title of person
     */
    private $_title;


    /**
     * string $_company company for person
     */
    private $_company;

    /**
     * string $_notes notes for person
     */
    private $_notes;


    /**
     * array $_ locations for a person
     */
    private $_locations;


    /**
     * constructor
     *
     * @param int $id optional id of person
     */ 
    public function __construct($id = null)
    {
        if (!empty($id)) {
            $this->setId($id);
        }
    }

    /**
     * Get Id
     *
     * Get id of person
     *
     * @param null
     * @return int $id id of person
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set Id
     *
     * Set id for person
     *
     * @param int $value id of person
     * @return Batchblue_Service_BatchBook_Person
     */
    public function setId($value)
    {
        $this->_id = (integer) $value;

        return $this;
    }



    /**
     * Get Locations
     *
     * Get locations for a person
     *
     * @param null
     * @return array $locations of person
     */
    public function getLocations()
    {
        return $this->_locations;
    }

    /**
     * Set Locations
     *
     * Set locations for person
     *
     * @param array $value locations of person 
     */
    public function setLocations($value)
    {
        $this->_locations = (array) $value;

        return $this;
    }






    /**
     * Get First Name
     *
     * Get first name of person
     *
     * @param null
     * @return string $firstName first name of person
     */
    public function getFirstName()
    {
        return $this->_firstName;
    }

    /**
     * Set First Name
     *
     * Set first name of person
     *
     * @param string $value first name of person
     * @return Batchblue_Service_BatchBook_Person
     */
    public function setFirstName($value)
    {
        $this->_firstName = (string) $value;

        return $this;
    }

    /**
     * Get Last name
     *
     * Get last name of person
     *
     * @param string $value first name of person
     * @return string
     */
    public function getLastName()
    {
        return $this->_lastName;
    }

    /**
     * Set last name of person
     *
     * @param string $value
     * @return Batchblue_Service_BatchBook_Person
     */
    public function setLastName($value)
    {
        $this->_lastName = (string) $value;

        return $this;
    }

    /**
     * Get Title
     *
     * Get title of person
     *
     * @param null
     * @return string $title title of person
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Set Title
     *
     * Set title for person
     *
     * @param string $value title for person
     * @return Batchblue_Service_BatchBook_Person
     */
    public function setTitle($value)
    {
        $this->_title = (string) $value;

        return $this;
    }

    /**
     * Get Company
     *
     * Get company for person
     *
     * @param null
     * @return string $company company name
     */
    public function getCompany()
    {
        return $this->_company;
    }

    /**
     * Set Company
     *
     * Set company for person
     *
     * @param string $value company for person
     * @return Batchblue_Service_BatchBook_Person
     */
    public function setCompany($value)
    {
        $this->_company = (string) $value;

        return $this;
    }

    /**
     * Get Notes
     *
     * Get notes for person
     *
     * @param null
     * @return string $notes notes for person
     */
    public function getNotes()
    {
        return $this->_notes;
    }

    /**
     * Set Notes
     *
     * Set notes for person
     * 
     * @param string $value notes for person
     * @return Batchblue_Service_BatchBook_Person
     */
    public function setNotes($value)
    {
        $this->_notes = (string) $value;

        return $this;
    }

}
