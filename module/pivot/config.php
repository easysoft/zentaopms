<?php
global $config;
$config->pivot = new stdclass();

$config->pivot->maxFeatureItem = 5;

$config->pivot->fileType =  array('xlsx' => 'xlsx', 'xls' => 'xls', 'html' => 'html', 'mht' => 'mht');
$config->pivot->realStep = array('1' => '1', '2' => '2', '3' => '4', '4' => '3', '5' => '5');

$config->pivot->create = new stdclass();
$config->pivot->create->requiredFields = 'type,group';

$config->pivot->edit = new stdclass();
$config->pivot->edit->requiredFields = 'type,group';

$config->pivot->design = new stdclass();
$config->pivot->design->requiredFields = 'group';

$config->pivot->scopeOptionList = array('user', 'product', 'project', 'execution', 'dept', 'product.status', 'product.type', 'productplan.status', 'project.status', 'project.type', 'project.model', 'execution.status', 'execution.type');

$config->pivot->multiColumn = array('cluBarX' => 'yaxis', 'cluBarY' => 'yaxis', 'radar' => 'yaxis', 'line' => 'yaxis', 'stackedBar' => 'yaxis', 'stackedBarY' => 'yaxis');

$config->pivot->checkForm = array();
$config->pivot->checkForm['line']        = array('cantequal' => 'xaxis,yaxis');
$config->pivot->checkForm['cluBarX']     = array('cantequal' => 'xaxis,yaxis');
$config->pivot->checkForm['cluBarY']     = array('cantequal' => 'xaxis,yaxis');
$config->pivot->checkForm['radar']       = array('cantequal' => 'xaxis,yaxis');
$config->pivot->checkForm['stackedBar']  = array('cantequal' => 'xaxis,yaxis');
$config->pivot->checkForm['stackedBarY'] = array('cantequal' => 'xaxis,yaxis');
global $lang;
$config->pivot->settings = array();
$config->pivot->settings['cluBarX'] = array();
$config->pivot->settings['cluBarX']['xaxis']   = array();
$config->pivot->settings['cluBarX']['xaxis'][] = array('field' => 'xaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 4);

$config->pivot->settings['cluBarX']['yaxis']   = array();
$config->pivot->settings['cluBarX']['yaxis'][] = array('field' => 'yaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 2);
$config->pivot->settings['cluBarX']['yaxis'][] = array('field' => 'agg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->pivot->aggType, 'col' => 2);

$config->pivot->settings['cluBarY'] = array();
$config->pivot->settings['cluBarY']['xaxis']   = array();
$config->pivot->settings['cluBarY']['xaxis'][] = array('field' => 'xaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 4);

$config->pivot->settings['cluBarY']['yaxis']   = array();
$config->pivot->settings['cluBarY']['yaxis'][] = array('field' => 'yaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 2);
$config->pivot->settings['cluBarY']['yaxis'][] = array('field' => 'agg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->pivot->aggType, 'col' => 2);

$config->pivot->settings['stackedBarY'] = array();
$config->pivot->settings['stackedBarY']['xaxis']   = array();
$config->pivot->settings['stackedBarY']['xaxis'][] = array('field' => 'xaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 4);

$config->pivot->settings['stackedBarY']['yaxis']   = array();
$config->pivot->settings['stackedBarY']['yaxis'][] = array('field' => 'yaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 2);
$config->pivot->settings['stackedBarY']['yaxis'][] = array('field' => 'agg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->pivot->aggType, 'col' => 2);

$config->pivot->settings['line'] = array();
$config->pivot->settings['line']['xaxis']   = array();
$config->pivot->settings['line']['xaxis'][] = array('field' => 'xaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 4);

$config->pivot->settings['line']['yaxis']   = array();
$config->pivot->settings['line']['yaxis'][] = array('field' => 'yaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 2);
$config->pivot->settings['line']['yaxis'][] = array('field' => 'agg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->pivot->aggType, 'col' => 2);

$config->pivot->settings['pie'] = array();
$config->pivot->settings['pie']['group']   = array();
$config->pivot->settings['pie']['group'][] = array('field' => 'group', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 2);

$config->pivot->settings['pie']['metric']   = array();
$config->pivot->settings['pie']['metric'][] = array('field' => 'metric',  'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 2);

$config->pivot->settings['pie']['stat']   = array();
$config->pivot->settings['pie']['stat'][] = array('field' => 'agg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->pivot->aggType, 'col' => 2);

$config->pivot->settings['radar'] = array();
$config->pivot->settings['radar']['xaxis']   = array();
$config->pivot->settings['radar']['xaxis'][] = array('field' => 'xaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 4);

$config->pivot->settings['radar']['yaxis']   = array();
$config->pivot->settings['radar']['yaxis'][] = array('field' => 'yaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 2);
$config->pivot->settings['radar']['yaxis'][] = array('field' => 'agg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->pivot->aggType, 'col' => 2);

$config->pivot->settings['stackedBar'] = array();
$config->pivot->settings['stackedBar']['xaxis']   = array();
$config->pivot->settings['stackedBar']['xaxis'][] = array('field' => 'xaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 4);

$config->pivot->settings['stackedBar']['yaxis']   = array();
$config->pivot->settings['stackedBar']['yaxis'][] = array('field' => 'yaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 2);
$config->pivot->settings['stackedBar']['yaxis'][] = array('field' => 'agg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->pivot->aggType, 'col' => 2);

$config->pivot->settings['testingReport'] = array('field' => array('type' => 'td'));

$config->pivot->transTypes = array();
$config->pivot->transTypes['int']      = 'number';
$config->pivot->transTypes['float']    = 'number';
$config->pivot->transTypes['double']   = 'number';
$config->pivot->transTypes['datetime'] = 'date';
$config->pivot->transTypes['date']     = 'date';

global $config, $app, $lang;
$config->pivot->drillObjectFields = array();
$config->pivot->drillObjectFields['program']     = array('id', 'name', 'status', 'PM', 'budget', 'progress', 'begin', 'end');
$config->pivot->drillObjectFields['project']     = array('id', 'name', 'status', 'PM', 'model', 'budget', 'progress', 'estimate', 'consumed', 'begin', 'end');
$config->pivot->drillObjectFields['execution']   = array('id', 'name', 'status', 'PM', 'budget', 'progress', 'estimate', 'consumed', 'begin', 'end');
$config->pivot->drillObjectFields['product']     = array('id', 'name', 'status', 'PO', 'totalEpics', 'totalRequirements', 'totalStories', 'unresolvedBugs', 'plans', 'releases');
$config->pivot->drillObjectFields['productplan'] = array('id', 'title', 'status', 'begin', 'end');
$config->pivot->drillObjectFields['release']     = array('id', 'name', 'status', 'date');
$config->pivot->drillObjectFields['story']       = array('id', 'title', 'pri', 'status', 'stage', 'estimate', 'openedBy', 'reviewer', 'assignedTo');
$config->pivot->drillObjectFields['task']        = array('id', 'name', 'pri', 'status', 'type', 'estimate', 'consumed', 'left', 'assignedTo', 'closedReason');
$config->pivot->drillObjectFields['bug']         = array('id', 'title', 'severity', 'pri', 'status', 'confirmed', 'resolution', 'resolvedBy', 'openedBy', 'assignedTo');
$config->pivot->drillObjectFields['testcase']    = array('id', 'title', 'lastRunner', 'lastRunResult', 'pri', 'status', 'openedBy', 'reviewedBy');
$config->pivot->drillObjectFields['doc']         = array('id', 'title', 'addedBy', 'addedDate', 'editedBy', 'editedDate');
$config->pivot->drillObjectFields['feedback']    = array('id', 'title', 'pri', 'status', 'type', 'assignedTo', 'solution', 'openedBy');
$config->pivot->drillObjectFields['ticket']      = array();
$config->pivot->drillObjectFields['productLine'] = array();
$config->pivot->drillObjectFields['user']        = array();

$app->loadLang('project');
$app->loadLang('product');
$app->loadLang('user');
$app->loadLang('ticket');
$app->loadLang('release');

$config->pivot->objectTableFields = new stdclass();
$config->pivot->objectTableFields->productLine = array();
$config->pivot->objectTableFields->productLine['id']['title']   = $lang->idAB;
$config->pivot->objectTableFields->productLine['id']['type']    = 'id';
$config->pivot->objectTableFields->productLine['name']['title'] = $lang->product->line;
$config->pivot->objectTableFields->productLine['name']['type']  = 'title';
$config->pivot->objectTableFields->productLine['root']['title'] = $lang->product->program;
$config->pivot->objectTableFields->productLine['root']['type']  = 'title';
$config->pivot->objectTableFields->productLine['root']['link']  = array('url' => array('module' => 'program', 'method' => 'project', "programID={root}"), 'target' => '_blank');

$config->pivot->objectTableFields->project = array();
$config->pivot->objectTableFields->project['model']['title']   = $lang->project->model;
$config->pivot->objectTableFields->project['model']['map']     = $lang->project->modelList;
$config->pivot->objectTableFields->project['model']['width']   = 120;
$config->pivot->objectTableFields->project['consumed']['type'] = 'number';

$config->pivot->objectTableFields->user = array();
$config->pivot->objectTableFields->user['id']['title']       = $lang->idAB;
$config->pivot->objectTableFields->user['id']['type']        = 'id';
$config->pivot->objectTableFields->user['id']['sort']        = false;
$config->pivot->objectTableFields->user['realname']['title'] = $lang->user->realname;
$config->pivot->objectTableFields->user['realname']['type']  = 'title';
$config->pivot->objectTableFields->user['realname']['sort']  = false;
$config->pivot->objectTableFields->user['account']['title']  = $lang->user->account;
$config->pivot->objectTableFields->user['gender']['title']   = $lang->user->gender;
$config->pivot->objectTableFields->user['gender']['map']     = $lang->user->genderList;
$config->pivot->objectTableFields->user['role']['title']     = $lang->user->role;
$config->pivot->objectTableFields->user['role']['map']       = $lang->user->roleList;

if($config->edition != 'open')
{
    $config->pivot->objectTableFields->ticket = array();
    $config->pivot->objectTableFields->ticket['id']['name']     = 'id';
    $config->pivot->objectTableFields->ticket['id']['title']    = $lang->idAB;
    $config->pivot->objectTableFields->ticket['id']['type']     = 'id';
    $config->pivot->objectTableFields->ticket['id']['sortType'] = false;

    $config->pivot->objectTableFields->ticket['title']['name']  = 'title';
    $config->pivot->objectTableFields->ticket['title']['type']  = 'title';
    $config->pivot->objectTableFields->ticket['title']['title'] = $lang->ticket->title;
    $config->pivot->objectTableFields->ticket['title']['link']  = array('module' => 'ticket', 'method' => 'view', 'params' => "ticketID={id}");

    $config->pivot->objectTableFields->ticket['pri']['name']  = 'pri';
    $config->pivot->objectTableFields->ticket['pri']['type']  = 'pri';
    $config->pivot->objectTableFields->ticket['pri']['title'] = $lang->ticket->pri;

    $config->pivot->objectTableFields->ticket['status']['name']      = 'status';
    $config->pivot->objectTableFields->ticket['status']['title']     = $lang->ticket->status;
    $config->pivot->objectTableFields->ticket['status']['type']      = 'status';
    $config->pivot->objectTableFields->ticket['status']['statusMap'] = $lang->ticket->statusList;
    $config->pivot->objectTableFields->ticket['status']['sortType']  = false;

    $config->pivot->objectTableFields->ticket['type']['name']  = 'type';
    $config->pivot->objectTableFields->ticket['type']['title'] = $lang->ticket->type;
    $config->pivot->objectTableFields->ticket['type']['type']  = 'category';
    $config->pivot->objectTableFields->ticket['type']['map']   = $lang->ticket->typeList;

    $config->pivot->objectTableFields->ticket['assignedTo']['name']     = 'assignedTo';
    $config->pivot->objectTableFields->ticket['assignedTo']['title']    = $lang->ticket->assignedTo;
    $config->pivot->objectTableFields->ticket['assignedTo']['type']     = 'user';
    $config->pivot->objectTableFields->ticket['assignedTo']['sortType'] = false;

    $config->pivot->objectTableFields->ticket['estimate']['title'] = $lang->ticket->estimate;
    $config->pivot->objectTableFields->ticket['estimate']['type']  = 'number';

    $config->pivot->objectTableFields->ticket['openedBy']['name']     = 'openedBy';
    $config->pivot->objectTableFields->ticket['openedBy']['title']    = $lang->ticket->openedBy;
    $config->pivot->objectTableFields->ticket['openedBy']['type']     = 'user';
    $config->pivot->objectTableFields->ticket['openedBy']['sortType'] = false;
}

$config->pivot->objectTableFields->story = array();
$config->pivot->objectTableFields->story['title']['title']   = $lang->pivot->drill->storyName;
$config->pivot->objectTableFields->story['title']['link']    = array('url' => helper::createLink('{type}', 'view', 'storyID={id}'), 'target' => '_blank');
$config->pivot->objectTableFields->story['reviewer']['name'] = 'reviewedBy';
$config->pivot->objectTableFields->story['reviewer']['type'] = 'user';

$config->pivot->objectTableFields->testcase = array();
$config->pivot->objectTableFields->testcase['id']['name']    = 'id';
$config->pivot->objectTableFields->testcase['title']['link'] = array('module' => 'testcase', 'method' => 'view', 'params' => "caseID={id}", 'target' => '_blank');

$config->pivot->objectTableFields->release = array();
$config->pivot->objectTableFields->release['stories']['name']   = 'stories';
$config->pivot->objectTableFields->release['stories']['title']  = $lang->pivot->drill->releaseStories;
$config->pivot->objectTableFields->release['bugs']['name']      = 'bugs';
$config->pivot->objectTableFields->release['bugs']['title']     = $lang->release->bugs;
$config->pivot->objectTableFields->release['leftBugs']['name']  = 'leftBugs';
$config->pivot->objectTableFields->release['leftBugs']['title'] = $lang->release->leftBugs;

$config->pivot->objectTableFields->product = array();
$config->pivot->objectTableFields->product['id']['name']              = 'id';
$config->pivot->objectTableFields->product['id']['title']             = $lang->idAB;
$config->pivot->objectTableFields->product['id']['type']              = 'id';
$config->pivot->objectTableFields->product['unresolvedBugs']['title'] = $lang->pivot->drill->activatedBug;
$config->pivot->objectTableFields->product['plans']['title']          = $lang->product->plans;
$config->pivot->objectTableFields->product['releases']['title']       = $lang->product->releases;
$config->pivot->objectTableFields->product['status']['name']          = 'status';
$config->pivot->objectTableFields->product['status']['title']         = $lang->statusAB;
$config->pivot->objectTableFields->product['status']['type']          = 'status';
$config->pivot->objectTableFields->product['status']['statusMap']     = $lang->product->statusList;

$config->pivot->objectTableFields->product['totalEpics']['width']        = 100;
$config->pivot->objectTableFields->product['totalRequirements']['width'] = 100;
$config->pivot->objectTableFields->product['totalStories']['width']      = 100;
$config->pivot->objectTableFields->product['unresolvedBugs']['width']    = 100;
