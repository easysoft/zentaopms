<?php
declare(strict_types=1);
class programplanZen extends programplan
{
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
        $rowData = $formData->rowdata;
        $plan    = $formData->join($rowData->output, ',')->get();
        return $plan;
    }

    /**
     * 阶段编辑后数据处理。
     * Handle data after edit.
     *
     * @param  object    $plan
     * @param  array    $changes
     * @access protected
     * @return viod
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
     * @return viod
     */
    protected function buildEditView(object $plan)
    {
        $this->loadModel('project');
        $this->app->loadLang('execution');
        $this->app->loadLang('stage');

        $parentStage = $this->project->getByID($plan->parent, 'stage');

        $this->view->title              = $this->lang->programplan->edit;
        $this->view->position[]         = $this->lang->programplan->edit;
        $this->view->isCreateTask       = $this->programplan->isCreateTask($plan->id);
        $this->view->plan               = $plan;
        $this->view->parentStageList    = $this->programplan->getParentStageList($this->session->project, $plan->id, $plan->product);
        $this->view->enableOptionalAttr = (empty($parentStage) or (!empty($parentStage) and $parentStage->attribute == 'mix'));
        $this->view->isTopStage         = $this->programplan->checkTopStage($plan->id);
        $this->view->isLeafStage        = $this->programplan->checkLeafStage($plan->id);
        $this->view->PMUsers            = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst',  $plan->PM);
        $this->display();
    }
}
