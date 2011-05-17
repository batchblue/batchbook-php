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



class Batchblue_Service_BatchBook_SuperTag extends  Batchblue_Service_BatchBook_Tag { 


    /**
     * array $_fields supertag fields
     */ 
    private $_fields;



    /**
     * Get SuperTag fields
     * 
     *
     * @param null
     * @return array $fields supertag fields
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * Set SuperTag fields
     * 
     *
     * @param array $value supertag fields
     * @return Batchblue_Service_BatchBook_SuperTag
     */
    public function setFields($value)
    {
        $this->_fields = (array) $value;

        return $this;
    } 

}
?>
