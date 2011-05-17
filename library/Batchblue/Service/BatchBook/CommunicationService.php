<?php
/**
 * Big Yellow
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */

/**
 * Communication Service
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */
class Batchblue_Service_BatchBook_CommunicationService
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
     * Construct new Communication Service
     *
     * @param string $token
     */
    public function __construct($accountName, $token)
    {
        $this->_accountName = (string) $accountName;
        $this->_token = (string) $token;
    }

    /**
     * Create Communication From XML
     *
     * @param SimpleXMLElement $xmlElement
     * @return Batchblue_Service_BatchBook_Communication
     */
    private function _populateCommunicationFromXmlElement(
        SimpleXMLElement $xmlElement,
        Batchblue_Service_BatchBook_Communication $communication = null
    )
    {
        if (null === $communication) {
            $communication = new Batchblue_Service_BatchBook_Communication();
        }
        $communication
            ->setId($xmlElement->id)
            ->setSubject($xmlElement->subject)
            ->setBody($xmlElement->body)
            ->setDate($xmlElement->date)
            ->setCtype($xmlElement->ctype)
        ;
        return $communication;
    }

    /**
     * Index Of Communications
     *
     * @param string $name
     * @param string $email
     * @param integer $offset
     * @param integer $limit
     * @return array
     */
    public function indexOfCommunications($contact_id = null, $ctype = null, $offset = null, $limit = null)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/communications.xml'
        );
        if (null !== $contact_id) {
            $httpClient->setParameterGet('contact_id', $contact_id);
        }
        if (null !== $ctype) {
            $httpClient->setParameterGet('ctype', $ctype);
        }
        if (null !== $offset) {
            $httpClient->setParameterGet('offset', $offset);
        }
        if (null !== $limit) {
            $httpClient->setParameterGet('limit', $limit);
        }
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::GET);
        $xmlResponse = simplexml_load_string($response->getBody());
        $communications = array();
        foreach ($xmlResponse->communication as $communicationElement) {
            $communications[] = $this->_populateCommunicationFromXmlElement($communicationElement);
        }
        return $communications;
    }

    /**
     * Get Communication
     *
     * @param integer $id
     * @return Batchblue_Service_BatchBook_Communication
     */
    public function getCommunication($id)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/communications/' . $id . '.xml'
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
                throw new Exception('Could not get Communication.');
        }
        $xmlResponse = simplexml_load_string($response->getBody());
        return $this->_populateCommunicationFromXmlElement($xmlResponse);
    }

    /**
     * Post Communication
     *
     * @param Batchblue_Service_BatchBook_Communication $communication
     * @return Batchblue_Service_BatchBook_CommunicationService   Provides a fluent interface
     */
    public function postCommunication(Batchblue_Service_BatchBook_Communication $communication)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/communications.xml'
        );
        $httpClient->setParameterPost(
            'communication[subject]',
            $communication->getSubject()
        );
        $httpClient->setParameterPost(
            'communication[body]',
            $communication->getBody()
        );
        $httpClient->setParameterPost(
            'communication[date]',
            $communication->getDate()
        );
        $httpClient->setParameterPost(
            'communication[ctype]',
            $communication->getCtype()
        );
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::POST);
        if (201 != $response->getStatus()) {
            //TODO: throw more specific exception
            var_dump($response);
            throw new Exception('Communication not created.');
        }
        $location = $response->getHeader('location');
        $httpClient = new Zend_Http_Client($location);
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::GET);
        $xmlResponse = simplexml_load_string($response->getBody());
        $this->_populateCommunicationFromXmlElement($xmlResponse, $communication);
        return $this;
    }

    /**
     * Put Communication
     *
     * @param Batchblue_Service_BatchBook_Communication $communication
     * @return Batchblue_Service_BatchBook_CommunicationService   Provides a fluent interface
     */
    public function putCommunication(Batchblue_Service_BatchBook_Communication $communication)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/communications/' . $communication->getId() . '.xml'
        );
        $paramsPut = array(
            'communication[subject]'    => $communication->getSubject(),
            'communication[body]'     => $communication->getBody(),
            'communication[date]'         => $communication->getDate(),
            'communication[ctype]'       => $communication->getCtype(),
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
            throw new Exception('Communication not updated.');
        }
        return $this;
    }

    /**
     * Delete Communication
     *
     * @param Batchblue_Service_BatchBook_Communication $communication
     * @return Batchblue_Service_BatchBook_CommunicationService   Provides a fluent interface
     */
    public function deleteCommunication(Batchblue_Service_BatchBook_Communication $communication)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/communications/' . $communication->getId() . '.xml'
        );
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::DELETE);
        if (200 != $response->getStatus()) {
            //TODO: throw more specific exception
            throw new Exception('Communication not deleted.');
        }
        return $this;
    }

    public function addParticipant(Batchblue_Service_BatchBook_Communication $communication, $id, $role)
    {
        $paramsPut = array(
            'contact_id'    => $id,
            'role'     => $role,
        );
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/communications/' . $communication->getId() . '/add_participant.xml'
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
            throw new Exception('Participant not added.');
        }
        return $this;
        
    }

    public function addTag(Batchblue_Service_BatchBook_Communication $communication, $tag)
    {
        $paramsPut = array(
            'tag'     => $tag,
        );
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/communications/' . $communication->getId() . '/add_tag.xml'
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
            throw new Exception('Tag not added.');
        }
        return $this;
        
    }
}
