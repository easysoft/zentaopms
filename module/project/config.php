<?php
$config->project = new stdclass();
$config->project->editor = new stdclass();

$config->project->editor->create   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->project->editor->edit     = array('id' => 'desc', 'tools' => 'simpleTools');
$config->project->editor->close    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->project->editor->suspend  = array('id' => 'comment', 'tools' => 'simpleTools');
$config->project->editor->start    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->project->editor->activate = array('id' => 'comment', 'tools' => 'simpleTools');
$config->project->editor->view     = array('id' => 'lastComment', 'tools' => 'simpleTools');

$config->project->list = new stdclass();
$config->project->list->exportFields = 'id,code,name,hasProduct,linkedProducts,status,begin,end,budget,PM,end,desc';
if($config->systemMode == 'ALM') $config->project->list->exportFields = substr_replace($config->project->list->exportFields, ',parent', 2, 0);

$config->project->create = new stdclass();
$config->project->edit   = new stdclass();
$config->project->create->requiredFields = 'name,code,begin,end';
$config->project->edit->requiredFields   = 'name,code,begin,end';

$config->project->start   = new stdclass();
$config->project->start->requiredFields = 'realBegan';

$config->project->close   = new stdclass();
$config->project->close->requiredFields = 'realEnd';

$config->project->sortFields         = new stdclass();
$config->project->sortFields->id     = 'id';
$config->project->sortFields->begin  = 'begin';
$config->project->sortFields->end    = 'end';
$config->project->sortFields->status = 'status';
$config->project->sortFields->budget = 'budget';

$config->project->multiple['project']   = ',qa,devops,doc,build,release,dynamic,settings,';
$config->project->multiple['execution'] = ',task,kanban,burn,view,story,CFD,';

global $lang;
$config->project->dtable = new stdclass();
$config->project->dtable->defaultField = array('id', 'name', 'status', 'PM', 'budget', 'begin', 'end', 'progress', 'actions');

$config->project->dtable->fieldList['id']['title']    = $lang->idAB;
$config->project->dtable->fieldList['id']['name']     = 'id';
$config->project->dtable->fieldList['id']['width']    = 90;
$config->project->dtable->fieldList['id']['flex']     = 0;
$config->project->dtable->fieldList['id']['fixed']    = 'left';
$config->project->dtable->fieldList['id']['type']     = 'id';
$config->project->dtable->fieldList['id']['sortType'] = 'desc';
$config->project->dtable->fieldList['id']['checkbox'] = true;

$config->project->dtable->fieldList['name']['title']        = $lang->project->name;
$config->project->dtable->fieldList['name']['name']         = 'name';
$config->project->dtable->fieldList['name']['width']        = 200;
$config->project->dtable->fieldList['name']['flex']         = 1;
$config->project->dtable->fieldList['name']['fixed']        = 'left';
$config->project->dtable->fieldList['name']['link']         = helper::createLink('project', 'index', 'projectID = {id}');

$config->project->dtable->fieldList['code']['title'] = $lang->project->code;
$config->project->dtable->fieldList['code']['name']  = 'code';
$config->project->dtable->fieldList['code']['width'] = 100;
$config->project->dtable->fieldList['code']['flex']  = 0;
$config->project->dtable->fieldList['code']['fixed'] = false;

$config->project->dtable->fieldList['PM']['title']    = $lang->project->PM;
$config->project->dtable->fieldList['PM']['name']     = 'PM';
$config->project->dtable->fieldList['PM']['width']    = '80';
$config->project->dtable->fieldList['PM']['flex']     = 1;
$config->project->dtable->fieldList['PM']['fixed']    = false;
$config->project->dtable->fieldList['PM']['type']     = 'avatarBtn';
$config->project->dtable->fieldList['PM']['sortType'] = true;

$config->project->dtable->fieldList['status']['title']     = $lang->project->status;
$config->project->dtable->fieldList['status']['name']      = 'status';
$config->project->dtable->fieldList['status']['width']     = 75;
$config->project->dtable->fieldList['status']['flex']      = 1;
$config->project->dtable->fieldList['status']['fixed']     = false;
$config->project->dtable->fieldList['status']['type']      = 'status';
$config->project->dtable->fieldList['status']['sortType']  = true;
$config->project->dtable->fieldList['status']['statusMap'] = $lang->project->statusList;

