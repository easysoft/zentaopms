<?php
global $lang,$config;

$waterfallProjectManage = new stdClass();
$waterfallProjectManage->basic = new stdClass();
$waterfallProjectManage->basic->name    = 'waterfallProjectManageBasic';
$waterfallProjectManage->basic->title   = $lang->tutorial->waterfallProjectManage->title;
$waterfallProjectManage->basic->icon    = 'waterfall text-special';
$waterfallProjectManage->basic->type    = 'basic';
$waterfallProjectManage->basic->modules = 'project,execution,build,task,bug,testreport,issue,risk,programplan,design,review';
$waterfallProjectManage->basic->app     = 'project';
$waterfallProjectManage->basic->tasks   = array();

$waterfallProjectManage->basic->tasks['manageProject'] = array();
$waterfallProjectManage->basic->tasks['manageProject']['name']     = 'manageProject';
$waterfallProjectManage->basic->tasks['manageProject']['title']    = $lang->tutorial->waterfallProjectManage->manageProject->title;
$waterfallProjectManage->basic->tasks['manageProject']['startUrl'] = array('project', 'browse');
$waterfallProjectManage->basic->tasks['manageProject']['steps']    = array();

$waterfallProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'project',
    'title' => $lang->tutorial->waterfallProjectManage->manageProject->step1->name,
    'desc'  => $lang->tutorial->waterfallProjectManage->manageProject->step1->desc
);

$waterfallProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar .create-project-btn',
    'page'   => 'project-browse',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step2->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step2->desc
);

$waterfallProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#modelList div.waterfall div.model-item',
    'page'   => 'project-browse',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step3->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step3->desc
);

$waterfallProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'   => 'form',
    'target' => '#form-project-create',
    'page'   => 'project-create',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step4->name
);

$waterfallProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#form-project-create .form-actions button[type="submit"]',
    'page'   => 'project-create',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step5->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step5->desc
);

$waterfallProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-project-browse .dtable-body div[data-row="2"] a',
    'page'   => 'project-browse',
    'url'    => array('project', 'browse'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step6->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step6->desc
);

$waterfallProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'settings',
    'page'   => 'project-execution',
    'url'    => array('project', 'execution', 'status=all&projectID=2'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step7->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step7->desc
);

$waterfallProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'   => 'clickMainNavbar',
    'target' => 'members',
    'page'   => 'project-view',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step8->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step8->desc
);

$waterfallProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#mainContainer #mainMenu #actionBar a',
    'page'   => 'project-team',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step9->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step9->desc
);

$waterfallProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'   => 'form',
    'target' => '#teamForm table',
    'page'   => 'project-manageMembers',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step10->name,
);

$waterfallProjectManage->basic->tasks['manageProject']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#teamForm #saveButton',
    'page'   => 'project-manageMembers',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step11->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step11->desc
);

$waterfallProjectManage->basic->tasks['setStage'] = array();
$waterfallProjectManage->basic->tasks['setStage']['name']     = 'setStage';
$waterfallProjectManage->basic->tasks['setStage']['title']    = $lang->tutorial->waterfallProjectManage->setStage->title;
$waterfallProjectManage->basic->tasks['setStage']['startUrl'] = array('project', 'execution', 'status=all&projectID=2');
$waterfallProjectManage->basic->tasks['setStage']['steps']    = array();

$waterfallProjectManage->basic->tasks['setStage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'execution',
    'page'   => 'project-execution',
    'title'  => $lang->tutorial->waterfallProjectManage->setStage->step1->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->setStage->step1->desc
);

$waterfallProjectManage->basic->tasks['setStage']['steps'][] = array(
    'type'   => 'click',
    'target' => in_array($config->edition, array('max', 'ipd')) ? '#actionBar a.programplan-create-btn' : '#actionBar a.create-execution-btn',
    'page'   => in_array($config->edition, array('max', 'ipd')) ? 'programplan-browse' : 'project-execution',
    'url'    => in_array($config->edition, array('max', 'ipd')) ? array('programplan', 'browse', 'projectID=2') : array('project', 'execution', 'status=all&projectID=2'),
    'title'  => $lang->tutorial->waterfallProjectManage->setStage->step2->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->setStage->step2->desc
);

