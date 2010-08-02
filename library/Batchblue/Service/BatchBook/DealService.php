<?php
/** 
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */

/**
 * Deal Service
 *
 * @category    Batchblue
 * @package     Batchblue_Service
 * @subpackage  BatchBook
 */
class Batchblue_Service_BatchBook_DealService
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
     * Construct new Deal Service
     *
     * @param string $token
     */
    public function __construct($accountName, $token)
    {
        $this->_accountName = (string) $accountName;
        $this->_token = (string) $token;
    }



    /**
     * Create Deal From XML
     *
     * @param SimpleXMLElement $xmlElement
     * @return Batchblue_Service_BatchBook_Deal
     */
    private function _populateDealFromXmlElement(
        SimpleXMLElement $xmlElement,
        Batchblue_Service_BatchBook_Deal $deal = null
    )
    {
        if (null === $deal) {
            $deal = new Batchblue_Service_BatchBook_Deal();
        }
        $deal
            ->setId($xmlElement->id)
            ->setTitle($xmlElement->title)
            ->setDescription($xmlElement->description)
            ->setStatus($xmlElement->status);
        return $deal;
    }


    /**
     * Index Of Deals
     *
     * @param string $name
     * @param string $email
     * @param integer $offset
     * @param integer $limit
     * @return array
     */
    public function indexOfDeals($status = null,$assignedToEmail = null)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/deals.xml'
        );
        if (null !== $status) {
            $httpClient->setParameterGet('status', $status);
        }
        if (null !== $assignedToEmail) {
            $httpClient->setParameterGet('assigned_to', $assignedToEmail);
        }

        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::GET);
        $xmlResponse = simplexml_load_string($response->getBody());
        $deals = array();
        foreach ($xmlResponse->deal as $dealElement) {
            $deals[] = $this->_populateDealFromXmlElement($dealElement);
        }
        return $deals;
    }


    /**
     * Get Deal
     *
     * @param integer $id
     * @return Batchblue_Service_BatchBook_Deal
     */
    public function getDeal($id)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/deals/' . $id . '.xml'
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
                throw new Exception('Could not get Deal.');
        } 
        $xmlResponse = simplexml_load_string($response->getBody());
        return $this->_populateDealFromXmlElement($xmlResponse);
    }




    /**
     * Post Deal
     *
     * @param Batchblue_Service_BatchBook_Deal $deal
     * @return Batchblue_Service_BatchBook_DealService   Provides a fluent interface
     */
    public function postDeal(Batchblue_Service_BatchBook_Deal $deal)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/deals.xml'
        );

        $httpClient->setParameterPost(
            'deal[title]',
            $deal->getTitle()
        );
        $httpClient->setParameterPost(
            'deal[amount]',
            $deal->getAmount()
        );
        $httpClient->setParameterPost(
            'deal[status]',
            $deal->getStatus()
        );
        $httpClient->setParameterPost(
            'deal[description]',
            $deal->getDescription()
        );

        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::POST);
        if (201 != $response->getStatus()) {
            //TODO: throw more specific exception
            throw new Exception('Deal not created.');
        }
        $location = $response->getHeader('location');
        $httpClient = new Zend_Http_Client($location);
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::GET);
        $xmlResponse = simplexml_load_string($response->getBody());
        $this->_populateDealFromXmlElement($xmlResponse, $deal);
        return $this;
    }

    /**
     * Put Deal
     *
     * @param Batchblue_Service_BatchBook_Deal $deal
     * @return Batchblue_Service_BatchBook_DealService   Provides a fluent interface
     */
    public function putDeal(Batchblue_Service_BatchBook_Deal $deal)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/deals/' . $deal->getId() . '.xml'
        );
        $paramsPut = array(
            'deal[title]'    => $deal->getTitle(),
            'deal[description]'     => $deal->getDescription(),
            'deal[amount]'         => $deal->getAmount(),
            'deal[status]'       => $deal->getStatus(), 
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
            throw new Exception('Deal not updated.');
        }
        return $this;
    }

    /**
     * Delete Deal
     *
     * @param Batchblue_Service_BatchBook_Deal $deal
     * @return Batchblue_Service_BatchBook_DealService   Provides a fluent interface
     */
    public function deleteDeal(Batchblue_Service_BatchBook_Deal $deal)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/deals/' . $deal->getId() . '.xml'
        );
        $httpClient->setAuth($this->_token, 'x');
        $response = $httpClient->request(Zend_Http_Client::DELETE);
        if (200 != $response->getStatus()) {
            //TODO: throw more specific exception
            throw new Exception('Deal not deleted.');
        }
        return $this;
    }


}

