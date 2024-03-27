<?php
$config->story = new stdclass();

$config->story->defaultPriority  = 3;
$config->story->batchCreate      = 10;
$config->story->affectedFixedNum = 7;
$config->story->needReview       = 1;
$config->story->removeFields     = 'objectTypeList,productList,executionList,execution';
$config->story->feedbackSource   = array('customer', 'user', 'market', 'service', 'operation', 'support', 'forum');

$config->story->batchClose = new stdclass();
$config->story->batchClose->columns = 10;
$config->story->create = new stdclass();
$config->story->edit   = new stdclass();
$config->story->change = new stdclass();
$config->story->close  = new stdclass();
$config->story->review = new stdclass();
$config->story->create->requiredFields = 'title';
$config->story->edit->requiredFields = 'title';
$config->story->change->requiredFields = 'title';
$config->story->close->requiredFields  = 'closedReason';
$config->story->review->requiredFields = '';

$config->story->editor = new stdclass();
$config->story->editor->create   = array('id' => 'spec,verify', 'tools' => 'simpleTools');
$config->story->editor->change   = array('id' => 'spec,verify,comment', 'tools' => 'simpleTools');
$config->story->editor->edit     = array('id' => 'spec,verify,comment', 'tools' => 'simpleTools');
$config->story->editor->view     = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');
$config->story->editor->close    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->story->editor->review   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->story->editor->activate = array('id' => 'comment', 'tools' => 'simpleTools');
$config->story->editor->assignto = array('id' => 'comment', 'tools' => 'simpleTools');

$config->story->list = new stdclass();
$config->story->exportFields = '
    id, product, branch, module, plan, source, sourceNote, title, spec, verify, keywords,
    pri, estimate, status, stage, category, taskCountAB, bugCountAB, caseCountAB,
    openedBy, openedDate, assignedTo, assignedDate, mailto,
    reviewedBy, reviewedDate,
    closedBy, closedDate, closedReason,
    lastEditedBy, lastEditedDate,
    childStories, linkStories, duplicateStory, files';

$config->story->list->customCreateFields      = '';
$config->story->list->customBatchCreateFields = 'plan,assignedTo,spec,source,verify,pri,estimate,URS,parent,keywords,mailto';
$config->story->list->customBatchEditFields   = 'branch,plan,estimate,pri,assignedTo,source,stage,closedBy,closedReason,keywords';

$config->story->list->actionsOperatedParentStory = ',edit,batchcreate,change,review,recall,submitreview,processstorychange,';

$config->story->custom = new stdclass();
$config->story->custom->createFields      = $config->story->list->customCreateFields;
$config->story->custom->batchCreateFields = 'module,plan,spec,pri,estimate,review,%s';
$config->story->custom->batchEditFields   = 'branch,module,plan,estimate,pri,source,stage,closedBy,closedReason';

$config->story->excludeCheckFields = ',uploadImage,category,reviewer,reviewDitto,lanes,regions,branch,pri,';

global $app, $lang;
$config->story->actionList = array();
$config->story->actionList['change']['icon']     = 'alert';
$config->story->actionList['change']['text']     = $lang->story->change;
$config->story->actionList['change']['hint']     = $lang->story->change;
$config->story->actionList['change']['url']      = array('module' => 'story', 'method' => 'change', 'params' => 'storyID={id}');
$config->story->actionList['change']['data-app'] = $app->tab;

$config->story->actionList['submitReview']['icon']        = 'confirm';
$config->story->actionList['submitReview']['text']        = $lang->story->submitReview;
$config->story->actionList['submitReview']['hint']        = $lang->story->submitReview;
$config->story->actionList['submitReview']['url']         = array('module' => 'story', 'method' => 'submitReview', 'params' => 'storyID={id}&storyType={type}');
$config->story->actionList['submitReview']['data-toggle'] = 'modal';

$config->story->actionList['recall']['icon']      = 'undo';
$config->story->actionList['recall']['text']      = $lang->story->recall;
$config->story->actionList['recall']['hint']      = $lang->story->recall;
$config->story->actionList['recall']['url']       = array('module' => 'story', 'method' => 'recall', 'params' => 'storyID={id}&from=view&confirm=no&storyType={type}');
$config->story->actionList['recall']['data-app']  = $app->tab;
$config->story->actionList['recall']['className'] = 'ajax-submit';

$config->story->actionList['recallChange']['icon']      = 'undo';
$config->story->actionList['recallChange']['text']      = $lang->story->recallChange;
$config->story->actionList['recallChange']['hint']      = $lang->story->recallChange;
$config->story->actionList['recallChange']['url']       = array('module' => 'story', 'method' => 'recall', 'params' => 'storyID={id}&from=view&confirm=no&storyType={type}');
$config->story->actionList['recallChange']['data-app']  = $app->tab;
$config->story->actionList['recallChange']['className'] = 'ajax-submit';

$config->story->actionList['review']['icon']     = 'alert';
$config->story->actionList['review']['text']     = $lang->story->review;
$config->story->actionList['review']['hint']     = $lang->story->review;
$config->story->actionList['review']['url']      = array('module' => 'story', 'method' => 'review', 'params' => 'storyID={id}&from=' . $app->tab . '&storyType={type}');
$config->story->actionList['review']['data-app'] = $app->tab;

$config->story->actionList['subdivide']['icon']     = 'split';
$config->story->actionList['subdivide']['text']     = $lang->story->subdivide;
$config->story->actionList['subdivide']['hint']     = $lang->story->subdivide;
$config->story->actionList['subdivide']['url']      = array('module' => 'story', 'method' => 'batchCreate', 'params' => 'productID={product}&branch={branch}&moduleID={module}&storyID={id}&executionID={executionID}&plan=0&storyType=story');
$config->story->actionList['subdivide']['data-app'] = $app->tab;

