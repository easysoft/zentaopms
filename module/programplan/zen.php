<?php
declare(strict_types=1);
/**
 * The zen file of programplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      lanzongjun <lanzongjun@easycorp.ltd>
 * @link        https://www.zentao.net
 */
class programplanZen extends programplan
{
    /**
     * Check legally date.
     *
     * @param  object      $plan
     * @param  object      $project
     * @param  object|null $parent
     * @access private
     * @return void
     */
    private function checkLegallyDate(object $plan, object $project, object|null $parent): void
    {
        $beginIsZeroDate = helper::isZeroDate($plan->begin);
        $endIsZeroDate   = helper::isZeroDate($plan->end);
        if($beginIsZeroDate) dao::$errors['begin'] = $this->lang->programplan->emptyBegin;
        if($endIsZeroDate)   dao::$errors['end']   = $this->lang->programplan->emptyEnd;
        if(!$beginIsZeroDate and !$endIsZeroDate and $plan->end < $plan->begin) dao::$errors['end'] = $this->lang->programplan->error->planFinishSmall;

        if(!empty($parent))
        {
            if(!$beginIsZeroDate and $plan->begin < $parent->begin) dao::$errors['begin'] = sprintf($this->lang->programplan->error->letterParent, $parent->begin);
            if(!$endIsZeroDate and $plan->end > $parent->end)       dao::$errors['end']   = sprintf($this->lang->programplan->error->greaterParent, $parent->end);
        }
        if(!$beginIsZeroDate and $plan->begin < $project->begin) dao::$errors['begin'] = sprintf($this->lang->programplan->errorBegin, $project->begin);
        if(!$endIsZeroDate and $plan->end > $project->end)       dao::$errors['end']   = sprintf($this->lang->programplan->errorEnd, $project->end);
    }

    /**
     * Process formData before use it to create programplan.
     *
     * @param  int       $projectID
     * @param  int       $parentID
     * @access protected
     * @return array
     */
    protected function buildPlansForCreate(int $projectID, int $parentID): array
    {
        $project = $this->loadModel('project')->getByID($projectID);
        if($parentID) $parentStage = $this->programplan->getByID($parentID);

        $fields = $this->config->programplan->form->create;
        foreach(explode(',', $this->config->programplan->create->requiredFields) as $field)
        {
            $field = trim($field);
            if(isset($fields[$field])) $fields[$field]['required'] = true;
        }

        $totalPercent = 0;
        $names  = $codes = array();
        $plans  = form::batchData($fields)->get();
        $orders = $this->programplan->computeOrders(array(), $plans);
        foreach($plans as $plan)
        {
            $plan->days       = helper::diffDate($plan->end, $plan->begin) + 1;
            $plan->project    = $projectID;
            $plan->parent     = $parentID ? $parentID : $projectID;
            $plan->order      = (int)array_shift($orders);
            $plan->hasProduct = $project->hasProduct;
            if(!empty($parentID) and !empty($parentStage) and $parentStage->attribute != 'mix') $plan->attribute = $parentStage->attribute;;
            if(!empty($parentID) and !empty($parentStage)) $plan->acl = $parentStage->acl;

            if(in_array($this->config->edition, array('max', 'ipd')))
            {
                $plan->planDuration = $this->programplan->getDuration($plan->begin, $plan->end);
                $plan->realDuration = $this->programplan->getDuration($plan->realBegan, $plan->realEnd);
            }

            /* Check duplicated names to avoid to save same names. */
            if(in_array($plan->name, $names)) dao::$errors['name'] = empty($plan->type) ? $this->lang->programplan->error->sameName : str_replace($this->lang->execution->stage, '', $this->lang->programplan->error->sameName);
            if(isset($plan->code))
            {
                if(in_array($plan->code, $codes)) dao::$errors['code'] = sprintf($this->lang->error->repeat, $plan->type == 'stage' ? $this->lang->execution->code : $this->lang->code, $plan->code);
                if(empty($plan->code))            dao::$errors['code'] = sprintf($this->lang->error->notempty, $plan->type == 'stage' ? $this->lang->execution->code : $this->lang->code);
            }

            $totalPercent += $plan->percent;
            $names[]       = $plan->name;
            if(!empty($plan->code)) $codes[] = $plan->code;

            $this->checkLegallyDate($plan, $project, !empty($parentStage) ? $parentStage : null);
        }
        if(!empty($this->config->setPercent) and $totalPercent > 100) dao::$errors['percent'] = $this->lang->programplan->error->percentOver;
        return $plans;
    }