$waterfallProjectManage->basic->tasks['setStage']['steps'][] = array(
    'type'   => 'form',
    'target' => '#dataform div.form-batch-container',
    'page'   => 'programplan-create',
    'title'  => $lang->tutorial->waterfallProjectManage->setStage->step3->name
);

$waterfallProjectManage->basic->tasks['setStage']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#dataform button[type="submit"]',
    'page'   => 'programplan-create',
    'title'  => $lang->tutorial->waterfallProjectManage->setStage->step4->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->setStage->step4->desc
);

if(in_array($config->edition, array('max', 'ipd')))
{
    $waterfallProjectManage->basic->tasks['setStage']['steps'][] = array(
        'type'   => 'click',
        'target' => '#actionBar a.switchBtn',
        'page'   => 'programplan-browse',
        'url'    => array('programplan', 'browse', 'projectID=2'),
        'title'  => $lang->tutorial->waterfallProjectManage->setStage->step5->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->setStage->step5->desc
    );
}

$waterfallProjectManage->basic->tasks['setStage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-project-execution div[data-col="nameCol"][data-row="pid3"] a',
    'page'   => 'project-execution',
    'url'    => array('project', 'execution', 'status=all&projectID=2'),
    'title'  => $lang->tutorial->waterfallProjectManage->setStage->step6->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->setStage->step6->desc
);

$waterfallProjectManage->basic->tasks['setStage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'burn',
    'page'   => 'execution-task',
    'app'    => 'execution',
    'url'    => array('execution', 'task', 'executionID=3'),
    'title'  => $lang->tutorial->waterfallProjectManage->setStage->step7->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->setStage->step7->desc
);

