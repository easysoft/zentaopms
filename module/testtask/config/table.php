<?php
global $lang, $app;
$app->loadLang('testcase');
$app->loadModuleConfig('testcase');

$config->testtask->dtable = new stdclass();
$config->testtask->dtable->fieldList['id']['name']  = 'id';
$config->testtask->dtable->fieldList['id']['title'] = $lang->idAB;
$config->testtask->dtable->fieldList['id']['type']  = 'id';

$config->testtask->dtable->fieldList['title']['name']  = 'name';
$config->testtask->dtable->fieldList['title']['title'] = $lang->testtask->name;
$config->testtask->dtable->fieldList['title']['type']  = 'title';
$config->testtask->dtable->fieldList['title']['link']  = array('module' => 'testtask', 'method' => 'cases', 'params' => 'taskID={id}');
$config->testtask->dtable->fieldList['title']['fixed'] = 'left';

$config->testtask->dtable->fieldList['build']['name']  = 'buildName';
$config->testtask->dtable->fieldList['build']['title'] = $lang->testtask->build;
$config->testtask->dtable->fieldList['build']['type']  = 'text';
$config->testtask->dtable->fieldList['build']['link']  = array('module' => 'build', 'method' => 'view', 'params' => 'buildID={build}');
$config->testtask->dtable->fieldList['build']['group'] = 'text';

$config->testtask->dtable->fieldList['product']['name']  = 'productName';
$config->testtask->dtable->fieldList['product']['title'] = $lang->testtask->product;
$config->testtask->dtable->fieldList['product']['type']  = 'text';
$config->testtask->dtable->fieldList['product']['group'] = 'text';

$config->testtask->dtable->fieldList['execution']['name']  = 'executionName';
$config->testtask->dtable->fieldList['execution']['title'] = $lang->testtask->execution;
$config->testtask->dtable->fieldList['execution']['type']  = 'text';
$config->testtask->dtable->fieldList['execution']['group'] = 'text';

$config->testtask->dtable->fieldList['owner']['name']    = 'owner';
$config->testtask->dtable->fieldList['owner']['title']   = $lang->testtask->owner;
$config->testtask->dtable->fieldList['owner']['type']    = 'user';
$config->testtask->dtable->fieldList['owner']['group']   = 'user';

$config->testtask->dtable->fieldList['members']['name']  = 'members';
$config->testtask->dtable->fieldList['members']['title'] = $lang->testtask->members;
$config->testtask->dtable->fieldList['members']['type']  = 'text';
$config->testtask->dtable->fieldList['members']['group'] = 'user';

$config->testtask->dtable->fieldList['begin']['name']  = 'begin';
$config->testtask->dtable->fieldList['begin']['title'] = $lang->testtask->begin;
$config->testtask->dtable->fieldList['begin']['type']  = 'date';
$config->testtask->dtable->fieldList['begin']['group'] = 'user';

$config->testtask->dtable->fieldList['end']['name']  = 'end';
$config->testtask->dtable->fieldList['end']['title'] = $lang->testtask->end;
$config->testtask->dtable->fieldList['end']['type']  = 'date';
$config->testtask->dtable->fieldList['end']['group'] = 'user';

$config->testtask->dtable->fieldList['status']['name']      = 'status';
$config->testtask->dtable->fieldList['status']['title']     = $lang->testtask->status;
$config->testtask->dtable->fieldList['status']['type']      = 'status';
$config->testtask->dtable->fieldList['status']['statusMap'] = $lang->testtask->statusList;

$config->testtask->dtable->fieldList['actions']['name']     = 'actions';
$config->testtask->dtable->fieldList['actions']['title']    = $lang->actions;
$config->testtask->dtable->fieldList['actions']['type']     = 'actions';
$config->testtask->dtable->fieldList['actions']['sortType'] = false;
$config->testtask->dtable->fieldList['actions']['list']     = $config->testtask->actionList;
$config->testtask->dtable->fieldList['actions']['menu']     = array('cases', 'linkCase', 'report', 'view', 'edit', 'delete');

$config->testtask->cases->dtable = new stdclass();