    /**
     * 生成创建项目阶段视图数据。
     * Build create view data.
     *
     * @param  object $viewData
     * @access protected
     * @return void
     */
    public function buildCreateView(object $viewData)
    {
        /* Compute fields for create view. */
        list($visibleFields, $requiredFields, $customFields, $showFields, $defaultFields) = $this->computeFieldsCreateView($viewData->executionType);

        $this->view->title              = $this->lang->programplan->create . $this->lang->colon . $viewData->project->name;
        $this->view->productList        = $viewData->productList;
        $this->view->project            = $viewData->project;
        $this->view->productID          = $viewData->productID ?: key($viewData->productList);
        $this->view->stages             = empty($viewData->planID) ? $this->loadModel('stage')->getStages('id_asc', 0, $viewData->project->model) : array();
        $this->view->programPlan        = $viewData->programPlan;
        $this->view->plans              = $viewData->plans;
        $this->view->planID             = $viewData->planID;
        $this->view->type               = 'lists';
        $this->view->executionType      = $viewData->executionType;
        $this->view->PMUsers            = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst',  $viewData->project->PM);
        $this->view->custom             = $viewData->executionType == 'stage' ? 'custom' : 'customAgilePlus';
        $this->view->customFields       = $customFields;
        $this->view->showFields         = $showFields;
        $this->view->visibleFields      = $visibleFields;
        $this->view->requiredFields     = $requiredFields;
        $this->view->defaultFields      = $defaultFields;
        $this->view->colspan            = count($visibleFields) + 3;
        $this->view->enableOptionalAttr = empty($viewData->programPlan) || (!empty($viewData->programPlan) && $viewData->programPlan->attribute == 'mix');

        $this->display();
    }

    /**
     * 处理编辑阶段的请求数据。
     * Processing edit request data.
     *
     * @param  object $formData
     * @access protected
     * @return object
     */
    protected function beforeEdit(object $formData): object
    {
        $rowData = $formData->data;
        return isset($rowData->output) ? $formData->join('output', ',')->get() : $rowData;
    }

    /**
     * 阶段编辑后数据处理。
     * Handle data after edit.
     *
     * @param  object    $plan
     * @param  array    $changes
     * @access protected
     * @return void
     */
    protected function afterEdit(object $plan, array $changes)
    {
        $actionID = $this->loadModel('action')->create('execution', $plan->id, 'edited');
        $this->action->logHistory($actionID, $changes);

        $newPlan = $this->programplan->getByID($plan->id);

        if($plan->parent != $newPlan->parent)
        {
            $this->programplan->computeProgress($plan->id, 'edit');
            $this->programplan->computeProgress($plan->parent, 'edit', true);
        }
    }

    /**
     * 生成编辑阶段数据。
     * Build edit view data.
     *
     * @param  object $plan
     * @access protected
     * @return void
     */
    protected function buildEditView(object $plan)
    {
        $this->loadModel('project');
        $this->app->loadLang('execution');
        $this->app->loadLang('stage');

        $parentStage = $this->project->getByID($plan->parent, 'stage');

        $this->view->title              = $this->lang->programplan->edit;
        $this->view->isCreateTask       = $this->programplan->isCreateTask($plan->id);
        $this->view->plan               = $plan;
        $this->view->project            = $this->project->getByID($plan->project);
        $this->view->parentStageList    = $this->programplan->getParentStageList($this->session->project, $plan->id, $plan->product);
        $this->view->enableOptionalAttr = empty($parentStage) || (!empty($parentStage) && $parentStage->attribute == 'mix');
        $this->view->isTopStage         = $this->programplan->isTopStage($plan->id);
        $this->view->isLeafStage        = $this->programplan->checkLeafStage($plan->id);
        $this->view->PMUsers            = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst',  $plan->PM);
        $this->view->project            = $this->project->getByID($plan->project);
        $this->display();
    }

    /**
     * 生成自定义设置视图。
     * Build custom setting view form data.
     *
     * @param  string $owner
     * @param  string $module
     * @param  array  $customFields
     * @access protected
     * @return void
     */
    protected function buildAjaxCustomView(string $owner, string $module, array $customFields)
    {
        $stageCustom = $this->setting->getItem("owner=$owner&module=$module&section=browse&key=stageCustom");
        $ganttFields = $this->setting->getItem("owner=$owner&module=$module&section=ganttCustom&key=ganttFields");
        $zooming     = $this->setting->getItem("owner=$owner&module=$module&section=ganttCustom&key=zooming");

        $this->view->zooming      = $zooming;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->programplan->ganttCustom->ganttFields;
        $this->view->ganttFields  = $ganttFields;
        $this->view->stageCustom  = $stageCustom;

        $this->display();
    }