$waterfallProjectManage->basic->tasks['manageTask'] = array();
$waterfallProjectManage->basic->tasks['manageTask']['name']     = 'manageTask';
$waterfallProjectManage->basic->tasks['manageTask']['title']    = $lang->tutorial->waterfallProjectManage->manageTask->title;
$waterfallProjectManage->basic->tasks['manageTask']['startUrl'] = array('execution', 'task', 'executionID=3');
$waterfallProjectManage->basic->tasks['manageTask']['steps']    = array();

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'story',
    'page'   => 'execution-task',
    'app'    => 'execution',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step1->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTask->step1->desc
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-execution-story div[data-row="3"] a.batchcreate-task-btn',
    'url'    => array('execution', 'story', 'executionID=3'),
    'page'   => 'execution-story',
    'app'    => 'execution',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step3->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTask->step3->desc
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'form',
    'target' => '#taskBatchCreateForm form .form-batch-container',
    'app'    => 'execution',
    'page'   => 'task-batchcreate',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step4->name,
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#taskBatchCreateForm form button[type="submit"]',
    'page'   => 'task-batchcreate',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step5->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTask->step5->desc
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'click',
    'target' => '#tasks div[data-row="1"] a.dtable-assign-btn',
    'page'   => 'execution-task',
    'url'    => array('execution', 'task', 'executionID=3'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step6->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTask->step6->desc
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step7->name,
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'form button[type="submit"]',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step8->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTask->step8->desc
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'click',
    'target' => '#tasks div[data-row="1"] a.task-start-btn',
    'page'   => 'execution-task',
    'url'    => array('execution', 'task', 'executionID=3'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step9->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTask->step9->desc
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step10->name,
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'form button[type="submit"]',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step11->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTask->step11->desc
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'click',
    'target' => '#tasks div[data-row="1"] a.task-record-btn',
    'page'   => 'execution-task',
    'url'    => array('execution', 'task', 'executionID=3'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step12->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTask->step12->desc
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step13->name,
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'form button[type="submit"]',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step14->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTask->step14->desc
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'click',
    'target' => '#tasks div[data-row="1"] a.task-finish-btn',
    'page'   => 'execution-task',
    'url'    => array('execution', 'task', 'executionID=3'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step15->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTask->step15->desc
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step16->name,
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => 'form button[type="submit"]',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step17->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTask->step17->desc
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'build',
    'page'   => 'execution-task',
    'url'    => array('execution', 'task', 'executionID=3'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step18->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTask->step18->desc
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'click',
    'target' => '#mainContainer #actionBar a',
    'page'   => 'execution-build',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step19->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTask->step19->desc
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'build-create',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step20->name,
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => 'form button[type="submit"]',
    'page'   => 'build-create',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step21->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTask->step21->desc
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div[data-row="1"] a.build-linkstory-btn',
    'page'   => 'execution-build',
    'url'    => array('execution', 'build', 'executionID=3'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step22->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTask->step22->desc
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'selectRow',
    'target' => '#unlinkStoryList div.dtable-body div[data-col="id"]',
    'page'   => 'build-view',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step23->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTask->step23->desc
);

$waterfallProjectManage->basic->tasks['manageTask']['steps'][] = array(
    'type'   => 'click',
    'target' => '#unlinkStoryList .dtable-footer .linkObjectBtn',
    'page'   => 'build-view',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTask->step24->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTask->step24->desc
);

$waterfallProjectManage->basic->tasks['manageTest'] = array();
$waterfallProjectManage->basic->tasks['manageTest']['name']     = 'manageTest';
$waterfallProjectManage->basic->tasks['manageTest']['title']    = $lang->tutorial->waterfallProjectManage->manageTest->title;
$waterfallProjectManage->basic->tasks['manageTest']['startUrl'] = array('execution', 'task', 'executionID=3');
$waterfallProjectManage->basic->tasks['manageTest']['steps']    = array();

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'qa',
    'page'   => 'execution-task',
    'app'    => 'execution',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step1->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTest->step1->desc
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'clickMainNavbar',
    'target' => 'testcase',
    'page'   => 'execution-bug',
    'url'    => array('execution', 'bug', 'executionID=3'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step2->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTest->step2->desc
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a#createTestCaseBtn',
    'page'   => 'execution-testcase',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step3->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTest->step3->desc
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'testcase-create',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step4->name
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'testcase-create',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step5->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTest->step5->desc
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'clickMainNavbar',
    'target' => 'testtask',
    'page'   => 'execution-testcase',
    'url'    => array('execution', 'testcase', 'executionID=3'),
    'app'    => 'execution',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step14->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTest->step14->desc
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a',
    'page'   => 'execution-testtask',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step15->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTest->step15->desc
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'testtask-create',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step16->name
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'testtask-create',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step17->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTest->step17->desc
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="name"][data-row="1"] a',
    'page'   => 'execution-testtask',
    'url'    => array('execution', 'testtask', 'executionID=3'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step18->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTest->step18->desc
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.linkCase-btn',
    'page'   => 'testtask-cases',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step19->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTest->step19->desc
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'selectRow',
    'target' => 'div.dtable div.dtable-body div[data-col="id"]',
    'page'   => 'testtask-linkCase',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step20->name
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable-footer nav.toolbar button',
    'page'   => 'testtask-linkCase',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step21->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTest->step21->desc
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="actions"][data-row="1"] a.testtask-runCase-btn',
    'page'   => 'testtask-cases',
    'url'    => array('testtask', 'cases', 'taskID=1'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step6->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTest->step6->desc
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'testtask-cases',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step7->name
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'testtask-cases',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step8->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTest->step8->desc
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="actions"][data-row="1"] a.testtask-results-btn',
    'page'   => 'testtask-cases',
    'url'    => array('testtask', 'cases', 'taskID=1'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step9->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTest->step9->desc
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'click',
    'target' => '.resultSteps div.steps-body div[data-id="1"] div.step-id',
    'page'   => 'testtask-cases',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step10->name
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => 'div.resultSteps button.to-bug-button',
    'page'   => 'testtask-cases',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step11->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTest->step11->desc
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'bug-create',
    'url'    => array('bug', 'create', 'productID=1&branch=0&extras=caseID=1,version=1,resultID=1,runID=1,testtask=1,buildID=1,stepList=1'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step12->name
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'bug-create',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step13->name
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'clickMainNavbar',
    'target' => 'testtask',
    'page'   => 'testtask-cases',
    'app'    => 'execution',
    'url'    => array('testtask', 'cases', 'taskID=1'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step22->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTest->step22->desc
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'selectRow',
    'target' => '#taskTable div.dtable-body div[data-col="taskID"]',
    'page'   => 'execution-testtask',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step23->name
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable-footer nav.toolbar button',
    'page'   => 'execution-testtask',
    'app'    => 'execution',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step24->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTest->step24->desc
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'testreport-create',
    'url'    => array('testreport', 'create', 'executionID=3'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step25->name
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'testreport-create',
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step26->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTest->step26->desc
);

$waterfallProjectManage->basic->tasks['manageTest']['steps'][] = array(
    'type'   => 'clickMainNavbar',
    'target' => 'testreport',
    'page'   => 'execution-testtask',
    'app'    => 'execution',
    'url'    => array('execution', 'testtask', 'executionID=3'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageTest->step27->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageTest->step27->desc
);

$waterfallProjectManage->basic->tasks['manageBug'] = array();
$waterfallProjectManage->basic->tasks['manageBug']['name']     = 'manageBug';
$waterfallProjectManage->basic->tasks['manageBug']['title']    = $lang->tutorial->waterfallProjectManage->manageBug->title;
$waterfallProjectManage->basic->tasks['manageBug']['startUrl'] = array('execution', 'task', 'executionID=3');
$waterfallProjectManage->basic->tasks['manageBug']['steps']    = array();

$waterfallProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'qa',
    'page'   => 'execution-task',
    'app'    => 'execution',
    'title'  => $lang->tutorial->waterfallProjectManage->manageBug->step1->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageBug->step1->desc
);

$waterfallProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.createBug-btn',
    'page'   => 'execution-bug',
    'url'    => array('execution', 'bug', 'executionID=3'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageBug->step2->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageBug->step2->desc
);

$waterfallProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'bug-create',
    'title'  => $lang->tutorial->waterfallProjectManage->manageBug->step3->name
);

$waterfallProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'bug-create',
    'title'  => $lang->tutorial->waterfallProjectManage->manageBug->step4->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageBug->step4->desc
);

$waterfallProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable-cells div[data-col="actions"][data-row="1"] a.bug-confirm-btn',
    'page'   => 'execution-bug',
    'url'    => array('execution', 'bug', 'executionID=3'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageBug->step5->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageBug->step5->desc
);

$waterfallProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'execution-bug',
    'title'  => $lang->tutorial->waterfallProjectManage->manageBug->step6->name
);

$waterfallProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'execution-bug',
    'title'  => $lang->tutorial->waterfallProjectManage->manageBug->step7->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageBug->step7->desc
);

$waterfallProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable-cells div[data-col="actions"][data-row="1"] a.bug-resolve-btn',
    'page'   => 'execution-bug',
    'url'    => array('execution', 'bug', 'executionID=3'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageBug->step8->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageBug->step8->desc
);

$waterfallProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'execution-bug',
    'title'  => $lang->tutorial->waterfallProjectManage->manageBug->step9->name
);

$waterfallProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'execution-bug',
    'title'  => $lang->tutorial->waterfallProjectManage->manageBug->step10->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageBug->step10->desc
);

$waterfallProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable-cells div[data-col="actions"][data-row="2"] a.bug-close-btn',
    'page'   => 'execution-bug',
    'url'    => array('execution', 'bug', 'executionID=3'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageBug->step11->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageBug->step11->desc
);

$waterfallProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'execution-bug',
    'title'  => $lang->tutorial->waterfallProjectManage->manageBug->step12->name
);

$waterfallProjectManage->basic->tasks['manageBug']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'execution-bug',
    'title'  => $lang->tutorial->waterfallProjectManage->manageBug->step13->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageBug->step13->desc
);

