<?php
global $lang;
$config->productplan->form = new stdclass();
$config->productplan->form->batchEdit = array();
$config->productplan->form->batchEdit['branch'] = array('type' => 'array',  'control' => 'picker', 'width' => '200px', 'name' => 'branch', 'label' => $lang->productplan->branch, 'required' => false, 'default' => 0, 'items' => array(), 'multiple' => true, 'filter' => 'join');
$config->productplan->form->batchEdit['title']  = array('type' => 'string', 'control' => 'text',                       'name' => 'title',  'label' => $lang->productplan->title,  'required' => true,  'base'    => true, 'filter' => 'trim');
$config->productplan->form->batchEdit['status'] = array('type' => 'string', 'control' => 'picker', 'width' => '200px', 'name' => 'status', 'label' => $lang->productplan->status, 'required' => false, 'default' => '', 'items'  => $lang->productplan->statusList);
$config->productplan->form->batchEdit['begin']  = array('type' => 'date',   'control' => 'date',   'width' => '128px', 'name' => 'begin',  'label' => $lang->productplan->begin,  'required' => false, 'default' => '');
$config->productplan->form->batchEdit['end']    = array('type' => 'date',   'control' => 'date',   'width' => '128px', 'name' => 'end',    'label' => $lang->productplan->end,    'required' => false, 'default' => '');
