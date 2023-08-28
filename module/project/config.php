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
$config->project->datatable = new stdclass();
$config->project->datatable->defaultField = array('id', 'name', 'status', 'PM', 'budget', 'begin', 'end', 'progress', 'actions');

$config->project->datatable->fieldList['id']['title']    = 'ID';
$config->project->datatable->fieldList['id']['fixed']    = 'left';
$config->project->datatable->fieldList['id']['width']    = '60';
$config->project->datatable->fieldList['id']['required'] = 'yes';
$config->project->datatable->fieldList['id']['pri']      = '1';

$config->project->datatable->fieldList['name']['title']    = 'name';
$config->project->datatable->fieldList['name']['fixed']    = 'left';
$config->project->datatable->fieldList['name']['width']    = 'auto';
$config->project->datatable->fieldList['name']['minWidth'] = '180';
$config->project->datatable->fieldList['name']['required'] = 'yes';
$config->project->datatable->fieldList['name']['sort']     = 'no';
$config->project->datatable->fieldList['name']['pri']      = '1';

$config->project->datatable->fieldList['code']['title']    = 'code';
$config->project->datatable->fieldList['code']['fixed']    = 'left';
$config->project->datatable->fieldList['code']['width']    = '100';
$config->project->datatable->fieldList['code']['minWidth'] = '180';
$config->project->datatable->fieldList['code']['required'] = 'no';
$config->project->datatable->fieldList['code']['sort']     = 'no';
$config->project->datatable->fieldList['code']['pri']      = '1';

$config->project->datatable->fieldList['PM']['title']    = 'PM';
$config->project->datatable->fieldList['PM']['fixed']    = 'no';
$config->project->datatable->fieldList['PM']['width']    = '80';
$config->project->datatable->fieldList['PM']['required'] = 'yes';
$config->project->datatable->fieldList['PM']['sort']     = 'no';
$config->project->datatable->fieldList['PM']['pri']      = '2';

$config->project->datatable->fieldList['status']['title']    = 'status';
$config->project->datatable->fieldList['status']['fixed']    = 'left';
$config->project->datatable->fieldList['status']['width']    = '75';
$config->project->datatable->fieldList['status']['required'] = 'no';
$config->project->datatable->fieldList['status']['sort']     = 'yes';
$config->project->datatable->fieldList['status']['pri']      = '2';

$config->project->datatable->fieldList['hasProduct']['title']    = 'type';
$config->project->datatable->fieldList['hasProduct']['fixed']    = 'left';
$config->project->datatable->fieldList['hasProduct']['width']    = '100';
$config->project->datatable->fieldList['hasProduct']['required'] = 'no';
$config->project->datatable->fieldList['hasProduct']['sort']     = 'yes';
$config->project->datatable->fieldList['hasProduct']['pri']      = '2';

$config->project->datatable->fieldList['budget']['title']    = 'budget';
$config->project->datatable->fieldList['budget']['fixed']    = 'no';
$config->project->datatable->fieldList['budget']['width']    = '100';
$config->project->datatable->fieldList['budget']['required'] = 'yes';
$config->project->datatable->fieldList['budget']['pri']      = '3';

$config->project->datatable->fieldList['begin']['title']    = 'begin';
$config->project->datatable->fieldList['begin']['fixed']    = 'no';
$config->project->datatable->fieldList['begin']['width']    = '115';
$config->project->datatable->fieldList['begin']['required'] = 'no';
$config->project->datatable->fieldList['begin']['pri']      = '9';

$config->project->datatable->fieldList['end']['title']    = 'end';
$config->project->datatable->fieldList['end']['fixed']    = 'no';
$config->project->datatable->fieldList['end']['width']    = '100';
$config->project->datatable->fieldList['end']['required'] = 'no';
$config->project->datatable->fieldList['end']['pri']      = '3';

