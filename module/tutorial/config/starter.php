<?php
global $lang;

$starter = new stdClass();
$starter->name    = 'starter';
$starter->title   = $lang->tutorial->starter->title;
$starter->icon    = 'front text-special';
$starter->type    = 'starter';
$starter->modules = 'admin,company,dept,group';
$starter->app     = 'admin';
$starter->tasks   = array();

$starter->tasks['createAccount'] = array();
$starter->tasks['createAccount']['name']     = 'createAccount';
$starter->tasks['createAccount']['title']    = $lang->tutorial->starter->createAccount->title;
$starter->tasks['createAccount']['startUrl'] = array('admin', 'index');
$starter->tasks['createAccount']['steps']    = array();

$starter->tasks['createAccount']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'admin',
    'title' => $lang->tutorial->starter->createAccount->step1->name,
    'desc'  => $lang->tutorial->starter->createAccount->step1->desc
);

$starter->tasks['createAccount']['steps'][] = array(
    'type'   => 'click',
    'target' => '#settings div[data-id="company"]',
    'page'   => 'admin-index',
    'title'  => $lang->tutorial->starter->createAccount->step2->name,
    'desc'   => $lang->tutorial->starter->createAccount->step2->desc
);

$starter->tasks['createAccount']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'browseUser',
    'title'  => $lang->tutorial->starter->createAccount->step3->name,
    'desc'   => $lang->tutorial->starter->createAccount->step3->desc
);

$starter->tasks['createAccount']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.create-user-btn',
    'page'   => 'company-browse',
    'title'  => $lang->tutorial->starter->createAccount->step4->name,
    'desc'   => $lang->tutorial->starter->createAccount->step4->desc
);

$starter->tasks['createAccount']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'user-create',
    'title'  => $lang->tutorial->starter->createAccount->step5->name
);

$starter->tasks['createAccount']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'user-create',
    'title'  => $lang->tutorial->starter->createAccount->step6->name,
    'desc'   => $lang->tutorial->starter->createAccount->step6->desc
);

$starter->tasks['createProgram'] = array();
$starter->tasks['createProgram']['name']     = 'createProgram';
$starter->tasks['createProgram']['title']    = $lang->tutorial->starter->createProgram->title;
$starter->tasks['createProgram']['startUrl'] = array('program', 'browse');
$starter->tasks['createProgram']['steps']    = array();

$starter->tasks['createProgram']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'program',
    'title' => $lang->tutorial->starter->createProgram->step1->name,
    'desc'  => $lang->tutorial->starter->createProgram->step1->desc
);

$starter->tasks['createProgram']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.create-program-btn',
    'page'   => 'program-browse',
    'app'   => 'program',
    'title'  => $lang->tutorial->starter->createProgram->step2->name,
    'desc'   => $lang->tutorial->starter->createProgram->step2->desc
);

$starter->tasks['createProgram']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'program-create',
    'title'  => $lang->tutorial->starter->createProgram->step3->name
);

$starter->tasks['createProgram']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'program-create',
    'title'  => $lang->tutorial->starter->createProgram->step4->name,
    'desc'   => $lang->tutorial->starter->createProgram->step4->desc
);

$starter->tasks['createProduct'] = array();
$starter->tasks['createProduct']['name']     = 'createProduct';
$starter->tasks['createProduct']['title']    = $lang->tutorial->starter->createProduct->title;
$starter->tasks['createProduct']['startUrl'] = array('product', 'all');
$starter->tasks['createProduct']['steps']    = array();

$starter->tasks['createProduct']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'product',
    'title' => $lang->tutorial->starter->createProduct->step1->name,
    'desc'  => $lang->tutorial->starter->createProduct->step1->desc
);

$starter->tasks['createProduct']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.create-product-btn',
    'page'   => 'product-all',
    'app'    => 'product',
    'title'  => $lang->tutorial->starter->createProduct->step2->name,
    'desc'   => $lang->tutorial->starter->createProduct->step2->desc
);

$starter->tasks['createProduct']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'product-create',
    'title'  => $lang->tutorial->starter->createProduct->step3->name
);

