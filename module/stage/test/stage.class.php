<?php
class stageTest
{
    public function __construct(string $account = 'admin')
    {
        global $tester, $app;
        $this->objectModel = $tester->loadModel('stage');

        su($account);

        $app->rawModule = 'stage';
        $app->rawMethod = 'browse';
        $app->setModuleName('stage');
        $app->setMethodName('browse');
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
     * 获取阶段列表信息。
     * Get stage list info.
     *
     * @param  string $orderBy
     * @param  int    $projectID
     * @param  string $type      waterfall|waterfallplus
     * @access public
     * @return array
     */
    public function getStagesTest(string $orderBy = 'id_desc', int $projectID = 0, string $type = ''): array
    {
        su('admin', true);

        $stages = $this->objectModel->getStages($orderBy, $projectID, $type);

        if(dao::isError()) return dao::getError();
        return $stages;
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

    /**
     * 设置阶段导航。
     * Set menu.
     *
     * @param  string $type   waterfall|waterfallplus
     * @param  string $method browse|browseplus
     * @access public
     * @return string
     */
    public function setMenuType(string $type, string $method): string
    {
        global $app;
        $app->rawMethod = $method;

        $this->objectModel->setMenu($type);

        $exclude = '';
        if(in_array($type, array('waterfall', 'waterfallplus')))
        {
            $exclude = $this->objectModel->lang->admin->menuList->model['subMenu'][$type]['exclude'];
        }

        return $exclude;
    }
}
