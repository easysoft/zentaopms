<?php
$config->my->doc = new stdclass();
$config->my->doc->actionList = array();
$config->my->doc->actionList['edit']['icon'] = 'edit';
$config->my->doc->actionList['edit']['text'] = $lang->edit;
$config->my->doc->actionList['edit']['hint'] = $lang->edit;
$config->my->doc->actionList['edit']['url']  = array('module' => 'doc', 'method' => 'edit', 'params' => "docID={id}");

$config->my->doc->actionList['delete']['icon']         = 'trash';
$config->my->doc->actionList['delete']['text']         = $lang->delete;
$config->my->doc->actionList['delete']['hint']         = $lang->delete;
$config->my->doc->actionList['delete']['url']          = array('module' => 'doc', 'method' => 'delete', 'params' => "docID={id}");
$config->my->doc->actionList['delete']['className']    = 'ajax-submit';
$config->my->doc->actionList['delete']['data-confirm'] = array('message' => $lang->doc->confirmDelete);

$config->my->doc->dtable = new stdclass();
$config->my->doc->dtable->fieldList['id']['name']  = 'id';
$config->my->doc->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->doc->dtable->fieldList['id']['type']  = 'id';

$config->my->doc->dtable->fieldList['title']['name']  = 'title';
$config->my->doc->dtable->fieldList['title']['title'] = $lang->doc->title;
$config->my->doc->dtable->fieldList['title']['type']  = 'title';
$config->my->doc->dtable->fieldList['title']['link']  = array('module' => 'doc', 'method' => 'view', 'params' => 'docID={id}');

$config->my->doc->dtable->fieldList['object']['name']  = 'objectName';
$config->my->doc->dtable->fieldList['object']['title'] = $lang->doc->object;
$config->my->doc->dtable->fieldList['object']['type']  = 'desc';
$config->my->doc->dtable->fieldList['object']['group'] = 'object';

$config->my->doc->dtable->fieldList['addedBy']['name']  = 'addedBy';
$config->my->doc->dtable->fieldList['addedBy']['title'] = $lang->doc->addedBy;
$config->my->doc->dtable->fieldList['addedBy']['type']  = 'user';
$config->my->doc->dtable->fieldList['addedBy']['group'] = 'addedBy';

$config->my->doc->dtable->fieldList['addedDate']['name']  = 'addedDate';
$config->my->doc->dtable->fieldList['addedDate']['title'] = $lang->doc->addedDate;
$config->my->doc->dtable->fieldList['addedDate']['type']  = 'date';
$config->my->doc->dtable->fieldList['addedDate']['group'] = 'addedBy';

$config->my->doc->dtable->fieldList['editedBy']['name']  = 'editedBy';
$config->my->doc->dtable->fieldList['editedBy']['title'] = $lang->doc->editedBy;
$config->my->doc->dtable->fieldList['editedBy']['type']  = 'user';
$config->my->doc->dtable->fieldList['editedBy']['group'] = 'addedBy';

$config->my->doc->dtable->fieldList['editedDate']['name']  = 'editedDate';
$config->my->doc->dtable->fieldList['editedDate']['title'] = $lang->doc->editedDate;
$config->my->doc->dtable->fieldList['editedDate']['type']  = 'date';
$config->my->doc->dtable->fieldList['editedDate']['group'] = 'addedBy';

$config->my->doc->dtable->fieldList['actions']['name']     = 'actions';
$config->my->doc->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->doc->dtable->fieldList['actions']['type']     = 'actions';
$config->my->doc->dtable->fieldList['actions']['sortType'] = false;
$config->my->doc->dtable->fieldList['actions']['list']     = $config->my->doc->actionList;
$config->my->doc->dtable->fieldList['actions']['menu']     = array('edit', 'delete');
