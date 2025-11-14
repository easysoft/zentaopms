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
    private function checkLegallyDate(object $plan, object $project, object|null $parent, int $rowID): void
    {
        if(isset($plan->enabled) && $plan->enabled == 'off') return;
        if(!empty($project->isTpl)) return; // 模板不校验日期

        $beginIsZeroDate = helper::isZeroDate($plan->begin);
        $endIsZeroDate   = helper::isZeroDate($plan->end);
        if(!$beginIsZeroDate and !$endIsZeroDate and $plan->end < $plan->begin) dao::$errors["end[{$rowID}]"] = $this->lang->programplan->error->planFinishSmall;

        if(!empty($parent))
        {
            if(!$beginIsZeroDate and $plan->begin < $parent->begin) dao::$errors["begin[{$rowID}]"] = sprintf($this->lang->programplan->error->letterParent, $parent->begin);
            if(!$endIsZeroDate and $plan->end > $parent->end)       dao::$errors["end[{$rowID}]"]   = sprintf($this->lang->programplan->error->greaterParent, $parent->end);
        }
        if(!$beginIsZeroDate and $plan->begin < $project->begin) dao::$errors["begin[{$rowID}]"] = sprintf($this->lang->programplan->errorBegin, $project->begin);
        if(!$endIsZeroDate and $plan->end > $project->end)       dao::$errors["end[{$rowID}]"]   = sprintf($this->lang->programplan->errorEnd, $project->end);
    }

    /**
     * Process formData before use it to create programplan.
     *
     * @param  int         $projectID
     * @param  int         $parentID
     * @access protected
     * @return array|false
     */
    protected function buildPlansForCreate(int $projectID, int $parentID): array|false
    {
        /* Check parent name is not empty when has child task. */
        $levelNames = array();
        foreach($this->post->level as $i => $level)
        {
            $level = (int)$level;
            $levelNames[$level]['name']  = trim($this->post->name[$i]);
            $levelNames[$level]['index'] = $i;

            $preLevel = $level - 1;
            if($level > 0 && !empty($levelNames[$level]['name']) && empty($levelNames[$preLevel]['name'])) dao::$errors["name[" . $levelNames[$preLevel]['index'] . "]"] = $this->lang->programplan->error->emptyParentName;
        }
        if(dao::isError()) return false;

        $project  = $this->loadModel('project')->getByID($projectID);
        $oldPlans = $this->loadModel('programplan')->getStage($projectID);
        if($parentID) $parentStage = $this->programplan->getByID($parentID);

        $fields = $this->config->programplan->form->create;
        foreach(explode(',', $this->config->programplan->create->requiredFields) as $field)
        {
            $field = trim($field);
            if(isset($fields[$field])) $fields[$field]['required'] = true;
        }

        $totalPercent = array();
        $lastLevels   = array();
        $names        = $codes = array();
        $plans        = form::batchData($fields)->get();
        $orders       = $this->programplan->computeOrders(array(), $plans);
        $group        = 0;
        $levelGroup   = array();
        $prevLevel    = 0;
        foreach($plans as $rowID => $plan)
        {

            if(empty($parentID) and empty($oldPlans)) $plan->id = '';
            $plan->days       = isset($plan->enabled) && $plan->enabled == 'on' ? $this->programplan->calcDaysForStage($plan->begin, $plan->end) : 0;
            $plan->project    = $projectID;
            $plan->order      = (int)array_shift($orders);
            $plan->hasProduct = $project->hasProduct;
            $plan->parent     = $parentID ? $parentID : $projectID;
            $plan->isTpl      = $project->isTpl;
            if($plan->id && isset($oldPlans[$plan->id])) $plan->parent = $oldPlans[$plan->id]->parent;
            if(!empty($plan->percent) && $plan->type != 'stage') $plan->percent = 0; // 非阶段类型，工作量占比为0

            if(empty($plan->days)) $plan->days = helper::diffDate($plan->end, $plan->begin) + 1;
            if(!empty($parentID) && !empty($parentStage) && $parentStage->attribute != 'mix') $plan->attribute = $parentStage->attribute;;
            if(!empty($parentID) && !empty($parentStage)) $plan->acl = $parentStage->acl;

            if(in_array($this->config->edition, array('max', 'ipd')) && !dao::isError())
            {
                $plan->planDuration = $this->programplan->getDuration((string)$plan->begin, (string)$plan->end);
                $plan->realDuration = $this->programplan->getDuration((string)$plan->realBegan, (string)$plan->realEnd);
            }

            /* 阶段停用和删除是一样的效果，方便控制相关数据的展示。 */
            if($project->model == 'ipd') $plan->deleted = $plan->enabled == 'off' ? '1' : '0';

            /* Check duplicated names to avoid to save same names. */
            if(in_array($plan->name, $names)) dao::$errors["name[{$rowID}]"] = empty($plan->type) ? $this->lang->programplan->error->sameName : str_replace($this->lang->execution->stage, '', $this->lang->programplan->error->sameName);
            if(isset($plan->code) && (!isset($plan->enabled) || (isset($plan->enabled) && $plan->enabled == 'on')))
            {
                if(in_array($plan->code, $codes)) dao::$errors["code[{$rowID}]"] = sprintf($this->lang->error->repeat, $plan->type == 'stage' ? $this->lang->execution->code : $this->lang->code, $plan->code);
                if(!empty($this->config->setCode) && empty($plan->code) && strpos(",{$this->config->execution->create->requiredFields},", ',code,') !== false) dao::$errors["code[{$rowID}]"] = sprintf($this->lang->error->notempty, $plan->type == 'stage' ? $this->lang->execution->code : $this->lang->code);
            }

            if(!is_numeric($plan->percent) || $plan->percent < 0) dao::$errors["percent[$rowID]"] = $this->lang->programplan->error->percentNumber;

            $customKey = 'create' . ucfirst($project->model) . 'Fields';
            if(strpos(",{$this->config->programplan->custom->$customKey},", ',percent,') !== false && isset($plan->percent) && !dao::isError())
            {
                if($plan->level == 0)
                {
                    $levelGroup      = array();
                    $totalPercent[0] = isset($totalPercent[0]) ? $totalPercent[0] + $plan->percent : $plan->percent;
                }
                else
                {
                    if(isset($levelGroup[$plan->level]))
                    {
                        $group = $levelGroup[$plan->level];
                    }
                    elseif($plan->level != $prevLevel)
                    {
                        $group++;
                    }

                    $totalPercent[$group] = isset($totalPercent[$group]) ? $totalPercent[$group] + $plan->percent : $plan->percent;
                }

                $levelGroup[$plan->level] = $group;
            }

            $prevLevel = $plan->level;
            $names[]   = $plan->name;
            if(!empty($plan->code)) $codes[] = $plan->code;

            $this->checkLegallyDate($plan, $project, !empty($parentStage) ? $parentStage : null, $rowID);

            $lastLevels[$plan->level] = $plan;
            if($plan->level > 0) $this->checkLegallyDate($plan, $project, zget($lastLevels, $plan->level - 1, null), $rowID);
        }

        foreach($totalPercent as $group => $percent)
        {
            if(!empty($this->config->setPercent) and $percent > 100)
            {
                dao::$errors["percent"] = $this->lang->programplan->error->percentOver;
                break;
            }
        }

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
        list($visibleFields, $requiredFields, $customFields, $showFields, $defaultFields) = $this->computeFieldsCreateView($viewData);

        if($viewData->project->model == 'ipd') $this->config->programplan->form->create['attribute']['options'] = $this->lang->stage->ipdTypeList;

        $this->view->title              = $this->lang->programplan->create . $this->lang->hyphen . $viewData->project->name;
        $this->view->productList        = $viewData->productList;
        $this->view->project            = $viewData->project;
        $this->view->productID          = $viewData->productID ?: key($viewData->productList);
        $this->view->stages             = empty($viewData->planID) ? $this->loadModel('stage')->getStages('order_asc', 0, $viewData->project->workflowGroup) : array();
        $this->view->programPlan        = $viewData->programPlan;
        $this->view->plans              = $viewData->plans;
        $this->view->planID             = $viewData->planID;
        $this->view->syncData           = $viewData->syncData;
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
    }

    /**
     * 处理编辑阶段的请求数据。
     * Processing edit request data.
     *
     * @param  int          $planID
     * @param  int          $projectID
     * @param  object       $plan
     * @param  object|null  $parentStage
     * @access protected
     * @return object|false
     */
    protected function prepareEditPlan(int $planID, int $projectID, object $plan, ?object $parentStage = null): object|false
    {
        if($plan->end < $plan->begin) dao::$errors['end'] = $this->lang->programplan->error->planFinishSmall;

        if($plan->parent)
        {
            if(!empty($parentStage) && $plan->begin < $parentStage->begin) dao::$errors['begin'] = sprintf($this->lang->programplan->error->letterParent,  $parentStage->begin);
            if(!empty($parentStage) && $plan->end   > $parentStage->end)   dao::$errors['end']   = sprintf($this->lang->programplan->error->greaterParent, $parentStage->end);

            if(dao::isError()) return false;
        }

        if(!is_numeric($plan->percent) || $plan->percent < 0) dao::$errors['percent'] = $this->lang->programplan->error->percentNumber;

        if($projectID) $this->loadModel('execution')->checkBeginAndEndDate($projectID, $plan->begin, $plan->end, $plan->parent);
        if(dao::isError()) return false;

        $project = $this->loadModel('project')->getById($projectID);
        $oldPlan = $this->programplan->getByID($planID);
        if(!empty($this->config->setPercent))
        {
            if($plan->parent > 0)
            {
                $childrenTotalPercent = $this->programplan->getTotalPercent($parentStage, true);
                $childrenTotalPercent = $plan->parent == $oldPlan->parent ? ($childrenTotalPercent - $oldPlan->percent + $plan->percent) : ($childrenTotalPercent + $plan->percent);
                if($childrenTotalPercent > 100) dao::$errors['percent'][] = $this->lang->programplan->error->percentOver;
            }
            else
            {
                /* 相同父阶段的子阶段工作量占比之和不超过100%。 */
                /* The workload of the parent plan cannot exceed 100%. */
                $oldPlan->parent = $plan->parent;
                $totalPercent    = $this->programplan->getTotalPercent($oldPlan);
                $totalPercent    = $totalPercent + $plan->percent;
                if($totalPercent > 100) dao::$errors['percent'][] = $this->lang->programplan->error->percentOver;
            }
        }

        if(dao::isError()) return false;
        /* 如果是调研阶段，设置默认值。*/
        /* If it is research stage, set default value. */
        if($project->model == 'research')
        {
            $plan->acl       = $oldPlan->acl;
            $plan->attribute = $oldPlan->attribute;
            $plan->milestone = $oldPlan->milestone;
        }

        return $plan;
    }

    /**
     * 生成编辑阶段数据。
     * Build edit view data.
     *
     * @param  object $plan
     * @access protected
     * @return void
     */
    public function buildEditView(object $plan)
    {
        $this->loadModel('project');
        $this->loadModel('execution');
        $this->app->loadLang('stage');

        $project     = $this->project->getByID($plan->project);
        $parentStage = $this->project->getByID($plan->parent, 'stage');

        $enableOptionalAttr = empty($parentStage) || (!empty($parentStage) && $parentStage->attribute == 'mix');
        if($project->model == 'ipd') $enableOptionalAttr = false;

        $this->view->title                  = $this->lang->programplan->edit;
        $this->view->isCreateTask           = $this->programplan->isCreateTask($plan->id);
        $this->view->plan                   = $plan;
        $this->view->project                = $project;
        $this->view->parentStageList        = $this->programplan->getParentStageList($plan->project, $plan->id, $plan->product);
        $this->view->enableOptionalAttr     = $enableOptionalAttr;
        $this->view->isTopStage             = $this->programplan->isTopStage($plan->id);
        $this->view->isLeafStage            = $this->programplan->checkLeafStage($plan->id);
        $this->view->PMUsers                = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst',  $plan->PM);
        $this->view->project                = $this->project->getByID($plan->project);
        $this->view->requiredFields         = $this->config->execution->edit->requiredFields;
        $this->view->hasUploadedDeliverable = in_array($this->config->edition, array('max', 'ipd')) ? $this->execution->hasUploadedDeliverable($plan) : false;

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
        $stageCustom = $this->loadModel('setting')->getItem("owner=$owner&module=$module&section=browse&key=stageCustom");
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
     * @param  object     $viewData
     * @access protected
     * @return array
     */
    protected function computeFieldsCreateView(object $viewData): array
    {
        $visibleFields      = array();
        $requiredFields     = array();
        $customFields       = array();
        $customModel        = !empty($viewData->project->model) ? $viewData->project->model : '';
        $custom             = $viewData->executionType == 'stage' ? 'custom' : 'customAgilePlus';
        $customCreateFields = $viewData->executionType == 'stage' ? 'customCreateFields' : 'customAgilePlusCreateFields';
        $createFields       = $custom == 'customAgilePlus' ? 'createFields' : 'create' . ucfirst($customModel) . 'Fields';
        $defaultFields      = $this->config->programplan->$custom->defaultFields;

        foreach(explode(',', $this->config->programplan->list->$customCreateFields) as $field) $customFields[$field] = $this->lang->programplan->{$field};

        $createRequiredFields = $this->config->execution->create->requiredFields;
        $showFields           = $this->config->programplan->$custom->$createFields;
        $checkCodeIsRequired  = !empty($this->config->setCode) && strpos(',' . trim($createRequiredFields, ',') . ',', ',code,') !== false;
        if($checkCodeIsRequired) $showFields .= ',code';
        foreach(explode(',', $showFields) as $field)
        {
            if($field) $visibleFields[$field] = '';
        }

        if($viewData->project->model == 'waterfallplus') $createRequiredFields = 'type,' . trim($createRequiredFields, ',');
        if($viewData->project->model == 'ipd') $createRequiredFields = ($viewData->planID ? 'type,' : 'enabled,point,type,') . trim($createRequiredFields, ',');
        foreach(explode(',', $createRequiredFields) as $field)
        {
            if($field)
            {
                $requiredFields[$field] = '';
                if(strpos(",{$this->config->programplan->list->$customCreateFields},", ",{$field},") !== false) $visibleFields[$field] = '';
            }
        }

        if(empty($this->config->setPercent)) unset($visibleFields['percent'], $requiredFields['percent']);
        if(empty($this->config->setCode)) unset($visibleFields['code'], $requiredFields['code']);
        if($checkCodeIsRequired) unset($customFields['code']);

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
     * j
     * @access protected
     * @return array
     */
    protected function buildStages(int $projectID, int $productID, int $baselineID, string $type, string $orderBy, string $browseType = '', int $queryID = 0): array
    {
        /* Obtain user page configuration items. */
        $this->loadModel('setting');
        $owner  = $this->app->user->account;
        $module = 'programplan';
        if(!isset($this->config->programplan->browse->stageCustom)) $this->loadModel('setting')->setItem("$owner.$module.browse.stageCustom", 'date,task,point');
        $selectCustom = $this->loadModel('setting')->getItem("owner={$owner}&module=programplan&section=browse&key=stageCustom");
        $dateDetails  = strpos($selectCustom, 'date') !== false ? false : true; // Gantt chart detail date display.

        foreach(explode(',', $this->config->programplan->custom->customGanttFields) as $field) $customFields[$field] = $this->lang->programplan->ganttCustom[$field];
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->programplan->ganttCustom->ganttFields;
        $this->view->dateDetails  = $dateDetails;
        $this->view->selectCustom = $selectCustom;

        /* Get data for gantt. */
        $stages = array();
        if($type == 'gantt')      $stages = $this->programplan->getDataForGantt($projectID, $productID, $baselineID, $selectCustom, false, $browseType, $queryID);
        if($type == 'assignedTo') $stages = $this->programplan->getDataForGanttGroupByAssignedTo($projectID, $productID, $baselineID, $selectCustom, false, $browseType, $queryID);

        return $stages;
    }

    /**
     * 生成gantt图视图数据。
     * Build gantt browse view.
     *
     * @param  int       $projectID
     * @param  int       $productID
     * @param  array     $stages
     * @param  string    $type
     * @param  string    $orderBy
     * @param  int       $baselineID
     * @param  string    $browseType
     * @param  int       $queryID
     * @access protected
     * @return void
     */
    protected function buildBrowseView(int $projectID, int $productID, array $stages, string $type, string $orderBy, int $baselineID, string $browseType, int $queryID): void
    {
        $project = $this->loadModel('project')->getByID($projectID);
        if($project->model == 'ipd' and $this->config->edition == 'ipd')
        {
            $this->view->reviewPoints = $this->loadModel('review')->getReviewPointByProject($projectID);
        }

        $stageCustom = $this->loadModel('setting')->getItem("owner={$this->app->user->account}&module=programplan&section=browse&key=stageCustom");
        $hasSearch   = strpos(",{$stageCustom},", ',task,') !== false;
        if($hasSearch)
        {
            /* Build the search form. */
            $actionURL  = $this->createLink('programplan', 'browse', "projectID=$projectID&productID=$productID&type=$type&orderBy=$orderBy&baselineID=$baselineID&browseType=bysearch&queryID=myQueryID");
            $executions = $this->programplan->getPairs($projectID, $productID, 'all');
            $this->loadModel('execution')->buildTaskSearchForm($projectID, $executions, $queryID, $actionURL, 'projectTask');
        }

        $this->view->title       = $this->lang->programplan->browse;
        $this->view->projectID   = $projectID;
        $this->view->productID   = $productID;
        $this->view->type        = $type;
        $this->view->ganttType   = $type;
        $this->view->plans       = $stages;
        $this->view->orderBy     = $orderBy;
        $this->view->project     = $project;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->product     = $this->loadModel('product')->getByID($productID);
        $this->view->productList = $this->loadModel('product')->getProductPairsByProject($projectID, 'all', '', false);
        $this->view->zooming     = !empty($this->config->programplan->ganttCustom->zooming) ? $this->config->programplan->ganttCustom->zooming : 'day';
        $this->view->hasSearch   = $hasSearch;
        $this->view->browseType  = $browseType;
        $this->view->queryID     = $queryID;

        $this->display();
    }

    /**
     * 重新排序阶段，将子阶段放在父阶段之后。
     * Reorder stage, put sub stage after parent stage.
     *
     * @param  array  $plans
     * @access public
     * @return array
     */
    public function sortPlans(array $plans): array
    {
        $parents = array();
        foreach($plans as $plan) $parents[$plan->parent][$plan->id] = $plan->id;

        $getChildren = function($planID) use($parents, &$getChildren)
        {
            if(!isset($parents[$planID])) return array();

            $children = array();
            foreach($parents[$planID] as $childPlanID)
            {
                $children[$childPlanID] = $childPlanID;
                $children = arrayUnion($children, $getChildren($childPlanID));
            }
            return $children;
        };

        $sortedPlans = array();
        foreach($plans as $plan)
        {
            if(isset($sortedPlans[$plan->id])) continue;

            $sortedPlans[$plan->id] = $plan;
            $children = $getChildren($plan->id);
            foreach($children as $childID) $sortedPlans[$childID] = $plans[$childID];
        }

        return $sortedPlans;
    }
}
