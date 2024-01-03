<?php
global $lang;
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

$config->project->create    = new stdclass();
$config->project->edit      = new stdclass();
$config->project->batchedit = new stdclass();
$config->project->create->requiredFields    = 'name,code,begin,end';
$config->project->edit->requiredFields      = 'name,code,begin,end';
$config->project->batchedit->requiredFields = 'name,code,begin,end';

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

$config->project->labelClass['scrum']         = 'secondary-outline';
$config->project->labelClass['waterfall']     = 'warning-outline';
$config->project->labelClass['kanban']        = 'special-outline';
$config->project->labelClass['agileplus']     = 'secondary-outline';
$config->project->labelClass['waterfallplus'] = 'warning-outline';

$config->project->multiple['project']   = ',qa,devops,doc,build,release,dynamic,settings,';
$config->project->multiple['execution'] = ',task,kanban,burn,view,story,CFD,';

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

$config->project->scrumList     = array('scrum', 'agileplus');
$config->project->waterfallList = array('waterfall', 'waterfallplus');

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
$config->project->search['params']['hasProduct']     = array('operator' => '='      , 'control' => 'select', 'values' => $lang->project->projectTypeList);
$config->project->search['params']['parent']         = array('operator' => '='      , 'control' => 'select', 'values' => '');
$config->project->search['params']['status']         = array('operator' => '='      , 'control' => 'select', 'values' => $lang->project->statusList);
$config->project->search['params']['desc']           = array('operator' => 'include', 'control' => 'input' , 'values' => '');
$config->project->search['params']['PM']             = array('operator' => '='      , 'control' => 'select', 'values' => 'users');
$config->project->search['params']['openedDate']     = array('operator' => '='      , 'control' => 'date',  'values' => '');
$config->project->search['params']['begin']          = array('operator' => '='      , 'control' => 'date',  'values' => '');
$config->project->search['params']['end']            = array('operator' => '='      , 'control' => 'date',  'values' => '');
$config->project->search['params']['realBegan']      = array('operator' => '='      , 'control' => 'date',  'values' => '');
$config->project->search['params']['realEnd']        = array('operator' => '='      , 'control' => 'date',  'values' => '');
$config->project->search['params']['openedBy']       = array('operator' => '='      , 'control' => 'select', 'values' => 'users');
$config->project->search['params']['closedBy']       = array('operator' => '='      , 'control' => 'select', 'values' => 'users');
$config->project->search['params']['lastEditedDate'] = array('operator' => '='      , 'control' => 'date',  'values' => '');
$config->project->search['params']['closedDate']     = array('operator' => '='      , 'control' => 'date',  'values' => '');

$config->project->noSprintPriv['project']    = array('edit', 'group', 'createGroup', 'managePriv', 'manageMembers', 'manageGroupMember', 'copyGroup', 'editGroup', 'start', 'suspend', 'close', 'activate', 'delete', 'view', 'whitelist', 'addWhitelist', 'unbindWhitelist', 'manageProducts', 'dynamic', 'bug', 'testcase', 'testtask', 'testreport', 'team', 'unlinkMember');
$config->project->noSprintPriv['execution']  = array('task', 'grouptask', 'importplanstories', 'importBug', 'story', 'burn', 'computeBurn', 'fixFirst', 'burnData', 'linkStory', 'unlinkStory', 'batchUnlinkStory', 'updateOrder', 'taskKanban', 'printKanban', 'tree', 'treeTask', 'treeStory', 'storyKanban', 'storySort', 'storyEstimate', 'setKanban', 'storyView', 'calendar', 'effortCalendar', 'effort', 'taskEffort', 'computeTaskEffort', 'deleterelation', 'maintainrelation', 'relation', 'gantt', 'ganttsetting', 'ganttEdit');
$config->project->noSprintPriv['story']      = array('create', 'batchCreate', 'edit', 'export', 'delete', 'view', 'change', 'review', 'batchReview', 'recall', 'close', 'batchClose', 'batchChangePlan', 'batchChangeStage', 'assignTo', 'batchAssignTo', 'activate', 'zeroCase', 'batchEdit', 'import', 'showImport', 'exportTemplate', 'importToLib', 'batchImportToLib', 'relation', 'browse');
$config->project->noSprintPriv['bug']        = array('create', 'confirm', 'view', 'edit', 'assignTo', 'batchAssignTo', 'resolve', 'activate', 'close', 'export', 'confirmStoryChange', 'delete', 'linkBugs', 'import', 'showImport', 'exportTemplate');
$config->project->noSprintPriv['testcase']   = array('groupCase', 'create', 'batchCreate', 'createBug', 'view', 'edit', 'delete', 'export', 'confirmChange', 'confirmStoryChange', 'batchEdit', 'batchDelete', 'linkCases', 'bugs', 'review', 'batchReview', 'batchConfirmStoryChange', 'importFromLib', 'batchChangeType', 'exportTemplate', 'import', 'showImport', 'confirmLibcaseChange', 'ignoreLibcaseChange', 'submit');
$config->project->noSprintPriv['testtask']   = array('create', 'cases', 'groupCase', 'edit', 'delete', 'batchAssign', 'linkcase', 'unlinkcase', 'runcase', 'results', 'batchUnlinkCases', 'report', 'browseUnits', 'unitCases', 'importUnitResult', 'batchRun', 'runDeployCase', 'deployCaseResults');
$config->project->includedPriv['doc']        = array('createLib', 'editLib', 'deleteLib', 'create', 'edit', 'view', 'delete', 'deleteFile', 'collect', 'projectSpace', 'showFiles', 'addCatalog', 'editCatalog', 'deleteCatalog', 'displaySetting', 'diff', 'importToPracticeLib', 'importToComponentLib');
$config->project->noSprintPriv['repo']       = array('create', 'showSyncCommit', 'browse', 'view', 'diff', 'log', 'revision', 'blame', 'download', 'apiGetRepoByUrl', 'review', 'addBug', 'editBug', 'deleteBug', 'addComment', 'editComment', 'deleteComment');
$config->project->noSprintPriv['testreport'] = array('create', 'view', 'delete', 'edit', 'export');
$config->project->noSprintPriv['auditplan']  = array('browse', 'create', 'edit', 'batchCreate', 'batchCheck', 'check', 'nc', 'result', 'assignTo');

