<?php
$config->program = new stdclass();
$config->program->PRJRecentQuantity = 15;

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

$config->program->list = new stdclass();
$config->program->list->exportFields = 'id,name,code,template,product,status,begin,end,budget,PM,end,desc';

$config->program->PGMCreate = new stdclass();
$config->program->PGMEdit   = new stdclass();
$config->program->PGMCreate->requiredFields = 'name,code,begin,end';
$config->program->PGMEdit->requiredFields   = 'name,code,begin,end';

$config->program->PRJCreate = new stdclass();
$config->program->PRJEdit   = new stdclass();
$config->program->PRJCreate->requiredFields = 'name,code,begin,end';
$config->program->PRJEdit->requiredFields   = 'name,code,begin,end';

$config->program->sortFields            = new stdclass();
$config->program->sortFields->idAB      = 'id';
$config->program->sortFields->PRJCode   = 'code';
$config->program->sortFields->PRJModel  = 'model';
$config->program->sortFields->begin     = 'begin';
$config->program->sortFields->end       = 'end';
$config->program->sortFields->PRJStatus = 'status';
$config->program->sortFields->PRJBudget = 'budget';

global $lang;
$config->program->datatable = new stdclass();
$config->program->datatable->defaultField = array('idAB', 'PRJCode', 'PRJName', 'PRJModel', 'PRJPM', 'begin', 'end', 'PRJStatus', 'PRJBudget', 'teamCount','PRJEstimate','PRJConsume', 'PRJProgress', 'actions');

$config->program->datatable->fieldList['idAB']['title']    = 'idAB';
$config->program->datatable->fieldList['idAB']['fixed']    = 'left';
$config->program->datatable->fieldList['idAB']['width']    = '60';
$config->program->datatable->fieldList['idAB']['required'] = 'yes';

$config->program->datatable->fieldList['PRJCode']['title']    = 'PRJCode';
$config->program->datatable->fieldList['PRJCode']['fixed']    = 'left';
$config->program->datatable->fieldList['PRJCode']['width']    = '80';
$config->program->datatable->fieldList['PRJCode']['required'] = 'yes';

$config->program->datatable->fieldList['PRJName']['title']    = 'PRJName';
$config->program->datatable->fieldList['PRJName']['fixed']    = 'left';
$config->program->datatable->fieldList['PRJName']['width']    = 'auto';
$config->program->datatable->fieldList['PRJName']['required'] = 'yes';
$config->program->datatable->fieldList['PRJName']['sort']     = 'no';

$config->program->datatable->fieldList['PRJModel']['title']    = 'PRJModel';
$config->program->datatable->fieldList['PRJModel']['fixed']    = 'left';
$config->program->datatable->fieldList['PRJModel']['width']    = '80';
$config->program->datatable->fieldList['PRJModel']['required'] = 'yes';

$config->program->datatable->fieldList['PRJPM']['title']    = 'PRJPM';
$config->program->datatable->fieldList['PRJPM']['fixed']    = 'no';
$config->program->datatable->fieldList['PRJPM']['width']    = '100';
$config->program->datatable->fieldList['PRJPM']['required'] = 'yes';
$config->program->datatable->fieldList['PRJPM']['sort']     = 'no';

$config->program->datatable->fieldList['begin']['title']    = 'begin';
$config->program->datatable->fieldList['begin']['fixed']    = 'no';
$config->program->datatable->fieldList['begin']['width']    = '120';
$config->program->datatable->fieldList['begin']['required'] = 'no';

$config->program->datatable->fieldList['end']['title']    = 'end';
$config->program->datatable->fieldList['end']['fixed']    = 'no';
$config->program->datatable->fieldList['end']['width']    = '120';
$config->program->datatable->fieldList['end']['required'] = 'no';

$config->program->datatable->fieldList['PRJStatus']['title']    = 'PRJStatus';
$config->program->datatable->fieldList['PRJStatus']['fixed']    = 'no';
$config->program->datatable->fieldList['PRJStatus']['width']    = '80';
$config->program->datatable->fieldList['PRJStatus']['required'] = 'yes';

$config->program->datatable->fieldList['PRJBudget']['title']    = 'PRJBudget';
$config->program->datatable->fieldList['PRJBudget']['fixed']    = 'no';
$config->program->datatable->fieldList['PRJBudget']['width']    = '100';
$config->program->datatable->fieldList['PRJBudget']['required'] = 'yes';

$config->program->datatable->fieldList['teamCount']['title']    = 'teamCount';
$config->program->datatable->fieldList['teamCount']['fixed']    = 'no';
$config->program->datatable->fieldList['teamCount']['width']    = '80';
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
$config->program->datatable->fieldList['PRJProgress']['width']    = '80';
$config->program->datatable->fieldList['PRJProgress']['required'] = 'no';
$config->program->datatable->fieldList['PRJProgress']['sort']     = 'no';

$config->program->datatable->fieldList['PRJSurplus']['title']    = 'PRJSurplus';
$config->program->datatable->fieldList['PRJSurplus']['fixed']    = 'no';
$config->program->datatable->fieldList['PRJSurplus']['width']    = '80';
$config->program->datatable->fieldList['PRJSurplus']['required'] = 'no';
$config->program->datatable->fieldList['PRJSurplus']['sort']     = 'no';

$config->program->datatable->fieldList['actions']['title']    = 'actions';
$config->program->datatable->fieldList['actions']['fixed']    = 'right';
$config->program->datatable->fieldList['actions']['width']    = '240';
$config->program->datatable->fieldList['actions']['required'] = 'yes';
