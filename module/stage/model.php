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
     * @param  object   $stage
     * @param  string   $type  waterfall|waterfallplus
     * @access public
     * @return int|bool
     */
    public function create(object $stage, string $type = 'waterfall'): int|bool
    {
        if(isset($this->config->setPercent) && $this->config->setPercent == 1)
        {
            $totalPercent = $this->getTotalPercent($type);

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

        $this->dao->insert(TABLE_STAGE)
            ->data($stage)
            ->autoCheck()
            ->batchCheck($this->config->stage->create->requiredFields, 'notempty')
            ->checkIF($stage->percent != '', 'percent', 'float')
            ->exec();

        if(dao::isError()) return false;
        return $this->dao->lastInsertID();
    }

    /**
     * 批量创建阶段。
     * Batch create stages.
     *
     * @param  string  $type   waterfall|waterfallplus
     * @param  array   $stages
     * @access public
     * @return bool
     */
    public function batchCreate($type = 'waterfall', array $stages = array()): bool
    {
        $setPercent = (isset($this->config->setPercent) && $this->config->setPercent == 1) ? true : false;
        if($setPercent)
        {
            $oldTotalPercent = $this->getTotalPercent($type);
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
            $stage->projectType = $type;
            $this->dao->insert(TABLE_STAGE)->data($stage)->autoCheck()
                ->batchCheck($this->config->stage->create->requiredFields, 'notempty')
                ->checkIF($stage->percent != '', 'percent', 'float')
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
            $totalPercent = $this->getTotalPercent($oldStage->projectType);
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
            ->checkIF($stage->percent != '', 'percent', 'float')->where('id')->eq((int)$stageID)
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
     * @param  string $type
     * @access public
     * @return array
     */
    public function getStages(string $orderBy = 'id_desc', int $projectID = 0, string $type = ''): array
    {
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

        $stageType = '';
        if($this->config->systemMode == 'PLM' && $this->app->rawMethod == 'create' && $this->app->rawModule == 'programplan' && $type == 'ipd')
        {
            $project   = $this->loadModel('project')->getByID($this->session->project);
            $stageType = $this->config->project->categoryStages[$project->category];
        }

        return $this->dao->select('*')->from(TABLE_STAGE)
            ->where('deleted')->eq(0)
            ->andWhere('projectType')->eq($type)
            ->beginIF(!empty($stageType))->andWhere('type')->in($stageType)->fi()
            ->orderBy($orderBy)
            ->fetchAll('id');
    }

    /**
     * Get pairs of stage.
     *
     * @access public
     * @return array
     */
    public function getPairs()
    {
        $stages = $this->getStages();

        $pairs = array();
        foreach($stages as $stageID => $stage)
        {
            $pairs[$stageID] = $stage->name;
        }

        return $pairs;
    }

    /**
     * Get a stage by id.
     *
     * @param  int    $stageID
     * @access public
     * @return object
     */
    public function getByID($stageID)
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
    public function getTotalPercent(string $type): float
    {
        return (float)$this->dao->select('sum(percent) as total')->from(TABLE_STAGE)->where('deleted')->eq('0')->andWhere('projectType')->eq($type)->fetch('total');
    }

    /**
     * 设置阶段导航。
     * Set menu.
     *
     * @param  string $type waterfall|waterfallplus
     * @access public
     * @return void
     */
    public function setMenu(string $type): void
    {
        $this->app->loadLang('admin');
        $moduleName = $this->app->rawModule;
        $methodName = $this->app->rawMethod;

        if(!isset($this->lang->admin->menuList->model['subMenu']['waterfall']['exclude'])) $this->lang->admin->menuList->model['subMenu']['waterfall']['exclude'] = '';
        if(!isset($this->lang->admin->menuList->model['subMenu']['waterfallplus']['exclude'])) $this->lang->admin->menuList->model['subMenu']['waterfallplus']['exclude'] = '';
        if($type == 'waterfall')
        {
            $this->lang->admin->menuList->model['subMenu']['waterfallplus']['exclude'] .= ",{$moduleName}-{$methodName}";
            unset($this->lang->admin->menuList->model['subMenu']['scrum']['subModule']);
            unset($this->lang->admin->menuList->model['subMenu']['scrumplus']['subModule']);
            unset($this->lang->admin->menuList->model['subMenu']['waterfallplus']['subModule']);
        }
        if($type == 'waterfallplus')
        {
            $this->lang->admin->menuList->model['subMenu']['waterfall']['exclude'] .= ",{$moduleName}-{$methodName}";
            unset($this->lang->admin->menuList->model['subMenu']['scrum']['subModule']);
            unset($this->lang->admin->menuList->model['subMenu']['waterfall']['subModule']);
            unset($this->lang->admin->menuList->model['subMenu']['scrumplus']['subModule']);
        }
    }
}
