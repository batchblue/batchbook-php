<?php

/**
 * Test class for PersonService
 *
 * @group Batchblue
 */
class Batchblue_Service_BatchBook_PersonServiceTest extends PHPUnit_Framework_TestCase 
{
    const ACCOUNT_NAME = 'changeToYourAccount';
    const TOKEN = 'changeToYourToken';

    /**
     * @var Batchblue_Service_BatchBook_PersonService
     */
    private $_personService;

    public function setUp()
    {
        $this->_personService = new Batchblue_Service_BatchBook_PersonService(
            self::ACCOUNT_NAME,
            self::TOKEN
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
        $person
           ->setFirstName('TestFirstName')
           ->setLastName('TestLastName')
           ->setTitle('Developer')
           ->setCompany('Test Company')
           ->setNotes('Test notes go here')
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
        $person
            ->setFirstName('TestFirstName')
            ->setLastName('TestLastName')
            ->setTitle('Developer')
            ->setCompany('Test Company')
            ->setNotes('Test notes go here')
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
        $this->assertNull($getPerson);
    }
}