    /**
     * 计算创建视图的可见字段字段和必填字段。
     * Compute visibleFields and requiredFields for create view.
     *
     * @param  string $executionType
     * @access protected
     * @return array
     */
    protected function computeFieldsCreateView(string $executionType): array
    {
        $visibleFields      = array();
        $requiredFields     = array();
        $customFields       = array();
        $custom             = $executionType == 'stage' ? 'custom' : 'customAgilePlus';
        $customCreateFields = $executionType == 'stage' ? 'customCreateFields' : 'customAgilePlusCreateFields';
        $defaultFields      = $this->config->programplan->$custom->defaultFields;

        foreach(explode(',', $this->config->programplan->list->$customCreateFields) as $field) $customFields[$field] = $this->lang->programplan->{$field};

        $showFields = $this->config->programplan->$custom->createFields;
        foreach(explode(',', $showFields) as $field)
        {
            if($field) $visibleFields[$field] = '';
        }

        foreach(explode(',', $this->config->programplan->create->requiredFields) as $field)
        {
            if($field)
            {
                $requiredFields[$field] = '';
                if(strpos(",{$this->config->programplan->list->$customCreateFields},", ",{$field},") !== false) $visibleFields[$field] = '';
            }
        }

        if(empty($this->config->setPercent)) unset($visibleFields['percent'], $requiredFields['percent']);

        return array($visibleFields, $requiredFields, $customFields, $showFields, $defaultFields);
    }

    /**
     * 生成阶段列表页阶段数据。
     * Build gantt browse stage view data.
     *
     * @param  int      $projectID
     * @param  int      $productID
     * @param  int      $baselineID
     * @param  string   $type
     * @param  string   $orderBy
     * @access protected
     * @return array
     */
    protected function buildStages(int $projectID, int $productID, int $baselineID, string $type, string $orderBy): array
    {
        $stages = array();
        $selectCustom = 0; // Display date and task settings.
        $dateDetails  = 1; // Gantt chart detail date display.

        /* Get data of type lists. */
        if($type == 'lists')
        {
            $sort   = common::appendOrder($orderBy);
            $stages = $this->programplan->getPlans($projectID, $productID, $sort);
            $this->view->dateDetails  = $dateDetails;

            return $stages;
        }

        /* Obtain user page configuration items. */
        $owner = $this->app->user->account;
        if(!isset($this->config->programplan->browse->stageCustom)) $this->setting->setItem("$owner.$module.browse.stageCustom", 'date,task,point');
        $selectCustom = $this->loadModel('setting')->getItem("owner={$owner}&module=programplan&section=browse&key=stageCustom");
        $dateDetails  = strpos($selectCustom, 'date') !== false ? 0 : 1; // Gantt chart detail date display.

        foreach(explode(',', $this->config->programplan->custom->customGanttFields) as $field) $customFields[$field] = $this->lang->programplan->ganttCustom[$field];
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->programplan->ganttCustom->ganttFields;
        $this->view->dateDetails  = $dateDetails;
        $this->view->selectCustom = $selectCustom;

        /* Get data for gantt. */
        if($type == 'gantt' )     $stages = $this->programplan->getDataForGantt($projectID, $productID, $baselineID, $selectCustom, false);
        if($type == 'assignedTo') $stages = $this->programplan->getDataForGanttGroupByAssignedTo($projectID, $productID, $baselineID, $selectCustom, false);

        return $stages;
    }

    /**
     * 生成gantt图视图数据。
     * Build gantt browse view.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  array  $stages
     * @param  string $type
     * @param  string $orderBy
     * @access protected
     * @return void
     */
    protected function buildBrowseView(int $projectID, int $productID, array $stages, string $type, string $orderBy): void
    {
        $project = $this->project->getByID($projectID);
        if($project->model == 'ipd' and $this->config->edition == 'ipd')
        {
            $this->view->reviewPoints = $this->loadModel('review')->getReviewPointByProject($projectID);
        }

        $this->view->title       = $this->lang->programplan->browse;
        $this->view->projectID   = $projectID;
        $this->view->productID   = $productID;
        $this->view->type        = $type;
        $this->view->ganttType   = $type;
        $this->view->stages      = $stages;
        $this->view->orderBy     = $orderBy;
        $this->view->project     = $project;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->product     = $this->loadModel('product')->getByID($productID);
        $this->view->productList = $this->loadModel('product')->getProductPairsByProject($projectID, 'all', '', false);
        $this->view->zooming     = !empty($this->config->programplan->ganttCustom->zooming) ? $this->config->programplan->ganttCustom->zooming : 'day';

        $this->display();
    }
}
