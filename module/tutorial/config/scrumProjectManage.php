<?php
global $lang,$config;

$scrumProjectManage = new stdClass();
$scrumProjectManage->basic = new stdClass();
$scrumProjectManage->basic->name    = 'scrumProjectManageBasic';
$scrumProjectManage->basic->title   = $lang->tutorial->scrumProjectManage->title;
$scrumProjectManage->basic->icon    = 'sprint text-special';
$scrumProjectManage->basic->type    = 'basic';
$scrumProjectManage->basic->modules = 'project,execution,build,task,bug,testreport,issue,risk';
$scrumProjectManage->basic->app     = 'project';
$scrumProjectManage->basic->tasks   = array();

$scrumProjectManage->basic->tasks['manageProject'] = array();
$scrumProjectManage->basic->tasks['manageProject']['name']     = 'manageProject';
$scrumProjectManage->basic->tasks['manageProject']['title']    = $lang->tutorial->scrumProjectManage->manageProject->title;
$scrumProjectManage->basic->tasks['manageProject']['startUrl'] = array('project', 'browse');
$scrumProjectManage->basic->tasks['manageProject']['steps']   = array();

$scrumProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'project',
    'title' => $lang->tutorial->scrumProjectManage->manageProject->step1->name,
    'desc'  => $lang->tutorial->scrumProjectManage->manageProject->step1->desc
);

$scrumProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar .create-project-btn',
    'page'   => 'project-browse',
    'title'  => $lang->tutorial->scrumProjectManage->manageProject->step2->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageProject->step2->desc
);

$scrumProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#modelList div.scrum div.model-item',
    'page'   => 'project-browse',
    'title'  => $lang->tutorial->scrumProjectManage->manageProject->step3->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageProject->step3->desc
);

$scrumProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'   => 'form',
    'target' => '#form-project-create',
    'page'   => 'project-create',
    'title'  => $lang->tutorial->scrumProjectManage->manageProject->step4->name
);

$scrumProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#form-project-create .form-actions button[type="submit"]',
    'page'   => 'project-create',
    'title'  => $lang->tutorial->scrumProjectManage->manageProject->step5->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageProject->step5->desc
);

$scrumProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-project-browse .dtable-body div[data-row="2"] a',
    'page'   => 'project-browse',
    'url'    => array('project', 'browse'),
    'title'  => $lang->tutorial->scrumProjectManage->manageProject->step6->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageProject->step6->desc
);

$scrumProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'settings',
    'page'   => 'project-index',
    'title'  => $lang->tutorial->scrumProjectManage->manageProject->step7->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageProject->step7->desc
);

$scrumProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'   => 'clickMainNavbar',
    'target' => 'members',
    'page'   => 'project-view',
    'title'  => $lang->tutorial->scrumProjectManage->manageProject->step8->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageProject->step8->desc
);

$scrumProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#mainContainer #mainMenu #actionBar a',
    'page'   => 'project-team',
    'title'  => $lang->tutorial->scrumProjectManage->manageProject->step9->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageProject->step9->desc
);

$scrumProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'   => 'form',
    'target' => '#teamForm table',
    'page'   => 'project-manageMembers',
    'title'  => $lang->tutorial->scrumProjectManage->manageProject->step10->name,
);

$scrumProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#teamForm #saveButton',
    'page'   => 'project-manageMembers',
    'title'  => $lang->tutorial->scrumProjectManage->manageProject->step11->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageProject->step11->desc
);

$scrumProjectManage->basic->tasks['manageExecution'] = array();
$scrumProjectManage->basic->tasks['manageExecution']['name']     = 'manageExecution';
$scrumProjectManage->basic->tasks['manageExecution']['title']    = $lang->tutorial->scrumProjectManage->manageExecution->title;
$scrumProjectManage->basic->tasks['manageExecution']['startUrl'] = array('project', 'index', 'project=2');
$scrumProjectManage->basic->tasks['manageExecution']['steps']    = array();

$scrumProjectManage->basic->tasks['manageExecution']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'execution',
    'page'   => 'project-index',
    'title'  => $lang->tutorial->scrumProjectManage->manageExecution->step1->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageExecution->step1->desc
);

