<?php
global $lang;

$config->tutorial->tasks = array();
$config->tutorial->tasks['createAccount'] = array();
$config->tutorial->tasks['createAccount']['title'] = $lang->tutorial->tasks->createAccount->title;

$config->tutorial->tasks['createAccount']['nav']   = array();
$config->tutorial->tasks['createAccount']['nav']['app']            = 'admin';
$config->tutorial->tasks['createAccount']['nav']['module']         = 'user';
$config->tutorial->tasks['createAccount']['nav']['method']         = 'create';
$config->tutorial->tasks['createAccount']['nav']['menuModule']     = 'company';
$config->tutorial->tasks['createAccount']['nav']['menu']           = 'browseUser';
$config->tutorial->tasks['createAccount']['nav']['form']           = '#createUser';
$config->tutorial->tasks['createAccount']['nav']['requiredFields'] = 'account,realname,verifyPassword,password1,password2';
$config->tutorial->tasks['createAccount']['nav']['submit']         = 'button[type=submit]';
$config->tutorial->tasks['createAccount']['nav']['target']         = '.create-user-btn';
$config->tutorial->tasks['createAccount']['nav']['targetPageName'] = $lang->tutorial->tasks->createAccount->targetPageName;
$config->tutorial->tasks['createAccount']['desc']                  = $lang->tutorial->tasks->createAccount->desc;

$config->tutorial->tasks['createProgram'] = array();
$config->tutorial->tasks['createProgram']['title'] = $lang->tutorial->tasks->createProgram->title;

$config->tutorial->tasks['createProgram']['nav']   = array();
$config->tutorial->tasks['createProgram']['nav']['app']            = 'program';
$config->tutorial->tasks['createProgram']['nav']['module']         = 'program';
$config->tutorial->tasks['createProgram']['nav']['method']         = 'create';
$config->tutorial->tasks['createProgram']['nav']['menuModule']     = 'program';
$config->tutorial->tasks['createProgram']['nav']['menu']           = '.create-program-btn';
$config->tutorial->tasks['createProgram']['nav']['form']           = '#createProgram';
$config->tutorial->tasks['createProgram']['nav']['submit']         = 'button[type=submit]';
$config->tutorial->tasks['createProgram']['nav']['target']         = '.create-program-btn';
$config->tutorial->tasks['createProgram']['nav']['targetPageName'] = $lang->tutorial->tasks->createProgram->targetPageName;

$config->tutorial->tasks['createProgram']['desc'] = $lang->tutorial->tasks->createProgram->desc;

$config->tutorial->tasks['createProduct'] = array();
$config->tutorial->tasks['createProduct']['title'] = $lang->tutorial->tasks->createProduct->title;

$config->tutorial->tasks['createProduct']['nav']   = array();
$config->tutorial->tasks['createProduct']['nav']['app']            = 'product';
$config->tutorial->tasks['createProduct']['nav']['module']         = 'product';
$config->tutorial->tasks['createProduct']['nav']['method']         = 'create';
$config->tutorial->tasks['createProduct']['nav']['menuModule']     = 'product';
$config->tutorial->tasks['createProduct']['nav']['menu']           = '#heading > .toolbar > .toolbar-item, .create-product-btn';
$config->tutorial->tasks['createProduct']['nav']['form']           = '#mainContent';
$config->tutorial->tasks['createProduct']['nav']['submit']         = 'button[type=submit]';
$config->tutorial->tasks['createProduct']['nav']['target']         = '';
$config->tutorial->tasks['createProduct']['nav']['targetPageName'] = $lang->tutorial->tasks->createProduct->targetPageName;

$config->tutorial->tasks['createProduct']['desc'] = $lang->tutorial->tasks->createProduct->desc;

$config->tutorial->tasks['createStory'] = array();
$config->tutorial->tasks['createStory']['title'] = $lang->tutorial->tasks->createStory->title;

$config->tutorial->tasks['createStory']['nav']   = array();
$config->tutorial->tasks['createStory']['nav']['app']            = 'product';
$config->tutorial->tasks['createStory']['nav']['module']         = 'story';
$config->tutorial->tasks['createStory']['nav']['method']         = 'create';
$config->tutorial->tasks['createStory']['nav']['menuModule']     = 'story';
$config->tutorial->tasks['createStory']['nav']['vars']           = 'productID=1';
$config->tutorial->tasks['createStory']['nav']['menu']           = '#products > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell > .dtable-cell-content > a, #heading > .toolbar > .toolbar-item, .create-story-btn';
$config->tutorial->tasks['createStory']['nav']['form']           = '#mainContent';
$config->tutorial->tasks['createStory']['nav']['submit']         = '#saveButton';
$config->tutorial->tasks['createStory']['nav']['target']         = '.create-story-btn';
$config->tutorial->tasks['createStory']['nav']['targetPageName'] = $lang->tutorial->tasks->createStory->targetPageName;

