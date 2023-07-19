<?php
global $lang;
$config->doc->myspace = new stdclass();
$config->doc->myspace->dtable = new stdclass();
$config->doc->myspace->dtable->fieldList['id']['title'] = $lang->idAB;
$config->doc->myspace->dtable->fieldList['id']['type']  = 'id';

$config->doc->myspace->dtable->fieldList['title']['type'] = 'title';
$config->doc->myspace->dtable->fieldList['title']['link'] = array('module' => 'doc', 'method' => 'view', 'params' => 'docID={id}');

$config->doc->myspace->dtable->fieldList['objectName']['title']      = $lang->doc->object;
$config->doc->myspace->dtable->fieldList['objectName']['type']       = 'desc';
$config->doc->myspace->dtable->fieldList['objectName']['iconRender'] = 'RAWJS<function(val,row){ if(row.data.objectType == \'execution\') return \'icon-run text-gray\'; if(row.data.objectType == \'mine\') return \'icon-contacts text-gray\'; if(row.data.objectType == \'custom\') return \'icon-groups text-gray\'; if([\'project\', \'product\'].indexOf(row.data.objectType) !== -1) return \'icon-\' + row.data.objectType + \' text-gray\'; return \'\';}>RAWJS';
$config->doc->myspace->dtable->fieldList['objectName']['sortType']   = false;

$config->doc->myspace->dtable->fieldList['module']['title']    = $lang->doc->position;
$config->doc->myspace->dtable->fieldList['module']['type']     = 'desc';
$config->doc->myspace->dtable->fieldList['module']['sortType'] = true;

$config->doc->myspace->dtable->fieldList['addedBy']['title'] = $lang->doc->addedByAB;
$config->doc->myspace->dtable->fieldList['addedBy']['type']  = 'user';

$config->doc->myspace->dtable->fieldList['addedDate']['type'] = 'date';

$config->doc->myspace->dtable->fieldList['editedBy']['type']  = 'user';

$config->doc->myspace->dtable->fieldList['editedDate']['type'] = 'date';

$config->doc->myspace->dtable->fieldList['actions']['type'] = 'actions';
$config->doc->myspace->dtable->fieldList['actions']['menu'] = array('edit', 'delete');
$config->doc->myspace->dtable->fieldList['actions']['list'] = $config->doc->actionList;
