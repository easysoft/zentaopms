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
    public function browse()
    {
        $this->view->deliverables = array();
        $this->view->title        = $this->lang->deliverable->common;
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
        $this->view->title     = $this->lang->deliverable->create;
        $this->view->modelList = $this->deliverable->buildModelList();
        $this->display();
    }
}
