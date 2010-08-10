
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



class Batchblue_Service_BatchBook_Tag {



    /**
     * string $_id of location
     */ 
    private $_name;



    /**
     * Get Tag Name
     * 
     *
     * @param null
     * @return string $name of tag
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Set Tag Name
     * 
     *
     * @param string $value name of tag
     * @return Batchblue_Service_BatchBook_Tag
     */
    public function setName($value)
    {
        $this->_name = (string) $value;

        return $this;
    } 

}

