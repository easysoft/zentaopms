<?php
public function __construct($appName = '')
{
    parent::__construct($appName);
    $this->lang->kanban->menu = new stdclass();
}
