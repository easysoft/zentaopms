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
     * @access public
     * @return void
     */
    public function browse(int $projectID = 0, int $productID = 0, string $type = 'gantt', string $orderBy = 'id_asc', int $baselineID = 0)
    {
        $this->app->loadLang('stage');
        $this->session->set('projectPlanList', $this->app->getURI(true), 'project');
        $this->commonAction($projectID, $productID);

        if(!defined('RUN_MODE') || RUN_MODE != 'api') $projectID = $this->project->checkAccess($projectID, $this->project->getPairsByProgram());

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
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $planID
     * @param  string $executionType
     * @access public
     * @return void
     */
    public function create(int $projectID = 0, int $productID = 0, int $planID = 0, string $executionType = 'stage')
    {
        $this->productID = $this->commonAction($projectID, $productID);
        if($_POST)
        {
            $plans = $this->programplanZen->buildPlansForCreate($projectID, $planID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->programplan->create($plans, $projectID, $this->productID, $planID);
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

        $viewData = new stdclass();
        $viewData->productID     = $productID;
        $viewData->planID        = $planID;
        $viewData->executionType = $executionType;
        $viewData->programPlan   = $programPlan;
        $viewData->productList   = $productList;
        $viewData->project       = $project;
        $viewData->plans         = !empty($executions) ? $executions : $plans;

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
        $plan = $this->programplan->getByID($planID);

        if($_POST)
        {
            $formData = form::data($this->config->programplan->form->edit);

            $postData = $this->programplanZen->beforeEdit($formData);
            $postData->id = $planID;

            $changes = $this->programplan->update($planID, $projectID, $postData);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes) $this->programplanZen->afterEdit($plan, $changes);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => 'loadCurrentPage', 'closeModal' => true));
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
        $postData   = form::data($this->config->programplan->form->updateDateByGantt)->get();
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

        $this->loadModel('action')->create('task', $taskID, 'ganttMove');
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

        $stageAttribute = $this->programplan->getStageAttribute($stageID);

        if($projectModel == 'ipd') $this->lang->stage->typeList = $this->lang->stage->ipdTypeList;

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
     * @param  int    $stageID
     * @access public
     * @return int
     */
    public function ajaxGetStageAttr(int $stageID): int
    {
        $stageAttribute = $this->programplan->getStageAttribute($stageID);

        if(!$stageAttribute) return print(js::error(dao::getError()));

        return print($stageAttribute);
    }
}