$config->project->includedPriv = $config->project->noSprintPriv;
$config->project->includedPriv['project'][]  = 'execution';
$config->project->includedPriv['task']       = array('create');
$config->project->includedPriv['story']      = array('create', 'batchCreate', 'edit', 'export', 'delete', 'view', 'change', 'review', 'batchReview', 'recall', 'close', 'batchClose', 'batchChangePlan', 'batchChangeStage', 'assignTo', 'batchAssignTo', 'activate', 'zeroCase', 'batchEdit', 'import', 'showImport', 'exportTemplate', 'importToLib', 'batchImportToLib', 'relation', 'browse');
$config->project->includedPriv['bug']        = array('create', 'confirm', 'view', 'edit', 'assignTo', 'batchAssignTo', 'resolve', 'activate', 'close', 'export', 'confirmStoryChange', 'delete', 'linkBugs', 'import', 'showImport', 'exportTemplate');
$config->project->includedPriv['testcase']   = array('groupCase', 'create', 'batchCreate', 'createBug', 'view', 'edit', 'delete', 'export', 'confirmChange', 'confirmStoryChange', 'batchEdit', 'batchDelete', 'linkCases', 'bugs', 'review', 'batchReview', 'batchConfirmStoryChange', 'importFromLib', 'batchChangeType', 'exportTemplate', 'import', 'showImport', 'confirmLibcaseChange', 'ignoreLibcaseChange', 'submit');
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
$config->project->budget->precision         = 2;
$config->project->budget->tenThousand       = 10000;
$config->project->budget->oneHundredMillion = 100000000;

$config->project->team = new stdclass();
$config->project->team->actionList['unlink']['icon'] = 'unlink';
$config->project->team->actionList['unlink']['hint'] = $lang->project->unlinkMember;
$config->project->team->actionList['unlink']['url']  = 'javascript:deleteMember("{root}", "{account}", "{userID}")';

$config->project->actionList = array();
$config->project->actionList['start']['icon']        = 'play';
$config->project->actionList['start']['hint']        = $lang->project->start;
$config->project->actionList['start']['url']         = helper::createLink('project', 'start', 'projectID={id}');
$config->project->actionList['start']['data-toggle'] = 'modal';

$config->project->actionList['close']['icon']        = 'off';
$config->project->actionList['close']['hint']        = $lang->project->close;
$config->project->actionList['close']['url']         = helper::createLink('project', 'close', 'projectID={id}');
$config->project->actionList['close']['data-toggle'] = 'modal';

$config->project->actionList['activate']['icon']        = 'magic';
$config->project->actionList['activate']['hint']        = $lang->project->activate;
$config->project->actionList['activate']['url']         = helper::createLink('project', 'activate', 'projectID={id}');
$config->project->actionList['activate']['data-toggle'] = 'modal';

$config->project->actionList['edit']['icon'] = 'edit';
$config->project->actionList['edit']['hint'] = $lang->project->edit;
$config->project->actionList['edit']['url']  = array('module' => 'project', 'method' => 'edit', 'params' => 'projectID={id}');

$config->project->actionList['suspend']['icon']        = 'pause';
$config->project->actionList['suspend']['hint']        = $lang->project->suspend;
$config->project->actionList['suspend']['url']         = helper::createLink('project', 'suspend', 'projectID={id}');
$config->project->actionList['suspend']['data-toggle'] = 'modal';

$config->project->actionList['group']['icon'] = 'group';
$config->project->actionList['group']['hint'] = $lang->project->team;
$config->project->actionList['group']['url']  = array('module' => 'project', 'method' => 'team', 'params' => 'projectID={id}');

$config->project->actionList['perm']['icon'] = 'lock';
$config->project->actionList['perm']['hint'] = $lang->project->group;
$config->project->actionList['perm']['url']  = array('module' => 'project', 'method' => 'group', 'params' => 'projectID={id}');

$config->project->actionList['link']['icon'] = 'link';
$config->project->actionList['link']['hint'] = $lang->project->manageProducts;
$config->project->actionList['link']['url']  = array('module' => 'project', 'method' => 'manageProducts', 'params' => 'projectID={id}');

$config->project->actionList['whitelist']['icon'] = 'shield-check';
$config->project->actionList['whitelist']['hint'] = $lang->project->whitelist;
$config->project->actionList['whitelist']['url']  = array('module' => 'project', 'method' => 'whitelist', 'params' => 'projectID={id}');

$config->project->actionList['delete']['icon'] = 'trash';
$config->project->actionList['delete']['hint'] = $lang->project->delete;
$config->project->actionList['delete']['url']  = 'javascript:confirmDelete("{id}", "{name}")';

$config->project->view = new stdclass();
$config->project->view->operateList['main']   = array('start', 'activate', 'suspend', 'close');
$config->project->view->operateList['common'] = array('edit', 'delete');

$config->project->statusLabelList['wait']      = 'wait lighter';
$config->project->statusLabelList['doing']     = 'doing primary-pale';
$config->project->statusLabelList['suspended'] = 'suspended gray text-white';
$config->project->statusLabelList['closed']    = 'closed success-pale';
$config->project->statusLabelList['delay']     = 'delay danger';