$scrumProjectManage->basic->tasks['manageExecution']['steps'][] = array(
    'type'   => 'click',
    'target' => '#mainMenu #actionBar a.create-execution-btn',
    'page'   => 'project-execution',
    'url'    => array('project', 'execution', 'status=all&projectID=2'),
    'title'  => $lang->tutorial->scrumProjectManage->manageExecution->step2->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageExecution->step2->desc
);

$scrumProjectManage->basic->tasks['manageExecution']['steps'][] = array(
    'type'   => 'form',
    'target' => '#form-execution-create',
    'app'    => 'execution',
    'page'   => 'execution-create',
    'title'  => $lang->tutorial->scrumProjectManage->manageExecution->step3->name
);

$scrumProjectManage->basic->tasks['manageExecution']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#form-execution-create .form-actions button[type="submit"]',
    'page'   => 'execution-create',
    'title'  => $lang->tutorial->scrumProjectManage->manageExecution->step4->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageExecution->step4->desc
);

$scrumProjectManage->basic->tasks['manageExecution']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-project-execution .dtable-cell[data-row="pid3"][data-col="nameCol"] a',
    'page'   => 'project-execution',
    'url'    => array('project', 'execution', 'status=all&projectID=2'),
    'app'    => 'project',
    'title'  => $lang->tutorial->scrumProjectManage->manageExecution->step5->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageExecution->step5->desc
);

$scrumProjectManage->basic->tasks['manageExecution']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'story',
    'page'   => 'execution-task',
    'url'    => array('execution', 'task', 'executionID=3'),
    'app'    => 'execution',
    'title'  => $lang->tutorial->scrumProjectManage->manageExecution->step6->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageExecution->step6->desc
);

$scrumProjectManage->basic->tasks['manageExecution']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.link-story-btn',
    'page'   => 'execution-story',
    'title'  => $lang->tutorial->scrumProjectManage->manageExecution->step7->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageExecution->step7->desc
);

$scrumProjectManage->basic->tasks['manageExecution']['steps'][] = array(
    'type'   => 'selectRow',
    'target' => 'div.dtable div.dtable-body div[data-col="id"]',
    'page'   => 'execution-linkstory',
    'title'  => $lang->tutorial->scrumProjectManage->manageExecution->step8->name
);

$scrumProjectManage->basic->tasks['manageExecution']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable .dtable-footer .link-story-btn',
    'page'   => 'execution-linkstory',
    'title'  => $lang->tutorial->scrumProjectManage->manageExecution->step9->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageExecution->step9->desc
);

$scrumProjectManage->basic->tasks['manageExecution']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'burn',
    'page'   => 'execution-story',
    'url'    => array('execution', 'story', 'executionID=3'),
    'title'  => $lang->tutorial->scrumProjectManage->manageExecution->step10->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageExecution->step10->desc
);

$scrumProjectManage->basic->tasks['manageTask'] = array();
$scrumProjectManage->basic->tasks['manageTask']['name']     = 'manageTask';
$scrumProjectManage->basic->tasks['manageTask']['title']    = $lang->tutorial->scrumProjectManage->manageTask->title;
$scrumProjectManage->basic->tasks['manageTask']['startUrl'] = array('execution', 'task', 'executionID=3');
$scrumProjectManage->basic->tasks['manageTask']['steps']    = array();

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'story',
    'page'   => 'execution-task',
    'app'    => 'execution',
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step1->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTask->step1->desc
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-execution-story div[data-row="3"] a.batchcreate-task-btn',
    'url'    => array('execution', 'story', 'executionID=3'),
    'page'   => 'execution-story',
    'app'    => 'execution',
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step3->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTask->step3->desc
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'form',
    'target' => '#taskBatchCreateForm',
    'app'    => 'execution',
    'page'   => 'task-batchcreate',
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step4->name,
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#taskBatchCreateForm form button[type="submit"]',
    'page'   => 'task-batchcreate',
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step5->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTask->step5->desc
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-execution-task div[data-row="1"] a.dtable-assign-btn',
    'page'   => 'execution-task',
    'url'    => array('execution', 'task', 'executionID=3'),
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step6->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTask->step6->desc
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step7->name,
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'form button[type="submit"]',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step8->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTask->step8->desc
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-execution-task div[data-row="1"] a.task-start-btn',
    'page'   => 'execution-task',
    'url'    => array('execution', 'task', 'executionID=3'),
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step9->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTask->step9->desc
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step10->name,
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'form button[type="submit"]',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step11->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTask->step11->desc
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-execution-task div[data-row="1"] a.task-record-btn',
    'page'   => 'execution-task',
    'url'    => array('execution', 'task', 'executionID=3'),
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step12->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTask->step12->desc
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step13->name,
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'form button[type="submit"]',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step14->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTask->step14->desc
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-execution-task div[data-row="1"] a.task-finish-btn',
    'page'   => 'execution-task',
    'url'    => array('execution', 'task', 'executionID=3'),
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step15->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTask->step15->desc
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step16->name,
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => 'form button[type="submit"]',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step17->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTask->step17->desc
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'build',
    'page'   => 'execution-task',
    'url'    => array('execution', 'task', 'executionID=3'),
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step18->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTask->step18->desc
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'click',
    'target' => '#mainContainer #actionBar a',
    'page'   => 'execution-build',
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step19->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTask->step19->desc
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'build-create',
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step20->name,
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => 'form button[type="submit"]',
    'page'   => 'build-create',
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step21->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTask->step21->desc
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div[data-row="1"] a.build-linkstory-btn',
    'page'   => 'execution-build',
    'url'    => array('execution', 'build', 'executionID=3'),
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step22->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTask->step22->desc
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'selectRow',
    'target' => '#unlinkStoryList div.dtable-body div[data-col="id"]',
    'page'   => 'build-view',
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step23->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTask->step23->desc
);

$scrumProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'click',
    'target' => '#unlinkStoryList .dtable-footer .linkObjectBtn',
    'page'   => 'build-view',
    'title'  => $lang->tutorial->scrumProjectManage->manageTask->step24->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTask->step24->desc
);

$scrumProjectManage->basic->tasks['manageTest'] = array();
$scrumProjectManage->basic->tasks['manageTest']['name']     = 'manageTest';
$scrumProjectManage->basic->tasks['manageTest']['title']    = $lang->tutorial->scrumProjectManage->manageTest->title;
$scrumProjectManage->basic->tasks['manageTest']['startUrl'] = array('execution', 'task', 'executionID=3');
$scrumProjectManage->basic->tasks['manageTest']['steps']    = array();

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'qa',
    'page'   => 'execution-task',
    'app'    => 'execution',
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step1->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTest->step1->desc
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'clickMainNavbar',
    'target' => 'testcase',
    'page'   => 'execution-bug',
    'url'    => array('execution', 'bug', 'executionID=3'),
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step2->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTest->step2->desc
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a',
    'page'   => 'execution-testcase',
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step3->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTest->step3->desc
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'testcase-create',
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step4->name
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'testcase-create',
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step5->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTest->step5->desc
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'clickMainNavbar',
    'target' => 'testtask',
    'page'   => 'execution-testcase',
    'url'    => array('execution', 'testcase', 'executionID=3'),
    'app'    => 'execution',
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step14->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTest->step14->desc
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a',
    'page'   => 'execution-testtask',
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step15->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTest->step15->desc
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'testtask-create',
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step16->name
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'testtask-create',
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step17->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTest->step17->desc
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="name"][data-row="1"] a',
    'page'   => 'execution-testtask',
    'url'    => array('execution', 'testtask', 'executionID=3'),
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step18->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTest->step18->desc
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.linkCase-btn',
    'page'   => 'testtask-cases',
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step19->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTest->step19->desc
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'selectRow',
    'target' => 'div.dtable div.dtable-body div[data-col="id"]',
    'page'   => 'testtask-linkCase',
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step20->name
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable-footer nav.toolbar button',
    'page'   => 'testtask-linkCase',
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step21->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTest->step21->desc
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="actions"][data-row="1"] a.testtask-runCase-btn',
    'page'   => 'testtask-cases',
    'url'    => array('testtask', 'cases', 'taskID=1'),
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step6->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTest->step6->desc
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'testtask-cases',
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step7->name
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'testtask-cases',
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step8->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTest->step8->desc
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="actions"][data-row="1"] a.testtask-results-btn',
    'page'   => 'testtask-cases',
    'url'    => array('testtask', 'cases', 'taskID=1'),
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step9->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTest->step9->desc
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'click',
    'target' => '.resultSteps div.steps-body div[data-id="1"] div.step-id',
    'page'   => 'testtask-cases',
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step10->name
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => 'div.resultSteps button.to-bug-button',
    'page'   => 'testtask-cases',
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step11->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTest->step11->desc
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'bug-create',
    'url'    => array('bug', 'create', 'productID=1&branch=0&extras=caseID=1,version=1,resultID=1,runID=1,testtask=1,buildID=1,stepList=1'),
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step12->name
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'bug-create',
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step13->name
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'clickMainNavbar',
    'target' => 'testtask',
    'page'   => 'testtask-cases',
    'app'    => 'execution',
    'url'    => array('testtask', 'cases', 'taskID=1'),
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step22->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTest->step22->desc
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'selectRow',
    'target' => '#taskTable div.dtable-body div[data-col="id"]',
    'page'   => 'execution-testtask',
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step23->name
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable-footer nav.toolbar button',
    'page'   => 'execution-testtask',
    'app'    => 'execution',
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step24->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTest->step24->desc
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'testreport-create',
    'url'    => array('testreport', 'create', 'executionID=3'),
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step25->name
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'testreport-create',
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step26->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTest->step26->desc
);

$scrumProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'clickMainNavbar',
    'target' => 'testreport',
    'page'   => 'execution-testtask',
    'app'    => 'execution',
    'url'    => array('execution', 'testtask', 'executionID=3'),
    'title'  => $lang->tutorial->scrumProjectManage->manageTest->step27->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageTest->step27->desc
);

$scrumProjectManage->basic->tasks['manageBug'] = array();
$scrumProjectManage->basic->tasks['manageBug']['name']     = 'manageBug';
$scrumProjectManage->basic->tasks['manageBug']['title']    = $lang->tutorial->scrumProjectManage->manageBug->title;
$scrumProjectManage->basic->tasks['manageBug']['startUrl'] = array('execution', 'task', 'executionID=3');
$scrumProjectManage->basic->tasks['manageBug']['steps']    = array();

$scrumProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'qa',
    'page'   => 'execution-task',
    'app'    => 'execution',
    'title'  => $lang->tutorial->scrumProjectManage->manageBug->step1->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageBug->step1->desc
);

$scrumProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.createBug-btn',
    'page'   => 'execution-bug',
    'url'    => array('execution', 'bug', 'executionID=3'),
    'title'  => $lang->tutorial->scrumProjectManage->manageBug->step2->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageBug->step2->desc
);

$scrumProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'bug-create',
    'title'  => $lang->tutorial->scrumProjectManage->manageBug->step3->name
);

$scrumProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'bug-create',
    'title'  => $lang->tutorial->scrumProjectManage->manageBug->step4->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageBug->step4->desc
);

$scrumProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable-cells div[data-col="actions"][data-row="1"] a.bug-confirm-btn',
    'page'   => 'execution-bug',
    'url'    => array('execution', 'bug', 'executionID=3'),
    'title'  => $lang->tutorial->scrumProjectManage->manageBug->step5->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageBug->step5->desc
);

$scrumProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'execution-bug',
    'title'  => $lang->tutorial->scrumProjectManage->manageBug->step6->name
);

$scrumProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'execution-bug',
    'title'  => $lang->tutorial->scrumProjectManage->manageBug->step7->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageBug->step7->desc
);

$scrumProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable-cells div[data-col="actions"][data-row="1"] a.bug-resolve-btn',
    'page'   => 'execution-bug',
    'url'    => array('execution', 'bug', 'executionID=3'),
    'title'  => $lang->tutorial->scrumProjectManage->manageBug->step8->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageBug->step8->desc
);

$scrumProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'execution-bug',
    'title'  => $lang->tutorial->scrumProjectManage->manageBug->step9->name
);

$scrumProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'execution-bug',
    'title'  => $lang->tutorial->scrumProjectManage->manageBug->step10->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageBug->step10->desc
);

$scrumProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable-cells div[data-col="actions"][data-row="2"] a.bug-close-btn',
    'page'   => 'execution-bug',
    'url'    => array('execution', 'bug', 'executionID=3'),
    'title'  => $lang->tutorial->scrumProjectManage->manageBug->step11->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageBug->step11->desc
);

$scrumProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'execution-bug',
    'title'  => $lang->tutorial->scrumProjectManage->manageBug->step12->name
);

$scrumProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'execution-bug',
    'title'  => $lang->tutorial->scrumProjectManage->manageBug->step13->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageBug->step13->desc
);

$scrumProjectManage->advance = new stdClass();
$scrumProjectManage->advance = clone $scrumProjectManage->basic;
$scrumProjectManage->advance->name = 'scrumProjectManageAdvance';
$scrumProjectManage->advance->type = 'advance';

if(in_array($config->edition, array('max', 'ipd')) && $config->systemMode != 'light')
{
    $scrumProjectManage->advance->tasks['manageIssue'] = array();
    $scrumProjectManage->advance->tasks['manageIssue']['name']     = 'manageIssue';
    $scrumProjectManage->advance->tasks['manageIssue']['title']    = $lang->tutorial->scrumProjectManage->manageIssue->title;
    $scrumProjectManage->advance->tasks['manageIssue']['startUrl'] = array('project', 'index', 'projectID=2');
    $scrumProjectManage->advance->tasks['manageIssue']['steps']    = array();

    $scrumProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'clickNavbar',
        'target' => 'other',
        'page'   => 'project-index',
        'title'  => $lang->tutorial->scrumProjectManage->manageIssue->step1->name
    );

    $scrumProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'click',
        'target' => '#other a[data-id="issue"]',
        'page'   => 'project-index',
        'title'  => $lang->tutorial->scrumProjectManage->manageIssue->step2->name,
        'desc'   => $lang->tutorial->scrumProjectManage->manageIssue->step2->desc
    );

    $scrumProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'click',
        'target' => 'a.create-issue-btn',
        'page'   => 'issue-browse',
        'title'  => $lang->tutorial->scrumProjectManage->manageIssue->step3->name,
        'desc'   => $lang->tutorial->scrumProjectManage->manageIssue->step3->desc
    );

    $scrumProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'form',
        'page'   => 'issue-create',
        'title'  => $lang->tutorial->scrumProjectManage->manageIssue->step4->name
    );

    $scrumProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'saveForm',
        'page'   => 'issue-create',
        'title'  => $lang->tutorial->scrumProjectManage->manageIssue->step5->name,
        'desc'   => $lang->tutorial->scrumProjectManage->manageIssue->step5->desc
    );

    $scrumProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'click',
        'target' => 'div[data-row="1"] a.issue-confirm-btn',
        'page'   => 'issue-browse',
        'url'    => array('issue', 'browse', 'projectID=2'),
        'title'  => $lang->tutorial->scrumProjectManage->manageIssue->step6->name,
        'desc'   => $lang->tutorial->scrumProjectManage->manageIssue->step6->desc
    );

    $scrumProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'form',
        'target' => '#confirmPanel',
        'page'   => 'issue-browse',
        'title'  => $lang->tutorial->scrumProjectManage->manageIssue->step7->name
    );

    $scrumProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'saveForm',
        'target' => '#confirmPanel button[type="submit"]',
        'page'   => 'issue-browse',
        'title'  => $lang->tutorial->scrumProjectManage->manageIssue->step8->name,
        'desc'   => $lang->tutorial->scrumProjectManage->manageIssue->step8->desc
    );

    $scrumProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'click',
        'target' => 'div[data-row="2"] a.issue-resolve-btn',
        'page'   => 'issue-browse',
        'url'    => array('issue', 'browse', 'projectID=2'),
        'title'  => $lang->tutorial->scrumProjectManage->manageIssue->step9->name,
        'desc'   => $lang->tutorial->scrumProjectManage->manageIssue->step9->desc
    );

    $scrumProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'form',
        'target' => '#resolvePanel',
        'page'   => 'issue-browse',
        'title'  => $lang->tutorial->scrumProjectManage->manageIssue->step10->name
    );

    $scrumProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'saveForm',
        'target' => '#resolvePanel button[type="submit"]',
        'page'   => 'issue-browse',
        'title'  => $lang->tutorial->scrumProjectManage->manageIssue->step11->name,
        'desc'   => $lang->tutorial->scrumProjectManage->manageIssue->step11->desc
    );

    $scrumProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'click',
        'target' => 'div[data-row="2"] a.issue-close-btn',
        'page'   => 'issue-browse',
        'url'    => array('issue', 'browse', 'projectID=2'),
        'title'  => $lang->tutorial->scrumProjectManage->manageIssue->step12->name,
        'desc'   => $lang->tutorial->scrumProjectManage->manageIssue->step12->desc
    );

    $scrumProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'form',
        'target' => '#closePanel',
        'page'   => 'issue-browse',
        'title'  => $lang->tutorial->scrumProjectManage->manageIssue->step13->name
    );

    $scrumProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'saveForm',
        'target' => '#closePanel button[type="submit"]',
        'page'   => 'issue-browse',
        'title'  => $lang->tutorial->scrumProjectManage->manageIssue->step14->name,
        'desc'   => $lang->tutorial->scrumProjectManage->manageIssue->step14->desc
    );

    $scrumProjectManage->advance->tasks['manageRisk'] = array();
    $scrumProjectManage->advance->tasks['manageRisk']['name']     = 'manageRisk';
    $scrumProjectManage->advance->tasks['manageRisk']['title']    = $lang->tutorial->scrumProjectManage->manageRisk->title;
    $scrumProjectManage->advance->tasks['manageRisk']['startUrl'] = array('project', 'index', 'projectID=2');
    $scrumProjectManage->advance->tasks['manageRisk']['steps']    = array();

    $scrumProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'clickNavbar',
        'target' => 'other',
        'page'   => 'project-index',
        'title'  => $lang->tutorial->scrumProjectManage->manageRisk->step1->name
    );

    $scrumProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'click',
        'target' => '#other a[data-id="risk"]',
        'page'   => 'project-index',
        'title'  => $lang->tutorial->scrumProjectManage->manageRisk->step2->name,
        'desc'   => $lang->tutorial->scrumProjectManage->manageRisk->step2->desc
    );

    $scrumProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'click',
        'target' => 'a.create-risk-btn',
        'page'   => 'risk-browse',
        'title'  => $lang->tutorial->scrumProjectManage->manageRisk->step3->name,
        'desc'   => $lang->tutorial->scrumProjectManage->manageRisk->step3->desc
    );

    $scrumProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'form',
        'page'   => 'risk-create',
        'title'  => $lang->tutorial->scrumProjectManage->manageRisk->step4->name
    );

    $scrumProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'saveForm',
        'page'   => 'risk-create',
        'title'  => $lang->tutorial->scrumProjectManage->manageRisk->step5->name,
        'desc'   => $lang->tutorial->scrumProjectManage->manageRisk->step5->desc
    );

    $scrumProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'click',
        'target' => 'div[data-row="1"] a.risk-track-btn',
        'page'   => 'risk-browse',
        'url'    => array('risk', 'browse', 'projectID=2'),
        'title'  => $lang->tutorial->scrumProjectManage->manageRisk->step6->name,
        'desc'   => $lang->tutorial->scrumProjectManage->manageRisk->step6->desc
    );

    $scrumProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'form',
        'target' => '#form-risk-track',
        'page'   => 'risk-browse',
        'title'  => $lang->tutorial->scrumProjectManage->manageRisk->step7->name
    );

    $scrumProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'saveForm',
        'target' => '#form-risk-track button[type="submit"]',
        'page'   => 'risk-browse',
        'title'  => $lang->tutorial->scrumProjectManage->manageRisk->step8->name,
        'desc'   => $lang->tutorial->scrumProjectManage->manageRisk->step8->desc
    );

    $scrumProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'click',
        'target' => 'div[data-row="1"] a.risk-close-btn',
        'page'   => 'risk-browse',
        'url'    => array('risk', 'browse', 'projectID=2'),
        'title'  => $lang->tutorial->scrumProjectManage->manageRisk->step9->name,
        'desc'   => $lang->tutorial->scrumProjectManage->manageRisk->step9->desc
    );

    $scrumProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'form',
        'target' => '#risk-close-form',
        'page'   => 'risk-browse',
        'title'  => $lang->tutorial->scrumProjectManage->manageRisk->step10->name
    );

    $scrumProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'saveForm',
        'target' => '#risk-close-form button[type="submit"]',
        'page'   => 'risk-browse',
        'title'  => $lang->tutorial->scrumProjectManage->manageRisk->step11->name
    );
}