$starter->tasks['createProduct']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'product-create',
    'title'  => $lang->tutorial->starter->createProduct->step4->name,
    'desc'   => $lang->tutorial->starter->createProduct->step4->desc
);

$starter->tasks['createStory'] = array();
$starter->tasks['createStory']['name']     = 'createStory';
$starter->tasks['createStory']['title']    = $lang->tutorial->starter->createStory->title;
$starter->tasks['createStory']['startUrl'] = array('product', 'all');
$starter->tasks['createStory']['steps']    = array();

$starter->tasks['createStory']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'product',
    'title' => $lang->tutorial->starter->createStory->step1->name,
    'desc'  => $lang->tutorial->starter->createStory->step1->desc
);

$starter->tasks['createStory']['steps'][] = array(
    'type'   => 'click',
    'target' => '#products div.dtable-body div[data-col="name"][data-row="1"] a',
    'page'   => 'product-all',
    'title'  => $lang->tutorial->starter->createStory->step2->name,
    'desc'   => $lang->tutorial->starter->createStory->step2->desc
);

$starter->tasks['createStory']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.create-story-btn',
    'page'   => 'product-browse',
    'url'    => array('product', 'browse', 'productID=1'),
    'title'  => $lang->tutorial->starter->createStory->step3->name,
    'desc'   => $lang->tutorial->starter->createStory->step3->desc
);

$starter->tasks['createStory']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'story-create',
    'title'  => $lang->tutorial->starter->createStory->step4->name
);

$starter->tasks['createStory']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'story-create',
    'title'  => $lang->tutorial->starter->createStory->step5->name,
    'desc'   => $lang->tutorial->starter->createStory->step5->desc
);

$starter->tasks['createProject'] = array();
$starter->tasks['createProject']['name']     = 'createProject';
$starter->tasks['createProject']['title']    = $lang->tutorial->starter->createProject->title;
$starter->tasks['createProject']['startUrl'] = array('project', 'browse');
$starter->tasks['createProject']['steps']    = array();

$starter->tasks['createProject']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'project',
    'title' => $lang->tutorial->starter->createProject->step1->name,
    'desc'  => $lang->tutorial->starter->createProject->step1->desc
);

$starter->tasks['createProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar .create-project-btn',
    'page'   => 'project-browse',
    'title'  => $lang->tutorial->starter->createProject->step2->name,
    'desc'   => $lang->tutorial->starter->createProject->step2->desc
);

$starter->tasks['createProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#modelList div.scrum div.model-item',
    'page'   => 'project-browse',
    'title'  => $lang->tutorial->starter->createProject->step3->name,
    'desc'   => $lang->tutorial->starter->createProject->step3->desc
);

$starter->tasks['createProject']['steps'][] = array(
    'type'   => 'form',
    'target' => '#form-project-create',
    'page'   => 'project-create',
    'title'  => $lang->tutorial->starter->createProject->step4->name
);

$starter->tasks['createProject']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#form-project-create .form-actions button[type="submit"]',
    'page'   => 'project-create',
    'title'  => $lang->tutorial->starter->createProject->step5->name,
    'desc'   => $lang->tutorial->starter->createProject->step5->desc
);

$starter->tasks['manageTeam'] = array();
$starter->tasks['manageTeam']['name']     = 'manageTeam';
$starter->tasks['manageTeam']['title']    = $lang->tutorial->starter->manageTeam->title;
$starter->tasks['manageTeam']['startUrl'] = array('project', 'browse');
$starter->tasks['manageTeam']['steps']    = array();

$starter->tasks['manageTeam']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'project',
    'title' => $lang->tutorial->starter->manageTeam->step1->name,
    'desc'  => $lang->tutorial->starter->manageTeam->step1->desc
);

$starter->tasks['manageTeam']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-project-browse .dtable-body div[data-row="2"] a',
    'page'   => 'project-browse',
    'url'    => array('project', 'browse'),
    'title'  => $lang->tutorial->starter->manageTeam->step2->name,
    'desc'   => $lang->tutorial->starter->manageTeam->step2->desc
);

