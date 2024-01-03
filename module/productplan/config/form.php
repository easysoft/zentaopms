<?php
global $lang, $app;
$config->productplan->form = new stdclass();
$config->productplan->form->batchEdit = array();
$config->productplan->form->batchEdit['id']     = array('type' => 'int',    'control' => 'text',   'width' => '60px',  'name' => 'id',     'label' => $lang->idAB,                'required' => false, 'default' => 0, 'base' => true);
$config->productplan->form->batchEdit['branch'] = array('type' => 'array',  'control' => 'picker', 'width' => '200px', 'name' => 'branch', 'label' => $lang->productplan->branch, 'required' => false, 'default' => 0, 'items' => array(), 'multiple' => true, 'filter' => 'join');
$config->productplan->form->batchEdit['title']  = array('type' => 'string', 'control' => 'text',                       'name' => 'title',  'label' => $lang->productplan->title,  'required' => true,  'default' => '', 'filter' => 'trim');
$config->productplan->form->batchEdit['status'] = array('type' => 'string', 'control' => 'picker', 'width' => '200px', 'name' => 'status', 'label' => $lang->productplan->status, 'required' => false, 'default' => '', 'items' => $lang->productplan->statusList);
$config->productplan->form->batchEdit['begin']  = array('type' => 'date',   'control' => 'date',   'width' => '128px', 'name' => 'begin',  'label' => $lang->productplan->begin,  'required' => false, 'default' => '');
$config->productplan->form->batchEdit['end']    = array('type' => 'date',   'control' => 'date',   'width' => '128px', 'name' => 'end',    'label' => $lang->productplan->end,    'required' => false, 'default' => '');

$now = helper::now();
$config->productplan->form->create['parent']      = array('type' => 'int',       'required' => false, 'default' => 0);
$config->productplan->form->create['branch']      = array('type' => 'array',     'required' => false, 'default' => '0', 'filter' => 'join');
$config->productplan->form->create['title']       = array('type' => 'string',    'required' => true,  'default' => '', 'filter' => 'trim');
$config->productplan->form->create['begin']       = array('type' => 'date',      'required' => false, 'default' => null);
$config->productplan->form->create['end']         = array('type' => 'date',      'required' => false, 'default' => null);
$config->productplan->form->create['desc']        = array('type' => 'string',    'required' => false, 'default' => '', 'control' => 'editor');
$config->productplan->form->create['product']     = array('type' => 'int',       'required' => false, 'default' => 0);
$config->productplan->form->create['createdBy']   = array('type' => 'string',    'required' => false, 'default' => $app->user->account);
$config->productplan->form->create['createdDate'] = array('type' => 'datetime ', 'required' => false, 'default' => $now);
$config->productplan->form->create['order']       = array('type' => 'string',    'required' => false, 'default' => 0);

$config->productplan->form->edit['parent']  = array('type' => 'int',       'required' => false, 'default' => 0);
$config->productplan->form->edit['branch']  = array('type' => 'array',     'required' => false, 'default' => '0', 'filter' => 'join');
$config->productplan->form->edit['title']   = array('type' => 'string',    'required' => true,  'default' => '', 'filter' => 'trim');
$config->productplan->form->edit['begin']   = array('type' => 'date',      'required' => false, 'default' => null);
$config->productplan->form->edit['end']     = array('type' => 'date',      'required' => false, 'default' => null);
$config->productplan->form->edit['desc']    = array('type' => 'string',    'required' => false, 'default' => '', 'control' => 'editor');
$config->productplan->form->edit['product'] = array('type' => 'int',       'required' => false, 'default' => 0);
$config->productplan->form->edit['status']  = array('type' => 'string',    'required' => false, 'default' => '');