$config->project->dtable->fieldList['hasProduct']['title']    = $lang->project->type;
$config->project->dtable->fieldList['hasProduct']['name']     = 'hasProduct';
$config->project->dtable->fieldList['hasProduct']['width']    = 100;
$config->project->dtable->fieldList['hasProduct']['flex']     = 1;
$config->project->dtable->fieldList['hasProduct']['fixed']    = false;
$config->project->dtable->fieldList['hasProduct']['sortType'] = true;

$config->project->dtable->fieldList['budget']['title'] = $lang->project->budget;
$config->project->dtable->fieldList['budget']['name']  = 'budget';
$config->project->dtable->fieldList['budget']['width'] = 100;
$config->project->dtable->fieldList['budget']['flex']  = 1;
$config->project->dtable->fieldList['budget']['fixed'] = 'no';
$config->project->dtable->fieldList['budget']['type']  = 'money';

$config->project->dtable->fieldList['begin']['title'] = $lang->project->begin;
$config->project->dtable->fieldList['begin']['name']  = 'begin';
$config->project->dtable->fieldList['begin']['width'] = 115;
$config->project->dtable->fieldList['begin']['flex']  = 1;
$config->project->dtable->fieldList['begin']['fixed'] = 'no';
$config->project->dtable->fieldList['begin']['type']  = 'date';

$config->project->dtable->fieldList['end']['title'] = $lang->project->end;
$config->project->dtable->fieldList['end']['name']  = 'end';
$config->project->dtable->fieldList['end']['width'] = 100;
$config->project->dtable->fieldList['end']['flex']  = 1;
$config->project->dtable->fieldList['end']['fixed'] = 'no';
$config->project->dtable->fieldList['end']['type']  = 'date';

$config->project->dtable->fieldList['teamCount']['title'] = $lang->project->teamCount;
$config->project->dtable->fieldList['teamCount']['name']  = 'teamCount';
$config->project->dtable->fieldList['teamCount']['width'] = 70;
$config->project->dtable->fieldList['teamCount']['flex']  = 1;
$config->project->dtable->fieldList['teamCount']['fixed'] = 'no';
$config->project->dtable->fieldList['teamCount']['type']  = 'count';

$config->project->dtable->fieldList['estimate']['title'] = $lang->project->estimate;
$config->project->dtable->fieldList['estimate']['name']  = 'estimate';
$config->project->dtable->fieldList['estimate']['width'] = 70;
$config->project->dtable->fieldList['estimate']['flex']  = 1;
$config->project->dtable->fieldList['estimate']['fixed'] = 'no';
$config->project->dtable->fieldList['estimate']['type']  = 'number';

$config->project->dtable->fieldList['consume']['title'] = $lang->project->consume;
$config->project->dtable->fieldList['consume']['name']  = 'consume';
$config->project->dtable->fieldList['consume']['width'] = 80;
$config->project->dtable->fieldList['consume']['flex']  = 1;
$config->project->dtable->fieldList['consume']['fixed'] = 'no';
$config->project->dtable->fieldList['consume']['type']  = 'number';

$config->project->dtable->fieldList['progress']['title'] = $lang->project->progress;
$config->project->dtable->fieldList['progress']['name']  = 'progress';
$config->project->dtable->fieldList['progress']['width'] = 65;
$config->project->dtable->fieldList['progress']['flex']  = 1;
$config->project->dtable->fieldList['progress']['fixed'] = 'no';
$config->project->dtable->fieldList['progress']['type']  = 'progress';

$config->project->dtable->fieldList['actions']['title'] = $lang->actions;
$config->project->dtable->fieldList['actions']['name']  = 'actions';
$config->project->dtable->fieldList['actions']['width'] = 165;
$config->project->dtable->fieldList['actions']['flex']  = 0;
$config->project->dtable->fieldList['actions']['fixed'] = 'right';
$config->project->dtable->fieldList['actions']['type']  = 'actions';

$config->project->dtable->fieldList['actions']['actionsMap']['start']['icon'] = 'icon-play';
$config->project->dtable->fieldList['actions']['actionsMap']['start']['hint'] = $lang->project->start;
$config->project->dtable->fieldList['actions']['actionsMap']['start']['url']  = helper::createLink('project', 'start', 'projectID={id}', '', true);

$config->project->dtable->fieldList['actions']['actionsMap']['close']['icon'] = 'icon-off';
$config->project->dtable->fieldList['actions']['actionsMap']['close']['hint'] = $lang->project->close;
$config->project->dtable->fieldList['actions']['actionsMap']['close']['url']  = helper::createLink('project', 'close', 'projectID={id}', '', true);