$starter->tasks['manageTeam']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'settings',
    'page'   => 'project-index',
    'title'  => $lang->tutorial->starter->manageTeam->step3->name,
    'desc'   => $lang->tutorial->starter->manageTeam->step3->desc
);

$starter->tasks['manageTeam']['steps'][] = array(
    'type'   => 'clickMainNavbar',
    'target' => 'members',
    'page'   => 'project-view',
    'title'  => $lang->tutorial->starter->manageTeam->step4->name,
    'desc'   => $lang->tutorial->starter->manageTeam->step4->desc
);

$starter->tasks['manageTeam']['steps'][] = array(
    'type'   => 'click',
    'target' => '#mainContainer #mainMenu #actionBar a',
    'page'   => 'project-team',
    'title'  => $lang->tutorial->starter->manageTeam->step5->name,
    'desc'   => $lang->tutorial->starter->manageTeam->step5->desc
);

$starter->tasks['manageTeam']['steps'][] = array(
    'type'   => 'form',
    'target' => '#teamForm table',
    'page'   => 'project-manageMembers',
    'title'  => $lang->tutorial->starter->manageTeam->step6->name
);

$starter->tasks['manageTeam']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#teamForm #saveButton',
    'page'   => 'project-manageMembers',
    'title'  => $lang->tutorial->starter->manageTeam->step7->name,
    'desc'   => $lang->tutorial->starter->manageTeam->step7->desc
);

$starter->tasks['createProjectExecution'] = array();
$starter->tasks['createProjectExecution']['name']     = 'createProjectExecution';
$starter->tasks['createProjectExecution']['title']    = $lang->tutorial->starter->createProjectExecution->title;
$starter->tasks['createProjectExecution']['startUrl'] = array('project', 'browse');
$starter->tasks['createProjectExecution']['steps']    = array();

$starter->tasks['createProjectExecution']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'project',
    'title' => $lang->tutorial->starter->createProjectExecution->step1->name,
    'desc'  => $lang->tutorial->starter->createProjectExecution->step1->desc
);

$starter->tasks['createProjectExecution']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-project-browse .dtable-body div[data-row="2"] a',
    'page'   => 'project-browse',
    'url'    => array('project', 'browse'),
    'title'  => $lang->tutorial->starter->createProjectExecution->step2->name,
    'desc'   => $lang->tutorial->starter->createProjectExecution->step2->desc
);

$starter->tasks['createProjectExecution']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'execution',
    'page'   => 'project-index',
    'title'  => $lang->tutorial->starter->createProjectExecution->step3->name,
    'desc'   => $lang->tutorial->starter->createProjectExecution->step3->desc
);

$starter->tasks['createProjectExecution']['steps'][] = array(
    'type'   => 'click',
    'target' => '#mainMenu #actionBar a.create-execution-btn',
    'page'   => 'project-execution',
    'url'    => array('project', 'execution', 'status=all&projectID=2'),
    'title'  => $lang->tutorial->starter->createProjectExecution->step4->name,
    'desc'   => $lang->tutorial->starter->createProjectExecution->step4->desc
);

$starter->tasks['createProjectExecution']['steps'][] = array(
    'type'   => 'form',
    'target' => '#form-execution-create',
    'app'    => 'execution',
    'page'   => 'execution-create',
    'title'  => $lang->tutorial->starter->createProjectExecution->step5->name
);

$starter->tasks['createProjectExecution']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#form-execution-create .form-actions button[type="submit"]',
    'page'   => 'execution-create',
    'title'  => $lang->tutorial->starter->createProjectExecution->step6->name,
    'desc'   => $lang->tutorial->starter->createProjectExecution->step6->desc
);

$starter->tasks['linkStory'] = array();
$starter->tasks['linkStory']['name']     = 'linkStory';
$starter->tasks['linkStory']['title']    = $lang->tutorial->starter->linkStory->title;
$starter->tasks['linkStory']['startUrl'] = array('execution', 'task', 'executionID=3');
$starter->tasks['linkStory']['steps']    = array();

$starter->tasks['linkStory']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'execution',
    'title' => $lang->tutorial->starter->linkStory->step1->name,
    'desc'  => $lang->tutorial->starter->linkStory->step1->desc
);

