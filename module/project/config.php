<?php
$config->project = new stdclass();

$config->project->editor = new stdclass();
$config->project->editor->pgmcreate   = array('id' => 'desc',    'tools' => 'simpleTools');
$config->project->editor->pgmedit     = array('id' => 'desc',    'tools' => 'simpleTools');
$config->project->editor->pgmclose    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->project->editor->pgmstart    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->project->editor->pgmactivate = array('id' => 'comment', 'tools' => 'simpleTools');
$config->project->editor->pgmfinish   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->project->editor->pgmsuspend  = array('id' => 'comment', 'tools' => 'simpleTools');

$config->project->editor->prjcreate   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->project->editor->prjedit     = array('id' => 'desc', 'tools' => 'simpleTools');
$config->project->editor->prjclose    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->project->editor->prjsuspend  = array('id' => 'comment', 'tools' => 'simpleTools');
$config->project->editor->prjstart    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->project->editor->prjactivate = array('id' => 'comment', 'tools' => 'simpleTools');
$config->project->editor->prjview     = array('id' => 'comment', 'tools' => 'simpleTools');

$config->project->list = new stdclass();
$config->project->list->exportFields = 'id,name,code,template,product,status,begin,end,budget,PM,end,desc';

$config->project->PGMCreate = new stdclass();
$config->project->PGMEdit   = new stdclass();
$config->project->PGMCreate->requiredFields = 'name,begin,end';
$config->project->PGMEdit->requiredFields   = 'name,begin,end';

$config->project->PRJCreate = new stdclass();
$config->project->PRJEdit   = new stdclass();
$config->project->PRJCreate->requiredFields = 'name,code,begin,end';
$config->project->PRJEdit->requiredFields   = 'name,code,begin,end';

$config->project->sortFields            = new stdclass();
$config->project->sortFields->id        = 'id';
$config->project->sortFields->begin     = 'begin';
$config->project->sortFields->end       = 'end';
$config->project->sortFields->PRJStatus = 'status';
$config->project->sortFields->PRJBudget = 'budget';

global $lang;
$config->project->datatable = new stdclass();
$config->project->datatable->defaultField = array('id', 'PRJName', 'PM', 'PRJStatus', 'begin', 'end', 'PRJBudget', 'teamCount','PRJEstimate','PRJConsume', 'PRJProgress', 'actions');

$config->project->datatable->fieldList['id']['title']    = 'ID';
$config->project->datatable->fieldList['id']['fixed']    = 'left';
$config->project->datatable->fieldList['id']['width']    = '60';
$config->project->datatable->fieldList['id']['required'] = 'yes';

$config->project->datatable->fieldList['PRJName']['title']    = 'PRJName';
$config->project->datatable->fieldList['PRJName']['fixed']    = 'left';
$config->project->datatable->fieldList['PRJName']['width']    = 'auto';
$config->project->datatable->fieldList['PRJName']['required'] = 'yes';
$config->project->datatable->fieldList['PRJName']['sort']     = 'no';

$config->project->datatable->fieldList['PM']['title']    = 'PM';
$config->project->datatable->fieldList['PM']['fixed']    = 'no';
$config->project->datatable->fieldList['PM']['width']    = '80';
$config->project->datatable->fieldList['PM']['required'] = 'yes';
$config->project->datatable->fieldList['PM']['sort']     = 'no';

$config->project->datatable->fieldList['PRJStatus']['title']    = 'PRJStatus';
$config->project->datatable->fieldList['PRJStatus']['fixed']    = 'left';
$config->project->datatable->fieldList['PRJStatus']['width']    = '80';
$config->project->datatable->fieldList['PRJStatus']['required'] = 'no';
$config->project->datatable->fieldList['PRJStatus']['sort']     = 'yes';

$config->project->datatable->fieldList['begin']['title']    = 'begin';
$config->project->datatable->fieldList['begin']['fixed']    = 'no';
$config->project->datatable->fieldList['begin']['width']    = '100';
$config->project->datatable->fieldList['begin']['required'] = 'no';

$config->project->datatable->fieldList['end']['title']    = 'end';
$config->project->datatable->fieldList['end']['fixed']    = 'no';
$config->project->datatable->fieldList['end']['width']    = '100';
$config->project->datatable->fieldList['end']['required'] = 'no';

$config->project->datatable->fieldList['PRJBudget']['title']    = 'PRJBudget';
$config->project->datatable->fieldList['PRJBudget']['fixed']    = 'no';
$config->project->datatable->fieldList['PRJBudget']['width']    = '80';
$config->project->datatable->fieldList['PRJBudget']['required'] = 'yes';

$config->project->datatable->fieldList['teamCount']['title']    = 'teamCount';
$config->project->datatable->fieldList['teamCount']['fixed']    = 'no';
$config->project->datatable->fieldList['teamCount']['width']    = '40';
$config->project->datatable->fieldList['teamCount']['required'] = 'no';
$config->project->datatable->fieldList['teamCount']['sort']     = 'no';

$config->project->datatable->fieldList['PRJEstimate']['title']    = 'PRJEstimate';
$config->project->datatable->fieldList['PRJEstimate']['fixed']    = 'no';
$config->project->datatable->fieldList['PRJEstimate']['width']    = '60';
$config->project->datatable->fieldList['PRJEstimate']['required'] = 'no';
$config->project->datatable->fieldList['PRJEstimate']['sort']     = 'no';

$config->project->datatable->fieldList['PRJConsume']['title']    = 'PRJConsume';
$config->project->datatable->fieldList['PRJConsume']['fixed']    = 'no';
$config->project->datatable->fieldList['PRJConsume']['width']    = '60';
$config->project->datatable->fieldList['PRJConsume']['required'] = 'no';
$config->project->datatable->fieldList['PRJConsume']['sort']     = 'no';

$config->project->datatable->fieldList['PRJProgress']['title']    = 'PRJProgress';
$config->project->datatable->fieldList['PRJProgress']['fixed']    = 'right';
$config->project->datatable->fieldList['PRJProgress']['width']    = '60';
$config->project->datatable->fieldList['PRJProgress']['required'] = 'no';
$config->project->datatable->fieldList['PRJProgress']['sort']     = 'no';

$config->project->datatable->fieldList['actions']['title']    = 'actions';
$config->project->datatable->fieldList['actions']['fixed']    = 'right';
$config->project->datatable->fieldList['actions']['width']    = '180';
$config->project->datatable->fieldList['actions']['required'] = 'yes';