$config->project->dtable->fieldList['actions']['actionsMap']['active']['icon'] = 'icon-magic';
$config->project->dtable->fieldList['actions']['actionsMap']['active']['hint'] = $lang->project->activate;
$config->project->dtable->fieldList['actions']['actionsMap']['active']['url']  = helper::createLink('project', 'activate', 'projectID={id}', '', true);

$config->project->dtable->fieldList['actions']['actionsMap']['edit']['icon'] = 'icon-edit';
$config->project->dtable->fieldList['actions']['actionsMap']['edit']['hint'] = $lang->project->edit;
$config->project->dtable->fieldList['actions']['actionsMap']['edit']['url']  = helper::createLink('project', 'edit', 'projectID={id}');

$config->project->dtable->fieldList['actions']['actionsMap']['pause']['icon'] = 'icon-pause';
$config->project->dtable->fieldList['actions']['actionsMap']['pause']['hint'] = $lang->project->suspend;
$config->project->dtable->fieldList['actions']['actionsMap']['pause']['url']  = helper::createLink('project', 'suspend', 'projectID={id}', '', true);

$config->project->dtable->fieldList['actions']['actionsMap']['group']['icon'] = 'icon-group';
$config->project->dtable->fieldList['actions']['actionsMap']['group']['hint'] = $lang->project->team;
$config->project->dtable->fieldList['actions']['actionsMap']['group']['url']  = helper::createLink('project', 'team', 'projectID={id}');

$config->project->dtable->fieldList['actions']['actionsMap']['perm']['icon'] = 'icon-lock';
$config->project->dtable->fieldList['actions']['actionsMap']['perm']['hint'] = $lang->project->group;
$config->project->dtable->fieldList['actions']['actionsMap']['perm']['url']  = helper::createLink('project', 'group', 'projectID={id}');

$config->project->dtable->fieldList['actions']['actionsMap']['link']['icon'] = 'icon-link';
$config->project->dtable->fieldList['actions']['actionsMap']['link']['hint'] = $lang->project->manageProducts;
$config->project->dtable->fieldList['actions']['actionsMap']['link']['url']  = helper::createLink('project', 'manageProducts', 'projectID={id}');

$config->project->dtable->fieldList['actions']['actionsMap']['whitelist']['icon'] = 'icon-shield-check';
$config->project->dtable->fieldList['actions']['actionsMap']['whitelist']['hint'] = $lang->project->whitelist;
$config->project->dtable->fieldList['actions']['actionsMap']['whitelist']['url']  = helper::createLink('project', 'whitelist', 'projectID={id}');

$config->project->dtable->fieldList['actions']['actionsMap']['delete']['icon'] = 'icon-trash';
$config->project->dtable->fieldList['actions']['actionsMap']['delete']['hint'] = $lang->project->delete;
$config->project->dtable->fieldList['actions']['actionsMap']['delete']['url']  = helper::createLink('project', 'delete', 'projectID={id}');

if(!isset($config->setCode) or $config->setCode == 0) unset($config->project->dtable->fieldList['code']);

$config->project->checkList = new stdclass();
$config->project->checkList->scrum         = array('bug', 'execution', 'build', 'doc', 'release', 'testtask', 'case');
$config->project->checkList->waterfall     = array('execution', 'design', 'doc', 'bug', 'case', 'build', 'release', 'testtask');
$config->project->checkList->kanban        = array('execution', 'build');
$config->project->checkList->agileplus     = $config->project->checkList->scrum;
$config->project->checkList->waterfallplus = $config->project->checkList->waterfall;

$config->project->maxCheckList = new stdclass();
$config->project->maxCheckList->scrum         = array('bug', 'execution', 'build', 'doc', 'release', 'testtask', 'case', 'issue', 'risk', 'meeting');
$config->project->maxCheckList->waterfall     = array('execution', 'design', 'doc', 'bug', 'case', 'build', 'release', 'testtask', 'review', 'build', 'researchplan', 'issue', 'risk', 'opportunity', 'auditplan', 'gapanalysis', 'meeting');
$config->project->maxCheckList->kanban        = array('execution', 'build');
$config->project->maxCheckList->agileplus     = $config->project->maxCheckList->scrum;
$config->project->maxCheckList->waterfallplus = $config->project->maxCheckList->waterfall;

