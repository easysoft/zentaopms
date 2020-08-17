<?php
class design extends control
{
    public function browse($productID = 0, $mode = 'browse', $label = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->design->setProductMenu($productID);
        $product = $this->loadModel('product')->getById($productID);
        $project = $this->loadModel('project')->getById($product->program);

        $module = 'design';
        $action = 'browse';
        list($flow, $action, $fields) = $this->design->setFlowActionFields($module, $action);

        $this->view->title  = $action->name;
        $this->view->flow   = $flow;
        $this->view->fields = $fields;
        if($action->action == 'view') $this->view->flowAction = $action;
        if($action->action != 'view') $this->view->action     = $action;

        $this->loadModel('flow')->checkPrivilege($flow, $action);
        $this->flow->setSearchParams($flow);

        $labels = $this->loadModel('workflowlabel', 'flow')->getList($flow->module);

        if(!$label)
        {
            reset($labels);
            $label = current($labels)->id;
        }

        $this->app->loadClass('pager', $static = true);
        $pager    = new pager($recTotal, $recPerPage, $pageID);
        $dataList = $this->flow->getDataList($flow, $mode, $label, 0, $orderBy, $pager);

        $this->view->dataList     = $dataList;
        $this->view->valueFields  = $this->loadModel('workflowfield', 'flow')->getValueFields($flow->module);
        $this->view->moduleMenu   = $this->flow->getModuleMenu($flow, $labels);
        $this->view->batchActions = $this->flow->buildBatchActions($flow);
        $this->view->summary      = $this->flow->getSummary($dataList, $fields);
        $this->view->mode         = $mode;
        $this->view->label        = $label;
        $this->view->allLabel     = reset($labels)->id == $label ? 1 : 0;
        $this->view->designType   = reset($labels)->id == $label ? 'all' : $labels[$label]->params[1]['value'];
        $this->view->orderBy      = $orderBy;
        $this->view->pager        = $pager;
        $this->view->productID    = $productID;
        $this->view->program      = $project;
        $this->display();
    }
}
