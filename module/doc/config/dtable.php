<?php
global $lang;
$config->doc->dtable = new stdclass();
$config->doc->dtable->fieldList['id']['title'] = $lang->idAB;
$config->doc->dtable->fieldList['id']['type']  = 'id';

$config->doc->dtable->fieldList['title']['type'] = 'title';
$config->doc->dtable->fieldList['title']['link'] = array('module' => 'doc', 'method' => 'view', 'params' => 'docID={id}');

$config->doc->dtable->fieldList['objectName']['title']      = $lang->doc->object;
$config->doc->dtable->fieldList['objectName']['type']       = 'desc';
$config->doc->dtable->fieldList['objectName']['iconRender'] = 'RAWJS<function(val,row){ if(row.data.objectType == \'execution\') return \'icon-run text-gray\'; if(row.data.objectType == \'mine\') return \'icon-contacts text-gray\'; if(row.data.objectType == \'custom\') return \'icon-groups text-gray\'; if([\'project\', \'product\'].indexOf(row.data.objectType) !== -1) return \'icon-\' + row.data.objectType + \' text-gray\'; return \'\';}>RAWJS';
$config->doc->dtable->fieldList['objectName']['sortType']   = false;

$config->doc->dtable->fieldList['module']['title']    = $lang->doc->position;
$config->doc->dtable->fieldList['module']['type']     = 'desc';
$config->doc->dtable->fieldList['module']['sortType'] = true;

$config->doc->dtable->fieldList['addedBy']['title'] = $lang->doc->addedByAB;
$config->doc->dtable->fieldList['addedBy']['type']  = 'user';

$config->doc->dtable->fieldList['addedDate']['type'] = 'date';

$config->doc->dtable->fieldList['editedBy']['type']  = 'user';

$config->doc->dtable->fieldList['editedDate']['type'] = 'date';

$config->doc->dtable->fieldList['actions']['type'] = 'actions';
$config->doc->dtable->fieldList['actions']['menu'] = array('edit', 'delete');
$config->doc->dtable->fieldList['actions']['list'] = $config->doc->actionList;