$config->project->search['module']                   = 'project';
$config->project->search['fields']['name']           = $lang->project->name;
$config->project->search['fields']['code']           = $lang->project->code;
$config->project->search['fields']['id']             = $lang->project->id;
$config->project->search['fields']['model']          = $lang->project->model;
$config->project->search['fields']['hasProduct']     = $lang->project->type;
$config->project->search['fields']['parent']         = $lang->project->parent;
$config->project->search['fields']['status']         = $lang->project->status;
$config->project->search['fields']['desc']           = $lang->project->desc;
$config->project->search['fields']['PM']             = $lang->project->PM;
$config->project->search['fields']['openedDate']     = $lang->project->openedDate;
$config->project->search['fields']['begin']          = $lang->project->begin;
$config->project->search['fields']['end']            = $lang->project->end;
$config->project->search['fields']['realBegan']      = $lang->project->realBeganAB;
$config->project->search['fields']['realEnd']        = $lang->project->realEndAB;
$config->project->search['fields']['openedBy']       = $lang->project->openedBy;
$config->project->search['fields']['closedBy']       = $lang->project->closedBy;
$config->project->search['fields']['lastEditedDate'] = $lang->project->lastEditedDate;
$config->project->search['fields']['closedDate']     = $lang->project->closedDate;

$config->project->search['params']['name']           = array('operator' => 'include', 'control' => 'input' , 'values' => '');
$config->project->search['params']['code']           = array('operator' => '='      , 'control' => 'input' , 'values' => '');
$config->project->search['params']['id']             = array('operator' => '='      , 'control' => 'input' , 'values' => '');
$config->project->search['params']['model']          = array('operator' => '='      , 'control' => 'select', 'values' => $lang->project->modelList);
$config->project->search['params']['hasProduct']     = array('operator' => '='      , 'control' => 'select', 'values' => array('' => '') + $lang->project->projectTypeList);
$config->project->search['params']['parent']         = array('operator' => '='      , 'control' => 'select', 'values' => '');
$config->project->search['params']['status']         = array('operator' => '='      , 'control' => 'select', 'values' => $lang->project->statusList);
$config->project->search['params']['desc']           = array('operator' => 'include', 'control' => 'input' , 'values' => '');
$config->project->search['params']['PM']             = array('operator' => '='      , 'control' => 'select', 'values' => 'users');
$config->project->search['params']['openedDate']     = array('operator' => '='      , 'control' => 'input' , 'values' => '', 'class' => 'date');
$config->project->search['params']['begin']          = array('operator' => '='      , 'control' => 'input' , 'values' => '', 'class' => 'date');
$config->project->search['params']['end']            = array('operator' => '='      , 'control' => 'input' , 'values' => '', 'class' => 'date');
$config->project->search['params']['realBegan']      = array('operator' => '='      , 'control' => 'input' , 'values' => '', 'class' => 'date');
$config->project->search['params']['realEnd']        = array('operator' => '='      , 'control' => 'input' , 'values' => '', 'class' => 'date');
$config->project->search['params']['openedBy']       = array('operator' => '='      , 'control' => 'select', 'values' => 'users');
$config->project->search['params']['closedBy']       = array('operator' => '='      , 'control' => 'select', 'values' => 'users');
$config->project->search['params']['lastEditedDate'] = array('operator' => '='      , 'control' => 'input' , 'values' => '', 'class' => 'date');
$config->project->search['params']['closedDate']     = array('operator' => '='      , 'control' => 'input' , 'values' => '', 'class' => 'date');

