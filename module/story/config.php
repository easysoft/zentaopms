<?php
$config->story = new stdclass();

$config->story->batchCreate      = 10;
$config->story->affectedFixedNum = 7;
$config->story->needReview = 1;

$config->story->batchClose = new stdclass();
$config->story->batchClose->columns = 10;

$config->story->create = new stdclass();
$config->story->edit   = new stdclass();
$config->story->change = new stdclass();
$config->story->close  = new stdclass();
$config->story->review = new stdclass();
$config->story->create->requiredFields = 'title';
$config->story->change->requiredFields = 'title';
$config->story->close->requiredFields  = 'closedReason';
$config->story->review->requiredFields = 'assignedTo,reviewedBy';

$config->story->editor = new stdclass();
$config->story->editor->create   = array('id' => 'spec,verify', 'tools' => 'simpleTools');
$config->story->editor->change   = array('id' => 'spec,verify,comment', 'tools' => 'simpleTools');
$config->story->editor->edit     = array('id' => 'comment', 'tools' => 'simpleTools');
$config->story->editor->view     = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');
$config->story->editor->close    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->story->editor->review   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->story->editor->activate = array('id' => 'comment', 'tools' => 'simpleTools');

$config->story->list = new stdclass();
$config->story->list->exportFields      = '
    id, product, branch, module, plan, source, title, spec, verify, keywords, 
    pri, estimate, status, stage,
    openedBy, openedDate, assignedTo, assignedDate, mailto, 
    reviewedBy, reviewedDate, 
    closedBy, closedDate, closedReason,
    lastEditedBy, lastEditedDate, 
    childStories, linkStories, duplicateStory, files';

$config->story->list->customCreateFields      = 'source,verify,pri,estimate,mailto,keywords';
$config->story->list->customBatchCreateFields = 'module,plan,spec,source,verify,pri,estimate,review,keywords';
$config->story->list->customBatchEditFields   = 'branch,module,plan,estimate,pri,assignedTo,source,stage,closedBy,closedReason,keywords';

$config->story->custom = new stdclass();
$config->story->custom->createFields      = $config->story->list->customCreateFields;
$config->story->custom->batchCreateFields = 'module,plan,spec,pri,estimate,review';
$config->story->custom->batchEditFields   = 'branch,module,plan,estimate,pri,source,stage,closedBy,closedReason';

$config->story->datatable = new stdclass();
$config->story->datatable->defaultField = array('id', 'pri', 'title', 'plan', 'source', 'openedBy', 'assignedTo', 'estimate', 'status', 'stage', 'taskCount', 'bugCount', 'caseCount', 'actions');

$config->story->datatable->fieldList['id']['title']    = 'idAB';
$config->story->datatable->fieldList['id']['fixed']    = 'left';
$config->story->datatable->fieldList['id']['width']    = '70';
$config->story->datatable->fieldList['id']['required'] = 'yes';

$config->story->datatable->fieldList['pri']['title']    = 'priAB';
$config->story->datatable->fieldList['pri']['fixed']    = 'left';
$config->story->datatable->fieldList['pri']['width']    = '40';
$config->story->datatable->fieldList['pri']['required'] = 'no';

$config->story->datatable->fieldList['title']['title']    = 'title';
$config->story->datatable->fieldList['title']['fixed']    = 'left';
$config->story->datatable->fieldList['title']['width']    = 'auto';
$config->story->datatable->fieldList['title']['required'] = 'yes';

$config->story->datatable->fieldList['plan']['title']    = 'planAB';
$config->story->datatable->fieldList['plan']['fixed']    = 'no';
$config->story->datatable->fieldList['plan']['width']    = '90';
$config->story->datatable->fieldList['plan']['required'] = 'no';

$config->story->datatable->fieldList['source']['title']    = 'source';
$config->story->datatable->fieldList['source']['fixed']    = 'no';
$config->story->datatable->fieldList['source']['width']    = '90';
$config->story->datatable->fieldList['source']['required'] = 'no';

$config->story->datatable->fieldList['status']['title']    = 'statusAB';
$config->story->datatable->fieldList['status']['fixed']    = 'no';
$config->story->datatable->fieldList['status']['width']    = '80';
$config->story->datatable->fieldList['status']['required'] = 'no';

$config->story->datatable->fieldList['estimate']['title']    = 'estimateAB';
$config->story->datatable->fieldList['estimate']['fixed']    = 'no';
$config->story->datatable->fieldList['estimate']['width']    = '80';
$config->story->datatable->fieldList['estimate']['required'] = 'no';

