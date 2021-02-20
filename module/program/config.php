<?php
$config->program = new stdclass();

$config->program->editor = new stdclass();
$config->program->editor->pgmcreate   = array('id' => 'desc',    'tools' => 'simpleTools');
$config->program->editor->pgmedit     = array('id' => 'desc',    'tools' => 'simpleTools');
$config->program->editor->pgmclose    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->pgmstart    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->pgmactivate = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->pgmfinish   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->pgmsuspend  = array('id' => 'comment', 'tools' => 'simpleTools');

$config->program->editor->prjcreate   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->program->editor->prjedit     = array('id' => 'desc', 'tools' => 'simpleTools');
$config->program->editor->prjclose    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->prjsuspend  = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->prjstart    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->prjactivate = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->prjview     = array('id' => 'comment', 'tools' => 'simpleTools');

$config->program->list = new stdclass();
$config->program->list->exportFields = 'id,name,code,template,product,status,begin,end,budget,PM,end,desc';

$config->program->PGMCreate = new stdclass();
$config->program->PGMEdit   = new stdclass();
$config->program->PGMCreate->requiredFields = 'name,begin,end';
$config->program->PGMEdit->requiredFields   = 'name,begin,end';

$config->program->PRJCreate = new stdclass();
$config->program->PRJEdit   = new stdclass();
$config->program->PRJCreate->requiredFields = 'name,code,begin,end';
$config->program->PRJEdit->requiredFields   = 'name,code,begin,end';

$config->program->sortFields            = new stdclass();
$config->program->sortFields->id        = 'id';
$config->program->sortFields->begin     = 'begin';
$config->program->sortFields->end       = 'end';
$config->program->sortFields->PRJStatus = 'status';
$config->program->sortFields->PRJBudget = 'budget';

global $lang;
$config->program->datatable = new stdclass();
$config->program->datatable->defaultField = array('id', 'PRJName', 'PM', 'PRJStatus', 'begin', 'end', 'PRJBudget', 'teamCount','PRJEstimate','PRJConsume', 'PRJProgress', 'actions');

$config->program->datatable->fieldList['id']['title']    = 'ID';
$config->program->datatable->fieldList['id']['fixed']    = 'left';
$config->program->datatable->fieldList['id']['width']    = '60';
$config->program->datatable->fieldList['id']['required'] = 'yes';

$config->program->datatable->fieldList['PRJName']['title']    = 'PRJName';
$config->program->datatable->fieldList['PRJName']['fixed']    = 'left';
$config->program->datatable->fieldList['PRJName']['width']    = 'auto';
$config->program->datatable->fieldList['PRJName']['required'] = 'yes';
$config->program->datatable->fieldList['PRJName']['sort']     = 'no';

//$config->program->datatable->fieldList['PRJProgram']['title']    = 'common';
//$config->program->datatable->fieldList['PRJProgram']['fixed']    = 'left';
//$config->program->datatable->fieldList['PRJProgram']['width']    = '140';
//$config->program->datatable->fieldList['PRJProgram']['required'] = 'no';
//$config->program->datatable->fieldList['PRJProgram']['sort']     = 'no';

$config->program->datatable->fieldList['PM']['title']    = 'PM';
$config->program->datatable->fieldList['PM']['fixed']    = 'no';
$config->program->datatable->fieldList['PM']['width']    = '80';
$config->program->datatable->fieldList['PM']['required'] = 'yes';
$config->program->datatable->fieldList['PM']['sort']     = 'no';

$config->program->datatable->fieldList['PRJStatus']['title']    = 'PRJStatus';
$config->program->datatable->fieldList['PRJStatus']['fixed']    = 'left';
$config->program->datatable->fieldList['PRJStatus']['width']    = '80';
$config->program->datatable->fieldList['PRJStatus']['required'] = 'no';
$config->program->datatable->fieldList['PRJStatus']['sort']     = 'yes';

$config->program->datatable->fieldList['begin']['title']    = 'begin';
$config->program->datatable->fieldList['begin']['fixed']    = 'no';
$config->program->datatable->fieldList['begin']['width']    = '100';
$config->program->datatable->fieldList['begin']['required'] = 'no';

$config->program->datatable->fieldList['end']['title']    = 'end';
$config->program->datatable->fieldList['end']['fixed']    = 'no';
$config->program->datatable->fieldList['end']['width']    = '100';
$config->program->datatable->fieldList['end']['required'] = 'no';

$config->program->datatable->fieldList['PRJBudget']['title']    = 'PRJBudget';
$config->program->datatable->fieldList['PRJBudget']['fixed']    = 'no';
$config->program->datatable->fieldList['PRJBudget']['width']    = '80';
$config->program->datatable->fieldList['PRJBudget']['required'] = 'yes';

$config->program->datatable->fieldList['teamCount']['title']    = 'teamCount';
$config->program->datatable->fieldList['teamCount']['fixed']    = 'no';
$config->program->datatable->fieldList['teamCount']['width']    = '60';
$config->program->datatable->fieldList['teamCount']['required'] = 'no';
$config->program->datatable->fieldList['teamCount']['sort']     = 'no';

$config->program->datatable->fieldList['PRJEstimate']['title']    = 'PRJEstimate';
$config->program->datatable->fieldList['PRJEstimate']['fixed']    = 'no';
$config->program->datatable->fieldList['PRJEstimate']['width']    = '60';
$config->program->datatable->fieldList['PRJEstimate']['required'] = 'no';
$config->program->datatable->fieldList['PRJEstimate']['sort']     = 'no';

$config->program->datatable->fieldList['PRJConsume']['title']    = 'PRJConsume';
$config->program->datatable->fieldList['PRJConsume']['fixed']    = 'no';
$config->program->datatable->fieldList['PRJConsume']['width']    = '60';
$config->program->datatable->fieldList['PRJConsume']['required'] = 'no';
$config->program->datatable->fieldList['PRJConsume']['sort']     = 'no';

$config->program->datatable->fieldList['PRJProgress']['title']    = 'PRJProgress';
$config->program->datatable->fieldList['PRJProgress']['fixed']    = 'right';
$config->program->datatable->fieldList['PRJProgress']['width']    = '60';
$config->program->datatable->fieldList['PRJProgress']['required'] = 'no';
$config->program->datatable->fieldList['PRJProgress']['sort']     = 'no';

$config->program->datatable->fieldList['actions']['title']    = 'actions';
$config->program->datatable->fieldList['actions']['fixed']    = 'right';
$config->program->datatable->fieldList['actions']['width']    = '180';
$config->program->datatable->fieldList['actions']['required'] = 'yes';
