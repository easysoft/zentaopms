<?php
$config->testcase = new stdclass();
$config->testcase->defaultSteps = 3;
$config->testcase->batchCreate  = 10;
$config->testcase->needReview   = 0;
$config->testcase->useDatatable = false;

$config->testcase->create = new stdclass();
$config->testcase->edit   = new stdclass();
$config->testcase->create->requiredFields = 'title,type';
$config->testcase->edit->requiredFields   = 'title,type';

$config->testcase->editor = new stdclass();
$config->testcase->editor->edit   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->testcase->editor->view   = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');
$config->testcase->editor->review = array('id' => 'comment', 'tools' => 'simpleTools');

$config->testcase->export   = new stdclass();
$config->testcase->export->listFields   = array('type', 'stage', 'pri', 'status');

$config->testcase->exportFields = '
    id, product, branch, module, story, scene,
    title, precondition, stepDesc, stepExpect, real, keywords,
    pri, type, stage, status, bugsAB, resultsAB, stepNumberAB, lastRunner, lastRunDate, lastRunResult, openedBy, openedDate,
    lastEditedBy, lastEditedDate, version, linkCase, files';

$config->testcase->customCreateFields      = 'story,stage,pri,keywords';
$config->testcase->customBatchCreateFields = 'module,stage,story,pri,precondition,keywords,review';
$config->testcase->customBatchEditFields   = 'module,story,stage,precondition,status,pri,keywords';

$config->testcase->custom = new stdclass();
$config->testcase->custom->createFields      = $config->testcase->customCreateFields;
$config->testcase->custom->batchCreateFields = 'module,story,%s';
$config->testcase->custom->batchEditFields   = 'branch,module,stage,status,pri,story';

$config->testcase->excludeCheckFileds = ',pri,type,stage,needReview,story,branch,';

global $lang;
$config->testcase->search['module']                 = 'testcase';
$config->testcase->search['fields']['title']        = $lang->testcase->title;
$config->testcase->search['fields']['story']        = $lang->testcase->linkStory;
$config->testcase->search['fields']['id']           = $lang->testcase->id;
$config->testcase->search['fields']['keywords']     = $lang->testcase->keywords;
$config->testcase->search['fields']['lastEditedBy'] = $lang->testcase->lastEditedByAB;
$config->testcase->search['fields']['type']         = $lang->testcase->type;
$config->testcase->search['fields']['auto']         = $lang->testcase->autoCase;

$config->testcase->search['fields']['openedBy']       = $lang->testcase->openedBy;
$config->testcase->search['fields']['status']         = $lang->testcase->status;
$config->testcase->search['fields']['product']        = $lang->testcase->product;
$config->testcase->search['fields']['branch']         = '';
$config->testcase->search['fields']['stage']          = $lang->testcase->stage;
$config->testcase->search['fields']['module']         = $lang->testcase->module;
$config->testcase->search['fields']['pri']            = $lang->testcase->pri;
$config->testcase->search['fields']['lib']            = $lang->testcase->lib;

$config->testcase->search['fields']['lastRunner']     = $lang->testcase->lastRunner;
$config->testcase->search['fields']['lastRunResult']  = $lang->testcase->lastRunResult;
$config->testcase->search['fields']['lastRunDate']    = $lang->testcase->lastRunDate;
$config->testcase->search['fields']['openedDate']     = $lang->testcase->openedDate;
$config->testcase->search['fields']['lastEditedDate'] = $lang->testcase->lastEditedDateAB;

$config->testcase->search['params']['title']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->testcase->search['params']['story']        = array('operator' => 'include', 'control' => 'select', 'values' => '');
$config->testcase->search['params']['module']       = array('operator' => 'belong',  'control' => 'select', 'values' => 'modules');
$config->testcase->search['params']['keywords']     = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->testcase->search['params']['lastEditedBy'] = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->testcase->search['params']['type']         = array('operator' => '=',       'control' => 'select', 'values' => $lang->testcase->typeList);
$config->testcase->search['params']['auto']         = array('operator' => '=',       'control' => 'select', 'values' => $lang->testcase->autoList);

