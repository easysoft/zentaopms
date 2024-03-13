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
$config->story->list->customBatchCreateFields = 'plan,assignedTo,spec,source,verify,pri,estimate,URS,keywords,mailto';
$config->story->list->customBatchEditFields   = 'branch,plan,estimate,pri,assignedTo,source,stage,closedBy,closedReason,keywords';

$config->story->list->actionsOperatedParentStory = ',edit,batchcreate,change,review,recall,submitreview,processstorychange,';

$config->story->custom = new stdclass();
$config->story->custom->createFields      = $config->story->list->customCreateFields;
$config->story->custom->batchCreateFields = 'module,plan,spec,pri,estimate,review,%s';
$config->story->custom->batchEditFields   = 'branch,module,plan,estimate,pri,source,stage,closedBy,closedReason';

$config->story->excludeCheckFields = ',uploadImage,category,reviewer,reviewDitto,lanes,regions,branch,pri,';