$config->story->datatable->fieldList['stage']['title']    = 'stageAB';
$config->story->datatable->fieldList['stage']['fixed']    = 'no';
$config->story->datatable->fieldList['stage']['width']    = '90';
$config->story->datatable->fieldList['stage']['required'] = 'no';

$config->story->datatable->fieldList['taskCount']['title']    = 'taskCount';
$config->story->datatable->fieldList['taskCount']['fixed']    = 'no';
$config->story->datatable->fieldList['taskCount']['width']    = '90';
$config->story->datatable->fieldList['taskCount']['required'] = 'no';
$config->story->datatable->fieldList['taskCount']['sort']     = 'no';

$config->story->datatable->fieldList['bugCount']['title']    = 'bugCount';
$config->story->datatable->fieldList['bugCount']['fixed']    = 'no';
$config->story->datatable->fieldList['bugCount']['width']    = '90';
$config->story->datatable->fieldList['bugCount']['required'] = 'no';
$config->story->datatable->fieldList['bugCount']['sort']     = 'no';

$config->story->datatable->fieldList['caseCount']['title']    = 'caseCount';
$config->story->datatable->fieldList['caseCount']['fixed']    = 'no';
$config->story->datatable->fieldList['caseCount']['width']    = '90';
$config->story->datatable->fieldList['caseCount']['required'] = 'no';
$config->story->datatable->fieldList['caseCount']['sort']     = 'no';

$config->story->datatable->fieldList['openedBy']['title']    = 'openedByAB';
$config->story->datatable->fieldList['openedBy']['fixed']    = 'no';
$config->story->datatable->fieldList['openedBy']['width']    = '80';
$config->story->datatable->fieldList['openedBy']['required'] = 'no';

$config->story->datatable->fieldList['openedDate']['title']    = 'openedDate';
$config->story->datatable->fieldList['openedDate']['fixed']    = 'no';
$config->story->datatable->fieldList['openedDate']['width']    = '90';
$config->story->datatable->fieldList['openedDate']['required'] = 'no';

$config->story->datatable->fieldList['assignedTo']['title']    = 'assignedToAB';
$config->story->datatable->fieldList['assignedTo']['fixed']    = 'no';
$config->story->datatable->fieldList['assignedTo']['width']    = '80';
$config->story->datatable->fieldList['assignedTo']['required'] = 'no';

$config->story->datatable->fieldList['assignedDate']['title']    = 'assignedDate';
$config->story->datatable->fieldList['assignedDate']['fixed']    = 'no';
$config->story->datatable->fieldList['assignedDate']['width']    = '90';
$config->story->datatable->fieldList['assignedDate']['required'] = 'no';

$config->story->datatable->fieldList['reviewedBy']['title']    = 'reviewedBy';
$config->story->datatable->fieldList['reviewedBy']['fixed']    = 'no';
$config->story->datatable->fieldList['reviewedBy']['width']    = '80';
$config->story->datatable->fieldList['reviewedBy']['required'] = 'no';

$config->story->datatable->fieldList['reviewedDate']['title']    = 'reviewedDate';
$config->story->datatable->fieldList['reviewedDate']['fixed']    = 'no';
$config->story->datatable->fieldList['reviewedDate']['width']    = '90';
$config->story->datatable->fieldList['reviewedDate']['required'] = 'no';

$config->story->datatable->fieldList['closedBy']['title']    = 'closedBy';
$config->story->datatable->fieldList['closedBy']['fixed']    = 'no';
$config->story->datatable->fieldList['closedBy']['width']    = '80';
$config->story->datatable->fieldList['closedBy']['required'] = 'no';

$config->story->datatable->fieldList['closedDate']['title']    = 'closedDate';
$config->story->datatable->fieldList['closedDate']['fixed']    = 'no';
$config->story->datatable->fieldList['closedDate']['width']    = '90';
$config->story->datatable->fieldList['closedDate']['required'] = 'no';

$config->story->datatable->fieldList['closedReason']['title']    = 'closedReason';
$config->story->datatable->fieldList['closedReason']['fixed']    = 'no';
$config->story->datatable->fieldList['closedReason']['width']    = '90';
$config->story->datatable->fieldList['closedReason']['required'] = 'no';

$config->story->datatable->fieldList['actions']['title']    = 'actions';
$config->story->datatable->fieldList['actions']['fixed']    = 'right';
$config->story->datatable->fieldList['actions']['width']    = '140';
$config->story->datatable->fieldList['actions']['required'] = 'yes';

$config->story->datatable->fieldList['branch']['title']    = 'branch';
$config->story->datatable->fieldList['branch']['fixed']    = 'left';
$config->story->datatable->fieldList['branch']['width']    = '100';
$config->story->datatable->fieldList['branch']['required'] = 'no';