$config->testcase->search['params']['pri']          = array('operator' => '=',       'control' => 'select', 'values' => $lang->testcase->priList);
$config->testcase->search['params']['openedBy']     = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->testcase->search['params']['status']       = array('operator' => '=',       'control' => 'select', 'values' => $lang->testcase->statusList);
$config->testcase->search['params']['product']      = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->testcase->search['params']['branch']       = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->testcase->search['params']['stage']        = array('operator' => 'include', 'control' => 'select', 'values' => $lang->testcase->stageList);
$config->testcase->search['params']['lib']          = array('operator' => '=',       'control' => 'select', 'values' => '');

$config->testcase->search['params']['lastRunner']     = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->testcase->search['params']['lastRunResult']  = array('operator' => '=', 'control' => 'select', 'values' => array_diff($lang->testcase->resultList, array('n/a' => $lang->testcase->resultList['n/a'])) + array('null' => $lang->testcase->unexecuted));
$config->testcase->search['params']['lastRunDate']    = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->testcase->search['params']['openedDate']     = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->testcase->search['params']['lastEditedDate'] = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');

global $app;
$config->testcase->datatable = new stdclass();
$config->testcase->datatable->defaultField = array('id', 'title', 'pri', 'openedBy', 'lastRunner', 'lastRunDate', 'lastRunResult', 'actions');

$config->testcase->datatable->fieldList['id']['title']    = 'idAB';
$config->testcase->datatable->fieldList['id']['fixed']    = 'left';
$config->testcase->datatable->fieldList['id']['width']    = '70';
$config->testcase->datatable->fieldList['id']['required'] = 'yes';

$config->testcase->datatable->fieldList['product']['title']      = 'product';
$config->testcase->datatable->fieldList['product']['control']    = 'hidden';
$config->testcase->datatable->fieldList['product']['display']    = false;
$config->testcase->datatable->fieldList['product']['dataSource'] = array('module' => 'product', 'method' => 'getPairs', 'params' => '&0&&all');

$config->testcase->datatable->fieldList['module']['title']      = 'module';
$config->testcase->datatable->fieldList['module']['control']    = 'select';
$config->testcase->datatable->fieldList['module']['display']    = false;
$config->testcase->datatable->fieldList['module']['dataSource'] = array('module' => 'testcase', 'method' => 'getDatatableModules', 'params' => '$productID');

$config->testcase->datatable->fieldList['scene']['title']      = 'scene';
$config->testcase->datatable->fieldList['scene']['control']    = 'select';
$config->testcase->datatable->fieldList['scene']['display']    = false;
$config->testcase->datatable->fieldList['scene']['dataSource'] = array('module' => 'testcase', 'method' => 'getSceneMenu', 'params' => '$productID&0');

$config->testcase->datatable->fieldList['title']['title']    = 'title';
$config->testcase->datatable->fieldList['title']['fixed']    = 'left';
$config->testcase->datatable->fieldList['title']['width']    = 'auto';
$config->testcase->datatable->fieldList['title']['required'] = 'yes';

$config->testcase->datatable->fieldList['branch']['title']      = 'branch';
$config->testcase->datatable->fieldList['branch']['fixed']      = 'left';
$config->testcase->datatable->fieldList['branch']['width']      = '100';
$config->testcase->datatable->fieldList['branch']['required']   = 'no';
$config->testcase->datatable->fieldList['branch']['dataSource'] = array('module' => 'branch', 'method' => 'getPairs', 'params' => '$productID');

$config->testcase->datatable->fieldList['pri']['title']    = 'priAB';
$config->testcase->datatable->fieldList['pri']['fixed']    = 'left';
$config->testcase->datatable->fieldList['pri']['width']    = '40';
$config->testcase->datatable->fieldList['pri']['required'] = 'no';
$config->testcase->datatable->fieldList['pri']['name']     = $lang->testcase->pri;

$config->testcase->datatable->fieldList['type']['title']    = 'type';
$config->testcase->datatable->fieldList['type']['fixed']    = 'no';
$config->testcase->datatable->fieldList['type']['width']    = '80';
$config->testcase->datatable->fieldList['type']['required'] = 'no';