$config->project->noSprintPriv['project']    = array('edit', 'group', 'createGroup', 'managePriv', 'manageMembers', 'manageGroupMember', 'copyGroup', 'editGroup', 'start', 'suspend', 'close', 'activate', 'delete', 'view', 'whitelist', 'addWhitelist', 'unbindWhitelist', 'manageProducts', 'dynamic', 'bug', 'testcase', 'testtask', 'testreport', 'team', 'unlinkMember');
$config->project->noSprintPriv['execution']  = array('task', 'grouptask', 'importplanstories', 'importBug', 'story', 'burn', 'computeBurn', 'fixFirst', 'burnData', 'linkStory', 'unlinkStory', 'batchUnlinkStory', 'updateOrder', 'taskKanban', 'printKanban', 'tree', 'treeTask', 'treeStory', 'storyKanban', 'storySort', 'storyEstimate', 'setKanban', 'storyView', 'calendar', 'effortCalendar', 'effort', 'taskEffort', 'computeTaskEffort', 'deleterelation', 'maintainrelation', 'relation', 'gantt', 'ganttsetting', 'ganttEdit');
$config->project->noSprintPriv['story']      = array('create', 'batchCreate', 'edit', 'export', 'delete', 'view', 'change', 'review', 'batchReview', 'recall', 'close', 'batchClose', 'batchChangePlan', 'batchChangeStage', 'assignTo', 'batchAssignTo', 'activate', 'zeroCase', 'batchEdit', 'import', 'showImport', 'exportTemplate', 'importToLib', 'batchImportToLib', 'relation', 'browse');
$config->project->noSprintPriv['bug']        = array('create', 'confirmBug', 'view', 'edit', 'assignTo', 'batchAssignTo', 'resolve', 'activate', 'close', 'export', 'confirmStoryChange', 'delete', 'linkBugs', 'import', 'showImport', 'exportTemplate');
$config->project->noSprintPriv['testcase']   = array('groupCase', 'create', 'batchCreate', 'createBug', 'view', 'edit', 'delete', 'export', 'confirmChange', 'confirmStoryChange', 'batchEdit', 'batchDelete', 'linkCases', 'bugs', 'review', 'batchReview', 'batchConfirmStoryChange', 'importFromLib', 'batchCaseTypeChange', 'exportTemplate', 'import', 'showImport', 'confirmLibcaseChange', 'ignoreLibcaseChange', 'submit');
$config->project->noSprintPriv['testtask']   = array('create', 'cases', 'groupCase', 'edit', 'delete', 'batchAssign', 'linkcase', 'unlinkcase', 'runcase', 'results', 'batchUnlinkCases', 'report', 'browseUnits', 'unitCases', 'importUnitResult', 'batchRun', 'runDeployCase', 'deployCaseResults');
$config->project->includedPriv['doc']        = array('createLib', 'editLib', 'deleteLib', 'create', 'edit', 'view', 'delete', 'deleteFile', 'collect', 'projectSpace', 'showFiles', 'addCatalog', 'editCatalog', 'deleteCatalog', 'displaySetting', 'diff', 'importToPracticeLib', 'importToComponentLib');
$config->project->noSprintPriv['repo']       = array('create', 'showSyncCommit', 'browse', 'view', 'diff', 'log', 'revision', 'blame', 'download', 'apiGetRepoByUrl', 'review', 'addBug', 'editBug', 'deleteBug', 'addComment', 'editComment', 'deleteComment');
$config->project->noSprintPriv['testreport'] = array('create', 'view', 'delete', 'edit', 'export');
$config->project->noSprintPriv['auditplan']  = array('browse', 'create', 'edit', 'batchCreate', 'batchCheck', 'check', 'nc', 'result', 'assignTo');

$config->project->includedPriv = $config->project->noSprintPriv;
$config->project->includedPriv['project'][]  = 'execution';
$config->project->includedPriv['task']       = array('create');
$config->project->includedPriv['story']      = array('create', 'batchCreate', 'edit', 'export', 'delete', 'view', 'change', 'review', 'batchReview', 'recall', 'close', 'batchClose', 'batchChangePlan', 'batchChangeStage', 'assignTo', 'batchAssignTo', 'activate', 'zeroCase', 'batchEdit', 'import', 'showImport', 'exportTemplate', 'importToLib', 'batchImportToLib', 'relation', 'browse');
$config->project->includedPriv['bug']        = array('create', 'confirmBug', 'view', 'edit', 'assignTo', 'batchAssignTo', 'resolve', 'activate', 'close', 'export', 'confirmStoryChange', 'delete', 'linkBugs', 'import', 'showImport', 'exportTemplate');
$config->project->includedPriv['testcase']   = array('groupCase', 'create', 'batchCreate', 'createBug', 'view', 'edit', 'delete', 'export', 'confirmChange', 'confirmStoryChange', 'batchEdit', 'batchDelete', 'linkCases', 'bugs', 'review', 'batchReview', 'batchConfirmStoryChange', 'importFromLib', 'batchCaseTypeChange', 'exportTemplate', 'import', 'showImport', 'confirmLibcaseChange', 'ignoreLibcaseChange', 'submit');
$config->project->includedPriv['testtask']   = array('create', 'cases', 'groupCase', 'edit', 'delete', 'batchAssign', 'linkcase', 'unlinkcase', 'runcase', 'results', 'batchUnlinkCases', 'report', 'browseUnits', 'unitCases', 'importUnitResult', 'batchRun', 'runDeployCase', 'deployCaseResults');
$config->project->includedPriv['doc']        = array('createLib', 'editLib', 'deleteLib', 'create', 'edit', 'view', 'delete', 'deleteFile', 'collect', 'projectSpace', 'showFiles', 'addCatalog', 'editCatalog', 'deleteCatalog', 'displaySetting', 'diff', 'importToPracticeLib', 'importToComponentLib');
$config->project->includedPriv['repo']       = array('create', 'showSyncCommit', 'browse', 'view', 'diff', 'log', 'revision', 'blame', 'download', 'apiGetRepoByUrl', 'review', 'addBug', 'deleteBug', 'addComment', 'editComment', 'deleteComment');
$config->project->includedPriv['testreport'] = array('create', 'view', 'delete', 'edit', 'export');
$config->project->includedPriv['auditplan']  = array('browse', 'create', 'edit', 'batchCreate', 'batchCheck', 'check', 'nc', 'result', 'assignTo');
$config->project->includedPriv['execution']  = array('create', 'start', 'delete', 'calendar', 'effortCalendar', 'effort', 'taskEffort', 'computeTaskEffort', 'deleterelation', 'maintainrelation', 'relation', 'gantt');
if($config->edition != 'max') $config->project->includedPriv['stakeholder'] = array('browse', 'create', 'batchCreate', 'edit', 'delete', 'view', 'communicate', 'expect', 'expectation', 'deleteExpect', 'createExpect', 'editExpect', 'viewExpect');

