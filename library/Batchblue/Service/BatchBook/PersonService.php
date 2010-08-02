<?php
/**
 * Big Yellow
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */

/**
 * Person Service
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */
class Batchblue_Service_BatchBook_PersonService
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
     * Construct new Person Service
     *
     * @param string $token
     */
    public function __construct($accountName, $token)
    {
        $this->_accountName = (string) $accountName;
        $this->_token = (string) $token;
    }

    /**
     * Create Person From XML
     *
     * @param SimpleXMLElement $xmlElement
     * @return Batchblue_Service_BatchBook_Person
     */
    private function _populatePersonFromXmlElement(
        SimpleXMLElement $xmlElement,
        Batchblue_Service_BatchBook_Person $person = null
    )
    {
        if (null === $person) {
            $person = new Batchblue_Service_BatchBook_Person();
        }
        $person
            ->setId($xmlElement->id)
            ->setFirstName($xmlElement->first_name)
            ->setLastName($xmlElement->last_name)
            ->setTitle($xmlElement->title)
            ->setCompany($xmlElement->company)
            ->setNotes($xmlElement->notes)
        ;
        return $person;
    }

    /**
     * Index Of Persons
     *
     * @param string $name
     * @param string $email
     * @param integer $offset
     * @param integer $limit
     * @return array
     */
    public function indexOfPersons($name = null, $email= null, $offset = null, $limit = null)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/people.xml'
        );
        if (null !== $name) {
            $httpClient->setParameterGet('name', $name);
        }
        if (null !== $email) {
            $httpClient->setParameterGet('email', $name);
        }
        if (null !== $offset) {
            $httpClient->setParameterGet('offset', $name);
        }
        if (null !== $limit) {
            $httpClient->setParameterGet('limit', $name);
        }
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::GET);
        $xmlResponse = simplexml_load_string($response->getBody());
        $persons = array();
        foreach ($xmlResponse->person as $personElement) {
            $persons[] = $this->_populatePersonFromXmlElement($personElement);
        }
        return $persons;
    }

    /**
     * Get Person
     *
     * @param integer $id
     * @return Batchblue_Service_BatchBook_Person
     */
    public function getPerson($id)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/people/' . $id . '.xml'
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
                throw new Exception('Could not get Person.');
        } 
        $xmlResponse = simplexml_load_string($response->getBody());
        return $this->_populatePersonFromXmlElement($xmlResponse);
    }

    /**
     * Post Person
     *
     * @param Batchblue_Service_BatchBook_Person $person
     * @return Batchblue_Service_BatchBook_PersonService   Provides a fluent interface
     */
    public function postPerson(Batchblue_Service_BatchBook_Person $person)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/people.xml'
        );
        $httpClient->setParameterPost(
            'person[first_name]',
            $person->getFirstName()
        );
        $httpClient->setParameterPost(
            'person[last_name]',
            $person->getLastName()
        );
        $httpClient->setParameterPost(
            'person[title]',
            $person->getTitle()
        );
        $httpClient->setParameterPost(
            'person[company]',
            $person->getCompany()
        );
        $httpClient->setParameterPost(
            'person[notes]',
            $person->getNotes()
        );
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::POST);
        if (201 != $response->getStatus()) {
            //TODO: throw more specific exception
            throw new Exception('Person not created.');
        }
        $location = $response->getHeader('location');
        $httpClient = new Zend_Http_Client($location);
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::GET);
        $xmlResponse = simplexml_load_string($response->getBody());
        $this->_populatePersonFromXmlElement($xmlResponse, $person);
        return $this;
    }

    /**
     * Put Person
     *
     * @param Batchblue_Service_BatchBook_Person $person
     * @return Batchblue_Service_BatchBook_PersonService   Provides a fluent interface
     */
    public function putPerson(Batchblue_Service_BatchBook_Person $person)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/people/' . $person->getId() . '.xml'
        );
        $paramsPut = array(
            'person[first_name]'    => $person->getFirstName(),
            'person[last_name]'     => $person->getLastName(),
            'person[title]'         => $person->getTitle(),
            'person[company]'       => $person->getCompany(),
            'person[notes]'         => $person->getNotes(),
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
            throw new Exception('Person not updated.');
        }
        return $this;
    }

    /**
     * Delete Person
     *
     * @param Batchblue_Service_BatchBook_Person $person
     * @return Batchblue_Service_BatchBook_PersonService   Provides a fluent interface
     */
    public function deletePerson(Batchblue_Service_BatchBook_Person $person)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/people/' . $person->getId() . '.xml'
        );
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::DELETE);
        if (200 != $response->getStatus()) {
            //TODO: throw more specific exception
            throw new Exception('Person not deleted.');
        }
        return $this;
    }
}