$config->project->datatable->fieldList['teamCount']['title']    = 'teamCount';
$config->project->datatable->fieldList['teamCount']['fixed']    = 'no';
$config->project->datatable->fieldList['teamCount']['width']    = '70';
$config->project->datatable->fieldList['teamCount']['required'] = 'no';
$config->project->datatable->fieldList['teamCount']['sort']     = 'no';
$config->project->datatable->fieldList['teamCount']['pri']      = '8';

$config->project->datatable->fieldList['estimate']['title']    = 'estimate';
$config->project->datatable->fieldList['estimate']['fixed']    = 'no';
$config->project->datatable->fieldList['estimate']['width']    = '70';
$config->project->datatable->fieldList['estimate']['maxWidth'] = '80';
$config->project->datatable->fieldList['estimate']['required'] = 'no';
$config->project->datatable->fieldList['estimate']['sort']     = 'no';
$config->project->datatable->fieldList['estimate']['pri']      = '8';

$config->project->datatable->fieldList['consume']['title']    = 'consume';
$config->project->datatable->fieldList['consume']['fixed']    = 'no';
$config->project->datatable->fieldList['consume']['width']    = '80';
$config->project->datatable->fieldList['consume']['maxWidth'] = '80';
$config->project->datatable->fieldList['consume']['required'] = 'no';
$config->project->datatable->fieldList['consume']['sort']     = 'no';
$config->project->datatable->fieldList['consume']['pri']      = '7';

$config->project->datatable->fieldList['progress']['title']    = 'progress';
$config->project->datatable->fieldList['progress']['fixed']    = 'no';
$config->project->datatable->fieldList['progress']['width']    = '65';
$config->project->datatable->fieldList['progress']['required'] = 'no';
$config->project->datatable->fieldList['progress']['sort']     = 'no';
$config->project->datatable->fieldList['progress']['pri']      = '6';

$config->project->datatable->fieldList['actions']['title']    = 'actions';
$config->project->datatable->fieldList['actions']['fixed']    = 'right';
$config->project->datatable->fieldList['actions']['width']    = '165';
$config->project->datatable->fieldList['actions']['required'] = 'yes';
$config->project->datatable->fieldList['actions']['pri']      = '1';

if(!isset($config->setCode) or $config->setCode == 0) unset($config->project->datatable->fieldList['code']);

$config->project->checkList = new stdclass();
$config->project->checkList->scrum         = array('bug', 'execution', 'build', 'doc', 'release', 'testtask', 'case');
$config->project->checkList->waterfall     = array('execution', 'design', 'doc', 'bug', 'case', 'build', 'release', 'testtask');
$config->project->checkList->kanban        = array('execution', 'build');
$config->project->checkList->agileplus     = $config->project->checkList->scrum;
$config->project->checkList->waterfallplus = $config->project->checkList->waterfall;
$config->project->checkList->ipd           = $config->project->checkList->waterfall;

$config->project->maxCheckList = new stdclass();
$config->project->maxCheckList->scrum         = array('bug', 'execution', 'build', 'doc', 'release', 'testtask', 'case', 'issue', 'risk', 'meeting');
$config->project->maxCheckList->waterfall     = array('execution', 'design', 'doc', 'bug', 'case', 'build', 'release', 'testtask', 'review', 'build', 'researchplan', 'issue', 'risk', 'opportunity', 'auditplan', 'gapanalysis', 'meeting');
$config->project->maxCheckList->kanban        = array('execution', 'build');
$config->project->maxCheckList->agileplus     = $config->project->maxCheckList->scrum;
$config->project->maxCheckList->waterfallplus = $config->project->maxCheckList->waterfall;
$config->project->maxCheckList->ipd           = $config->project->maxCheckList->waterfall;

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
if($config->edition != 'max') $config->project->includedPriv['stakeholder'] = array('browse', 'create', 'batchCreate', 'edit', 'delete', 'view', 'communicate', 'expect');
