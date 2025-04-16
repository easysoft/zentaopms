<?php
class deliverable extends control
{
    /**
     * 交付物列表。
     * Browse deliverables.
     *
     * @access public
     * @return void
     */
    public function browse($browseType = '', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 0, $pageID = 1)
    {
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $queryID = $browseType == 'bysearch' ? (int)$param : 0;

        $this->view->title        = $this->lang->deliverable->common;
        $this->view->deliverables = $this->deliverable->getList($browseType, $queryID, $orderBy, $pager);
        $this->view->orderBy      = $orderBy;
        $this->view->pager        = $pager;
        $this->view->browseType   = $browseType;
        $this->view->param        = $param;
        $this->view->users        = $this->loadModel('user')->getPairs('noclosed|nodeleted|noletter');
        $this->display();
    }

    /**
     * 创建交付物。
     * Create deliverable.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $files = $this->loadModel('file')->getUpload();
            if(empty($files[0]['title'])) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->error->notempty, $this->lang->deliverable->files)));

            $deliverable = form::data($this->config->deliverable->form->create)
                ->add('createdBy', $this->app->user->account)
                ->add('createdDate', helper::today())
                ->get();

            $this->deliverable->create($deliverable);

            if(dao::isError())  return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->sendSuccess(array('load' => inlink('browse')));
        }

        $this->view->title     = $this->lang->deliverable->create;
        $this->view->modelList = $this->deliverable->buildModelList('project');
        $this->display();
    }

    /**
     * Ajax获取交付物适用范围列表。
     * Ajax get deliverable model list.
     *
     * @param string $type execution|project
     * @access public
     * @return void
     */
    public function ajaxGetModelList($type = 'execution')
    {
        $items     = array();
        $modelList = $this->deliverable->buildModelList($type);
        foreach($modelList as $key => $value)
        {
            $items[] = array('value' => $key, 'text' => $value);
        }

        return print(json_encode($items));
    }
}