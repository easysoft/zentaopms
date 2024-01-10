<?php
global $lang;

$config->tutorial->tasksConfig = array();
$config->tutorial->tasksConfig['createAccount'] = array();
$config->tutorial->tasksConfig['createAccount']['title'] = $lang->tutorial->tasks->createAccount->title;

$config->tutorial->tasksConfig['createAccount']['nav']   = array();
$config->tutorial->tasksConfig['createAccount']['nav']['app']            = 'admin';
$config->tutorial->tasksConfig['createAccount']['nav']['module']         = 'user';
$config->tutorial->tasksConfig['createAccount']['nav']['method']         = 'create';
$config->tutorial->tasksConfig['createAccount']['nav']['menuModule']     = 'company';
$config->tutorial->tasksConfig['createAccount']['nav']['menu']           = 'browseUser';
$config->tutorial->tasksConfig['createAccount']['nav']['form']           = '#createUser';
$config->tutorial->tasksConfig['createAccount']['nav']['requiredFields'] = 'account,realname,verifyPassword,password1,password2';
$config->tutorial->tasksConfig['createAccount']['nav']['submit']         = 'button[type=submit]';
$config->tutorial->tasksConfig['createAccount']['nav']['target']         = '.create-user-btn';
$config->tutorial->tasksConfig['createAccount']['nav']['targetPageName'] = $lang->tutorial->tasks->createAccount->targetPageName;
$config->tutorial->tasksConfig['createAccount']['desc']                  = $lang->tutorial->tasks->createAccount->desc;

$config->tutorial->tasksConfig['createProgram'] = array();
$config->tutorial->tasksConfig['createProgram']['title'] = $lang->tutorial->tasks->createProgram->title;

$config->tutorial->tasksConfig['createProgram']['nav']   = array();
$config->tutorial->tasksConfig['createProgram']['nav']['app']            = 'program';
$config->tutorial->tasksConfig['createProgram']['nav']['module']         = 'program';
$config->tutorial->tasksConfig['createProgram']['nav']['method']         = 'create';
$config->tutorial->tasksConfig['createProgram']['nav']['menuModule']     = 'program';
$config->tutorial->tasksConfig['createProgram']['nav']['menu']           = '.create-program-btn';
$config->tutorial->tasksConfig['createProgram']['nav']['form']           = '#createProgram';
$config->tutorial->tasksConfig['createProgram']['nav']['submit']         = 'button[type=submit]';
$config->tutorial->tasksConfig['createProgram']['nav']['target']         = '.create-program-btn';
$config->tutorial->tasksConfig['createProgram']['nav']['targetPageName'] = $lang->tutorial->tasks->createProgram->targetPageName;

$config->tutorial->tasksConfig['createProgram']['desc'] = $lang->tutorial->tasks->createProgram->desc;

$config->tutorial->tasksConfig['createProduct'] = array();
$config->tutorial->tasksConfig['createProduct']['title'] = $lang->tutorial->tasks->createProduct->title;

$config->tutorial->tasksConfig['createProduct']['nav']   = array();
$config->tutorial->tasksConfig['createProduct']['nav']['app']            = 'product';
$config->tutorial->tasksConfig['createProduct']['nav']['module']         = 'product';
$config->tutorial->tasksConfig['createProduct']['nav']['method']         = 'create';
$config->tutorial->tasksConfig['createProduct']['nav']['menuModule']     = 'product';
$config->tutorial->tasksConfig['createProduct']['nav']['requiredFields'] = 'name,code';
$config->tutorial->tasksConfig['createProduct']['nav']['menu']           = '#heading > .toolbar > .toolbar-item';
$config->tutorial->tasksConfig['createProduct']['nav']['form']           = '#mainContent';
$config->tutorial->tasksConfig['createProduct']['nav']['submit']         = 'button[type=submit]';
$config->tutorial->tasksConfig['createProduct']['nav']['target']         = '.create-product-btn';
$config->tutorial->tasksConfig['createProduct']['nav']['targetPageName'] = $lang->tutorial->tasks->createProduct->targetPageName;

$config->tutorial->tasksConfig['createProduct']['desc'] = $lang->tutorial->tasks->createProduct->desc;

$config->tutorial->tasksConfig['createStory'] = array();
$config->tutorial->tasksConfig['createStory']['title'] = $lang->tutorial->tasks->createStory->title;