$config->testcase->datatable->fieldList['assignedTo']['title']    = 'assignedTo';
$config->testcase->datatable->fieldList['assignedTo']['fixed']    = 'no';
$config->testcase->datatable->fieldList['assignedTo']['width']    = '90';
$config->testcase->datatable->fieldList['assignedTo']['required'] = 'no';

$config->testcase->datatable->fieldList['status']['title'] = 'statusAB';
if($app->rawMethod == 'cases')
{
    $config->testcase->datatable->fieldList['status']['width'] = '90';
}
else
{
    $config->testcase->datatable->fieldList['status']['width'] = '70';
}
$config->testcase->datatable->fieldList['status']['fixed']    = 'no';
$config->testcase->datatable->fieldList['status']['required'] = 'no';

$config->testcase->datatable->fieldList['openedBy']['title']    = 'openedByAB';
$config->testcase->datatable->fieldList['openedBy']['fixed']    = 'no';
$config->testcase->datatable->fieldList['openedBy']['width']    = '80';
$config->testcase->datatable->fieldList['openedBy']['required'] = 'no';

$config->testcase->datatable->fieldList['openedDate']['title']    = 'openedDate';
$config->testcase->datatable->fieldList['openedDate']['fixed']    = 'no';
$config->testcase->datatable->fieldList['openedDate']['width']    = '90';
$config->testcase->datatable->fieldList['openedDate']['required'] = 'no';

$config->testcase->datatable->fieldList['stage']['title']    = 'stage';
$config->testcase->datatable->fieldList['stage']['fixed']    = 'no';
$config->testcase->datatable->fieldList['stage']['width']    = '110';
$config->testcase->datatable->fieldList['stage']['required'] = 'no';
$config->testcase->datatable->fieldList['stage']['control']  = 'multiple';

$config->testcase->datatable->fieldList['precondition']['title']    = 'precondition';
$config->testcase->datatable->fieldList['precondition']['fixed']    = 'no';
$config->testcase->datatable->fieldList['precondition']['width']    = '120';
$config->testcase->datatable->fieldList['precondition']['required'] = 'no';

$config->testcase->datatable->fieldList['keywords']['title']    = 'keywords';
$config->testcase->datatable->fieldList['keywords']['fixed']    = 'no';
$config->testcase->datatable->fieldList['keywords']['width']    = '100';
$config->testcase->datatable->fieldList['keywords']['required'] = 'no';

$config->testcase->datatable->fieldList['story']['title']    = 'story';
$config->testcase->datatable->fieldList['story']['fixed']    = 'no';
$config->testcase->datatable->fieldList['story']['width']    = '90';
$config->testcase->datatable->fieldList['story']['required'] = 'no';
$config->testcase->datatable->fieldList['story']['control']    = 'select';
$config->testcase->datatable->fieldList['story']['dataSource'] = array('module' => 'story', 'method' => 'getProductStoryPairs', 'params' => '$productID&$branch');

$config->testcase->datatable->fieldList['reviewedBy']['title']    = 'reviewedBy';
$config->testcase->datatable->fieldList['reviewedBy']['fixed']    = 'no';
$config->testcase->datatable->fieldList['reviewedBy']['width']    = '80';
$config->testcase->datatable->fieldList['reviewedBy']['required'] = 'no';

$config->testcase->datatable->fieldList['reviewedDate']['title']    = 'reviewedDate';
$config->testcase->datatable->fieldList['reviewedDate']['fixed']    = 'no';
$config->testcase->datatable->fieldList['reviewedDate']['width']    = '90';
$config->testcase->datatable->fieldList['reviewedDate']['required'] = 'no';

$config->testcase->datatable->fieldList['lastRunner']['title']    = 'lastRunner';
$config->testcase->datatable->fieldList['lastRunner']['fixed']    = 'no';
$config->testcase->datatable->fieldList['lastRunner']['width']    = '70';
$config->testcase->datatable->fieldList['lastRunner']['required'] = 'no';

$config->testcase->datatable->fieldList['lastRunDate']['title']    = 'lastRunDate';
$config->testcase->datatable->fieldList['lastRunDate']['fixed']    = 'no';
$config->testcase->datatable->fieldList['lastRunDate']['width']    = '90';
$config->testcase->datatable->fieldList['lastRunDate']['required'] = 'no';