$config->tutorial->tasks['createStory']['desc'] = $lang->tutorial->tasks->createStory->desc;

$config->tutorial->tasks['createProject'] = array();
$config->tutorial->tasks['createProject']['title'] = $lang->tutorial->tasks->createProject->title;

$config->tutorial->tasks['createProject']['nav']   = array();
$config->tutorial->tasks['createProject']['nav']['app']            = 'project';
$config->tutorial->tasks['createProject']['nav']['module']         = 'project';
$config->tutorial->tasks['createProject']['nav']['method']         = 'create';
$config->tutorial->tasks['createProject']['nav']['menuModule']     = 'project';
$config->tutorial->tasks['createProject']['nav']['menu']           = '#heading > .toolbar > .toolbar-item, .create-project-btn';
$config->tutorial->tasks['createProject']['nav']['form']           = '#mainContent';
$config->tutorial->tasks['createProject']['nav']['submit']         = 'button[type=submit]';
$config->tutorial->tasks['createProject']['nav']['target']         = '';
$config->tutorial->tasks['createProject']['nav']['targetPageName'] = $lang->tutorial->tasks->createProject->targetPageName;

$config->tutorial->tasks['createProject']['desc'] = $lang->tutorial->tasks->createProject->desc;

$config->tutorial->tasks['manageTeam'] = array();
$config->tutorial->tasks['manageTeam']['title'] = $lang->tutorial->tasks->manageTeam->title;

$config->tutorial->tasks['manageTeam']['nav']   = array();
$config->tutorial->tasks['manageTeam']['nav']['app']            = 'project';
$config->tutorial->tasks['manageTeam']['nav']['module']         = 'project';
$config->tutorial->tasks['manageTeam']['nav']['method']         = 'managemembers';
$config->tutorial->tasks['manageTeam']['nav']['vars']           = 'projectID=0';
$config->tutorial->tasks['manageTeam']['nav']['menuModule']     = '';
$config->tutorial->tasks['manageTeam']['nav']['menu']           = '#actionBar, #header > .container > #heading > .toolbar, #table-tutorial-wizard > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell[data-col="name"] > .dtable-cell-content > a, #table-project-browse > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell[data-col="name"] > .dtable-cell-content > a, .nav-item > a[data-id="settings"], #mainNavbar > .container > .nav > .nav-item > a[data-id="members"]';
$config->tutorial->tasks['manageTeam']['nav']['form']           = '#teamForm';
$config->tutorial->tasks['manageTeam']['nav']['requiredFields'] = 'accounts[1]';
$config->tutorial->tasks['manageTeam']['nav']['formType']       = 'table';
$config->tutorial->tasks['manageTeam']['nav']['submit']         = '.form-row > .form-actions > button:first-child';
$config->tutorial->tasks['manageTeam']['nav']['target']         = '.manage-team-btn';
$config->tutorial->tasks['manageTeam']['nav']['targetPageName'] = $lang->tutorial->tasks->manageTeam->targetPageName;

$config->tutorial->tasks['manageTeam']['desc'] = $lang->tutorial->tasks->manageTeam->desc;

$config->tutorial->tasks['createProjectExecution'] = array();
$config->tutorial->tasks['createProjectExecution']['title'] = $lang->tutorial->tasks->createProjectExecution->title;

$config->tutorial->tasks['createProjectExecution']['nav']   = array();
$config->tutorial->tasks['createProjectExecution']['nav']['app']            = 'project';
$config->tutorial->tasks['createProjectExecution']['nav']['module']         = 'execution';
$config->tutorial->tasks['createProjectExecution']['nav']['method']         = 'create';
$config->tutorial->tasks['createProjectExecution']['nav']['menuModule']     = '';
$config->tutorial->tasks['createProjectExecution']['nav']['menu']           = '#heading > .toolbar > .toolbar-item, #table-tutorial-wizard > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell[data-col="name"] > .dtable-cell-content > a, .create-execution-btn';
$config->tutorial->tasks['createProjectExecution']['nav']['form']           = '#mainContent';
$config->tutorial->tasks['createProjectExecution']['nav']['submit']         = 'button[type=submit]';
$config->tutorial->tasks['createProjectExecution']['nav']['targetPageName'] = $lang->tutorial->tasks->createProjectExecution->targetPageName;

