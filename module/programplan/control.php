<?php
declare(strict_types=1);

/**
 * The control file of programplan currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @version     $Id: control.php 5107 2013-07-12 01:46:12Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
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
        $products  = $this->loadModel('product')->getProductPairsByProject($projectID);
        $productID = $this->loadModel('product')->saveVisitState($productID, $products);
        $project   = $this->loadModel('project')->getByID($projectID);

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

        /* 获取项目下产品并调整三级导航操作按钮。 */
        $products = $this->loadModel('product')->getProducts($projectID);
        if($this->session->hasProduct) $this->lang->modulePageNav = $this->product->select($products, $productID, 'programplan', 'browse', $type, 0, false);

        /* 生成阶段列表页阶段数据。 */
        $stages = $this->programplanZen->buildBrowseStages($projectID, $productID, $baselineID, $type, $orderBy);

        $this->programplanZen->buildBrowseView($projectID, $productID, $stages, $type, $orderBy);
    }

    /**
     * 创建一个项目阶段。
     * Create a project plan/phase.
     *
     * @param  string  $projectID
     * @param  string  $productID
     * @param  string  $planID
     * @param  string  $executionType
     * @access public
     * @return void
     */
    public function create(string $projectID = '0', string $productID = '0', string $planID = '0', string $executionType = 'stage'): void
    {
        $projectID = (int) $projectID;
        $productID = (int) $productID;
        $planID    = (int) $planID;

        $this->commonAction($projectID, $productID);

        if($_POST)
        {
            $formData = form::data($this->config->programplan->create->form);
            $this->programplan->create($formData, $projectID, $productID, $planID);
            if(dao::isError())
            {
                $errors = dao::getError();
                if(isset($errors['message']))  $this->send(array('result' => 'fail', 'message' => $errors));
                if(!isset($errors['message'])) $this->send(array('result' => 'fail', 'callback' => array('name' => 'addRowErrors', 'params' => array($errors))));
            }

            $locate = $this->createLink('project', 'execution', "status=all&projectID=$projectID&orderBy=order_asc&productID=$productID");
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        $project     = $this->project->getById($projectID);
        $programPlan = $this->project->getById($planID, 'stage');
        $productList = $this->session->hasProduct ? $this->product->getProductPairsByProject($projectID) : array();
        $executions  = !empty($planID) ? $this->loadModel('execution')->getChildExecutions($planID, 'order_asc') : array();

        /* Set programplan typeList. */
        if($executionType != 'stage') unset($this->lang->execution->typeList[''], $this->lang->execution->typeList['stage']);
        $plans = $this->programplan->getStage($planID ? $planID : $projectID, $productID, 'parent', 'order_asc');
        if(!empty($planID) and !empty($plans) and $project->model == 'waterfallplus')
        {
            $executionType = 'stage';
            unset($this->lang->programplan->typeList['agileplus']);

            if(!empty($executions))
            {
                $executionType = 'agileplus';
                unset($this->lang->programplan->typeList['stage']);
            }
        }

        /* Compute fields for create view. */
        list($visibleFields, $requiredFields, $customFields, $showFields) = $this->programplanZen->computeFieldsCreateView($executionType);

        $this->view->title              = $this->lang->programplan->create . $this->lang->colon . $project->name;
        $this->view->position[]         = html::a($this->createLink('programplan', 'browse', "projectID=$projectID"), $project->name);
        $this->view->position[]         = $this->lang->programplan->create;
        $this->view->productList        = $productList;
        $this->view->project            = $project;
        $this->view->productID          = $productID ? $productID : key($productList);
        $this->view->stages             = empty($planID) ? $this->loadModel('stage')->getStages('id_asc', 0, $project->model) : array();
        $this->view->programPlan        = $programPlan;
        $this->view->plans              = empty($executions) ? $plans : $executions;
        $this->view->planID             = $planID;
        $this->view->type               = 'lists';
        $this->view->executionType      = $executionType;
        $this->view->PMUsers            = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst',  $project->PM);
        $this->view->custom             = $executionType == 'stage' ? 'custom' : 'customAgilePlus';
        $this->view->customFields       = $customFields;
        $this->view->showFields         = $showFields;
        $this->view->visibleFields      = $visibleFields;
        $this->view->requiredFields     = $requiredFields;
        $this->view->colspan            = count($visibleFields) + 3;
        $this->view->enableOptionalAttr = (empty($programPlan) or (!empty($programPlan) and $programPlan->attribute == 'mix'));

        $this->display();
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
        if(empty($_POST['id']) || empty($_POST['type'])) return $this->send(array('result' => 'fail', 'message' => ''));

        $objectID   = $_POST['id'];
        $objectType = $_POST['type'];
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
        if(empty($_POST['id'])) return $this->send(array('result' => 'fail', 'message' => ''));

        $idList = explode('-', $_POST['id']);
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
