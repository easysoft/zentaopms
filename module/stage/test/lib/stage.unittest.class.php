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
     * 创建一个阶段。
     * Create a stage.
     *
     * @param  object       $stage
     * @param  string       $type  waterfall|waterfallplus
     * @access public
     * @return object|array
     */
    public function createTest(object $stage, string $type = 'waterfall'): object|array
    {
        $this->objectModel->config->setPercent = 1;
        $this->objectModel->config->stage->create->requiredFields = 'type,name,percent';
        $stageID = $this->objectModel->create($stage, $type);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('*')->from(TABLE_STAGE)->where('id')->eq($stageID)->fetch();
    }

    /**
     * 批量创建阶段。
     * Batch create stages.
     *
     * @param  array            $data
     * @param  string           $type waterfall|waterfallplus
     * @access public
     * @return int|string|array
     */
    public function batchCreateTest(array $data, string $type = 'waterfall'): int|string|array
    {
        $this->objectModel->config->setPercent = 1;
        $this->objectModel->config->stage->create->requiredFields = 'type,name,percent';

        $stages = array();
        foreach($data['name'] as $rowID => $value)
        {
            $stage = new stdclass();
            $stage->name = $value;
            $stage->type = $data['type'][$rowID];
            $stage->percent = $data['percent'][$rowID];

            $stages[] = $stage;
        }

        $this->objectModel->batchCreate($type, $stages);
        if(dao::isError()) return current(dao::getError());

        $objects = $this->objectModel->getStages('id_desc', 0, $type);
        return count($objects);
    }

    /**
     * 编辑一个阶段。
     * Update a stage.
     *
     * @param  int        $stageID
     * @param  array      $data
     * @access public
     * @return array|bool
     */
    public function updateTest(int $stageID, array $data): array|bool
    {
        $oldStage = $this->objectModel->getByID($stageID);

        $name    = isset($oldStage->name) ? $oldStage->name : '';
        $percent = isset($oldStage->percent) ? $oldStage->percent : 0;
        $type    = isset($oldStage->type) ? $oldStage->type : '';

        $stage = new stdclass();
        $stage->name = isset($data['name']) ? $data['name'] : $name;
        $stage->percent = isset($data['percent']) ? $data['percent'] : $percent;
        $stage->type = isset($data['type']) ? $data['type'] : $type;

        $changes = $this->objectModel->update($stageID, $stage);

        if(dao::isError()) return dao::getError();
        return $changes;
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
     * 通过ID获取阶段信息。
     * Get stage info by ID.
     *
     * @param  int         $stageID
     * @access public
     * @return object|bool
     */
    public function getByIDTest(int $stageID): object|bool
    {
        $stage = $this->objectModel->getByID($stageID);

        if(dao::isError()) return dao::getError();
        return $stage;
    }

    /**
     * 获取给定模型下的阶段总百分比。
     * Get total percent of the type.
     *
     * @param  string    $projectType waterfall|waterfallplus
     * @access public
     * @return int|array
     */
    public function getTotalPercentTest(string $projectType): int|array
    {
        $totalPercent = $this->objectModel->getTotalPercent($projectType);

        if(dao::isError()) return dao::getError();
        return $totalPercent;
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
