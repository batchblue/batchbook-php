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


        $locations =  array(); 
        $tags =  array(); 

        

        foreach( $xmlElement->tags->tag as $xmlTag ) { 
            $tag = new Batchblue_Service_BatchBook_Tag();
            $tag->setName( $xmlTag['name'] )
                  ; 

            array_push( $tags,$tag); 
        } 
 
        foreach( $xmlElement->locations->location as $xmlLocation ) {
            

            $location = new Batchblue_Service_BatchBook_Location();
            $location
                     ->setId( $xmlLocation->id )
                     ->setLabel( $xmlLocation->label )
                     ->setEmail( $xmlLocation->email )
                     ->setWebsite( $xmlLocation->website )
                     ->setPhone( $xmlLocation->phone )
                     ->setCell( $xmlLocation->cell )
                     ->setFax( $xmlLocation->fax )
                     ->setStreet1( $xmlLocation->street_1 )
                     ->setStreet2( $xmlLocation->street_2 )
                     ->setCity( $xmlLocation->city )
                     ->setState( $xmlLocation->state )
                     ->setPostalCode( $xmlLocation->postal_code )
                     ->setCountry( $xmlLocation->country )
            ; 

            array_push( $locations,$location); 
        } 
        
        $person->setLocations( $locations );                 
        $person->setTags( $tags );                 

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
            $httpClient->setParameterGet('email', $email );
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


        $personLocations = $person->getLocations();


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

        if( $personLocations != null ) {
            $this->postLocationsOnPerson($person,$personLocations ); 
        }

        return $this;
    }


    /**
     * Post Locations on a Person
     *
     * @param Batchblue_Service_BatchBook_Person $person
     * @param array $locations
     * @return 
     */
    
    public function postLocationsOnPerson(Batchblue_Service_BatchBook_Person $person,array $locations) {
        //If there is a location set on this person, then add it
        if( $locations != null ) { 

            $personIdAsStr = strval($person->getId());

            $httpClient = new Zend_Http_Client(
                'https://' . $this->_accountName . '.batchbook.com/service/people/' . $personIdAsStr . '/locations.xml'
            ); 


            $httpClient->setAuth($this->_token, 'x'); 

            foreach( $locations as $aLocation ) { 

                $httpClient->setParameterPost(
                    'location[label]',
                    $aLocation->getLabel()
                );

                $httpClient->setParameterPost(
                    'location[email]',
                    $aLocation->getEmail()
                );

                $httpClient->setParameterPost(
                    'location[website]',
                    $aLocation->getWebsite()
                );

                $httpClient->setParameterPost(
                    'location[phone]',
                    $aLocation->getPhone()
                );

                $httpClient->setParameterPost(
                    'location[cell]',
                    $aLocation->getCell()
                );

                $httpClient->setParameterPost(
                    'location[fax]',
                    $aLocation->getFax()
                );

                $httpClient->setParameterPost(
                    'location[street_1]',
                    $aLocation->getStreet1()
                );

                $httpClient->setParameterPost(
                    'location[street_2]',
                    $aLocation->getStreet2()
                );

                $httpClient->setParameterPost(
                    'location[city]',
                    $aLocation->getCity()
                );

                $httpClient->setParameterPost(
                    'location[state]',
                    $aLocation->getState()
                );

                $httpClient->setParameterPost(
                    'location[postal_code]',
                    $aLocation->getPostalCode()
                );


                $httpClient->setParameterPost(
                    'location[country]',
                    $aLocation->getCountry()
                ); 

                $response = $httpClient->request(Zend_Http_Client::POST); 


                if (201 != $response->getStatus()) {
                    //TODO: throw more specific exception 
                    throw new Exception('Location on Person not updated.');
                } 
            } 
        } 
    }

    /**
     * Put Person
     *
     * @param Batchblue_Service_BatchBook_Person $person
     * @return Batchblue_Service_BatchBook_PersonService   Provides a fluent interface */
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


        //update the locations
        $this->postLocationsOnPerson($person,$person->getLocations() ); 

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



    /**
     * Add Super Tag
     * 
     * NOTE: Super Tags cannot be created via the API, so they need to be created via the HTML interface before you apply them 
     *
     * @param Batchblue_Service_BatchBook_Person $person
     * @param string $tag
     */ 
    public function addSuperTag(Batchblue_Service_BatchBook_Person $person,Batchblue_Service_BatchBook_SuperTag $tag) {

        $realTagName = str_replace( ' ', '_',strtolower($tag->getName() ) );
        $reqUrl = 'https://' . $this->_accountName . '.batchbook.com/service/people/' . $person->getId() . '/super_tags/' . $realTagName . '.xml';
        error_log( 'requrl:' . $reqUrl );


        $httpClient = new Zend_Http_Client(
            $reqUrl 
        );


        $paramsPut = array();

        $fields = $tag->getFields();

        foreach( $fields as $key => $value ) { 
           
            //keys must be lower case and have spaces replaced with underscore 
            $realKey = str_replace( ' ', '_',strtolower($key) ); 
            $realValue = urlencode( $value ); 

            error_log('realKey:' . $realKey );
            error_log('realValue:' . $realValue );

            $paramsPut['super_tag[' . strtolower($realKey) . ']' ] = $value; 
        };

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
            throw new Exception('SuperTag \'' . $tag->getName() . '\' not added to Person with id=' . $person->getId() . "\n" . $response->getMessage() . "\n" .
            $response->getBody() . "\n" . $httpClient->getLastRequest() );
        } 

    } 


    /**
     * Add Tag
     *
     * @param Batchblue_Service_BatchBook_Person $person
     * @param string $tag
     */ 
    public function addTag(Batchblue_Service_BatchBook_Person $person,Batchblue_Service_BatchBook_Tag $tag)
    {
        $httpClient = new Zend_Http_Client(
            'https://' . $this->_accountName . '.batchbook.com/service/people/' . $person->getId() . '/add_tag.xml'
        );
        $paramsPut = array(
            'tag'    => $tag->getName(), 
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
            throw new Exception('Tag not added to person with id=' . $person->getId() );
        } 
    }



}