$starter->tasks['linkStory']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'story',
    'page'   => 'execution-task',
    'url'    => array('execution', 'task', 'executionID=3'),
    'title'  => $lang->tutorial->starter->linkStory->step2->name,
    'desc'   => $lang->tutorial->starter->linkStory->step2->desc
);

$starter->tasks['linkStory']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.link-story-btn',
    'page'   => 'execution-story',
    'title'  => $lang->tutorial->starter->linkStory->step3->name,
    'desc'   => $lang->tutorial->starter->linkStory->step3->desc
);

$starter->tasks['linkStory']['steps'][] = array(
    'type'   => 'selectRow',
    'target' => 'div.dtable div.dtable-body div[data-col="id"][data-row="3"]',
    'page'   => 'execution-linkstory',
    'title'  => $lang->tutorial->starter->linkStory->step4->name
);

$starter->tasks['linkStory']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable .dtable-footer .link-story-btn',
    'page'   => 'execution-linkstory',
    'title'  => $lang->tutorial->starter->linkStory->step5->name,
    'desc'   => $lang->tutorial->starter->linkStory->step5->desc
);

$starter->tasks['createTask'] = array();
$starter->tasks['createTask']['name']     = 'createTask';
$starter->tasks['createTask']['title']    = $lang->tutorial->starter->createTask->title;
$starter->tasks['createTask']['startUrl'] = array('execution', 'task', 'executionID=3');
$starter->tasks['createTask']['steps']    = array();

$starter->tasks['createTask']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'execution',
    'title' => $lang->tutorial->starter->createTask->step1->name,
    'desc'  => $lang->tutorial->starter->createTask->step1->desc
);

$starter->tasks['createTask']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'story',
    'page'   => 'execution-task',
    'url'    => array('execution', 'task', 'executionID=3'),
    'title'  => $lang->tutorial->starter->createTask->step2->name,
    'desc'   => $lang->tutorial->starter->createTask->step2->desc
);

$starter->tasks['createTask']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-execution-story a.create-task-btn',
    'url'    => array('execution', 'story', 'executionID=3'),
    'page'   => 'execution-story',
    'app'    => 'execution',
    'title'  => $lang->tutorial->starter->createTask->step3->name,
    'desc'   => $lang->tutorial->starter->createTask->step3->desc
);

$starter->tasks['createTask']['steps'][] = array(
    'type'   => 'form',
    'url'    => array('task', 'create', 'executionID=3'),
    'app'    => 'execution',
    'page'   => 'task-create',
    'title'  => $lang->tutorial->starter->createTask->step4->name,
);

$starter->tasks['createTask']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => 'form button[type="submit"]',
    'page'   => 'task-create',
    'title'  => $lang->tutorial->starter->createTask->step5->name,
    'desc'   => $lang->tutorial->starter->createTask->step5->desc
);

$starter->tasks['createBug'] = array();
$starter->tasks['createBug']['name']     = 'createBug';
$starter->tasks['createBug']['title']    = $lang->tutorial->starter->createBug->title;
$starter->tasks['createBug']['startUrl'] = array('qa', 'index');
$starter->tasks['createBug']['steps']    = array();

$starter->tasks['createBug']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'qa',
    'title' => $lang->tutorial->starter->createBug->step1->name,
    'desc'  => $lang->tutorial->starter->createBug->step1->desc
);

$starter->tasks['createBug']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'bug',
    'page'   => 'qa-index',
    'title'  => $lang->tutorial->starter->createBug->step2->name,
    'desc'   => $lang->tutorial->starter->createBug->step2->desc
);

$starter->tasks['createBug']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.create-bug-btn',
    'page'   => 'bug-browse',
    'title'  => $lang->tutorial->starter->createBug->step3->name,
    'desc'   => $lang->tutorial->starter->createBug->step3->desc
);

$starter->tasks['createBug']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'bug-create',
    'title'  => $lang->tutorial->starter->createBug->step4->name
);

$starter->tasks['createBug']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'bug-create',
    'title'  => $lang->tutorial->starter->createBug->step5->name,
    'desc'   => $lang->tutorial->starter->createBug->step5->desc
);
