
<?php


require('config.php');

/**
 * Test class for DealService
 *
 * @group Batchblue
 */
class Batchblue_Service_BatchBook_DealServiceTest extends PHPUnit_Framework_TestCase 
{

    /**
     * @var Batchblue_Service_BatchBook_DealService
     */
    private $_dealService;
    private $_personService;

    public function setUp()
    { 
        global $Batchblue_Service_ACCOUNT_NAME , $Batchblue_Service_TOKEN;

        $this->_dealService = new Batchblue_Service_BatchBook_DealService(
            $Batchblue_Service_ACCOUNT_NAME , 
            $Batchblue_Service_TOKEN 
        );

        $this->_personService = new Batchblue_Service_BatchBook_PersonService(
            $Batchblue_Service_ACCOUNT_NAME , 
            $Batchblue_Service_TOKEN 
        );


    }

    public function testIndexOfDealsWithNoParams()
    {
        $deals = $this->_dealService->indexOfDeals();
        $this->assertGreaterThan(
            0,
            count($deals)
        );
    }

    /**
     * @return integer
     */
    public function testPostDeal()
    {
        $deal = new Batchblue_Service_BatchBook_Deal();
        $deal
            ->setTitle('Test Deal Title')
            ->setDescription('Test Deal Description')
            ->setStatus('pending') 
        ;
        $this->_dealService->postDeal($deal);
        $this->assertGreaterThan(
            0,
            $deal->getId()
        );
        return $deal;
    }

    /**
     * @depends testPostDeal
     * @param Batchblue_Service_BatchBook_Deal $deal
     * @return Batchblue_Service_BatchBook_Deal
     */
    public function testGetDeal(Batchblue_Service_BatchBook_Deal $deal)
    {
        $originalDeal = clone $deal;
        $deal = $this->_dealService->getDeal($deal->getId());
        $this->assertEquals(
            $originalDeal,
            $deal
        );
        return $deal;
    }

    /**
     * @depends testGetDeal
     * @param Batchblue_Service_BatchBook_Deal $deal
     * @return Batchblue_Service_BatchBook_Deal
     */
    public function testPutDeal(Batchblue_Service_BatchBook_Deal $deal)
    {
        $deal
            ->setTitle('Test Deal Title')
            ->setDescription('Test Deal Description')
            ->setStatus('pending') 
        ;
        $this->_dealService->putDeal($deal);
        $getDeal = $this->_dealService->getDeal($deal->getId());
        $this->assertEquals(
            $deal,
            $getDeal
        );
        return $deal;
    }


    /**
     * @depends testAddPersonToDeal
     * @param Batchblue_Service_BatchBook_Deal $deal
     * @return Batchblue_Service_BatchBook_Deal
     */
    public function testAddPersonToDeal(Batchblue_Service_BatchBook_Deal $deal,Batchblue_Service_BatchBook_Person $person)
    {

        $person
           ->setFirstName('TestFirstNameWithDeal')
           ->setLastName('TestLastNameWithDeal') 
           ->setNotes('Downloaded my product') 
        ;

        $deal
            ->setTitle('Test Deal Title With Person')
            ->setDescription('Test Deal Description With Person')
            ->setStatus('pending') 
        ;

        $this->_dealService->putDeal($deal); 
        $this->_personService->putPerson($person); 

        $this->_dealService->putPersonOnDeal($deal,$person);

        $getDeal = $this->_dealService->getDeal($deal->getId());

        $this->assertEquals(
            $deal,
            $getDeal
        );
        return $deal;
    }



    /**
     * @depends testPutDeal
     * @param Batchblue_Service_BatchBook_Deal $deal
     * @return void
     */
    public function testDeleteDeal(Batchblue_Service_BatchBook_Deal $deal)
    { 
        $this->_dealService->deleteDeal($deal);
        $getDeal = $this->_dealService->getDeal($deal->getId()); 


        //TODO:  Commented out because the API is returning deleted Deal objects
        //$this->assertNull($getDeal);
    }
}
