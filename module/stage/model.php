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

        if(!empty($stage->percent)) $stage->percent = (float)$stage->percent;
        $this->dao->insert(TABLE_STAGE)
            ->data($stage)
            ->autoCheck()
            ->batchCheck($this->config->stage->create->requiredFields, 'notempty')
            ->exec();

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

        $this->loadModel('action');
        foreach($stages as $rowID => $stage)
        {
            $stage->workflowGroup = $groupID;
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
            if(round($totalPercent + (float)$stage->percent - $oldStage->percent) > 100)
            {
                dao::$errors['percent'] = $this->lang->stage->error->percentOver;
                return false;
            }
        }

        $this->dao->update(TABLE_STAGE)
            ->data($stage)
            ->autoCheck()
            ->batchCheck($this->config->stage->edit->requiredFields, 'notempty')
            ->checkIF(isset($stage->percent) && $stage->percent != '', 'percent', 'float')->where('id')->eq((int)$stageID)
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
     * @access public
     * @return array
     */
    public function getStages(string $orderBy = 'id_desc', int $projectID = 0, int $groupID = 0): array
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
                ->andWhere('project')->eq($projectID)
                ->orderBy($orderBy)
                ->fetchAll('id');
        }

        return $this->dao->select('*')->from(TABLE_STAGE)
            ->where('deleted')->eq(0)
            ->andWhere('workflowGroup')->eq($groupID)
            ->orderBy($orderBy)
            ->fetchAll('id');
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
        if($workflowGroup->projectModel != 'ipd') return;

        $builtinPoints = $this->dao->select('*')->from(TABLE_DECISION)->where('workflowGroup')->eq($groupID)->andWhere('builtin')->eq('1')->fetchAll();
        if(!empty($builtinPoints)) return;

        $this->app->loadConfig('review');
        $this->app->loadConfig('project');
        $stageList = $this->dao->select('*')->from(TABLE_STAGE)->where('workflowGroup')->eq($groupID)->fetchAll('id');
        if(empty($stageList))
        {
            $stageTypeList = array();
            if($workflowGroup->projectType == 'ipd') $stageTypeList = $this->config->project->categoryStages['IPD'];
            if($workflowGroup->projectType == 'tpd') $stageTypeList = $this->config->project->categoryStages['TPD'];
            if($workflowGroup->projectType == 'cbb') $stageTypeList = $this->config->project->categoryStages['CBB'];
            if(in_array($workflowGroup->projectType, array('cpdproduct', 'cpdproject'))) $stageTypeList= $this->config->project->categoryStages['CPD'];

            foreach($stageTypeList as $stageType)
            {
                $builtinStage = new stdClass();
                $builtinStage->workflowGroup = $groupID;
                $builtinStage->createdBy     = 'system';
                $builtinStage->createdDate   = helper::now();
                $builtinStage->name          = zget($this->lang->stage->ipdTypeList, $stageType, '');
                $builtinStage->type          = $stageType;
                $this->dao->insert(TABLE_STAGE)->data($builtinStage)->exec();

                $stageID = $this->dao->lastInsertID();
                $stageList[$stageID] = $builtinStage;
            }
        }

        $decision = new stdClass();
        $decision->builtin       = '1';
        $decision->createdBy     = 'system';
        $decision->createdDate   = helper::now();
        $decision->workflowGroup = $groupID;
        foreach($stageList as $id => $stage)
        {
            foreach($this->config->review->ipdReviewPoint->{$stage->type} as $point)
            {
                $decision->stage    = $id;
                $decision->title    = $point;
                $decision->type     = strpos($point, 'TR') !== false ? 'TR' : 'DCP';
                $decision->category = $point;
                $this->dao->insert(TABLE_DECISION)->data($decision)->exec();
            }
        }
    }
}
