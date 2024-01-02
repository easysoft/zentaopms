<?php
$config->testcase = new stdclass();
$config->testcase->defaultSteps = 3;
$config->testcase->batchCreate  = 10;
$config->testcase->needReview   = 0;

$config->testcase->create = new stdclass();
$config->testcase->create->requiredFields = 'product,title,type';

$config->testcase->edit = new stdclass();
$config->testcase->edit->requiredFields = 'title,type';

$config->testcase->editor = new stdclass();
$config->testcase->editor->edit   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->testcase->editor->view   = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');
$config->testcase->editor->review = array('id' => 'comment', 'tools' => 'simpleTools');

$config->testcase->export   = new stdclass();
$config->testcase->export->listFields   = array('type', 'stage', 'pri', 'status');

$config->testcase->actions = new stdclass();
$config->testcase->actions->view = array();
$config->testcase->actions->view['mainActions']   = array('runResult', 'runCase', 'review', 'importToLib', 'createBug', 'showScript');
$config->testcase->actions->view['suffixActions'] = array('edit', 'create', 'delete');

$config->testcase->exportFields = '
    id, product, branch, module, story, scene,
    title, precondition, stepDesc, stepExpect, real, keywords,
    pri, type, stage, status, bugsAB, resultsAB, stepNumberAB, lastRunner, lastRunDate, lastRunResult, openedBy, openedDate,
    lastEditedBy, lastEditedDate, version, linkCase, files';

$config->testcase->list = new stdclass();
$config->testcase->list->customCreateFields      = 'story,stage,pri,keywords';
$config->testcase->list->customBatchCreateFields = 'module,scene,stage,story,pri,precondition,keywords,review';
$config->testcase->list->customBatchEditFields   = 'module,scene,story,stage,precondition,status,pri,keywords';

$config->testcase->custom = new stdclass();
$config->testcase->custom->createFields      = $config->testcase->list->customCreateFields;
$config->testcase->custom->batchCreateFields = 'module,scene,story,%s';
$config->testcase->custom->batchEditFields   = 'branch,module,scene,stage,status,pri,story';

$config->testcase->excludeCheckFields = ',pri,type,stage,needReview,story,branch,';

$config->testcase->scriptAcceptFileTypes = '.php,.py,.js,.go,.sh,.bat,.lua,.rb,.tcl,.pl';

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
$config->testcase->search['params']['lastRunDate']    = array('operator' => '=', 'control' => 'date',  'values' => '');
$config->testcase->search['params']['openedDate']     = array('operator' => '=', 'control' => 'date',  'values' => '');
$config->testcase->search['params']['lastEditedDate'] = array('operator' => '=', 'control' => 'date',  'values' => '');

global $app;
$config->testcase->search['module']          = 'testcase';
$config->testcase->search['fields']['scene'] = $lang->testcase->iScene;
$config->testcase->search['params']['scene'] = array('operator' => 'belong',  'control' => 'select', 'values' => '');

$config->testcase->createscene = new stdclass();
$config->testcase->createscene->requiredFields = 'product,title';

$config->testcase->editscene = new stdclass();
$config->testcase->editscene->requiredFields = 'product,title';

$app->loadLang('story');
$config->testcase->zerocase = new stdclass();
$config->testcase->zerocase->actionList['change']['icon']        = 'change';
$config->testcase->zerocase->actionList['change']['text']        = $lang->story->change;
$config->testcase->zerocase->actionList['change']['hint']        = $lang->story->change;
$config->testcase->zerocase->actionList['change']['url']         = array('module' => 'story', 'method' => 'change', 'params' => 'bugID={id}');
$config->testcase->zerocase->actionList['change']['data-toggle'] = 'modal';

$config->testcase->zerocase->actionList['review']['icon']        = 'review';
$config->testcase->zerocase->actionList['review']['text']        = $lang->story->review;
$config->testcase->zerocase->actionList['review']['hint']        = $lang->story->review;
$config->testcase->zerocase->actionList['review']['url']         = array('module' => 'story', 'method' => 'review', 'params' => 'bugID={id}');
$config->testcase->zerocase->actionList['review']['data-toggle'] = 'modal';

$config->testcase->zerocase->actionList['close']['icon']        = 'off';
$config->testcase->zerocase->actionList['close']['text']        = $lang->story->close;
$config->testcase->zerocase->actionList['close']['hint']        = $lang->story->close;
$config->testcase->zerocase->actionList['close']['url']         = array('module' => 'story', 'method' => 'close', 'params' => 'bugID={id}');
$config->testcase->zerocase->actionList['close']['data-toggle'] = 'modal';

$config->testcase->zerocase->actionList['edit']['icon'] = 'edit';
$config->testcase->zerocase->actionList['edit']['text'] = $lang->story->edit;
$config->testcase->zerocase->actionList['edit']['hint'] = $lang->story->edit;
$config->testcase->zerocase->actionList['edit']['url']  = array('module' => 'story', 'method' => 'edit', 'params' => 'bugID={id}');

$config->testcase->zerocase->actionList['createcase']['icon']      = 'sitemap';
$config->testcase->zerocase->actionList['createcase']['text']      = $lang->testcase->create;
$config->testcase->zerocase->actionList['createcase']['hint']      = $lang->testcase->create;
$config->testcase->zerocase->actionList['createcase']['url']       = array('module' => 'testcase', 'method' => 'create', 'params' => 'productID={product}&branch=0&module=0&from=0&storyID={id}');
$config->testcase->zerocase->actionList['createcase']['data-app']  = 'qa';
