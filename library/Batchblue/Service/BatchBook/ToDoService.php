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

        

        if( $xmlElement->complete == 'true' ) {
            $completeBool = true;
        } else { 
            $completeBool = false;
        }


        if( $xmlElement->flagged == 'true' ) {
            $flaggedBool = true;
        } else { 
            $flaggedBool = false;
        }


        $todo 
            ->setId($xmlElement->id)
            ->setTitle($xmlElement->title)
            ->setDescription($xmlElement->description)
            ->setDueDate( new DateTime( $xmlElement->due_date ) )
            ->setFlagged( $flaggedBool )
            ->setComplete($completeBool)
            ;

        

        return $todo;
    }


    /**
     * Index Of ToDos
     *
     * @param void
     * @return array
     */
    public function indexOfToDos()
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/todos.xml'
        );
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::GET);
        $xmlResponse = simplexml_load_string($response->getBody());
        $todos = array();
        foreach ($xmlResponse->todo as $todoElement) {
            $todos[] = $this->_populateTodoFromXmlElement($todoElement);
        }
        return $todos;
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


    private function formatDateForBatchbook(DateTime $dt) {
        return date_format( $dt, 'Y-m-d H:i:s O');
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
            $this->formatDateForBatchbook( $todo->getDueDate() )
        );

        //this effectively defaults flagged to false
        if( $todo->getFlagged() == null || $todo->getFlagged() == false ) {
            $flaggedParam = "false";
        } else { 
            $flaggedParam = "true";
        }



        $httpClient->setParameterPost(
            'todo[flagged]',
            $flaggedParam
        );

        //this effectively defaults complete to false
        if( $todo->getComplete() == null || $todo->getComplete() == false ) {
            $completeParam = "false";
        } else { 
            $completeParam = "true";
        }


        $httpClient->setParameterPost(
            'todo[complete]',
            $completeParam 
        );




        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::POST);
        if (201 != $response->getStatus()) {
            //TODO: throw more specific exception
            throw new Exception('ToDo not created.'  . $response->getMessage() . $response->getBody() );
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



        if( $todo->getFlagged() == null || $todo->getFlagged() == false ) {
            $flaggedParam = "false";
        } else { 
            $flaggedParam = "true";
        } 

        if( $todo->getComplete() == null || $todo->getComplete() == false ) {
            $completeParam = "false";
        } else { 
            $completeParam = "true";
        } 

        $paramsPut = array(
            'todo[title]'    => $todo->getTitle(),
            'todo[description]'     => $todo->getDescription(),
            'todo[due_date]'         => $this->formatDateForBatchbook( $todo->getDueDate() ),
            'todo[flagged]'       => $flaggedParam, 
            'todo[complete]'       => $completeParam, 
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

    public function addPersonToToDo(Batchblue_Service_BatchBook_ToDo $todo, Batchblue_Service_BatchBook_Person $person)
    {
    
        

        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/todos/' . $todo->getId() . '/add_related_contact.xml'
        );


        $paramsPut = array(
            'contact_id'    => $person->getId(), 
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
            throw new Exception('Person not added to todo');
        }
        return $this; 
    }
}
?>
