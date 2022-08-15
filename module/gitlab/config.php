<?php
$config->gitlab->create = new stdclass;
$config->gitlab->create->requiredFields = 'name,url,token';

$config->gitlab->edit = new stdclass;
$config->gitlab->edit->requiredFields = 'name,url,token';

$config->gitlab->createbranch = new stdclass;
$config->gitlab->createbranch->requiredFields = 'branch,ref';

$config->gitlab->createbranchpriv = new stdclass;
$config->gitlab->createbranchpriv->requiredFields = 'name';

$config->gitlab->createtag = new stdclass;
$config->gitlab->createtag->requiredFields = 'tag_name,ref';

$config->gitlab->labelPattern = new stdclass;
$config->gitlab->labelPattern->task  = '/^zentao_task\/\d+$/';
$config->gitlab->labelPattern->bug   = '/^zentao_bug\/\d+$/';
$config->gitlab->labelPattern->story = '/^zentao_story\/\d+$/';

$config->gitlab->actions = array();
$config->gitlab->actions['issue'] = array();

$config->gitlab->zentaoObjectLabel = new stdclass;
$config->gitlab->zentaoObjectLabel->name         = "zentao_%s/%s";
$config->gitlab->zentaoObjectLabel->description  = "%s";

$config->gitlab->zentaoObjectLabel->color = new stdclass;
$config->gitlab->zentaoObjectLabel->color->task  = '#0033CC';
$config->gitlab->zentaoObjectLabel->color->story = '#69D100';
$config->gitlab->zentaoObjectLabel->color->bug   = '#D10069';
$config->gitlab->zentaoObjectLabel->priority     = "0";

$config->gitlab->webhookURL = "%s/api.php?m=gitlab&f=webhook&product=%s&gitlab=%s";

$config->gitlab->skippedFields = new stdclass;
$config->gitlab->skippedFields->issueCreate = array();
$config->gitlab->skippedFields->issueCreate['story'] = array();
$config->gitlab->skippedFields->issueCreate['task']  = array();
$config->gitlab->skippedFields->issueCreate['bug']   = array();

$config->gitlab->maps = new stdclass;
$config->gitlab->maps->task = array();
$config->gitlab->maps->task['name']           = 'title|field|';
$config->gitlab->maps->task['desc']           = 'description|field|';
$config->gitlab->maps->task['openedDate']     = 'created_at|field|datetime';
$config->gitlab->maps->task['assignedTo']     = 'assignee_id|userPairs|';
$config->gitlab->maps->task['lastEditedDate'] = 'updated_at|field|datetime';
$config->gitlab->maps->task['deadline']       = 'due_date|field|date';
$config->gitlab->maps->task['status']         = 'state|configItems|taskStateMap';
$config->gitlab->maps->task['pri']            = 'weight|configItems|taskWeightMap';
$config->gitlab->maps->task['lastEditedBy']   = 'updated_by_id|userPairs|';

$config->gitlab->maps->story = array();
$config->gitlab->maps->story['title']      = 'title|field|';
$config->gitlab->maps->story['spec']       = 'description|fields|verify';
$config->gitlab->maps->story['openedDate'] = 'created_at|field|datetime';
$config->gitlab->maps->story['assignedTo'] = 'assignee_id|userPairs|';
$config->gitlab->maps->story['status']     = 'state|configItems|storyStateMap';
$config->gitlab->maps->story['pri']        = 'weight|configItems|storyWeightMap';

$config->gitlab->maps->bug = array();
$config->gitlab->maps->bug['title']      = 'title|field|';
$config->gitlab->maps->bug['steps']      = 'description|field|';
$config->gitlab->maps->bug['openedDate'] = 'created_at|field|datetime';
$config->gitlab->maps->bug['deadline']   = 'due_date|field|date';
$config->gitlab->maps->bug['assignedTo'] = 'assignee_id|userPairs|';
$config->gitlab->maps->bug['status']     = 'state|configItems|bugStateMap';
$config->gitlab->maps->bug['pri']        = 'weight|configItems|bugWeightMap';

$config->gitlab->taskWeightMap = array();
$config->gitlab->taskWeightMap['1'] = '1';
$config->gitlab->taskWeightMap['2'] = '2';
$config->gitlab->taskWeightMap['3'] = '3';

$config->gitlab->taskStateMap = array();
$config->gitlab->taskStateMap['doing']  = 'opened';
$config->gitlab->taskStateMap['wait']   = 'opened';
$config->gitlab->taskStateMap['closed'] = 'closed';
$config->gitlab->taskStateMap['done']   = 'closed';
$config->gitlab->taskStateMap['cancel'] = 'closed';

$config->gitlab->taskTypesToSync = 'design,devel,request,discuss,ui,affair,misc';

$config->gitlab->storyWeightMap = array();
$config->gitlab->storyWeightMap['1'] = '1';
$config->gitlab->storyWeightMap['2'] = '2';
$config->gitlab->storyWeightMap['3'] = '3';

$config->gitlab->storyStateMap = array();
$config->gitlab->storyStateMap['active']   = 'opened';
$config->gitlab->storyStateMap['resolved'] = 'closed';
$config->gitlab->storyStateMap['closed']   = 'closed';

$config->gitlab->bugWeightMap = array();
$config->gitlab->bugWeightMap['1'] = '1';
$config->gitlab->bugWeightMap['2'] = '2';
$config->gitlab->bugWeightMap['3'] = '3';
$config->gitlab->bugWeightMap['4'] = '4';

$config->gitlab->bugStateMap = array();
$config->gitlab->bugStateMap['active']   = 'opened';
$config->gitlab->bugStateMap['resolved'] = 'closed';
$config->gitlab->bugStateMap['closed']   = 'closed';

$config->gitlab->objectTables = new stdclass;
$config->gitlab->objectTables->story = TABLE_STORY;
$config->gitlab->objectTables->task  = TABLE_TASK;
$config->gitlab->objectTables->bug   = TABLE_BUG;

$config->gitlab->objectTypes = array();
$config->gitlab->objectTypes['']      = '';
$config->gitlab->objectTypes['task']  = '任务';
$config->gitlab->objectTypes['bug']   = 'Bug';
$config->gitlab->objectTypes['story'] = '需求';

$config->gitlab->accessLevel = array();
$config->gitlab->accessLevel['guest']      = 10;
$config->gitlab->accessLevel['reporter']   = 20;
$config->gitlab->accessLevel['developer']  = 30;
$config->gitlab->accessLevel['maintainer'] = 40;
$config->gitlab->accessLevel['owner']      = 50;

/* Minimum compatible version. */
$config->gitlab->minCompatibleVersion = '9.0';

$config->gitlab->menus['project'] = 'browseProject';
$config->gitlab->menus['group']   = 'browseGroup';
$config->gitlab->menus['user']    = 'browseUser';
