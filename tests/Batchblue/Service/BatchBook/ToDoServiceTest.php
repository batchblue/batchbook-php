

<?php


require('config.php');

/**
 * Test class for ToDoService
 *
 * @group Batchblue
 */
class Batchblue_Service_BatchBook_ToDoServiceTest extends PHPUnit_Framework_TestCase 
{

    /**
     * @var Batchblue_Service_BatchBook_ToDoService
     */
    private $_toDoService; 

    public function setUp()
    { 
        global $Batchblue_Service_ACCOUNT_NAME , $Batchblue_Service_TOKEN;

        $this->_toDoService = new Batchblue_Service_BatchBook_ToDoService(
            $Batchblue_Service_ACCOUNT_NAME , 
            $Batchblue_Service_TOKEN 
        );



    }


    /**
     * @return integer
     */
    public function testPostToDo()
    {
        $toDo = new Batchblue_Service_BatchBook_ToDo();
        $toDo
            ->setTitle('Test ToDo Title')
            ->setDescription('Test ToDo Description') 
           
        ;
        $this->_toDoService->postToDo($toDo);
        $this->assertGreaterThan(
            0,
            $toDo->getId()
        );
        return $toDo;
    }

    /**
     * @depends testPostToDo
     * @param Batchblue_Service_BatchBook_ToDo $toDo
     * @return Batchblue_Service_BatchBook_ToDo
     */
    public function testGetToDo(Batchblue_Service_BatchBook_ToDo $toDo)
    {
        $originalToDo = clone $toDo;
        $toDo = $this->_toDoService->getToDo($toDo->getId());
        $this->assertEquals(
            $originalToDo,
            $toDo
        );
        return $toDo;
    }

    /**
     * @depends testGetToDo
     * @param Batchblue_Service_BatchBook_ToDo $toDo
     * @return Batchblue_Service_BatchBook_ToDo
     */
    public function testPutToDo(Batchblue_Service_BatchBook_ToDo $toDo)
    {
        $toDo
            ->setTitle('Test ToDo Title updated')
            ->setDescription('Test ToDo Description updated') 
        ;
        $this->_toDoService->putToDo($toDo);
        $getToDo = $this->_toDoService->getToDo($toDo->getId());
        $this->assertEquals(
            $toDo,
            $getToDo
        );
        return $toDo;
    }




    /**
     * @depends testPutToDo
     * @param Batchblue_Service_BatchBook_ToDo $toDo
     * @return void
     */
    public function testDeleteToDo(Batchblue_Service_BatchBook_ToDo $toDo)
    { 
 //       $this->_toDoService->deleteToDo($toDo);
  //      $getToDo = $this->_toDoService->getToDo($toDo->getId()); 
  //      $this->assertNull($getToDo);

        //TODO:  Intentially not deleting TODO items for purposes of testing missing TODO items that are created via the API
        $this->markTestIncomplete("Not deleting ToDo's until we figure out why created ToDo's don't show up on dashboard and calendar.... "); 
 


    }
}