$config->tutorial->tasksConfig['createStory']['nav']   = array();
$config->tutorial->tasksConfig['createStory']['nav']['app']            = 'product';
$config->tutorial->tasksConfig['createStory']['nav']['module']         = 'story';
$config->tutorial->tasksConfig['createStory']['nav']['method']         = 'create';
$config->tutorial->tasksConfig['createStory']['nav']['menuModule']     = 'story';
$config->tutorial->tasksConfig['createStory']['nav']['vars']           = 'productID=1';
$config->tutorial->tasksConfig['createStory']['nav']['menu']           = '#products > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell > .dtable-cell-content > a, #heading > .toolbar > .toolbar-item, .create-story-btn';
$config->tutorial->tasksConfig['createStory']['nav']['form']           = '#mainContent';
$config->tutorial->tasksConfig['createStory']['nav']['submit']         = 'button[type=submit]';
$config->tutorial->tasksConfig['createStory']['nav']['target']         = '.create-story-btn';
$config->tutorial->tasksConfig['createStory']['nav']['targetPageName'] = $lang->tutorial->tasks->createStory->targetPageName;

$config->tutorial->tasksConfig['createStory']['desc'] = $lang->tutorial->tasks->createStory->desc;

$config->tutorial->tasksConfig['createProject'] = array();
$config->tutorial->tasksConfig['createProject']['title'] = $lang->tutorial->tasks->createProject->title;

$config->tutorial->tasksConfig['createProject']['nav']   = array();
$config->tutorial->tasksConfig['createProject']['nav']['app']            = 'project';
$config->tutorial->tasksConfig['createProject']['nav']['module']         = 'project';
$config->tutorial->tasksConfig['createProject']['nav']['method']         = 'create';
$config->tutorial->tasksConfig['createProject']['nav']['menuModule']     = 'project';
$config->tutorial->tasksConfig['createProject']['nav']['menu']           = '#heading > .toolbar > .toolbar-item, .create-project-btn';
$config->tutorial->tasksConfig['createProject']['nav']['form']           = '#mainContent';
$config->tutorial->tasksConfig['createProject']['nav']['submit']         = 'button[type=submit]';
$config->tutorial->tasksConfig['createProject']['nav']['target']         = '';
$config->tutorial->tasksConfig['createProject']['nav']['targetPageName'] = $lang->tutorial->tasks->createProject->targetPageName;

$config->tutorial->tasksConfig['createProject']['desc'] = $lang->tutorial->tasks->createProject->desc;

$config->tutorial->tasksConfig['manageTeam'] = array();
$config->tutorial->tasksConfig['manageTeam']['title'] = $lang->tutorial->tasks->manageTeam->title;

$config->tutorial->tasksConfig['manageTeam']['nav']   = array();
$config->tutorial->tasksConfig['manageTeam']['nav']['app']            = 'project';
$config->tutorial->tasksConfig['manageTeam']['nav']['module']         = 'project';
$config->tutorial->tasksConfig['manageTeam']['nav']['method']         = 'managemembers';
$config->tutorial->tasksConfig['manageTeam']['nav']['vars']           = 'projectID=0';
$config->tutorial->tasksConfig['manageTeam']['nav']['menuModule']     = '';
$config->tutorial->tasksConfig['manageTeam']['nav']['menu']           = '#actionBar, #header > .container > #heading > .toolbar, #table-tutorial-wizard > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell[data-col="name"] > .dtable-cell-content > a, #table-project-browse > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell[data-col="name"] > .dtable-cell-content > a, .nav-item > a[data-id="settings"], #mainNavbar > .container > .nav > .nav-item > a[data-id="members"]';
$config->tutorial->tasksConfig['manageTeam']['nav']['form']           = '#teamForm';
$config->tutorial->tasksConfig['manageTeam']['nav']['requiredFields'] = 'account[1]';
$config->tutorial->tasksConfig['manageTeam']['nav']['formType']       = 'table';
$config->tutorial->tasksConfig['manageTeam']['nav']['submit']         = '.form-row > .form-actions > button:first-child';
$config->tutorial->tasksConfig['manageTeam']['nav']['target']         = '.manage-team-btn';
$config->tutorial->tasksConfig['manageTeam']['nav']['targetPageName'] = $lang->tutorial->tasks->manageTeam->targetPageName;

$config->tutorial->tasksConfig['manageTeam']['desc'] = $lang->tutorial->tasks->manageTeam->desc;

$config->tutorial->tasksConfig['createProjectExecution'] = array();
$config->tutorial->tasksConfig['createProjectExecution']['title'] = $lang->tutorial->tasks->createProjectExecution->title;

$config->tutorial->tasksConfig['createProjectExecution']['nav']   = array();
$config->tutorial->tasksConfig['createProjectExecution']['nav']['app']            = 'project';
$config->tutorial->tasksConfig['createProjectExecution']['nav']['module']         = 'execution';
$config->tutorial->tasksConfig['createProjectExecution']['nav']['method']         = 'create';
$config->tutorial->tasksConfig['createProjectExecution']['nav']['menuModule']     = '';
$config->tutorial->tasksConfig['createProjectExecution']['nav']['menu']           = '#heading > .toolbar > .toolbar-item, #table-tutorial-wizard > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell[data-col="name"] > .dtable-cell-content > a, .create-execution-btn';
$config->tutorial->tasksConfig['createProjectExecution']['nav']['form']           = '#mainContent';
$config->tutorial->tasksConfig['createProjectExecution']['nav']['submit']         = 'button[type=submit]';
$config->tutorial->tasksConfig['createProjectExecution']['nav']['targetPageName'] = $lang->tutorial->tasks->createProjectExecution->targetPageName;

