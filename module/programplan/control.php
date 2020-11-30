<?php
/**
 * The control file of programplan currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @version     $Id: control.php 5107 2013-07-12 01:46:12Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class programplan extends control
{
    /**
     * __construct
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->app->loadLang('review');
    }

    /**
     * Common action.
     *
     * @param  int     $projectID
     * @param  int     $productID
     * @param  string  $extra
     * @access public
     * @return void
     */
    public function commonAction($projectID, $productID = 0, $extra = '')
    {
        $products  = $this->loadModel('product')->getProductPairsByProject($projectID);
        $productID = $this->product->saveState($productID, $products);
        $this->productID = $productID;
        $this->product->setMenu($products, $productID, 0, 0, '', $extra);
        $this->programplan->setMenu($projectID, $productID);
    }

    /**
     * Browse program plans.
     *
     * @param  int     $projectID
     * @param  int     $productID
     * @param  string  $type
     * @param  string  $orderBy
     * @param  int     $baselineID
     * @access public
     * @return void
     */
    public function browse($projectID = 0, $productID = 0, $type = 'gantt', $orderBy = 'id_asc', $baselineID = 0)
    {
        $this->app->loadLang('stage');
        $this->commonAction($projectID, $productID, $type);
        $this->session->set('projectPlanList', $this->app->getURI(true));

        if(common::hasPriv('programplan', 'create')) $this->lang->TRActions = html::a($this->createLink('programplan', 'create', "projectID=$projectID"), "<i class='icon icon-sm icon-plus'></i> " . $this->lang->programplan->create, '', "class='btn btn-primary'");

        $selectCustom = 0; // Display date and task settings.
        $dateDetails  = 1; // Gantt chart detail date display.
        if($type == 'gantt')
        {
            $owner        = $this->app->user->account;
            $module       = 'programplan';
            $section      = 'browse';
            $object       = 'stageCustom';
            $selectCustom = $this->loadModel('setting')->getItem("owner={$owner}&module={$module}&section={$section}&key={$object}");
            if(strpos($selectCustom, 'date') !== false) $dateDetails = 0;

            $plans = $this->programplan->getDataForGantt($projectID, $this->productID, $baselineID);
        }

        if($type == 'lists')
        {
            $sort  = $this->loadModel('common')->appendOrder($orderBy);
            $this->loadModel('datatable');
            $plans = $this->programplan->getPlans($projectID, $this->productID, $sort);
        }

        $this->view->title        = $this->lang->programplan->browse;
        $this->view->position[]   = $this->lang->programplan->browse;
        $this->view->projectID    = $projectID;
        $this->view->productID    = $this->productID;
        $this->view->type         = $type;
        $this->view->plans        = $plans;
        $this->view->orderBy      = $orderBy;
        $this->view->selectCustom = $selectCustom;
        $this->view->dateDetails  = $dateDetails;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Create a project plan.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function create($projectID = 0, $productID = 0, $planID = 0)
    {
        $this->commonAction($projectID, $productID);
        $this->app->loadLang('project');
        if($_POST)
        {
            $this->programplan->create($projectID, $this->productID, $planID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $locate = $this->session->projectPlanList ? $this->session->projectPlanList : $this->createLink('programplan', 'browse', "projectID=$projectID");
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        $stages = empty($planID) ? $this->loadModel('stage')->getStages('id_asc') : array();

        $this->app->loadLang('stage');
        $project = $this->loadModel('project')->getById($projectID);
        $plans   = $this->programplan->getStage($planID ? $planID : $projectID, $this->productID, 'parent');

        $this->view->title      = $this->lang->programplan->create . $this->lang->colon . $project->name;
        $this->view->position[] = html::a($this->createLink('programplan', 'browse', "projectID=$projectID"), $project->name);
        $this->view->position[] = $this->lang->programplan->create;

        $this->view->project  = $project;
        $this->view->stages   = $stages;
        $this->view->plans    = $plans;
        $this->view->planID   = $planID;
        $this->view->type     = 'lists';

        $this->display();
    }

    /**
     * Edit a project plan.
     *
     * @param  int    $planID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function edit($planID = 0, $projectID = 0)
    {
        $this->app->loadLang('project');
        $plan = $this->programplan->getByID($planID);
        if($_POST)
        {
            $changes = $this->programplan->update($planID, $projectID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('programplan', $planID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            $locate = isonlybody() ? 'parent' : inlink('browse', "program=$plan->program&type=lists");
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        $this->app->loadLang('stage');
        $this->view->title        = $this->lang->programplan->edit;
        $this->view->position[]   = $this->lang->programplan->edit;
        $this->view->parentStage  = $this->programplan->getParentStageList($this->session->PRJ, $planID, $plan->product);
        $this->view->isCreateTask = $this->programplan->isCreateTask($planID);
        $this->view->plan         = $plan;

        $this->display();
    }

    /**
     * Delete a project plan.
     *
     * @param  int    $planID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($planID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->programplan->confirmDelete, $this->createLink('programplan', 'delete', "planID=$planID&confirm=yes")));
        }
        else
        {
            $response['result']  = 'success';
            $response['message'] = '';
            $this->programplan->delete(TABLE_PROJECT, $planID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
            }
            $this->send($response);
        }
    }

    /**
     * Save custom settings via ajax.
     *
     * @access public
     * @return void
     */
    public function ajaxCustom()
    {
        $data    = fixer::input('post')->get();
        $owner   = $this->app->user->account;
        $module  = 'programplan';
        $section = 'browse';
        $object  = 'stageCustom';
        $setting = $this->loadModel('setting');
        $custom  = empty($data->stageCustom) ? '' : implode(',', $data->stageCustom);
        $setting->setItem("$owner.$module.$section.$object", $custom);

        $response            = array();
        $response['result']  = 'success';
        $response['message'] = '';
        $this->send($response);
    }
}