$config->project->browseTable = new stdClass();
$config->project->browseTable->cols = array();

$config->project->browseTable->cols['name']['name']         = 'name';
$config->project->browseTable->cols['name']['title']        = $lang->project->name;
$config->project->browseTable->cols['name']['fixed']        = 'left';
$config->project->browseTable->cols['name']['width']        = 408;
$config->project->browseTable->cols['name']['sortType']     = true;
$config->project->browseTable->cols['name']['link']         = helper::createLink('project', 'index', 'projectID={id}');

$config->project->browseTable->cols['PM']['name']     = 'PM';
$config->project->browseTable->cols['PM']['title']    = $lang->project->PM;
$config->project->browseTable->cols['PM']['minWidth'] = 104;
$config->project->browseTable->cols['PM']['type']     = 'avatarBtn';
$config->project->browseTable->cols['PM']['flex']     = 1;
$config->project->browseTable->cols['PM']['border']   = 'right';

$config->project->browseTable->cols['storyCount']['name']     = 'storyCount';
$config->project->browseTable->cols['storyCount']['title']    = $lang->project->storyCount;
$config->project->browseTable->cols['storyCount']['minWidth'] = 94;
$config->project->browseTable->cols['storyCount']['sortType'] = true;
$config->project->browseTable->cols['storyCount']['type']     = 'format';
$config->project->browseTable->cols['storyCount']['align']    = 'right';

$config->project->browseTable->cols['executionCount']['name']     = 'executionCount';
$config->project->browseTable->cols['executionCount']['title']    = $lang->project->executionCount;
$config->project->browseTable->cols['executionCount']['minWidth'] = 94;
$config->project->browseTable->cols['executionCount']['sortType'] = true;
$config->project->browseTable->cols['executionCount']['type']     = 'format';
$config->project->browseTable->cols['executionCount']['border']   = 'right';
$config->project->browseTable->cols['executionCount']['align']    = 'center';

$config->project->browseTable->cols['invested']['name']     = 'invested';
$config->project->browseTable->cols['invested']['title']    = $lang->project->invested;
$config->project->browseTable->cols['invested']['minWidth'] = 94;
$config->project->browseTable->cols['invested']['sortType'] = true;
$config->project->browseTable->cols['invested']['type']     = 'format';
$config->project->browseTable->cols['invested']['border']   = 'right';
$config->project->browseTable->cols['invested']['align']    = 'center';

$config->project->browseTable->cols['begin']['name']     = 'begin';
$config->project->browseTable->cols['begin']['title']    = $lang->project->begin;
$config->project->browseTable->cols['begin']['width']    = 96;
$config->project->browseTable->cols['begin']['sortType'] = true;

$config->project->browseTable->cols['end']['name']     = 'end';
$config->project->browseTable->cols['end']['title']    = $lang->project->end;
$config->project->browseTable->cols['end']['width']    = 96;
$config->project->browseTable->cols['end']['sortType'] = true;

