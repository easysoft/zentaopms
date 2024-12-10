<?php
declare(strict_types=1);

/**
 * The control file of programplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @link        https://www.zentao.net
 */
class programplan extends control
{
    /**
     * Common action.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @access public
     * @return int
     */
    public function commonAction(int $projectID, int $productID = 0): int
    {
        $this->loadModel('product');
        $this->loadModel('project');
        $products  = $this->product->getProductPairsByProject($projectID);

        $productID = $this->product->checkAccess($productID, $products);
        $project = $this->project->getByID($projectID);

        $this->session->set('hasProduct', $project->hasProduct);
        $this->project->setMenu($projectID);

        return $productID;
    }

    /**
     * 渲染阶段数据页面。
     * Browse program plans.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $baselineID
     * @param  string $browseType
     * @param  int    $queryID
     * @access public
     * @return void
     */
    public function browse(int $projectID = 0, int $productID = 0, string $type = 'gantt', string $orderBy = 'id_asc', int $baselineID = 0, string $browseType = '', int $queryID = 0)
    {
        if($type == 'lists')
        {
            echo $this->fetch('project', 'execution', "status=all&projectID={$projectID}");
            return;
        }

        $this->app->loadLang('stage');
        $this->session->set('projectPlanList', $this->app->getURI(true), 'project');
        $this->commonAction($projectID, $productID);

        if(!defined('RUN_MODE') || RUN_MODE != 'api') $projectID = $this->project->checkAccess($projectID, $this->project->getPairsByProgram());

        /* Get the product under the project and adjust the three-level navigation action button. */
        $products = $this->loadModel('product')->getProducts($projectID);
        if($this->session->hasProduct) $this->lang->modulePageNav = $this->product->select($products, $productID, 'programplan', 'browse', $type, 0, false);

        /* Generate stage list page data. */
        $browseType = strtolower($browseType);
        $plans = $this->programplanZen->buildStages($projectID, $productID, $baselineID, $type, $orderBy, $browseType, $queryID);

        /* Build gantt browse view. */
        $this->programplanZen->buildBrowseView($projectID, $productID, $plans, $type, $orderBy, $baselineID, $browseType, $queryID);
    }

    /**
     * 创建一个项目阶段。
     * Create a project plan/phase.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $planID
     * @param  string $executionType
     * @param  string $from
     * @param  int    $syncData
     * @access public
     * @return void
     */
    public function create(int $projectID = 0, int $productID = 0, int $planID = 0, string $executionType = 'stage', string $from = '', int $syncData = 0)
    {
        $this->loadModel('review');
        $this->productID = $this->commonAction($projectID, $productID);
        if($_POST)
        {
            $plans = $this->programplanZen->buildPlansForCreate($projectID, $planID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->programplan->create($plans, $projectID, $this->productID, $planID, $syncData);
            if(dao::isError())
            {
                $errors = dao::getError();
                if(isset($errors['message']))  $this->send(array('result' => 'fail', 'message' => $errors));
                if(!isset($errors['message'])) $this->send(array('result' => 'fail', 'callback' => array('name' => 'addRowErrors', 'params' => array($errors))));
            }

            $locate = $this->session->projectPlanList ? $this->session->projectPlanList : $this->createLink('project', 'execution', "status=all&projectID={$projectID}&orderBy=order_asc&productID={$productID}");
            if($from == 'projectCreate') $locate = $this->createLink('project', 'create', "model=&programID=0&copyProjectID=0&extra=showTips=1,project=$projectID");
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $locate));
        }

        $project     = $this->project->getById($projectID);
        $programPlan = $this->project->getById($planID);
        $productList = $this->session->hasProduct ? $this->product->getProductPairsByProject($projectID) : array();
        $executions  = !empty($planID) ? $this->loadModel('execution')->getChildExecutions($planID, 'order_asc') : array();

        $plans = $this->programplan->getStage($planID ?: $projectID, $this->productID, 'parent', 'order_asc');
        if(!empty($planID) and in_array($project->model, array('ipd', 'waterfallplus')))
        {
            if(!empty($plans))
            {
                $executionType = 'stage';
                unset($this->lang->programplan->typeList['agileplus']);
            }
            elseif(!empty($executions))
            {
                $executionType = 'agileplus';
                unset($this->lang->programplan->typeList['stage']);
            }
        }

        /* Set programplan typeList. */
        if($executionType != 'stage') unset($this->lang->execution->typeList[''], $this->lang->execution->typeList['stage']);

        /* Unset percent for create IPD project.*/
        if($project->model == 'ipd') $this->config->programplan->list->customCreateFields = str_replace(',percent', '', $this->config->programplan->list->customCreateFields);

        $viewData = new stdclass();
        $viewData->productID     = $productID;
        $viewData->planID        = $planID;
        $viewData->executionType = $executionType;
        $viewData->programPlan   = $programPlan;
        $viewData->productList   = $productList;
        $viewData->project       = $project;
        $viewData->plans         = !empty($executions) ? $executions : $plans;
        $viewData->syncData      = $syncData;

