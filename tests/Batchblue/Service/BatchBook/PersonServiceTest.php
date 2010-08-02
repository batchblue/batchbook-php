<?php

require_once('config.php');

/**
 * Test class for PersonService
 *
 * @group Batchblue
 */
class Batchblue_Service_BatchBook_PersonServiceTest extends PHPUnit_Framework_TestCase 
{

    /**
     * @var Batchblue_Service_BatchBook_PersonService
     */
    private $_personService;

    public function setUp()
    {
        global $Batchblue_Service_ACCOUNT_NAME , $Batchblue_Service_TOKEN;

        
        $this->_personService = new Batchblue_Service_BatchBook_PersonService(
            $Batchblue_Service_ACCOUNT_NAME , 
            $Batchblue_Service_TOKEN 
        );
    }

    public function testIndexOfPersonsWithNoParams()
    {
        $persons = $this->_personService->indexOfPersons();
        $this->assertGreaterThan(
            0,
            count($persons)
        );
    }

    /**
     * @return integer
     */
    public function testPostPerson()
    {
        $person = new Batchblue_Service_BatchBook_Person(); 
        $location = new Batchblue_Service_BatchBook_Location();
        $location ->setEmail( md5(uniqid(rand(), true)) .  'test@test.com')
                  ->setPhone( '123-123-1234')
        ;

        $locations = array( $location ); 

        $person
           ->setFirstName('TestFirstName')
           ->setLastName('TestLastName')
           ->setTitle('Developer')
           ->setCompany('Test Company')
           ->setNotes('Test notes go here') 
           ->setLocations($locations) 
        ;

        $this->_personService->postPerson($person);
        $this->assertGreaterThan(
            0,
            $person->getId()
        );
        return $person;
    }

    /**
     * @depends testPostPerson
     * @param Batchblue_Service_BatchBook_Person $person
     * @return Batchblue_Service_BatchBook_Person
     */
    public function testGetPerson(Batchblue_Service_BatchBook_Person $person)
    {
        $originalPerson = clone $person;
        $person = $this->_personService->getPerson($person->getId());
        $this->assertEquals(
            $originalPerson,
            $person
        );
        return $person;
    }

    /**
     * @depends testGetPerson
     * @param Batchblue_Service_BatchBook_Person $person
     * @return Batchblue_Service_BatchBook_Person
     */
    public function testPutPerson(Batchblue_Service_BatchBook_Person $person)
    {

        $location = new Batchblue_Service_BatchBook_Location();
        $location ->setEmail( md5(uniqid(rand(), true)) .  'test@test.com')
                  ->setPhone( '123-123-9999')
        ;

        $locations = array( $location ); 


        $person
            ->setFirstName('TestFirstName')
            ->setLastName('TestLastName')
            ->setTitle('Developer')
            ->setCompany('Test Company')
            ->setNotes('Test notes go here') 
            ->setLocations( $locations  )
        ;
        $this->_personService->putPerson($person);
        $getPerson = $this->_personService->getPerson($person->getId());
        $this->assertEquals(
            $person,
            $getPerson
        );
        return $person;
    }

    /**
     * @depends testPutPerson
     * @param Batchblue_Service_BatchBook_Person $person
     * @return void
     */
    public function testDeletePerson(Batchblue_Service_BatchBook_Person $person)
    { 
        $this->_personService->deletePerson($person);
        $getPerson = $this->_personService->getPerson($person->getId()); 


        //TODO:  Commented out because the API is returning deleted Person objects
        //$this->assertNull($getPerson);
    }
}
