<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class stageModelTest extends baseTest
{
    protected $moduleName = 'stage';
    protected $className  = 'model';

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
        $this->instance->config->setPercent = 1;
        $this->instance->config->stage->create->requiredFields = 'type,name,percent';
        if($type == 'waterfall') $stage->workflowGroup = 4;
        if($type == 'waterfallplus') $stage->workflowGroup = 8;
        $stageID = $this->instance->create($stage);

        if(dao::isError()) return dao::getError();
        return $this->instance->dao->select('*')->from(TABLE_STAGE)->where('id')->eq($stageID)->fetch();
    }

    /**
     * 批量创建阶段。
     * Batch create stages.
     *
     * @param  array            $data
     * @param  int              $groupID
     * @access public
     * @return int|string|array
     */
    public function batchCreateTest(array $data, int $groupID = 0): int|string|array
    {
        $this->instance->config->setPercent = 1;
        $this->instance->config->stage->create->requiredFields = 'type,name,percent';

        $stages = array();
        foreach($data['name'] as $rowID => $value)
        {
            $stage = new stdclass();
            $stage->name = $value;
            $stage->type = $data['type'][$rowID];
            $stage->percent = $data['percent'][$rowID];

            $stages[] = $stage;
        }

        $this->instance->batchCreate($groupID, $stages);
        if(dao::isError()) return current(dao::getError());

        $objects = $this->instance->getStages('id_desc', 0, $groupID);
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
        $oldStage = $this->instance->getByID($stageID);

        $name    = isset($oldStage->name) ? $oldStage->name : '';
        $percent = isset($oldStage->percent) ? $oldStage->percent : 0;
        $type    = isset($oldStage->type) ? $oldStage->type : '';

        $stage = new stdclass();
        $stage->name = isset($data['name']) ? $data['name'] : $name;
        $stage->percent = isset($data['percent']) ? $data['percent'] : $percent;
        $stage->type = isset($data['type']) ? $data['type'] : $type;

        $changes = $this->instance->update($stageID, $stage);

        if(dao::isError()) return dao::getError();
        return $changes;
    }

    /**
     * 获取阶段列表信息。
     * Get stage list info.
     *
     * @param  string $orderBy
     * @param  int    $projectID
     * @param  int    $groupID
     * @param  int    $grade
     * @access public
     * @return array
     */
    public function getStagesTest(string $orderBy = 'id_desc', int $projectID = 0, int $groupID = 0, int $grade = 0): array
    {
        su('admin', true);

        $stages = $this->instance->getStages($orderBy, $projectID, $groupID, $grade);

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
        $stage = $this->instance->getByID($stageID);

        if(dao::isError()) return dao::getError();
        return $stage;
    }

    /**
     * 获取给定模型下的阶段总百分比。
     * Get total percent of the type.
     *
     * @param  int       $groupID
     * @access public
     * @return float|array
     */
    public function getTotalPercentTest(int $groupID): float|array
    {
        $totalPercent = $this->instance->getTotalPercent($groupID);

        if(dao::isError()) return dao::getError();
        return $totalPercent;
    }

    /**
     * 设置阶段导航。
     * Set menu.
     *
     * @param  string $module
     * @param  string $method
     * @access public
     * @return string
     */
    public function setMenuType(string $module, string $method): string
    {
        global $app;

        $originalRawModule = $app->rawModule ?? null;
        $originalRawMethod = $app->rawMethod ?? null;
        $originalRawParams = $app->rawParams ?? null;

        $app->rawModule = $module;
        $app->rawMethod = $method;
        $app->rawParams = array();

        $admin   = $this->getInstance('admin', 'model');
        $menuKey = $admin->getMenuKey();

        if(empty($menuKey))
        {
            $app->rawModule = $originalRawModule;
            $app->rawMethod = $originalRawMethod;
            $app->rawParams = $originalRawParams;
            return '0';
        }

        $admin->setMenu();
        $stageTab = $admin->lang->admin->menuList->feature['tabMenu']['execution']['stage'] ?? null;

        $app->rawModule = $originalRawModule;
        $app->rawMethod = $originalRawMethod;
        $app->rawParams = $originalRawParams;

        if(empty($stageTab)) return '0';

        $linkParts = explode('|', $stageTab['link']);
        $link      = isset($linkParts[1], $linkParts[2], $linkParts[3]) ? "{$linkParts[1]}|{$linkParts[2]}|{$linkParts[3]}" : '';
        $links     = empty($stageTab['links']) ? '' : implode(',', $stageTab['links']);

        return trim(implode(',', array_filter(array($link, $stageTab['subModule'] ?? '', $links), fn($value) => $value !== '')), ',');
    }
}