$waterfallProjectManage->advance = new stdClass();
$waterfallProjectManage->advance = clone $waterfallProjectManage->basic;
$waterfallProjectManage->advance->name  = 'waterfallProjectManageAdvance';
$waterfallProjectManage->advance->type  = 'advance';
$waterfallProjectManage->advance->tasks = array();
$waterfallProjectManage->advance->tasks['manageProject'] = $waterfallProjectManage->basic->tasks['manageProject'];
$waterfallProjectManage->advance->tasks['setStage']      = $waterfallProjectManage->basic->tasks['setStage'];
$waterfallProjectManage->advance->tasks['manageTask']    = $waterfallProjectManage->basic->tasks['manageTask'];

if(in_array($config->edition, array('max', 'ipd')))
{
    $waterfallProjectManage->advance->tasks['design'] = array();
    $waterfallProjectManage->advance->tasks['design']['name']     = 'design';
    $waterfallProjectManage->advance->tasks['design']['title']    = $lang->tutorial->waterfallProjectManage->design->title;
    $waterfallProjectManage->advance->tasks['design']['startUrl'] = array('project', 'execution', 'status=all&projectID=2');
    $waterfallProjectManage->advance->tasks['design']['steps']    = array();

    $waterfallProjectManage->advance->tasks['design']['steps'][] = array(
        'type'   => 'clickNavbar',
        'target' => 'design',
        'page'   => 'project-execution',
        'url'    => array('project', 'execution', 'status=all&projectID=2'),
        'title'  => $lang->tutorial->waterfallProjectManage->design->step1->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->design->step1->desc
    );

    $waterfallProjectManage->advance->tasks['design']['steps'][] = array(
        'type'   => 'click',
        'target' => 'a.design-create-btn',
        'page'   => 'design-browse',
        'url'    => array('design', 'browse', 'projectID=2&productID=1'),
        'title'  => $lang->tutorial->waterfallProjectManage->design->step2->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->design->step2->desc
    );

    $waterfallProjectManage->advance->tasks['design']['steps'][] = array(
        'type'   => 'form',
        'page'   => 'design-create',
        'title'  => $lang->tutorial->waterfallProjectManage->design->step3->name
    );

    $waterfallProjectManage->advance->tasks['design']['steps'][] = array(
        'type'   => 'saveForm',
        'page'   => 'design-create',
        'title'  => $lang->tutorial->waterfallProjectManage->design->step4->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->design->step4->desc
    );

    $waterfallProjectManage->advance->tasks['design']['steps'][] = array(
        'type'   => 'click',
        'target' => 'div[data-col="name"][data-row="1"] a',
        'page'   => 'design-browse',
        'url'    => array('design', 'browse', 'projectID=2'),
        'title'  => $lang->tutorial->waterfallProjectManage->design->step5->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->design->step5->desc
    );

    $waterfallProjectManage->advance->tasks['design']['steps'][] = array(
        'type'   => 'click',
        'target' => 'a.linkCommit-btn',
        'page'   => 'design-view',
        'title'  => $lang->tutorial->waterfallProjectManage->design->step6->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->design->step6->desc
    );

    $waterfallProjectManage->advance->tasks['design']['steps'][] = array(
        'type'   => 'selectRow',
        'target' => '#table-design-linkcommit .dtable-body .dtable-checkbox',
        'page'   => 'design-view',
        'title'  => $lang->tutorial->waterfallProjectManage->design->step7->name
    );

    $waterfallProjectManage->advance->tasks['design']['steps'][] = array(
        'type'   => 'click',
        'target' => '#table-design-linkcommit div.dtable-footer nav.toolbar button',
        'page'   => 'design-view',
        'title'  => $lang->tutorial->waterfallProjectManage->design->step8->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->design->step8->desc
    );

    $waterfallProjectManage->advance->tasks['review'] = array();
    $waterfallProjectManage->advance->tasks['review']['name']     = 'review';
    $waterfallProjectManage->advance->tasks['review']['title']    = $lang->tutorial->waterfallProjectManage->review->title;
    $waterfallProjectManage->advance->tasks['review']['startUrl'] = array('project', 'execution', 'status=all&projectID=2');
    $waterfallProjectManage->advance->tasks['review']['steps']    = array();

    $waterfallProjectManage->advance->tasks['review']['steps'][] = array(
        'type'   => 'clickNavbar',
        'target' => 'review',
        'page'   => 'project-execution',
        'url'    => array('project', 'execution', 'status=all&projectID=2'),
        'title'  => $lang->tutorial->waterfallProjectManage->review->step1->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->review->step1->desc
    );

    $waterfallProjectManage->advance->tasks['review']['steps'][] = array(
        'type'   => 'clickMainNavbar',
        'target' => 'browse',
        'page'   => 'review-browse',
        'url'    => array('review', 'browse', 'projectID=2'),
        'title'  => $lang->tutorial->waterfallProjectManage->review->step2->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->review->step2->desc
    );

    $waterfallProjectManage->advance->tasks['review']['steps'][] = array(
        'type'   => 'click',
        'target' => '#actionBar a.review-create-btn',
        'page'   => 'review-browse',
        'url'    => array('review', 'browse', 'projectID=2'),
        'title'  => $lang->tutorial->waterfallProjectManage->review->step3->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->review->step3->desc
    );

    $waterfallProjectManage->advance->tasks['review']['steps'][] = array(
        'type'   => 'form',
        'page'   => 'review-create',
        'title'  => $lang->tutorial->waterfallProjectManage->review->step4->name
    );

    $waterfallProjectManage->advance->tasks['review']['steps'][] = array(
        'type'   => 'saveForm',
        'page'   => 'review-create',
        'title'  => $lang->tutorial->waterfallProjectManage->review->step5->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->review->step5->desc
    );

    $waterfallProjectManage->advance->tasks['review']['steps'][] = array(
        'type'   => 'click',
        'target' => 'div[data-row="1"] a.review-toaudit-btn',
        'page'   => 'review-browse',
        'url'    => array('review', 'browse', 'projectID=2'),
        'title'  => $lang->tutorial->waterfallProjectManage->review->step6->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->review->step6->desc
    );

    $waterfallProjectManage->advance->tasks['review']['steps'][] = array(
        'type'   => 'form',
        'page'   => 'review-browse',
        'title'  => $lang->tutorial->waterfallProjectManage->review->step7->name
    );

    $waterfallProjectManage->advance->tasks['review']['steps'][] = array(
        'type'   => 'saveForm',
        'page'   => 'review-browse',
        'title'  => $lang->tutorial->waterfallProjectManage->review->step8->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->review->step8->desc
    );
    unset($waterfallProjectManage->advance->tasks['review']);

    $waterfallProjectManage->advance->tasks['manageIssue'] = array();
    $waterfallProjectManage->advance->tasks['manageIssue']['name']     = 'manageIssue';
    $waterfallProjectManage->advance->tasks['manageIssue']['title']    = $lang->tutorial->waterfallProjectManage->manageIssue->title;
    $waterfallProjectManage->advance->tasks['manageIssue']['startUrl'] = array('project', 'execution', 'status=all&projectID=2');
    $waterfallProjectManage->advance->tasks['manageIssue']['steps']    = array();

    $waterfallProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'clickNavbar',
        'target' => 'other',
        'page'   => 'project-execution',
        'title'  => $lang->tutorial->waterfallProjectManage->manageIssue->step1->name
    );

    $waterfallProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'click',
        'target' => '#other a[data-id="issue"]',
        'page'   => 'project-execution',
        'title'  => $lang->tutorial->waterfallProjectManage->manageIssue->step2->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->manageIssue->step2->desc
    );

    $waterfallProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'click',
        'target' => 'a.create-issue-btn',
        'page'   => 'issue-browse',
        'title'  => $lang->tutorial->waterfallProjectManage->manageIssue->step3->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->manageIssue->step3->desc
    );

    $waterfallProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'form',
        'page'   => 'issue-create',
        'title'  => $lang->tutorial->waterfallProjectManage->manageIssue->step4->name
    );

    $waterfallProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'saveForm',
        'page'   => 'issue-create',
        'title'  => $lang->tutorial->waterfallProjectManage->manageIssue->step5->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->manageIssue->step5->desc
    );

    $waterfallProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'click',
        'target' => 'div[data-row="1"] a.issue-confirm-btn',
        'page'   => 'issue-browse',
        'url'    => array('issue', 'browse', 'projectID=2'),
        'title'  => $lang->tutorial->waterfallProjectManage->manageIssue->step6->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->manageIssue->step6->desc
    );

    $waterfallProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'form',
        'target' => '#confirmPanel',
        'page'   => 'issue-browse',
        'title'  => $lang->tutorial->waterfallProjectManage->manageIssue->step7->name
    );

    $waterfallProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'saveForm',
        'target' => '#confirmPanel button[type="submit"]',
        'page'   => 'issue-browse',
        'title'  => $lang->tutorial->waterfallProjectManage->manageIssue->step8->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->manageIssue->step8->desc
    );

    $waterfallProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'click',
        'target' => 'div[data-row="2"] a.issue-resolve-btn',
        'page'   => 'issue-browse',
        'url'    => array('issue', 'browse', 'projectID=2'),
        'title'  => $lang->tutorial->waterfallProjectManage->manageIssue->step9->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->manageIssue->step9->desc
    );

    $waterfallProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'form',
        'target' => '#resolvePanel',
        'page'   => 'issue-browse',
        'title'  => $lang->tutorial->waterfallProjectManage->manageIssue->step10->name
    );

    $waterfallProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'saveForm',
        'target' => '#resolvePanel button[type="submit"]',
        'page'   => 'issue-browse',
        'title'  => $lang->tutorial->waterfallProjectManage->manageIssue->step11->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->manageIssue->step11->desc
    );

    $waterfallProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'click',
        'target' => 'div[data-row="2"] a.issue-close-btn',
        'page'   => 'issue-browse',
        'url'    => array('issue', 'browse', 'projectID=2'),
        'title'  => $lang->tutorial->waterfallProjectManage->manageIssue->step12->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->manageIssue->step12->desc
    );

    $waterfallProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'form',
        'target' => '#closePanel',
        'page'   => 'issue-browse',
        'title'  => $lang->tutorial->waterfallProjectManage->manageIssue->step13->name
    );

    $waterfallProjectManage->advance->tasks['manageIssue']['steps'][] = array(
        'type'   => 'saveForm',
        'target' => '#closePanel button[type="submit"]',
        'page'   => 'issue-browse',
        'title'  => $lang->tutorial->waterfallProjectManage->manageIssue->step14->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->manageIssue->step14->desc
    );

    $waterfallProjectManage->advance->tasks['manageRisk'] = array();
    $waterfallProjectManage->advance->tasks['manageRisk']['name']     = 'manageRisk';
    $waterfallProjectManage->advance->tasks['manageRisk']['title']    = $lang->tutorial->waterfallProjectManage->manageRisk->title;
    $waterfallProjectManage->advance->tasks['manageRisk']['startUrl'] = array('project', 'execution', 'status=all&projectID=2');
    $waterfallProjectManage->advance->tasks['manageRisk']['steps']    = array();

    $waterfallProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'clickNavbar',
        'target' => 'other',
        'page'   => 'project-execution',
        'title'  => $lang->tutorial->waterfallProjectManage->manageRisk->step1->name
    );

    $waterfallProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'click',
        'target' => '#other a[data-id="risk"]',
        'page'   => 'project-execution',
        'title'  => $lang->tutorial->waterfallProjectManage->manageRisk->step2->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->manageRisk->step2->desc
    );

    $waterfallProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'click',
        'target' => 'a.create-risk-btn',
        'page'   => 'risk-browse',
        'title'  => $lang->tutorial->waterfallProjectManage->manageRisk->step3->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->manageRisk->step3->desc
    );

    $waterfallProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'form',
        'page'   => 'risk-create',
        'title'  => $lang->tutorial->waterfallProjectManage->manageRisk->step4->name
    );

    $waterfallProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'saveForm',
        'page'   => 'risk-create',
        'title'  => $lang->tutorial->waterfallProjectManage->manageRisk->step5->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->manageRisk->step5->desc
    );

    $waterfallProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'click',
        'target' => 'div[data-row="1"] a.risk-track-btn',
        'page'   => 'risk-browse',
        'url'    => array('risk', 'browse', 'projectID=2'),
        'title'  => $lang->tutorial->waterfallProjectManage->manageRisk->step6->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->manageRisk->step6->desc
    );

    $waterfallProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'form',
        'target' => '#form-risk-track',
        'page'   => 'risk-browse',
        'title'  => $lang->tutorial->waterfallProjectManage->manageRisk->step7->name
    );

    $waterfallProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'saveForm',
        'target' => '#form-risk-track button[type="submit"]',
        'page'   => 'risk-browse',
        'title'  => $lang->tutorial->waterfallProjectManage->manageRisk->step8->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->manageRisk->step8->desc
    );

    $waterfallProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'click',
        'target' => 'div[data-row="1"] a.risk-close-btn',
        'page'   => 'risk-browse',
        'url'    => array('risk', 'browse', 'projectID=2'),
        'title'  => $lang->tutorial->waterfallProjectManage->manageRisk->step9->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->manageRisk->step9->desc
    );

    $waterfallProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'form',
        'target' => '#risk-close-form',
        'page'   => 'risk-browse',
        'title'  => $lang->tutorial->waterfallProjectManage->manageRisk->step10->name
    );

    $waterfallProjectManage->advance->tasks['manageRisk']['steps'][] = array(
        'type'   => 'saveForm',
        'target' => '#risk-close-form button[type="submit"]',
        'page'   => 'risk-browse',
        'title'  => $lang->tutorial->waterfallProjectManage->manageRisk->step11->name
    );
}

$waterfallProjectManage->advance->tasks['manageTest'] = $waterfallProjectManage->basic->tasks['manageTest'];
$waterfallProjectManage->advance->tasks['manageBug']  = $waterfallProjectManage->basic->tasks['manageBug'];
