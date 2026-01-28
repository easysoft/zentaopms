<?php
declare(strict_types=1);
/**
 * The model file of stage module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     stage
 * @version     $Id: model.php 5079 2013-07-10 00:44:34Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php
class stageModel extends model
{
    /**
     * 创建一个阶段。
     * Create a stage.
     *
     * @param  object $stage
     * @access public
     * @return int|bool
     */
    public function create(object $stage): int|bool
    {
        if(isset($this->config->setPercent) && $this->config->setPercent == 1)
        {
            $totalPercent = $this->getTotalPercent($stage->workflowGroup);

            if(!is_numeric($stage->percent))
            {
                dao::$errors['percent'] = $this->lang->stage->error->notNum;
                return false;
            }
            if(round($totalPercent + $stage->percent) > 100)
            {
                dao::$errors['percent'] = $this->lang->stage->error->percentOver;
                return false;
            }
        }

        $this->lang->error->unique = $this->lang->error->repeat;
        if(!empty($stage->percent)) $stage->percent = (float)$stage->percent;
        $this->dao->insert(TABLE_STAGE)
            ->data($stage)
            ->autoCheck()
            ->batchCheck($this->config->stage->create->requiredFields, 'notempty')
            ->checkIF(!empty($stage->name), 'name', 'unique', "`workflowGroup` = '$stage->workflowGroup' AND `deleted` = '0'")
            ->exec();
        $stageID = $this->dao->lastInsertID();

        $maxOrder = $this->dao->select('MAX(`order`) AS maxOrder')->from(TABLE_STAGE)->where('workflowGroup')->eq($stage->workflowGroup)->andWhere('deleted')->eq('0')->fetch('maxOrder');
        $this->dao->update(TABLE_STAGE)->set('order')->eq((int)$maxOrder + 1)->where('id')->eq($stageID)->exec();

        if(dao::isError()) return false;
        return $this->dao->lastInsertID();
    }

    /**
     * 批量创建阶段。
     * Batch create stages.
     *
     * @param  int     $groupID
     * @param  array   $stages
     * @access public
     * @return bool
     */
    public function batchCreate(int $groupID = 0, array $stages = array()): bool
    {
        $setPercent = (isset($this->config->setPercent) && $this->config->setPercent == 1) ? true : false;
        if($setPercent)
        {
            $oldTotalPercent = $this->getTotalPercent($groupID);
            $totalPercent    = 0;
            foreach($stages as $stage)
            {
                if($totalPercent > 100) break;
                $totalPercent += (float)$stage->percent;
            }

            if(round($oldTotalPercent + $totalPercent) > 100)
            {
                dao::$errors['message'] = $this->lang->stage->error->percentOver;
                return false;
            }
        }

        $stageNames = $this->dao->select('id,name')->from(TABLE_STAGE)->where('workflowGroup')->eq($groupID)->andWhere('deleted')->eq('0')->fetchPairs('name');
        foreach($stages as $rowID => $stage)
        {
            if(in_array($stage->name, $stageNames))
            {
                dao::$errors["name[$rowID]"] = sprintf($this->lang->error->repeat, $this->lang->stage->name, $stage->name);
                return false;
            }
            $stageNames[] = $stage->name;
        }

        $maxOrder = $this->dao->select('MAX(`order`) AS maxOrder')->from(TABLE_STAGE)->where('workflowGroup')->eq($groupID)->andWhere('deleted')->eq('0')->fetch('maxOrder');

        $this->loadModel('action');
        $now = helper::now();
        foreach($stages as $rowID => $stage)
        {
            $stage->workflowGroup = $groupID;
            $stage->createdBy     = $this->app->user->account;
            $stage->createdDate   = $now;
            if(!empty($stage->percent)) $stage->percent = (float)$stage->percent;
            $this->dao->insert(TABLE_STAGE)->data($stage)->autoCheck()
                ->batchCheck($this->config->stage->create->requiredFields, 'notempty')
                ->exec();

            if(dao::isError())
            {
                foreach(dao::getError() as $field => $error) dao::$errors["{$field}[{$rowID}]"] = $error;
                return false;
            }

            $stageID = $this->dao->lastInsertID();
            $this->action->create('stage', $stageID, 'Opened');

            $stageOrder = empty($maxOrder) ? 1 : ++ $maxOrder;
            $this->dao->update(TABLE_STAGE)->set('order')->eq($stageOrder)->where('id')->eq($stageID)->exec();
        }

        return true;
    }

    /**
     * 编辑一个阶段。
     * Update a stage.
     *
     * @param  int        $stageID
     * @param  object     $stage
     * @access public
     * @return bool|array
     */
    public function update(int $stageID, object $stage): bool|array
    {
        $oldStage = $this->dao->select('*')->from(TABLE_STAGE)->where('id')->eq((int)$stageID)->fetch();
        if(!$oldStage) return false;

        if(isset($this->config->setPercent) && $this->config->setPercent == 1)
        {
            $totalPercent = $this->getTotalPercent($oldStage->workflowGroup);
            if(round($totalPercent + (float)$stage->percent - (float)$oldStage->percent) > 100)
            {
                dao::$errors['percent'] = $this->lang->stage->error->percentOver;
                return false;
            }
        }

        $this->lang->error->unique = $this->lang->error->repeat;
        $this->dao->update(TABLE_STAGE)
            ->data($stage)
            ->autoCheck()
            ->batchCheck($this->config->stage->edit->requiredFields, 'notempty')
            ->checkIF(isset($stage->percent) && $stage->percent != '', 'percent', 'float')->where('id')->eq((int)$stageID)
            ->checkIF(!empty($stage->name), 'name', 'unique', "`id` != '$stageID' AND `workflowGroup` = '$oldStage->workflowGroup' AND `deleted` = '0'")
            ->exec();

        if(dao::isError()) return false;
        return common::createChanges($oldStage, $stage);
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
    public function getStages(string $orderBy = 'order_desc', int $projectID = 0, int $groupID = 0, int $grade = 0): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getStages();

        if($projectID)
        {
            return $this->dao->select('`id`,name,type,percent,openedBy as createdBy,`begin` as createdDate,lastEditedBy as editedBy,`end` as editedDate,deleted')
                ->from(TABLE_EXECUTION)
                ->where('type')->in('sprint,stage,kanban')
                ->andWhere('deleted')->eq('0')
                ->andWhere('vision')->eq($this->config->vision)
                ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
                ->beginIF($grade)->andWhere('grade')->eq($grade)->fi()
                ->andWhere('project')->eq($projectID)
                ->orderBy($orderBy)
                ->fetchAll('id');
        }

        $stages = $this->dao->select('*')->from(TABLE_STAGE)
            ->where('deleted')->eq(0)
            ->andWhere('workflowGroup')->eq($groupID)
            ->beginIF($this->config->edition != 'ipd')->andWhere('projectType')->ne('ipd')->fi()
            ->andWhere('type')->ne('lifecycle')
            ->orderBy($orderBy)
            ->fetchAll('id');

        $stagePoints = $this->dao->select('*')->from(TABLE_DECISION)->where('stage')->in(array_keys($stages))->andWhere('deleted')->eq('0')->orderBy('order_asc')->fetchGroup('stage');
        $pointList   = array();
        foreach($stagePoints as $stageID => $points)
        {
            if(!isset($pointList[$stageID])) $pointList[$stageID] = array();
            foreach($points as $point)
            {
                if(!isset($pointList[$stageID][$point->type])) $pointList[$stageID][$point->type] = array();
                $pointList[$stageID][$point->type][$point->id] = $point->title;
            }
        }
        foreach($stages as $stage)
        {
            $stage->TRpoint   = isset($pointList[$stage->id]['TR']) ? implode(', ', $pointList[$stage->id]['TR']) : '';
            $stage->DCPpoint  = isset($pointList[$stage->id]['DCP']) ? implode(', ', $pointList[$stage->id]['DCP']) : '';
            $stage->pointList = zget($pointList, $stage->id, array());
        }
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
    public function getByID(int $stageID): object|bool
    {
        return $this->dao->select('*')->from(TABLE_STAGE)->where('deleted')->eq(0)->andWhere('id')->eq((int)$stageID)->fetch();
    }

    /**
     * 获取给定模型下的阶段总百分比。
     * Get total percent of the type.
     *
     *  @param  string $type waterfall|waterfallplus
     *  @return float
     */
    public function getTotalPercent(int $groupID): float
    {
        return (float)$this->dao->select('sum(percent) as total')->from(TABLE_STAGE)->where('deleted')->eq('0')->andWhere('workflowGroup')->eq($groupID)->fetch('total');
    }

    /**
     * 添加内置评审点。
     * Add the builtin point.
     *
     *  @param  int    $groupID
     *  @return void
     */
    public function addBuiltinPoint(int $groupID)
    {
        $workflowGroup = $this->dao->select('*')->from(TABLE_WORKFLOWGROUP)->where('id')->eq($groupID)->fetch();

        $this->app->loadConfig('project');
        $stageList = $this->dao->select('*')->from(TABLE_STAGE)->where('workflowGroup')->eq($groupID)->fetchAll('id');
        if(empty($stageList))
        {
            $stageTypeList = array();
            if($workflowGroup->projectModel == 'ipd')
            {
                if($workflowGroup->projectType == 'ipd') $stageTypeList = $this->config->project->categoryStages['IPD'];
                if($workflowGroup->projectType == 'tpd') $stageTypeList = $this->config->project->categoryStages['TPD'];
                if($workflowGroup->projectType == 'cbb') $stageTypeList = $this->config->project->categoryStages['CBB'];
                if(in_array($workflowGroup->projectType, array('cpdproduct', 'cpdproject'))) $stageTypeList = $this->config->project->categoryStages['CPD'];
            }
            if(in_array($workflowGroup->projectModel, array('waterfall', 'waterfallplus'))) $stageTypeList = $this->config->project->categoryStages['waterfall'];

            foreach($stageTypeList as $stageType)
            {
                $builtinStage = new stdClass();
                $builtinStage->workflowGroup = $groupID;
                $builtinStage->createdBy     = 'system';
                $builtinStage->createdDate   = helper::now();
                $builtinStage->name          = in_array($workflowGroup->projectModel, array('waterfall', 'waterfallplus')) ? zget($this->lang->stage->typeList, $stageType, '') : zget($this->lang->stage->ipdTypeList, $stageType, '');
                $builtinStage->type          = $stageType;
                $builtinStage->projectType   = $workflowGroup->projectModel;
                $this->dao->insert(TABLE_STAGE)->data($builtinStage)->exec();

                $stageID = $this->dao->lastInsertID();
                $stageList[$stageID] = $builtinStage;
            }
        }
        if($workflowGroup->projectModel != 'ipd') return;

        $builtinPoints = $this->dao->select('*')->from(TABLE_DECISION)->where('workflowGroup')->eq($groupID)->andWhere('builtin')->eq('1')->fetchAll();
        if(!empty($builtinPoints)) return;

        $decision = new stdClass();
        $decision->builtin       = '1';
        $decision->createdBy     = 'system';
        $decision->createdDate   = helper::now();
        $decision->workflowGroup = $groupID;

        $decisionFlow = new stdClass();
        $decisionFlow->flow        = 1;
        $decisionFlow->objectType  = 'decision';
        $decisionFlow->relatedBy   = 'system';
        $decisionFlow->relatedDate = helper::now();
        foreach($stageList as $id => $stage)
        {
            foreach($this->config->stage->ipdReviewPoint->{$stage->type} as $index => $point)
            {
                $decision->stage    = $id;
                $decision->order    = $index + 1;
                $decision->title    = $point;
                $decision->type     = strpos($point, 'TR') !== false ? 'TR' : 'DCP';
                $decision->category = $point;
                $this->dao->insert(TABLE_DECISION)->data($decision)->exec();
                $decisionID = $this->dao->lastInsertID();

                $decisionFlow->root     = $stage->workflowGroup;
                $decisionFlow->objectID = $decisionID;
                $this->dao->insert(TABLE_APPROVALFLOWOBJECT)->data($decisionFlow)->exec();
            }
        }
    }

    /**
     * 获取阶段的评审点。
     * Get points of stage.
     *
     * @param  string $type TR|DCP
     * @param  int    $stageID
     * @access public
     * @return bool
     */
    public function getStagePoints(string $type, int $stageID)
    {
        $stage = $this->dao->select('*')->from(TABLE_STAGE)->where('id')->eq($stageID)->fetch();
        return $this->dao->select('t1.*,t2.flow')->from(TABLE_DECISION)->alias('t1')
            ->leftJoin(TABLE_APPROVALFLOWOBJECT)->alias('t2')->on("t1.id = t2.objectID AND t2.objectType = 'decision'")
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.stage')->eq($stageID)
            ->andWhere('t1.type')->eq($type)
            ->andWhere('t2.root')->eq($stage->workflowGroup)
            ->orderBy('order_asc')
            ->fetchAll('id');
    }

    /**
     * 设置阶段的评审点。
     * Set point of stage.
     *
     * @param  string $type TR|DCP
     * @param  int    $stageID
     * @param  array  $stageID
     * @access public
     * @return bool
     */
    public function setPoint(string $type, int $stageID, array $points): bool
    {
        $stage          = $this->getByID($stageID);
        $oldStagePoints = $this->getStagePoints($type, $stageID);

        foreach($points as $index => $point)
        {
            if(!empty($point->id) && isset($oldStagePoints[$point->id]))
            {
                $oldPoint = $oldStagePoints[$point->id];
                if($oldPoint->title != $point->title || $oldPoint->order != $index)
                {
                    $this->dao->update(TABLE_DECISION)
                        ->set('title')->eq($point->title)
                        ->set('order')->eq($index)
                        ->set('editedBy')->eq($this->app->user->account)
                        ->set('editedDate')->eq(helper::now())
                        ->where('id')->eq($point->id)
                        ->exec();
                    if(dao::isError()) return false;
                }
                if($oldPoint->flow != $point->flow)
                {
                    $this->dao->update(TABLE_APPROVALFLOWOBJECT)
                        ->set('flow')->eq($point->flow)
                        ->where('objectID')->eq($point->id)
                        ->andWhere('objectType')->eq('decision')
                        ->andWhere('root')->eq($stage->workflowGroup)
                        ->exec();
                    if(dao::isError()) return false;
                }
                unset($oldStagePoints[$point->id]);
            }
            else
            {
                $newPoint = new stdClass();
                $newPoint->workflowGroup = $stage->workflowGroup;
                $newPoint->stage         = $stageID;
                $newPoint->title         = $point->title;
                $newPoint->type          = $type;
                $newPoint->builtin       = '0';
                $newPoint->createdBy     = $this->app->user->account;
                $newPoint->createdDate   = helper::now();
                $newPoint->order         = $index;
                $this->dao->insert(TABLE_DECISION)->data($newPoint)->exec();
                $newPointID = $this->dao->lastInsertID();
                if(dao::isError()) return false;

                $approvalFlowObject = new stdClass();
                $approvalFlowObject->root        = $stage->workflowGroup;
                $approvalFlowObject->flow        = $point->flow;
                $approvalFlowObject->objectType  = 'decision';
                $approvalFlowObject->objectID    = $newPointID;
                $approvalFlowObject->relatedBy   = $this->app->user->account;
                $approvalFlowObject->relatedDate = helper::now();
                $approvalFlowObject->extra       = $type;
                $this->dao->insert(TABLE_APPROVALFLOWOBJECT)->data($approvalFlowObject)->exec();
                if(dao::isError()) return false;
            }
        }

        if(!empty($oldStagePoints))
        {
            $this->dao->update(TABLE_DECISION)->set('deleted')->eq('1')->where('id')->in(array_keys($oldStagePoints))->exec();
            $approvalFlowObjectIDList = $this->dao->select('id')->from(TABLE_APPROVALFLOWOBJECT)
                ->where('objectID')->in(array_keys($oldStagePoints))
                ->andWhere('objectType')->eq('decision')
                ->andWhere('root')->eq($stage->workflowGroup)
                ->fetchPairs();

            $this->dao->delete()->from(TABLE_APPROVALFLOWOBJECT)->where('id')->in($approvalFlowObjectIDList)->exec(); // 删除评审流程
            $this->dao->delete()->from(TABLE_REVIEWCL)->where('object')->in($approvalFlowObjectIDList)->exec(); // 删除检查清单
        }
        return true;
    }

    /**
     * 更新排序。
     * Update order.
     *
     * @param  array  $sortedIdList
     * @access public
     * @return void
     */
    public function updateOrder(array $sortedIdList)
    {
        foreach($sortedIdList as $stageID => $order)
        {
            $this->dao->update(TABLE_STAGE)->set('order')->eq($order + 1)->where('id')->eq($stageID)->exec();
        }
    }
}
