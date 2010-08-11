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
        $originalPerson = $person;
        $getPerson = $this->_personService->getPerson($person->getId());

        $this->assertEquals(
            $originalPerson->getFirstName(),
            $getPerson->getFirstName()
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

        //the id is in there when it comes back, so to make the test pass, put it in!
        $getLocations = $getPerson->getLocations();
        $location->setId(  $getLocations[0]->getId() );

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
    public function testAddTag(Batchblue_Service_BatchBook_Person $person)
    { 

        $tagA = new Batchblue_Service_BatchBook_Tag();
        $tagA->setName( 'my_test_tag_a' . md5(uniqid(rand(), true)) );

        $tagB = new Batchblue_Service_BatchBook_Tag();
        $tagB->setName(  'my_test_tag_b' . md5(uniqid(rand(), true)) );

        $this->_personService->addTag($person,$tagA);
        $this->_personService->addTag($person,$tagB);

        $getPerson = $this->_personService->getPerson($person->getId()); 

        $tags = $getPerson->getTags();

        $this->assertGreaterThanOrEqual(2, count($tags) ); 

        $foundTagA = false;
        $foundTagB = false;

        foreach( $tags as $currentTag ) {
            if( $currentTag->getName() == $tagA->getName() ) {
                $foundTagA = true;
            }

            if( $currentTag->getName() == $tagB->getName() ) {
                $foundTagB = true;
            } 
        }
        

        $this->assertTrue( $foundTagA, "Looking for tagA: " . $tagA->getName() );
        $this->assertTrue( $foundTagB, "Looking for tagB: " . $tagB->getName() );
     
     
    }



    /** 
     * @depends testPutPerson
     * @param Batchblue_Service_BatchBook_Person $person
     * @return void
     */

    public function testAddSuperTag(Batchblue_Service_BatchBook_Person $person)
    { 
        /**
            NOTE:  This test will only work if you have created a SuperTag via the HTML interface as follows: 

            Name:  MY_TEST_SUPER_TAG_A
            Fields:  key (text)
                     key With Space (number)
        */

        /**
        $tagA = new Batchblue_Service_BatchBook_SuperTag();

        $fields = array ( "key" => "my23customvalue", 
                          "key With Space" => "99" ); 

        $tagA->setName( 'my_test_super_tag_a');
        $tagA->setFields( $fields );

        $this->_personService->addSuperTag($person,$tagA); 
        **/
   
        $this->markTestIncomplete("Test is incomplete. We can't really test this until the REST API supports creating SuperTags."); 
     
    } 


    /**
     * @depends testPutPerson
     * @param Batchblue_Service_BatchBook_Person $person
     * @return void
     */
    public function testDeletePerson(Batchblue_Service_BatchBook_Person $person)
    { 
//        $this->_personService->deletePerson($person);
        $getPerson = $this->_personService->getPerson($person->getId()); 

        //TODO:  Test is incomplete because the API is returning deleted Person objects
        $this->markTestIncomplete("Test is incomplete as the REST API currently returns deleted Person objects"); 
        // When REST API is fixed, remove comment below and delete the markTestIncomplete() line above
        //$this->assertNull($getPerson);
    }





}