$config->tutorial->tasksConfig['createProjectExecution']['desc'] = $lang->tutorial->tasks->createProjectExecution->desc;

$config->tutorial->tasksConfig['linkStory'] = array();
$config->tutorial->tasksConfig['linkStory']['title'] = $lang->tutorial->tasks->linkStory->title;

$config->tutorial->tasksConfig['linkStory']['nav']   = array();
$config->tutorial->tasksConfig['linkStory']['nav']['app']            = 'execution';
$config->tutorial->tasksConfig['linkStory']['nav']['module']         = 'execution';
$config->tutorial->tasksConfig['linkStory']['nav']['method']         = 'linkStory';
$config->tutorial->tasksConfig['linkStory']['nav']['menuModule']     = 'story';
$config->tutorial->tasksConfig['linkStory']['nav']['menu']           = '#heading > .toolbar > .toolbar-item, #table-execution-all > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell[data-col="nameCol"] > .dtable-cell-content > .dtable-cell-html';
$config->tutorial->tasksConfig['linkStory']['nav']['form']           = '#table-tutorial-wizard,#table-execution-linkstory';
$config->tutorial->tasksConfig['linkStory']['nav']['formType']       = 'table';
$config->tutorial->tasksConfig['linkStory']['nav']['submit']         = '.import-story-btn';
$config->tutorial->tasksConfig['linkStory']['nav']['target']         = '.link-story-btn';
$config->tutorial->tasksConfig['linkStory']['nav']['targetPageName'] = $lang->tutorial->tasks->linkStory->targetPageName;

$config->tutorial->tasksConfig['linkStory']['desc'] = $lang->tutorial->tasks->linkStory->desc;

$config->tutorial->tasksConfig['createTask'] = array();
$config->tutorial->tasksConfig['createTask']['title'] = $lang->tutorial->tasks->createTask->title;

$config->tutorial->tasksConfig['createTask']['nav']   = array();
$config->tutorial->tasksConfig['createTask']['nav']['app']            = 'execution';
$config->tutorial->tasksConfig['createTask']['nav']['module']         = 'task';
$config->tutorial->tasksConfig['createTask']['nav']['method']         = 'create';
$config->tutorial->tasksConfig['createTask']['nav']['menuModule']     = 'story';
$config->tutorial->tasksConfig['createTask']['nav']['vars']           = 'executionID=2&storyID=0';
$config->tutorial->tasksConfig['createTask']['nav']['menu']           = '#heading > .toolbar > .toolbar-item, .create-task-btn, #table-execution-all > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell[data-col="nameCol"] > .dtable-cell-content > .dtable-cell-html';
$config->tutorial->tasksConfig['createTask']['nav']['form']           = '#mainContent';
$config->tutorial->tasksConfig['createTask']['nav']['submit']         = 'button[type=submit]';
$config->tutorial->tasksConfig['createTask']['nav']['target']         = '.create-task-btn';
$config->tutorial->tasksConfig['createTask']['nav']['targetPageName'] = $lang->tutorial->tasks->createTask->targetPageName;

$config->tutorial->tasksConfig['createTask']['desc'] = $lang->tutorial->tasks->createTask->desc;

$config->tutorial->tasksConfig['createBug'] = array();
$config->tutorial->tasksConfig['createBug']['title'] = $lang->tutorial->tasks->createBug->title;

$config->tutorial->tasksConfig['createBug']['nav']   = array();
$config->tutorial->tasksConfig['createBug']['nav']['app']            = 'qa';
$config->tutorial->tasksConfig['createBug']['nav']['module']         = 'bug';
$config->tutorial->tasksConfig['createBug']['nav']['method']         = 'create';
$config->tutorial->tasksConfig['createBug']['nav']['menuModule']     = 'bug';
$config->tutorial->tasksConfig['createBug']['nav']['vars']           = 'productID=1';
$config->tutorial->tasksConfig['createBug']['nav']['menu']           = '#heading > .toolbar > .toolbar-item, .create-bug-btn';
$config->tutorial->tasksConfig['createBug']['nav']['form']           = '#mainContent';
$config->tutorial->tasksConfig['createBug']['nav']['submit']         = 'button[type=submit]';
$config->tutorial->tasksConfig['createBug']['nav']['target']         = '.create-bug-btn';
$config->tutorial->tasksConfig['createBug']['nav']['targetPageName'] = $lang->tutorial->tasks->createBug->targetPageName;

$config->tutorial->tasksConfig['createBug']['desc'] = $lang->tutorial->tasks->createBug->desc;

if($config->systemMode == 'light') unset($config->tutorial->tasksConfig['createProgram']);
