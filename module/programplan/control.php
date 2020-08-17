<?php
class programplan extends control
{
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->app->loadLang('review');
    }

    public function commonAction($programID, $productID = 0, $extra = '')
    {
        $products  = $this->loadModel('product')->getPairs($programID);
        $productID = $this->product->saveState($productID, $products);
        $this->product->setMenu($products, $productID, 0, 0, '', $extra);
        $this->productID = $productID;
        $this->programplan->setMenu($programID, $productID);
    }

    public function browse($programID = 0, $productID = 0, $type = 'gantt', $orderBy = 'id_asc', $baselineID = 0)
    {
        $this->app->loadLang('stage');
        $this->commonAction($programID, $productID, $type);
        $this->session->set('programplanList', $this->app->getURI(true));

        $selectCustom = 0;
        $dateDetails  = 1;
        if($type == 'gantt')
        {
            $owner        = $this->app->user->account;
            $module       = 'programplan';
            $section      = 'browse';
            $object       = 'stageCustom';
            $setting      = $this->loadModel('setting');
            $selectCustom = $setting->getItem("owner={$owner}&module={$module}&section={$section}&key={$object}");

            if(strpos($selectCustom, 'date') !== false) $dateDetails = 0;

            $plans = $this->programplan->getDataForGantt($programID, $this->productID, $baselineID);
        }

        if($type == 'lists')
        {
            $sort  = $this->loadModel('common')->appendOrder($orderBy);
            $this->loadModel('datatable');
            $plans = $this->programplan->getPlans($programID, $this->productID, $sort);
        }

        $this->view->title        = $this->lang->programplan->browse;
        $this->view->position[]   = $this->lang->programplan->browse;
        $this->view->programID    = $programID;
        $this->view->productID    = $this->productID;
        $this->view->type         = $type;
        $this->view->plans        = $plans;
        $this->view->orderBy      = $orderBy;
        $this->view->selectCustom = $selectCustom;
        $this->view->dateDetails  = $dateDetails;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    public function create($programID = 0, $productID = 0, $planID = 0)
    {
        $this->commonAction($programID, $productID);
        if($_POST)
        {
            $this->programplan->create($programID, $planID, $this->productID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $locate = $this->session->programplanList ? $this->session->programplanList : $this->createLink('programplan', 'browse', "program=$programID");

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        $this->app->loadLang('stage');
        $program = $this->loadModel('project')->getById($programID);
        $stages  = $planID ? array() : $this->loadModel('stage')->getStages();
        $plans   = $this->programplan->getList($programID, $this->productID, $planID);

        $title      = $this->lang->programplan->create . $this->lang->colon . $program->name;
        $position[] = html::a($this->createLink('programplan', 'browse', "program=$programID"), $program->name);
        $position[] = $this->lang->programplan->create;

        $this->view->title        = $title;
        $this->view->position     = $position;
        $this->view->program      = $program;
        $this->view->stages       = $stages;
        $this->view->plans        = $plans;
        $this->view->planID       = $planID;
        $this->view->type         = 'lists';

        $this->display();
    }

    public function edit($planID = 0)
    {
        $plan = $this->programplan->getByID($planID);
        $this->commonAction($plan->program, $plan->product);

        if($_POST)
        {
            $changes = $this->programplan->update($planID);
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
        $this->view->parentStage  = $this->programplan->getParentStageList($planID, $plan->product);
        $this->view->plan         = $plan;
        $this->view->isParent     = $this->programplan->isParent($planID);
        $this->view->isCreateTask = $this->programplan->isCreateTask($planID);

        $this->display();
    }

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

        $respone             = array();
        $response['result']  = 'success';
        $response['message'] = '';
        $this->send($response);
    }
}
