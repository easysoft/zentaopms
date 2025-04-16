<?php
global $lang;
$config->deliverable = new stdclass();
$config->deliverable->create = new stdclass();
$config->deliverable->edit   = new stdclass();
$config->deliverable->create->requiredFields = 'name,module,method,model';
$config->deliverable->edit->requiredFields   = 'name,module,method,model';

$config->deliverable->actionList = array();
$config->deliverable->actionList['edit']['icon'] = 'edit';
$config->deliverable->actionList['edit']['text'] = $lang->deliverable->edit;
$config->deliverable->actionList['edit']['hint'] = $lang->deliverable->edit;
$config->deliverable->actionList['edit']['url']  = array('module' => 'deliverable', 'method' => 'edit', 'params' => 'id={id}');

$config->deliverable->actionList['delete']['icon']         = 'trash';
$config->deliverable->actionList['delete']['text']         = $lang->deliverable->delete;
$config->deliverable->actionList['delete']['hint']         = $lang->deliverable->delete;
$config->deliverable->actionList['delete']['url']          = array('module' => 'deliverable', 'method' => 'delete', 'params' => 'id={id}');
$config->deliverable->actionList['delete']['data-confirm'] = $lang->deliverable->confirmDelete;