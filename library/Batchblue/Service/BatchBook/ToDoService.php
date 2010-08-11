
<?php
/** 
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */

/**
 * ToDo Service
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */
class Batchblue_Service_BatchBook_ToDoService
{

    /**
     * @var string
     */
    private $_accountName;

    /**
     * @var string
     */
    private $_token;
   

    /**
     * Construct new ToDo Service
     *
     * @param string $token
     */
    public function __construct($accountName, $token)
    {
        $this->_accountName = (string) $accountName;
        $this->_token = (string) $token;
    }



    /**
     * Create ToDo From XML
     *
     * @param SimpleXMLElement $xmlElement
     * @return Batchblue_Service_BatchBook_ToDo
     */
    private function _populateToDoFromXmlElement(
        SimpleXMLElement $xmlElement,
        Batchblue_Service_BatchBook_ToDo $todo = null
    )
    {
        if (null === $todo) {
            $todo = new Batchblue_Service_BatchBook_ToDo();
        }
        $todo 
            ->setId($xmlElement->id)
            ->setTitle($xmlElement->title)
            ->setDescription($xmlElement->description)
            ->setDueDate($xmlElement->due_date)
            ->setFlagged($xmlElement->flagged)
            ->setComplete($xmlElement->complete)
            ;
        return $todo;
    }



    /**
     * Get ToDo
     *
     * @param integer $id
     * @return Batchblue_Service_BatchBook_ToDo
     */
    public function getToDo($id)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/todos/' . $id . '.xml'
        );
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::GET);
        switch ($response->getStatus()) {
            case 200:
                break;
            case 404:
                return null;
                break;
            default;
                //TODO: throw more specific exception
                throw new Exception('Could not get ToDo.');
        } 
        $xmlResponse = simplexml_load_string($response->getBody());
        return $this->_populateToDoFromXmlElement($xmlResponse);
    }




    /**
     * Post ToDo
     *
     * @param Batchblue_Service_BatchBook_ToDo $todo
     * @return Batchblue_Service_BatchBook_ToDoService   Provides a fluent interface
     */
    public function postToDo(Batchblue_Service_BatchBook_ToDo $todo)
    {


        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/todos.xml'
        );

        $httpClient->setParameterPost(
            'todo[title]',
            $todo->getTitle()
        );
        $httpClient->setParameterPost(
            'todo[description]',
            $todo->getDescription()
        );

        $httpClient->setParameterPost(
            'todo[due_date]',
            $todo->getDueDate()
        );
        $httpClient->setParameterPost(
            'todo[flagged]',
            $todo->getFlagged()
        );
        $httpClient->setParameterPost(
            'todo[complete]',
            $todo->getComplete()
        );




        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::POST);
        if (201 != $response->getStatus()) {
            //TODO: throw more specific exception
            throw new Exception('ToDo not created.');
        }


        $location = $response->getHeader('location');
        $httpClient = new Zend_Http_Client($location);
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::GET);
        $xmlResponse = simplexml_load_string($response->getBody());
        $this->_populateToDoFromXmlElement($xmlResponse, $todo); 
    

        return $this;
    }

    /**
     * Put ToDo
     *
     * @param Batchblue_Service_BatchBook_ToDo $todo
     * @return Batchblue_Service_BatchBook_ToDoService   Provides a fluent interface
     */
    public function putToDo(Batchblue_Service_BatchBook_ToDo $todo)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/todos/' . $todo->getId() . '.xml'
        );
        $paramsPut = array(
            'todo[title]'    => $todo->getTitle(),
            'todo[description]'     => $todo->getDescription(),
            'todo[due_date]'         => $todo->getDueDate(),
            'todo[flagged]'       => $todo->getFlagged(), 
            'todo[complete]'       => $todo->getComplete(), 
        );
        $httpClient->setAuth($this->_token, 'x');
        $httpClient->setHeaders(
            Zend_Http_Client::CONTENT_TYPE,
            Zend_Http_Client::ENC_URLENCODED
        );
        $httpClient->setRawData(
            http_build_query($paramsPut, '', '&'),
            Zend_Http_Client::ENC_URLENCODED
        );
        $response = $httpClient->request(Zend_Http_Client::PUT);
        if (200 != $response->getStatus()) {
            //TODO: throw more specific exception
            echo $httpClient->getLastRequest();
            throw new Exception('ToDo not updated:' . $response->getMessage() . "\n" . $response->getBody() );
        }
        return $this;
    }

    /**
     * Delete ToDo
     *
     * @param Batchblue_Service_BatchBook_ToDo $todo
     * @return Batchblue_Service_BatchBook_ToDoService   Provides a fluent interface
     */
    public function deleteToDo(Batchblue_Service_BatchBook_ToDo $todo)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/todos/' . $todo->getId() . '.xml'
        );
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::DELETE);
        if (200 != $response->getStatus()) {
            //TODO: throw more specific exception
            throw new Exception('ToDo not deleted.');
        }
        return $this;
    }




}

