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
     * @param  string $type
     * @access public
     * @return object
     */
    public function createTest($stage, $type = 'waterfall')
    {
        foreach($stage as $key => $value) $_POST[$key] = $value;

        $objectID = $this->objectModel->create($type);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getByID($objectID, $type);
        return $object;
    }

    /**
     * Test batch create stages.
     *
     * @param  array  $param
     * @param  string $type
     * @access public
     * @return int
     */
    public function batchCreateTest($param ,$type = 'waterfall')
    {
        foreach($param as $key => $value) $_POST[$key] = $value;

        $this->objectModel->batchCreate($type);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getStages('id_desc', 0, $type);
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

    /**
     * Test get a stage by id.
     *
     * @param  string $projectType
     * @access public
     * @return object
     */
    public function getTotalPercentTest($projectType)
    {
        $object = $this->objectModel->getTotalPercent($projectType);

        if(dao::isError()) return dao::getError();

        return $object;
    }
}
