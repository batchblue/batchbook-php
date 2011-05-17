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



class Batchblue_Service_BatchBook_Deal {

    /**
     * int $_id id of deal
     */ 
    private $_id;

    /**
     * string $_title of deal
     */ 
    private $_title;

    /**
     * string $_description of deal
     */ 
    private $_description;


    /**
     * float $_amount of deal
     */ 
    private $_amount;

    /**
     * string $_status of deal
     */ 
    private $_status;

    /**
     * constructor
     *
     * @param int $id optional id of deal
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
     * Get id of deal
     *
     * @param null
     * @return int $id id of deal
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set Id
     *
     * Set id for deal
     *
     * @param int $value id of deal
     * @return Batchblue_Service_BatchBook_Deal
     */
    public function setId($value)
    {
        $this->_id = (integer) $value;

        return $this;
    }


    /**
     * Get title
     *
     * Get title of deal
     *
     * @param null
     * @return string $title  of deal
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Set Title
     *
     * Set title for deal
     *
     * @param string $value title of deal
     * @return Batchblue_Service_BatchBook_Deal
     */
    public function setTitle($value)
    {
        $this->_title = (string) $value;

        return $this;
    }


    /**
     * Get description
     *
     * Get description of deal
     *
     * @param null
     * @return string $description  of deal
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * Set Description
     *
     * Set description for deal
     *
     * @param string $value description of deal
     * @return Batchblue_Service_BatchBook_Deal
     */
    public function setDescription($value)
    {
        $this->_description = (string) $value;

        return $this;

    }

    /**
     * Get status
     *
     * Get status of deal
     *
     * @param null
     * @return string $status  of deal
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * Set Status
     *
     * Set status for deal
     *
     * @param string $value status of deal
     * @return Batchblue_Service_BatchBook_Deal
     */
    public function setStatus($value)
    {
        $this->_status = (string) $value;

        return $this;
    } 

    /**
     * Get amount
     *
     * Get amount of deal
     *
     * @param null
     * @return string $amount  of deal
     */
    public function getAmount()
    {
        return $this->_amount;
    }

    /**
     * Set Amount
     *
     * Set amount for deal
     *
     * @param string $value amount of deal
     * @return Batchblue_Service_BatchBook_Deal
     */
    public function setAmount($value)
    {
        $this->_amount = (float) $value;

        return $this;
    } 



}
?>