$config->story->actionList['assignTo']['icon']        = 'hand-right';
$config->story->actionList['assignTo']['text']        = $lang->story->assignTo;
$config->story->actionList['assignTo']['hint']        = $lang->story->assignTo;
$config->story->actionList['assignTo']['url']         = array('module' => 'story', 'method' => 'assignTo', 'params' => 'storyID={id}&kanbanGroup=default&from=&storyType={type}');
$config->story->actionList['assignTo']['data-toggle'] = 'modal';

$config->story->actionList['close']['icon']        = 'off';
$config->story->actionList['close']['text']        = $lang->story->close;
$config->story->actionList['close']['hint']        = $lang->story->close;
$config->story->actionList['close']['url']         = array('module' => 'story', 'method' => 'close', 'params' => 'storyID={id}&from=&storyType={type}');
$config->story->actionList['close']['data-toggle'] = 'modal';

$config->story->actionList['activate']['icon']        = 'magic';
$config->story->actionList['activate']['text']        = $lang->story->activate;
$config->story->actionList['activate']['hint']        = $lang->story->activate;
$config->story->actionList['activate']['url']         = array('module' => 'story', 'method' => 'activate', 'params' => 'storyID={id}&storyType={type}');
$config->story->actionList['activate']['data-toggle'] = 'modal';

$config->story->actionList['importToLib']['icon']        = 'assets';
if(isset($lang->story->importToLib)) $config->story->actionList['importToLib']['text']        = $lang->story->importToLib;
if(isset($lang->story->importToLib)) $config->story->actionList['importToLib']['hint']        = $lang->story->importToLib;
$config->story->actionList['importToLib']['url']         = '#importToLib';
$config->story->actionList['importToLib']['data-toggle'] = 'modal';

$app->loadLang('testcase');
$config->story->actionList['createTestcase']['text']        = $lang->story->create;
$config->story->actionList['createTestcase']['hint']        = $lang->story->create;
$config->story->actionList['createTestcase']['url']         = array('module' => 'testcase', 'method' => 'create', 'params' => 'product={product}&branch={branch}&moduleID=0&from=&param=0&storyid={id}');
$config->story->actionList['createTestcase']['data-size']   = 'lg';
$config->story->actionList['createTestcase']['data-toggle'] = 'modal';

$config->story->actionList['batchCreateTestcase']['text']        = $lang->story->batchCreate;
$config->story->actionList['batchCreateTestcase']['hint']        = $lang->story->batchCreate;
$config->story->actionList['batchCreateTestcase']['url']         = array('module' => 'testcase', 'method' => 'batchCreate', 'params' => 'product={product}&branch={branch}&moduleID=0&storyid={id}');
$config->story->actionList['batchCreateTestcase']['data-size']   = 'lg';
$config->story->actionList['batchCreateTestcase']['data-toggle'] = 'modal';

$config->story->actionList['testcase']['type']      = 'dropdown';
$config->story->actionList['testcase']['text']      = $lang->testcase->common;
$config->story->actionList['testcase']['hint']      = $lang->testcase->common;
$config->story->actionList['testcase']['caret']     = 'up';
$config->story->actionList['testcase']['placement'] = 'top-end';
$config->story->actionList['testcase']['items']     = array('createTestcase', 'batchCreateTestcase');

$app->loadLang('task');
$config->story->actionList['createTask']['icon']        = 'plus';
$config->story->actionList['createTask']['text']        = $lang->task->create;
$config->story->actionList['createTask']['hint']        = $lang->task->create;
$config->story->actionList['createTask']['url']         = array('module' => 'task', 'method' => 'create', 'params' => 'executionID={execution}&storyID={id}&moduleID={module}');
$config->story->actionList['createTask']['data-size']   = 'lg';
$config->story->actionList['createTask']['data-toggle'] = 'modal';

$config->story->actionList['edit']['icon']      = 'edit';
$config->story->actionList['edit']['text']      = $lang->story->edit;
$config->story->actionList['edit']['hint']      = $lang->story->edit;
$config->story->actionList['edit']['url']       = array('module' => 'story', 'method' => 'edit', 'params' => 'storyID={id}&kanbanGroup=default&storyType={type}');
$config->story->actionList['edit']['data-app']  = $app->tab;
$config->story->actionList['edit']['notInModal'] = true;

$config->story->actionList['copy']['icon']      = 'copy';
$config->story->actionList['copy']['text']      = $lang->story->copy;
$config->story->actionList['copy']['hint']      = $lang->story->copy;
$config->story->actionList['copy']['url']       = array('module' => 'story', 'method' => 'create', 'params' => 'product={product}&branch={branch}&moduleID={module}&storyID={id}&executionID=0&bugID=0&planID=0&todoID=0&extra=&storyType={type}');
$config->story->actionList['copy']['data-app']  = $app->tab;
$config->story->actionList['copy']['notInModal'] = true;

$config->story->actionList['delete']['icon']      = 'trash';
$config->story->actionList['delete']['text']      = $lang->task->delete;
$config->story->actionList['delete']['hint']      = $lang->task->delete;
$config->story->actionList['delete']['url']       = array('module' => 'story', 'method' => 'delete', 'params' => 'storyID={id}');
$config->story->actionList['delete']['className'] = 'ajax-submit';

$config->story->actions = new stdclass();
$config->story->actions->view = array();
$config->story->actions->view['mainActions']   = array('change', 'submitReview', 'recall', 'recallChange', 'review', 'subdivide', 'assignTo', 'close', 'activate', 'importToLib', 'testcase', 'createTask');
$config->story->actions->view['suffixActions'] = array('edit', 'copy', 'delete');