        $this->programplanZen->buildCreateView($viewData);
    }

    /**
     * 编辑阶段内容。
     * Edit a project plan.
     *
     * @param  int    $planID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function edit(int $planID = 0, int $projectID = 0)
    {
        if($_POST)
        {
            $this->loadModel('execution');
            if(!empty($this->config->setCode) && strpos(",{$this->config->execution->edit->requiredFields},", ',code,') !== false) $this->config->programplan->form->edit['code']['required'] = true;

            $plan = form::data()->get();

            /* 设置计划和真实起始日期间隔时间。 */
            /* Set planDuration and realDuration. */
            if(in_array($this->config->edition, array('max', 'ipd')))
            {
                $plan->planDuration = $this->programplan->getDuration($plan->begin, $plan->end);
                $plan->realDuration = $this->programplan->getDuration($plan->realBegan, $plan->realEnd);
            }

            if($plan->parent)
            {
                $parentStage = $this->programplan->getByID($plan->parent);
                $plan->acl   = $parentStage->acl;
                if($parentStage->attribute != 'mix') $plan->attribute = $parentStage->attribute;
            }

            if(empty($plan->realBegan)) $plan->realBegan = null;
            if(empty($plan->realEnd))   $plan->realEnd   = null;

            $plan = $this->programplanZen->prepareEditPlan($planID, $projectID, $plan, isset($parentStage) ? $parentStage : null);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->programplan->update($planID, $projectID, $plan);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $newPlan = $this->programplan->fetchByID($planID);
            if($plan->parent != $newPlan->parent)
            {
                $this->programplan->computeProgress($planID, 'edit');
                $this->programplan->computeProgress($plan->parent, 'edit', true);
            }

            if($this->app->rawModule == 'marketresearch') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->session->marketstageList));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => 'loadCurrentPage', 'closeModal' => true));
        }

        $plan = $this->programplan->getByID($planID);
        $this->programplanZen->buildEditView($plan);
    }

    /**
     * 通过ajax请求保存自定义设置。
     * Save custom settings via ajax.
     *
     * @access public
     * @return void
     */
    public function ajaxCustom()
    {
        $owner  = $this->app->user->account;
        $module = 'programplan';
        $this->app->loadLang('execution');
        $this->loadModel('datatable');
        $this->loadModel('setting');

        if($_POST)
        {
            $settings = form::data($this->config->programplan->form->ajaxCustom)->get();
            $this->programplan->saveCustomSetting($settings, $owner, $module);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
        }

        /* Set Custom. */
        foreach(explode(',', $this->config->programplan->custom->customGanttFields) as $field) $customFields[$field] = $this->lang->programplan->ganttCustom[$field];

        $this->programplanZen->buildAjaxCustomView($owner, $module, $customFields);
    }

    /**
     * 处理甘特图拖拽事件数据。
     * Response gantt drag event.
     *
     * @access public
     */
    public function ajaxResponseGanttDragEvent()
    {
        if(!$this->post->id || !$this->post->type) return $this->send(array('result' => 'fail', 'message' => ''));

        $postData = form::data($this->config->programplan->form->updateDateByGantt)->get();
        $this->loadModel('task')->updateEsDateByGantt($postData);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success'));
    }

    /**
     * 处理甘特图移动事件数据。
     * Response gantt move event.
     *
     * @access public
     */
    public function ajaxResponseGanttMoveEvent()
    {
        if(!$this->post->id) return $this->send(array('result' => 'fail', 'message' => ''));

        $idList = explode('-', $this->post->id);
        $taskID = !empty($idList[1]) ? $idList[1] : 0;
        if(empty($taskID)) return $this->send(array('result' => 'fail', 'message' => ''));

        $postData = form::data($this->config->programplan->form->updateTaskOrderByGantt)->get();
        $this->loadModel('task')->updateOrderByGantt($postData);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->loadModel('action')->create('task', (int)$taskID, 'ganttMove');
        return $this->send(array('result' => 'success'));
    }

    /**
     * ajax请求：获取阶段ID的属性。
     * AJAX: Get stageID attributes.
     *
     * @param  int    $stageID
     * @param  string $attribute
     * @param  string $projectModel
     * @access public
     * @return int
     */
    public function ajaxGetAttribute(int $stageID, string $attribute, string $projectModel = ''): int
    {
        $this->app->loadLang('stage');
        if($projectModel == 'ipd') $this->lang->stage->typeList = $this->lang->stage->ipdTypeList;

        $stageAttribute = $this->programplan->getStageAttribute($stageID);

        if(empty($stageAttribute) || $stageAttribute == 'mix') return print(html::select('attribute', $this->lang->stage->typeList, $attribute, "class='form-control chosen'"));
        return print(zget($this->lang->stage->typeList, $stageAttribute));
    }

    /**
     * ajax请求：获取阶段ID的属性。
     * AJAX: Get stageID attribute.
     *
     * @param  int    $stageID
     * @access public
     * @return int
     */
    public function ajaxGetStageAttr(int $stageID): int
    {
        $stageAttribute = $this->programplan->getStageAttribute($stageID);
        return print($stageAttribute);
    }
}