$config->project->browseTable->cols['progress']['name']     = 'progress';
$config->project->browseTable->cols['progress']['title']    = $lang->project->progress;
$config->project->browseTable->cols['progress']['width']    = 92;
$config->project->browseTable->cols['progress']['type']     = 'circleProgress';
$config->project->browseTable->cols['progress']['sortType'] = true;

$config->project->browseTable->cols['actions']['name']       = 'actions';
$config->project->browseTable->cols['actions']['title']      = $lang->actions;
$config->project->browseTable->cols['actions']['fixed']      = 'right';
$config->project->browseTable->cols['actions']['width']      = 160;
$config->project->browseTable->cols['actions']['type']       = 'actions';
$config->project->browseTable->cols['actions']['actionsMap'] = array(
    'start'     => array('icon'=> 'icon-start',        'hint'=> $lang->project->start),
    'close'     => array('icon'=> 'icon-off',          'hint'=> $lang->project->close, 'data-toggle' => 'modal', 'url' => helper::createLink('project', 'close', 'projectID={id}')),
    'pause'     => array('icon'=> 'icon-pause',        'text'=> $lang->project->suspend),
    'active'    => array('icon'=> 'icon-magic',        'text'=> $lang->project->activate),
    'edit'      => array('icon'=> 'icon-edit',         'hint'=> $lang->project->edit),
    'group'     => array('icon'=> 'icon-group',        'hint'=> $lang->project->teamMember),
    'perm'      => array('icon'=> 'icon-lock',         'hint'=> $lang->project->group),
    'delete'    => array('icon'=> 'icon-trash',        'hint'=> $lang->delete,                  'text'  => $lang->delete),
    'other'     => array('type'=> 'dropdown',          'hint'=> $lang->project->other,          'caret' => true),
    'link'      => array('icon'=> 'icon-link',         'text'=> $lang->project->manageProducts, 'name'  => 'link'),
    'more'      => array('icon'=> 'icon-ellipsis-v',   'hint'=> $lang->more,                    'type'  => 'dropdown', 'caret' => false),
    'whitelist' => array('icon'=> 'icon-shield-check', 'text'=> $lang->project->whitelist,      'name'  => 'whitelist')
);

$config->project->linkMap = new stdclass();

$config->project->linkMap->project = array();
$config->project->linkMap->project['execution']      = array('', '', 'status=all&projectID=%s', '');
$config->project->linkMap->project['bug']            = array('', '', 'projectID=%s', '');
$config->project->linkMap->project['testcase']       = array('', '', 'projectID=%s', '');
$config->project->linkMap->project['testtask']       = array('', '', 'projectID=%s', '');
$config->project->linkMap->project['testreport']     = array('', '', 'projectID=%s', '');
$config->project->linkMap->project['build']          = array('', '', 'projectID=%s', '');
$config->project->linkMap->project['view']           = array('', '', 'projectID=%s', '');
$config->project->linkMap->project['dynamic']        = array('', '', 'projectID=%s', '');
$config->project->linkMap->project['manageproducts'] = array('', '', 'projectID=%s', '');
$config->project->linkMap->project['team']           = array('', '', 'projectID=%s', '');
$config->project->linkMap->project['managemembers']  = array('', '', 'projectID=%s', '');
$config->project->linkMap->project['whitelist']      = array('', '', 'projectID=%s', '');
$config->project->linkMap->project['addwhitelist']   = array('', '', 'projectID=%s', '');
$config->project->linkMap->project['group']          = array('', '', 'projectID=%s', '');
$config->project->linkMap->project['managePriv']     = array('project', 'group', 'projectID=%s', '');

$config->project->linkMap->product = array();
$config->project->linkMap->product['showerrornone'] = array('projectstory', 'story', 'projectID=%s', '');

$config->project->linkMap->projectstory = array();
$config->project->linkMap->projectstory['story']     = array('', '', 'projectID=%s', '');
$config->project->linkMap->projectstory['linkstory'] = array('', '', 'projectID=%s', '');
$config->project->linkMap->projectstory['track']     = array('', '', 'projectID=%s', '');

$config->project->linkMap->bug = array();
$config->project->linkMap->bug['create'] = array('', '', 'productID=0&branch=0&extras=projectID=%s', '');
$config->project->linkMap->bug['edit']   = array('project', 'bug', 'projectID=%s', '');