foreach($config->testcase->dtable->fieldList as $field => $setting)
{
    if($field == 'actions') continue;

    $config->testtask->cases->dtable->fieldList[$field] = $setting;

    if($field == 'title') $config->testtask->cases->dtable->fieldList[$field]['nestedToggle'] = false;
    if($field == 'story') $config->testtask->cases->dtable->fieldList[$field]['name']         = 'storyTitle';

    if($field == 'keywords')
    {
        $config->testtask->cases->dtable->fieldList['assignedTo']['title'] = $lang->testcase->assignedTo;
        $config->testtask->cases->dtable->fieldList['assignedTo']['type']  = 'user';
        $config->testtask->cases->dtable->fieldList['assignedTo']['show']  = true;
        $config->testtask->cases->dtable->fieldList['assignedTo']['group'] = 99; // Set a different group between testcase.
    }
}

$config->testtask->cases->dtable->fieldList['actions']['name']  = 'actions';
$config->testtask->cases->dtable->fieldList['actions']['title'] = $lang->actions;
$config->testtask->cases->dtable->fieldList['actions']['type']  = 'actions';
$config->testtask->cases->dtable->fieldList['actions']['list']  = $config->testtask->cases->actionList;
$config->testtask->cases->dtable->fieldList['actions']['menu']  = array(array('confirmChange'), array('createBug', 'runCase', 'results', 'unlinkCase'));

$config->testtask->linkcase = new stdclass();
$config->testtask->linkcase->dtable = new stdclass();
$config->testtask->linkcase->dtable->fieldList['id']         = $config->testcase->dtable->fieldList['id'];
$config->testtask->linkcase->dtable->fieldList['id']['name'] = 'id';

$config->testtask->linkcase->dtable->fieldList['title']  = $config->testcase->dtable->fieldList['title'];
$config->testtask->linkcase->dtable->fieldList['title']['link'] = array('module' => 'testcase', 'method' => 'view', 'params' => "caseID={id}");

$config->testtask->linkcase->dtable->fieldList['pri']    = $config->testcase->dtable->fieldList['pri'];
$config->testtask->linkcase->dtable->fieldList['type']   = $config->testcase->dtable->fieldList['type'];
$config->testtask->linkcase->dtable->fieldList['status'] = $config->testcase->dtable->fieldList['status'];

$config->testtask->linkcase->dtable->fieldList['version']['name']  = 'version';
$config->testtask->linkcase->dtable->fieldList['version']['title'] = $lang->testtask->linkVersion;
$config->testtask->linkcase->dtable->fieldList['version']['type']  = 'text';
$config->testtask->linkcase->dtable->fieldList['version']['group'] = 'version';

$config->testtask->linkcase->dtable->fieldList['openedBy']      = $config->testcase->dtable->fieldList['openedBy'];
$config->testtask->linkcase->dtable->fieldList['lastRunner']    = $config->testcase->dtable->fieldList['lastRunner'];
$config->testtask->linkcase->dtable->fieldList['lastRunDate']   = $config->testcase->dtable->fieldList['lastRunDate'];
$config->testtask->linkcase->dtable->fieldList['lastRunResult'] = $config->testcase->dtable->fieldList['lastRunResult'];
unset($config->testtask->linkcase->dtable->fieldList['title']['nestedToggle']);

$config->testtask->browseUnits = new stdclass();
$config->testtask->browseUnits->dtable = new stdclass();
$config->testtask->browseUnits->dtable->fieldList['id']    = $config->testtask->dtable->fieldList['id'];

$config->testtask->browseUnits->dtable->fieldList['title'] = $config->testtask->dtable->fieldList['title'];
$config->testtask->browseUnits->dtable->fieldList['title']['title'] = $lang->testtask->unitName;
$config->testtask->browseUnits->dtable->fieldList['title']['link']  = array('module' => 'testtask', 'method' => 'unitCases', 'params' => 'taskID={id}');

$config->testtask->browseUnits->dtable->fieldList['execution'] = $config->testtask->dtable->fieldList['execution'];
$config->testtask->browseUnits->dtable->fieldList['build']     = $config->testtask->dtable->fieldList['build'];

$config->testtask->browseUnits->dtable->fieldList['owner'] = $config->testtask->dtable->fieldList['owner'];
$config->testtask->browseUnits->dtable->fieldList['execTime']['name']  = 'execTime';
$config->testtask->browseUnits->dtable->fieldList['execTime']['title'] = $lang->testtask->execTime;
$config->testtask->browseUnits->dtable->fieldList['execTime']['type']  = 'datetime';
$config->testtask->browseUnits->dtable->fieldList['execTime']['group'] = 'user';

