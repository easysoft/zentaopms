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
     * @param  int     $projectID
     * @param  int     $productID
     * @access public
     * @return void
     */
    public function commonAction(int $projectID, int $productID = 0)
    {
        $this->loadModel('product');
        $this->loadModel('project');
        $products  = $this->product->getProductPairsByProject($projectID);

        $this->product->saveVisitState($productID, $products);
        $project = $this->project->getByID($projectID);

        $this->session->set('hasProduct', $project->hasProduct);
        $this->project->setMenu($projectID);
    }

    /**
     * 渲染阶段数据页面。
     * Browse program plans.
     *
     * @param  string  $projectID
     * @param  string  $productID
     * @param  string  $type
     * @param  string  $orderBy
     * @param  string  $baselineID
     * @access public
     * @return void
     */
    public function browse(string $projectID = '0', string $productID = '0', string $type = 'gantt', string $orderBy = 'id_asc', string $baselineID = '0')
    {
        $productID  = (int)$productID;
        $projectID  = (int)$projectID;
        $baselineID = (int)$baselineID;
        $this->app->loadLang('stage');
        $this->session->set('projectPlanList', $this->app->getURI(true), 'project');
        $this->commonAction($projectID, $productID);

        if(!defined('RUN_MODE') || RUN_MODE != 'api') $projectID = $this->project->saveState($projectID, $this->project->getPairsByProgram());

        /* Get the product under the project and adjust the three-level navigation action button. */
        $products = $this->loadModel('product')->getProducts($projectID);
        if($this->session->hasProduct) $this->lang->modulePageNav = $this->product->select($products, $productID, 'programplan', 'browse', $type, 0, false);

        /* Generate stage list page data. */
        $stages = $this->programplanZen->buildStages($projectID, $productID, $baselineID, $type, $orderBy);

        /* Build gantt browse view. */
        $this->programplanZen->buildBrowseView($projectID, $productID, $stages, $type, $orderBy);
    }

    /**
     * 创建一个项目阶段。
     * Create a project plan/phase.
     *
     * @param  int     $projectID
     * @param  int     $productID
     * @param  int     $planID
     * @param  string  $executionType
     * @access public
     * @return void
     */
    public function create(int $projectID = 0, int $productID = 0, int $planID = 0, string $executionType = 'stage')
    {
        $this->commonAction($projectID, $productID);
        if($_POST)
        {
            $formData = form::data($this->config->programplan->create->form);
            $formData = $this->programplanZen->beforeCreate($formData);

            $this->programplan->create($formData, $projectID, $productID, $planID);
            if(dao::isError())
            {
                $errors = dao::getError();
                if(isset($errors['message']))  $this->send(array('result' => 'fail', 'message' => $errors));
                if(!isset($errors['message'])) $this->send(array('result' => 'fail', 'callback' => array('name' => 'addRowErrors', 'params' => array($errors))));
            }

            $locate = $this->createLink('project', 'execution', "status=all&projectID={$projectID}&orderBy=order_asc&productID={$productID}");
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        $project     = $this->project->getById($projectID);
        $programPlan = $this->project->getById($planID, 'stage');
        $productList = $this->session->hasProduct ? $this->product->getProductPairsByProject($projectID) : array();
        $executions  = !empty($planID) ? $this->loadModel('execution')->getChildExecutions($planID, 'order_asc') : array();

        /* Set programplan typeList. */
        if($executionType != 'stage') unset($this->lang->execution->typeList[''], $this->lang->execution->typeList['stage']);

        $plans = $this->programplan->getStage($planID ?: $projectID, $productID, 'parent', 'order_asc');
        if(!empty($planID) && !empty($plans) && $project->model == 'waterfallplus')
        {
            $executionType = 'stage';
            unset($this->lang->programplan->typeList['agileplus']);

            if(!empty($executions))
            {
                $executionType = 'agileplus';
                unset($this->lang->programplan->typeList['stage']);
            }
        }

        $viewData = new stdclass();
        $viewData->projectID     = $projectID;
        $viewData->productID     = $productID;
        $viewData->planID        = $planID;
        $viewData->executionType = $executionType;
        $viewData->programPlan   = $programPlan;
        $viewData->productList   = $productList;
        $viewData->project       = $project;
        $viewData->plans         = $plans;
        $viewData->executions    = $executions;

        $this->programplanZen->buildCreateView($viewData);
    }

    /**
     * 编辑阶段内容。
     * Edit a project plan.
     *
     * @param  string    $planID
     * @param  string    $projectID
     * @access public
     * @return void
     */
    public function edit(string $planID = '0', string $projectID = '0')
    {
        $planID    = (int)$planID;
        $projectID = (int)$projectID;
        $plan      = $this->programplan->getByID($planID);

        if($_POST)
        {
            $formData     = form::data($this->config->programplan->edit->form);
            $postData     = $this->programplanZen->beforeEdit($formData);
            $postData->id = $planID;

            $changes = $this->programplan->update($planID, $projectID, $postData);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes) $this->programplanZen->afterEdit($plan, $changes);

            $locate = isonlybody() ? 'parent' : inlink('browse', "program=$plan->program&type=lists");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

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
            $formData = form::data($this->config->product->form->create);
            $this->programplanZen->beforeAjaxCustom($formData, $owner, $module);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
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

        $objectID   = $this->post->id;
        $objectType = $this->post->type;
        $this->loadModel('task')->updateEsDateByGantt($objectID, $objectType);
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

        $this->loadModel('task')->updateOrderByGantt();
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->loadModel('action')->create('task', $taskID, 'ganttMove');
        return $this->send(array('result' => 'success'));
    }

    /**
     * ajax请求：获取阶段ID的属性。
     * AJAX: Get stageID attributes.
     *
     * @param  string $stageID
     * @param  string $attribute
     * @access public
     * @return int
     */
    public function ajaxGetAttribute(string $stageID, string $attribute): int
    {
        $this->app->loadLang('stage');

        $stageID        = (int)$stageID;
        $stageAttribute = $this->programplan->getStageAttribute($stageID);

        if(empty($stageAttribute) || $stageAttribute == 'mix')
        {
            return print(html::select('attribute', $this->lang->stage->typeList, $attribute, "class='form-control chosen'"));
        }

        return print(zget($this->lang->stage->typeList, $stageAttribute));
    }

    /**
     * ajax请求：获取阶段ID的属性。
     * AJAX: Get stageID attribute.
     *
     * @param  string $stageID
     * @access public
     * @return int
     */
    public function ajaxGetStageAttr(string $stageID): int
    {
        $stageID        = (int)$stageID;
        $stageAttribute = $this->programplan->getStageAttribute($stageID);

        if(!$stageAttribute) return print(js::error(dao::getError()));

        return print($stageAttribute);
    }
}