$config->tutorial->tasks['createProjectExecution']['desc'] = $lang->tutorial->tasks->createProjectExecution->desc;

$config->tutorial->tasks['linkStory'] = array();
$config->tutorial->tasks['linkStory']['title'] = $lang->tutorial->tasks->linkStory->title;

$config->tutorial->tasks['linkStory']['nav']   = array();
$config->tutorial->tasks['linkStory']['nav']['app']            = 'execution';
$config->tutorial->tasks['linkStory']['nav']['module']         = 'execution';
$config->tutorial->tasks['linkStory']['nav']['method']         = 'linkStory';
$config->tutorial->tasks['linkStory']['nav']['menuModule']     = 'story';
$config->tutorial->tasks['linkStory']['nav']['menu']           = '#heading > .toolbar > .toolbar-item, #table-execution-all > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell[data-col="nameCol"] > .dtable-cell-content > .dtable-cell-html, .link-story-btn';
$config->tutorial->tasks['linkStory']['nav']['form']           = '#table-tutorial-wizard,#table-execution-linkstory';
$config->tutorial->tasks['linkStory']['nav']['formType']       = 'table';
$config->tutorial->tasks['linkStory']['nav']['submit']         = '.link-story-btn';
$config->tutorial->tasks['linkStory']['nav']['target']         = '.dtable-checkbox';
$config->tutorial->tasks['linkStory']['nav']['targetPageName'] = $lang->tutorial->tasks->linkStory->targetPageName;

$config->tutorial->tasks['linkStory']['desc'] = $lang->tutorial->tasks->linkStory->desc;

$config->tutorial->tasks['createTask'] = array();
$config->tutorial->tasks['createTask']['title'] = $lang->tutorial->tasks->createTask->title;

$config->tutorial->tasks['createTask']['nav']   = array();
$config->tutorial->tasks['createTask']['nav']['app']            = 'execution';
$config->tutorial->tasks['createTask']['nav']['module']         = 'task';
$config->tutorial->tasks['createTask']['nav']['method']         = 'create';
$config->tutorial->tasks['createTask']['nav']['menuModule']     = 'story';
$config->tutorial->tasks['createTask']['nav']['vars']           = 'executionID=2&storyID=0';
$config->tutorial->tasks['createTask']['nav']['menu']           = '#heading > .toolbar > .toolbar-item, .create-task-btn, #table-execution-all > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell[data-col="nameCol"] > .dtable-cell-content > .dtable-cell-html';
$config->tutorial->tasks['createTask']['nav']['form']           = '#mainContent';
$config->tutorial->tasks['createTask']['nav']['submit']         = 'button[type=submit]';
$config->tutorial->tasks['createTask']['nav']['target']         = '.create-task-btn';
$config->tutorial->tasks['createTask']['nav']['targetPageName'] = $lang->tutorial->tasks->createTask->targetPageName;

$config->tutorial->tasks['createTask']['desc'] = $lang->tutorial->tasks->createTask->desc;

$config->tutorial->tasks['createBug'] = array();
$config->tutorial->tasks['createBug']['title'] = $lang->tutorial->tasks->createBug->title;

$config->tutorial->tasks['createBug']['nav']   = array();
$config->tutorial->tasks['createBug']['nav']['app']            = 'qa';
$config->tutorial->tasks['createBug']['nav']['module']         = 'bug';
$config->tutorial->tasks['createBug']['nav']['method']         = 'create';
$config->tutorial->tasks['createBug']['nav']['menuModule']     = 'bug';
$config->tutorial->tasks['createBug']['nav']['vars']           = 'productID=1';
$config->tutorial->tasks['createBug']['nav']['menu']           = '#heading > .toolbar > .toolbar-item, .create-bug-btn';
$config->tutorial->tasks['createBug']['nav']['form']           = '#mainContent';
$config->tutorial->tasks['createBug']['nav']['submit']         = 'button[type=submit]';
$config->tutorial->tasks['createBug']['nav']['target']         = '.create-bug-btn';
$config->tutorial->tasks['createBug']['nav']['targetPageName'] = $lang->tutorial->tasks->createBug->targetPageName;

$config->tutorial->tasks['createBug']['desc'] = $lang->tutorial->tasks->createBug->desc;

if($config->systemMode == 'light') unset($config->tutorial->tasks->createProgram);
