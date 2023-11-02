<?php
global $lang;
$config->webhook->dtable = new stdclass();
$config->webhook->dtable->fieldList['id']['name']     = 'id';
$config->webhook->dtable->fieldList['id']['title']    = $lang->idAB;
$config->webhook->dtable->fieldList['id']['fixed']    = 'left';
$config->webhook->dtable->fieldList['id']['required'] = 'yes';
$config->webhook->dtable->fieldList['id']['type']     = 'checkID';
$config->webhook->dtable->fieldList['id']['checkbox'] = false;
$config->webhook->dtable->fieldList['id']['show']     = true;
$config->webhook->dtable->fieldList['id']['sortType'] = true;
$config->webhook->dtable->fieldList['id']['group']    = 1;

$config->webhook->dtable->fieldList['type']['name']     = 'type';
$config->webhook->dtable->fieldList['type']['title']    = $lang->webhook->type;
$config->webhook->dtable->fieldList['type']['width']    = 136;
$config->webhook->dtable->fieldList['type']['type']     = 'html';
$config->webhook->dtable->fieldList['type']['sortType'] = true;
$config->webhook->dtable->fieldList['type']['align']    = 'left';

$config->webhook->dtable->fieldList['name']['name']     = 'name';
$config->webhook->dtable->fieldList['name']['title']    = $lang->webhook->name;
$config->webhook->dtable->fieldList['name']['minWidth'] = 108;
$config->webhook->dtable->fieldList['name']['type']     = 'html';
$config->webhook->dtable->fieldList['name']['sortType'] = true;
$config->webhook->dtable->fieldList['name']['align']    = 'left';

$config->webhook->dtable->fieldList['url']['name']     = 'url';
$config->webhook->dtable->fieldList['url']['title']    = $lang->webhook->url;
$config->webhook->dtable->fieldList['url']['minWidth'] = 150;
$config->webhook->dtable->fieldList['url']['type']     = 'html';
$config->webhook->dtable->fieldList['url']['sortType'] = true;
$config->webhook->dtable->fieldList['url']['align']    = 'left';

$config->webhook->dtable->fieldList['actions']['name']     = 'actions';
$config->webhook->dtable->fieldList['actions']['title']    = $lang->actions;
$config->webhook->dtable->fieldList['actions']['type']     = 'actions';
$config->webhook->dtable->fieldList['actions']['minWidth'] = '110';
$config->webhook->dtable->fieldList['actions']['fixed']    = 'right';

$config->webhook->dtable->fieldList['actions']['actionsMap']['chooseDept']['icon'] = 'link';
$config->webhook->dtable->fieldList['actions']['actionsMap']['chooseDept']['hint'] = $lang->webhook->chooseDept;

$config->webhook->dtable->fieldList['actions']['actionsMap']['bind']['icon'] = 'link';
$config->webhook->dtable->fieldList['actions']['actionsMap']['bind']['hint'] = $lang->webhook->bind;

$config->webhook->dtable->fieldList['actions']['actionsMap']['delete']['icon'] = 'trash';
$config->webhook->dtable->fieldList['actions']['actionsMap']['delete']['hint'] = $lang->webhook->delete;

$config->webhook->dtable->fieldList['actions']['actionsMap']['log']['icon'] = 'file-text';
$config->webhook->dtable->fieldList['actions']['actionsMap']['log']['hint'] = $lang->webhook->log;

$config->webhook->dtable->fieldList['actions']['actionsMap']['edit']['icon'] = 'edit';
$config->webhook->dtable->fieldList['actions']['actionsMap']['edit']['hint'] = $lang->webhook->edit;

$config->webhook->dtable->log = new stdclass();
$config->webhook->dtable->log->fieldList['id']['name']     = 'id';
$config->webhook->dtable->log->fieldList['id']['title']    = $lang->idAB;
$config->webhook->dtable->log->fieldList['id']['fixed']    = 'left';
$config->webhook->dtable->log->fieldList['id']['required'] = 'yes';
$config->webhook->dtable->log->fieldList['id']['type']     = 'checkID';
$config->webhook->dtable->log->fieldList['id']['checkbox'] = false;
$config->webhook->dtable->log->fieldList['id']['show']     = true;
$config->webhook->dtable->log->fieldList['id']['sortType'] = false;
$config->webhook->dtable->log->fieldList['id']['group']    = 1;

$config->webhook->dtable->log->fieldList['date']['name']     = 'date';
$config->webhook->dtable->log->fieldList['date']['title']    = $lang->webhook->date;
$config->webhook->dtable->log->fieldList['date']['width']    = 136;
$config->webhook->dtable->log->fieldList['date']['type']     = 'html';
$config->webhook->dtable->log->fieldList['date']['align']    = 'left';
$config->webhook->dtable->log->fieldList['date']['sortType'] = false;

$config->webhook->dtable->log->fieldList['url']['name']     = 'url';
$config->webhook->dtable->log->fieldList['url']['title']    = $lang->webhook->url;
$config->webhook->dtable->log->fieldList['url']['minWidth'] = 150;
$config->webhook->dtable->log->fieldList['url']['type']     = 'html';
$config->webhook->dtable->log->fieldList['url']['align']    = 'left';

$config->webhook->dtable->log->fieldList['action']['name']     = 'action';
$config->webhook->dtable->log->fieldList['action']['title']    = $lang->webhook->action;
$config->webhook->dtable->log->fieldList['action']['minWidth'] = 300;
$config->webhook->dtable->log->fieldList['action']['type']     = 'html';
$config->webhook->dtable->log->fieldList['action']['align']    = 'left';

$config->webhook->dtable->log->fieldList['contentType']['name']     = 'contentType';
$config->webhook->dtable->log->fieldList['contentType']['title']    = $lang->webhook->contentType;
$config->webhook->dtable->log->fieldList['contentType']['minWidth'] = 200;
$config->webhook->dtable->log->fieldList['contentType']['type']     = 'html';

$config->webhook->dtable->log->fieldList['result']['name']     = 'result';
$config->webhook->dtable->log->fieldList['result']['title']    = $lang->webhook->result;
$config->webhook->dtable->log->fieldList['result']['minWidth'] = 200;
$config->webhook->dtable->log->fieldList['result']['type']     = 'html';
