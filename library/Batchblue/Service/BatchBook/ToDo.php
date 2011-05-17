<?php
/**
 * 
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */

/**
 * Tag
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook 
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 * @author      Chris Kohlhardt <chrisk@gliffy.com>
 */



class Batchblue_Service_BatchBook_ToDo { 


    /**
     * int $_id of todo 
     */ 
    private $_id; 


    /**
     * string $_title of todo 
     */ 
    private $_title;


    /**
     * string $_description of todo 
     */ 
    private $_description;


    /**
     * string $_due_date of todo 
     */ 
    private $_due_date;

    /**
     * boolean $_flagged of todo 
     */ 
    private $_flagged;

    /**
     * boolean $_complete of todo 
     */ 
    private $_complete;


    /**
     * constructor
     *
     * @param int $id optional id of todo
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
     * Get id of todo
     *
     * @param null
     * @return int $id id of to
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set Id
     *
     * Set id for todo
     *
     * @param int $value id of todo
     * @return Batchblue_Service_BatchBook_ToDo
     */
    public function setId($value)
    {
        $this->_id = (integer) $value;

        return $this;
    }




    /**
     * Get Title 
     * 
     *
     * @param null
     * @return string $title title of todo
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Set Title 
     * 
     *
     * @param string $value title of todo
     * @return Batchblue_Service_BatchBook_ToDo
     */
    public function setTitle($value)
    {
        $this->_title = (string) $value;

        return $this;
    } 


    /**
     * Get Description 
     * 
     *
     * @param null
     * @return string $description of todo
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * Set Description 
     * 
     *
     * @param string $value description of todo
     * @return Batchblue_Service_BatchBook_ToDo
     */
    public function setDescription($value)
    {
        $this->_description = (string) $value;

        return $this;
    } 


    /**
     * Get DueDate 
     * 
     *
     * @param null
     * @return DateTime $due_date of todo
     */
    public function getDueDate()
    {
        return $this->_due_date;
    }

    /**
     * Set DueDate 
     * 
     *
     * @param DateTime $value due_date of todo
     * @return Batchblue_Service_BatchBook_ToDo
     */
    public function setDueDate(DateTime $value)
    {
        $this->_due_date =  $value;

        return $this;
    } 

    /**
     * Get Flagged 
     * 
     *
     * @param null
     * @return boolean $flagged of todo
     */
    public function getFlagged()
    {
        return $this->_flagged;
    }

    /**
     * Set Flagged 
     * 
     *
     * @param string $value flagged of todo
     * @return Batchblue_Service_BatchBook_ToDo
     */
    public function setFlagged($value)
    {
        $this->_flagged = (boolean) $value;

        return $this;
    } 


    /**
     * Get Complete 
     * 
     *
     * @param null
     * @return boolean $complete of todo
     */
    public function getComplete()
    {
        return $this->_complete;
    }

    /**
     * Set Complete 
     * 
     *
     * @param string $value complete of todo
     * @return Batchblue_Service_BatchBook_ToDo
     */
    public function setComplete($value)
    {
        $this->_complete = (boolean) $value;

        return $this;
    } 


}
?>
