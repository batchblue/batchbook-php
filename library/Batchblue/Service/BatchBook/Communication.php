<?php
/**
 * Big Yellow
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */

/**
 * Communication
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 * @copyright   Copyright (c) 2010 Big Yellow Technologies, LLC
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 * @author      Rob Riggen <rob@bigyellowtech.com>
 */
class Batchblue_Service_BatchBook_Communication
{
    /**
     * int $_id id of communication
     */
    private $_id;

    /**
     * string $_to to of communication
     */
    private $_to;

    /**
     * string $_from from of communication
     */
    private $_from ;

    /**
     * string $_subject subject of communication
     */
    private $_subject;

    /**
     * string $_body body of communication
     */
    private $_body;

    /**
     * string $_date date of communication
     */
    private $_date;

    /**
     * string $_ctype communication type for communication
     */
    private $_ctype;

    /**
     * constructor
     *
     * @param int $id optional id of communication
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
     * Get id of communication
     *
     * @param null
     * @return int $id id of communication
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set Id
     *
     * Set id for communication
     *
     * @param int $value id of communication
     * @return Batchblue_Service_BatchBook_Communication
     */
    public function setId($value)
    {
        $this->_id = (integer) $value;

        return $this;
    }

    /**
     * Get To
     *
     * Get to of communication
     *
     * @param null
     * @return string $subject subject of communication
     */
    public function getTo()
    {
        return $this->_to;
    }

    /**
     * Set To
     *
     * Set to of communication
     *
     * @param string $value subject of communication
     * @return Batchblue_Service_BatchBook_Communication
     */
    public function setTo($value)
    {
        $this->_to = (string) $value;

        return $this;
    }

    /**
     * Get From 
     *
     * Get from of communication
     *
     * @param null
     * @return string $subject subject of communication
     */
    public function getFrom()
    {
        return $this->_from;
    }

    /**
     * Set From
     *
     * Set from of communication
     *
     * @param string $value subject of communication
     * @return Batchblue_Service_BatchBook_Communication
     */
    public function setFrom($value)
    {
        $this->_from = (string) $value;

        return $this;
    }

    /**
     * Get Subject
     *
     * Get subject of communication
     *
     * @param null
     * @return string $subject subject of communication
     */
    public function getSubject()
    {
        return $this->_subject;
    }

    /**
     * Set Subject
     *
     * Set subject of communication
     *
     * @param string $value subject of communication
     * @return Batchblue_Service_BatchBook_Communication
     */
    public function setSubject($value)
    {
        $this->_subject = (string) $value;

        return $this;
    }

    /**
     * Get Body
     *
     * Get body of communication
     *
     * @param string $value body of communication
     * @return string
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * Set body of communication
     *
     * @param string $value
     * @return Batchblue_Service_BatchBook_Communication
     */
    public function setBody($value)
    {
        $this->_body = (string) $value;

        return $this;
    }

    /**
     * Get Date
     *
     * Get date of communication
     *
     * @param null
     * @return string $date date of communication
     */
    public function getDate()
    {
        return $this->_date;
    }

    /**
     * Set Date
     *
     * Set date for communication
     *
     * @param string $value date for communication
     * @return Batchblue_Service_BatchBook_Communication
     */
    public function setDate($value)
    {
        $this->_date = (string) $value;

        return $this;
    }

    /**
     * Get Ctype
     *
     * Get ctype for communication
     *
     * @param null
     * @return string $ctype ctype name
     */
    public function getCtype()
    {
        return $this->_ctype;
    }

    /**
     * Set Ctype
     *
     * Set ctype for communication
     *
     * @param string $value ctype for communication
     * @return Batchblue_Service_BatchBook_Communication
     */
    public function setCtype($value)
    {
        $this->_ctype = (string) $value;

        return $this;
    }

}
?>
