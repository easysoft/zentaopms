<?php
class dev extends control
{
    public function api()
    {
        $this->view->tables     = $this->dev->getTables();
        $this->view->tab        = 'api';
        $this->view->position[] = html::a(inlink('api'), $this->lang->dev->common);
        $this->view->position[] = $this->lang->dev->api;
        $this->display();
    }
    
    public function db($table = '')
    {
        $this->view->tables        = $this->dev->getTables();
        $this->view->tab           = 'db';
        $this->view->selectedTable = $table;
        $this->view->tab           = 'db';
        $this->view->fields        = $table ? $this->dev->descTable($table) : array();
        $this->view->position[]    = html::a(inlink('api'), $this->lang->dev->common);
        $this->view->position[]    = $this->lang->dev->db;
        $this->display();
    }
}
