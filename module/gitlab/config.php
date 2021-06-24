<?php
$config->gitlab->create = new stdclass();
$config->gitlab->create->requiredFields = 'name,url,token';

$config->gitlab->edit = new stdclass();
$config->gitlab->edit->requiredFields = 'name,url,token';

$config->gitlab->labelPattern = new stdclass;
$config->gitlab->labelPattern->task  = '/^zentao_task\/\d+$/';
$config->gitlab->labelPattern->bug   = '/^zentao_bug\/\d+$/';
$config->gitlab->labelPattern->story = '/^zentao_story\/\d+$/';

$config->gitlab->actions = array();
$config->gitlab->actions['issue'] = array();

$config->gitlab->taskLabel = new stdclass();
$config->gitlab->taskLabel->name        = "zentao task";
$config->gitlab->taskLabel->description = "task label from zentao, do NOT remove this";
$config->gitlab->taskLabel->color       = "#0033CC";
$config->gitlab->taskLabel->priority    = "0";

$config->gitlab->bugLabel = new stdclass();
$config->gitlab->bugLabel->name         = "zentao bug";
$config->gitlab->bugLabel->description  = "bug label from zentao, do NOT remove this";
$config->gitlab->bugLabel->color        = "#D10069";
$config->gitlab->bugLabel->priority     = "0";

$config->gitlab->storyLabel = new stdclass();
$config->gitlab->storyLabel->name         = "zentao story";
$config->gitlab->storyLabel->description  = "story label from zentao, do NOT remove this";
$config->gitlab->storyLabel->color        = "##69D100";
$config->gitlab->storyLabel->priority     = "0";

$config->gitlab->zentaoApiWebhookUrl    = "%s/api.php?m=gitlab&f=webhook&product=%s&gitlab=%s";
$config->gitlab->zentaoApiWebhookToken  = "<access token>";

$config->gitlab->skippedFields = new stdclass;
$config->gitlab->skippedFields->issueCreate = array();
$config->gitlab->skippedFields->issueCreate[] = '';

$config->gitlab->maps = new stdclass;
$config->gitlab->maps->task = array();
$config->gitlab->maps->task['name']           = 'title|field|';
$config->gitlab->maps->task['desc']           = 'description|field|';
$config->gitlab->maps->task['openedDate']     = 'created_at|field|';
$config->gitlab->maps->task['assignedTo']     = 'assignee_id|userPairs|';
$config->gitlab->maps->task['lastEditedDate'] = 'updated_at|field|';
$config->gitlab->maps->task['deadline']       = 'due_date|field|';
$config->gitlab->maps->task['status']         = 'state|configItems|taskStateMap';
$config->gitlab->maps->task['pri']            = 'weight|configItems|taskWeightMap';

$config->gitlab->taskWeightMap = array();
$config->gitlab->taskWeightMap['1'] = '1';
$config->gitlab->taskWeightMap['2'] = '2';
$config->gitlab->taskWeightMap['3'] = '3';

$config->gitlab->taskStateMap  = array();
$config->gitlab->taskStateMap['doing']  = 'reopen';
$config->gitlab->taskStateMap['wait']   = 'reopen';
$config->gitlab->taskStateMap['done']   = 'close';
$config->gitlab->taskStateMap['cancel'] = 'close';
$config->gitlab->taskStateMap['closed'] = 'close';

$config->gitlab->taskTypesToSync = 'design,devel,request,discuss,ui,affair,misc';