$config->testcase->datatable->fieldList['lastRunResult']['title']    = 'lastRunResult';
$config->testcase->datatable->fieldList['lastRunResult']['fixed']    = 'no';
$config->testcase->datatable->fieldList['lastRunResult']['width']    = '70';
$config->testcase->datatable->fieldList['lastRunResult']['required'] = 'no';

$config->testcase->datatable->fieldList['lastEditedBy']['title']    = 'lastEditedBy';
$config->testcase->datatable->fieldList['lastEditedBy']['fixed']    = 'no';
$config->testcase->datatable->fieldList['lastEditedBy']['width']    = '92';
$config->testcase->datatable->fieldList['lastEditedBy']['required'] = 'no';

$config->testcase->datatable->fieldList['lastEditedDate']['title']    = 'lastEditedDate';
$config->testcase->datatable->fieldList['lastEditedDate']['fixed']    = 'no';
$config->testcase->datatable->fieldList['lastEditedDate']['width']    = '90';
$config->testcase->datatable->fieldList['lastEditedDate']['required'] = 'no';

$config->testcase->datatable->fieldList['version']['title']    = 'version';
$config->testcase->datatable->fieldList['version']['fixed']    = 'no';
$config->testcase->datatable->fieldList['version']['width']    = '60';
$config->testcase->datatable->fieldList['version']['sort']     = 'no';
$config->testcase->datatable->fieldList['version']['required'] = 'no';

$config->testcase->datatable->fieldList['bugs']['title']    = 'B';
$config->testcase->datatable->fieldList['bugs']['fixed']    = 'no';
$config->testcase->datatable->fieldList['bugs']['width']    = '32';
$config->testcase->datatable->fieldList['bugs']['required'] = 'no';
$config->testcase->datatable->fieldList['bugs']['sort']     = 'no';
$config->testcase->datatable->fieldList['bugs']['name']     = $lang->testcase->bugs;

$config->testcase->datatable->fieldList['results']['title']      = 'R';
$config->testcase->datatable->fieldList['results']['fixed']      = 'no';
$config->testcase->datatable->fieldList['results']['width']      = '32';
$config->testcase->datatable->fieldList['results']['required']   = 'no';
$config->testcase->datatable->fieldList['results']['sort']       = 'no';
$config->testcase->datatable->fieldList['results']['name']       = $lang->testcase->results;
$config->testcase->datatable->fieldList['results']['dataSource'] = array('lang' => 'resultList');

$config->testcase->datatable->fieldList['stepNumber']['title']    = 'S';
$config->testcase->datatable->fieldList['stepNumber']['fixed']    = 'no';
$config->testcase->datatable->fieldList['stepNumber']['width']    = '32';
$config->testcase->datatable->fieldList['stepNumber']['required'] = 'no';
$config->testcase->datatable->fieldList['stepNumber']['sort']     = 'no';
$config->testcase->datatable->fieldList['stepNumber']['name']     = $lang->testcase->stepNumber;

$config->testcase->datatable->fieldList['actions']['title']    = 'actions';
$config->testcase->datatable->fieldList['actions']['fixed']    = 'right';
$config->testcase->datatable->fieldList['actions']['width']    = '180';
$config->testcase->datatable->fieldList['actions']['required'] = 'yes';
$config->testcase->datatable->fieldList['actions']['sort']     = 'no';

$config->testcase->search['module']          = 'testcase';
$config->testcase->search['fields']['scene'] = $lang->testcase->iScene;
$config->testcase->search['params']['scene'] = array('operator' => 'belong',  'control' => 'select', 'values' => '');

$config->testcase->createscene = new stdclass();
$config->testcase->createscene->requiredFields = 'product,title';

$config->testcase->editscene = new stdclass();
$config->testcase->editscene->requiredFields = 'product,title';

$config->testcase->customBatchCreateFields   = 'module,scene,stage,story,pri,precondition,keywords,review';
$config->testcase->customBatchEditFields     = 'module,scene,story,stage,precondition,status,pri,keywords';
$config->testcase->custom->batchCreateFields = 'module,scene,story,%s';
$config->testcase->custom->batchEditFields   = 'branch,module,scene,stage,status,pri,story';