$config->testtask->browseUnits->dtable->fieldList['caseCount']['name']  = 'caseCount';
$config->testtask->browseUnits->dtable->fieldList['caseCount']['title'] = $lang->testtask->caseCount;
$config->testtask->browseUnits->dtable->fieldList['caseCount']['type']  = 'number';
$config->testtask->browseUnits->dtable->fieldList['caseCount']['group'] = 'number';

$config->testtask->browseUnits->dtable->fieldList['passCount']['name']  = 'passCount';
$config->testtask->browseUnits->dtable->fieldList['passCount']['title'] = $lang->testtask->passCount;
$config->testtask->browseUnits->dtable->fieldList['passCount']['type']  = 'number';
$config->testtask->browseUnits->dtable->fieldList['passCount']['group'] = 'number';

$config->testtask->browseUnits->dtable->fieldList['failCount']['name']  = 'failCount';
$config->testtask->browseUnits->dtable->fieldList['failCount']['title'] = $lang->testtask->failCount;
$config->testtask->browseUnits->dtable->fieldList['failCount']['type']  = 'number';
$config->testtask->browseUnits->dtable->fieldList['failCount']['group'] = 'number';

$config->testtask->browseUnits->dtable->fieldList['actions']['name']     = 'actions';
$config->testtask->browseUnits->dtable->fieldList['actions']['title']    = $lang->actions;
$config->testtask->browseUnits->dtable->fieldList['actions']['type']     = 'actions';
$config->testtask->browseUnits->dtable->fieldList['actions']['sortType'] = false;
$config->testtask->browseUnits->dtable->fieldList['actions']['list']     = $config->testtask->actionList;
$config->testtask->browseUnits->dtable->fieldList['actions']['menu']     = array('unitCases', 'edit', 'delete');

$config->testtask->unitgroup = new stdclass();
$config->testtask->unitgroup->dtable = new stdclass();
$config->testtask->unitgroup->dtable->fieldList['suiteTitle']['title']    = $lang->testcase->suite;
$config->testtask->unitgroup->dtable->fieldList['suiteTitle']['width']    = 'auto';
$config->testtask->unitgroup->dtable->fieldList['suiteTitle']['type']     = 'title';
$config->testtask->unitgroup->dtable->fieldList['suiteTitle']['fixed']    = false;
$config->testtask->unitgroup->dtable->fieldList['suiteTitle']['sortType'] = true;
$config->testtask->unitgroup->dtable->fieldList['suiteTitle']['group']    = 'story';

$config->testtask->unitgroup->dtable->fieldList['id'] = $config->testcase->dtable->fieldList['id'];
$config->testtask->unitgroup->dtable->fieldList['id']['type']  = 'id';
$config->testtask->unitgroup->dtable->fieldList['id']['fixed'] = false;

$config->testtask->unitgroup->dtable->fieldList['title']  = $config->testcase->dtable->fieldList['title'];
$config->testtask->unitgroup->dtable->fieldList['title']['width']        = 'auto';
$config->testtask->unitgroup->dtable->fieldList['title']['nestedToggle'] = false;
$config->testtask->unitgroup->dtable->fieldList['title']['fixed']        = false;

$config->testtask->unitgroup->dtable->fieldList['pri']           = $config->testcase->dtable->fieldList['pri'];
$config->testtask->unitgroup->dtable->fieldList['lastRunResult'] = $config->testcase->dtable->fieldList['lastRunResult'];
$config->testtask->unitgroup->dtable->fieldList['type']          = $config->testcase->dtable->fieldList['type'];
$config->testtask->unitgroup->dtable->fieldList['bugs']          = $config->testcase->dtable->fieldList['bugs'];
$config->testtask->unitgroup->dtable->fieldList['results']       = $config->testcase->dtable->fieldList['results'];
$config->testtask->unitgroup->dtable->fieldList['stepNumber']    = $config->testcase->dtable->fieldList['stepNumber'];
$config->testtask->unitgroup->dtable->fieldList['lastRunner']    = $config->testcase->dtable->fieldList['lastRunner'];
$config->testtask->unitgroup->dtable->fieldList['lastRunDate']   = $config->testcase->dtable->fieldList['lastRunDate'];
$config->testtask->unitgroup->dtable->fieldList['actions']       = $config->testcase->dtable->fieldList['actions'];
$config->testtask->unitgroup->dtable->fieldList['actions']['fixed'] = false;
$config->testtask->unitgroup->dtable->fieldList['actions']['menu']  = array('runResult');
