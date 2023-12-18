<?php
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
     * Create a stage.
     *
     * @access public
     * @return int|bool
     */
    public function create($type = 'waterfall')
    {
        $stage = fixer::input('post')
            ->setDefault('projectType', $type)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::today())
            ->get();

        if(isset($this->config->setPercent) and $this->config->setPercent == 1)
        {
            $totalPercent = $this->getTotalPercent($type);

            if(!is_numeric($stage->percent)) return dao::$errors['percent'] = $this->lang->stage->error->notNum;
            if(round($totalPercent + $stage->percent) > 100) return dao::$errors['percent'] = $this->lang->stage->error->percentOver;
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
     * Batch create stages.
     *
     * @access public
     * @return bool
     */
    public function batchCreate($type = 'waterfall')
    {
        $data = fixer::input('post')->get();

        $setPercent = (isset($this->config->setPercent) and $this->config->setPercent == 1) ? true : false;
        if($setPercent)
        {
            $totalPercent = $this->getTotalPercent($type);
            if(round($totalPercent + array_sum($data->percent)) > 100) return dao::$errors['message'] = $this->lang->stage->error->percentOver;
        }

        $this->loadModel('action');
        foreach($data->name as $i => $name)
        {
            if(!$name) continue;

            $stage = new stdclass();
            $stage->name        = $name;
            $stage->type        = $data->type[$i];
            $stage->projectType = $type;
            $stage->createdBy   = $this->app->user->account;
            $stage->createdDate = helper::today();
            if($setPercent) $stage->percent = $data->percent[$i];

            $this->dao->insert(TABLE_STAGE)->data($stage)->autoCheck()
                ->batchCheck($this->config->stage->create->requiredFields, 'notempty')
                ->checkIF($stage->percent != '', 'percent', 'float')
                ->exec();

            if(dao::isError())
            {
                foreach(dao::getError() as $field => $error) dao::$errors["{$field}[{$i}]"] = $error;
                return false;
            }

            $stageID = $this->dao->lastInsertID();
            $this->action->create('stage', $stageID, 'Opened');
        }

        return true;
    }

    /**
     * Update a stage.
     *
     * @param  int    $stageID
     * @access public
     * @return bool
     */
    public function update($stageID)
    {
        $oldStage = $this->dao->select('*')->from(TABLE_STAGE)->where('id')->eq((int)$stageID)->fetch();

        $stage = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->get();

        if(isset($this->config->setPercent) and $this->config->setPercent == 1)
        {
            $totalPercent = $this->getTotalPercent($oldStage->projectType);
            if(round($totalPercent + (float)$stage->percent - $oldStage->percent) > 100) return dao::$errors['percent'] = $this->lang->stage->error->percentOver;
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
     *  Get stage total percent
     *
     *  @param  string $type
     *  @return string
     */
    public function getTotalPercent($type)
    {
        return $this->dao->select('sum(percent) as total')->from(TABLE_STAGE)->where('deleted')->eq('0')->andWhere('projectType')->eq($type)->fetch('total');
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
