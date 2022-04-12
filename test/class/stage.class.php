<?php
class stageTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('stage');
    }

    /**
     * Test create a stage.
     *
     * @param  object $stage
     * @access public
     * @return object
     */
    public function createTest($stage)
    {
        foreach($stage as $key => $value) $_POST[$key] = $value;

        $objectID = $this->objectModel->create();

        unset($_POST);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getByID($objectID);
        return $object;
    }

    /**
     * Test batch create stages.
     *
     * @param  array  $param
     * @access public
     * @return int
     */
    public function batchCreateTest($param)
    {
        foreach($param as $key => $value) $_POST[$key] = $value;

        $this->objectModel->batchCreate();

        unset($_POST);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getStages();
        return count($objects);
    }

    /**
     * Test update a stage.
     *
     * @param  int    $stageID
     * @param  array  $param
     * @access public
     * @return array
     */
    public function updateTest($stageID, $param)
    {
        foreach($param as $key => $value) $_POST[$key] = $value;

        $objects = $this->objectModel->update($stageID);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get stages.
     *
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getStagesTest($orderBy = 'id_desc')
    {
        $objects = $this->objectModel->getStages($orderBy = 'id_desc');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get pairs of stage.
     *
     * @access public
     * @return array
     */
    public function getPairsTest()
    {
        $objects = $this->objectModel->getPairs();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get a stage by id.
     *
     * @param  int    $stageID
     * @access public
     * @return object
     */
    public function getByIDTest($stageID)
    {
        $object = $this->objectModel->getByID($stageID);

        if(dao::isError()) return dao::getError();

        return $object;
    }
}