$config->project->linkMap->story = array();
$config->project->linkMap->story['change']   = array('projectstory', 'story', 'projectID=%s', '');
$config->project->linkMap->story['create']   = array('projectstory', 'story', 'projectID=%s', '');
$config->project->linkMap->story['zerocase'] = array('project', 'testcase', 'projectID=%s', '');

$config->project->linkMap->testcase = array();
$config->project->linkMap->testcase[''] = array('project', 'testcase', 'projectID=%s', '');

$config->project->linkMap->testtask = array();
$config->project->linkMap->testtask['browseunits'] = array('project', 'testcase', 'projectID=%s', '');
$config->project->linkMap->testtask['']            = array('project', 'testtask', 'projectID=%s', '');

$config->project->linkMap->testreport = array();
$config->project->linkMap->testreport[''] = array('project', 'testreport', 'projectID=%s', '');

$config->project->linkMap->repo = array();
$config->project->linkMap->repo[''] = array('repo', 'browse', 'repoID=&branchID=&objectID=%s', '#app=project');

$config->project->linkMap->doc = array();
$config->project->linkMap->doc[''] = array('doc', 'projectSpace', 'objectID=%s', '#app=project');

$config->project->linkMap->build = array();
$config->project->linkMap->build['create'] = array('', '', 'executionID=&productID=&projectID=%s', '#app=project');

$config->project->linkMap->projectrelease = array();
$config->project->linkMap->projectrelease['create'] = array('', '', 'projectID=%s', '');
$config->project->linkMap->projectrelease['']       = array('projectrelease', 'browse', 'projectID=%s', '');

$config->project->linkMap->stakeholder = array();
$config->project->linkMap->stakeholder['create'] = array('', '', 'projectID=%s', '');
$config->project->linkMap->stakeholder['']       = array('stakeholder', 'browse', 'projectID=%s', '');

$config->project->linkMap->issue = array();
$config->project->linkMap->issue['projectsummary'] = array('', '', 'projectID=%s', '#app=project');
$config->project->linkMap->issue['']               = array('issue', 'browse', 'projectID=%s', '');

$config->project->linkMap->risk = array();
$config->project->linkMap->risk['projectsummary'] = array('', '', 'projectID=%s', '#app=project');
$config->project->linkMap->risk['']               = array('issue', 'browse', 'projectID=%s', '');

$config->project->linkMap->meeting = array();
$config->project->linkMap->meeting['projectsummary'] = array('', '', 'projectID=%s', '#app=project');
$config->project->linkMap->meeting['']               = array('issue', 'browse', 'projectID=%s', '');

$config->project->linkMap->report = array();
$config->project->linkMap->report['projectsummary'] = array('', '', 'projectID=%s', '#app=project');
$config->project->linkMap->report['']               = array('issue', 'browse', 'projectID=%s', '');

$config->project->linkMap->measrecord = array();
$config->project->linkMap->measrecord['projectsummary'] = array('', '', 'projectID=%s', '#app=project');
$config->project->linkMap->measrecord['']               = array('issue', 'browse', 'projectID=%s', '');

$config->project->linkMap->reviewissue = array();
$config->project->linkMap->reviewissue[''] = array('reviewissue', 'issue', 'projectID=%s', '');

$config->project->linkMap->cm = array();
$config->project->linkMap->cm['report'] = array('cm', 'report', 'projectID=%s', '');

$config->project->linkMap->weekly = array();
$config->project->linkMap->weekly['index'] = array('weekly', 'index', 'projectID=%s', '');

$config->project->linkMap->milestone = array();
$config->project->linkMap->milestone['index'] = array('milestone', 'index', 'projectID=%s', '');

$config->project->linkMap->workestimation = array();
$config->project->linkMap->workestimation['index'] = array('workestimation', 'index', 'projectID=%s', '');

$config->project->linkMap->durationestimation = array();
$config->project->linkMap->durationestimation['index'] = array('durationestimation', 'index', 'projectID=%s', '');

$config->project->linkMap->budget = array();
$config->project->linkMap->budget['summary'] = array('budget', 'summary', 'projectID=%s', '');

$config->project->linkMap->programplan = array();
$config->project->linkMap->programplan[''] = array('project', 'execution', 'type=all&projectID=%s', '');

$config->project->budget = new stdclass();
$config->project->budget->tenThousand       = 10000;
$config->project->budget->oneHundredMillion = 100000000;
$config->project->budget->precision    = 2;


include 'config/form.php';
