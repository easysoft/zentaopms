<?php
global $lang,$config;

$waterfallProjectManage = new stdClass();
$waterfallProjectManage->name    = 'waterfallProjectManage';
$waterfallProjectManage->title   = $lang->tutorial->waterfallProjectManage->title;
$waterfallProjectManage->icon    = 'waterfall text-special';
$waterfallProjectManage->type    = 'basic';
$waterfallProjectManage->modules = 'project,execution,build,task,bug,testreport,issue,risk,programplan';
$waterfallProjectManage->app     = 'project';
$waterfallProjectManage->tasks   = array();

$waterfallProjectManage->tasks['manageProject'] = array();
$waterfallProjectManage->tasks['manageProject']['name']     = 'manageProject';
$waterfallProjectManage->tasks['manageProject']['title']    = $lang->tutorial->waterfallProjectManage->manageProject->title;
$waterfallProjectManage->tasks['manageProject']['startUrl'] = array('project', 'browse');
$waterfallProjectManage->tasks['manageProject']['steps']    = array();

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'project',
    'title' => $lang->tutorial->waterfallProjectManage->manageProject->step1->name,
    'desc'  => $lang->tutorial->waterfallProjectManage->manageProject->step1->desc
);

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar .create-project-btn',
    'page'   => 'project-browse',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step2->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step2->desc
);

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#modelList div.waterfall div.model-item',
    'page'   => 'project-browse',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step3->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step3->desc
);

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'form',
    'target' => '#form-project-create',
    'page'   => 'project-create',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step4->name
);

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#form-project-create .form-actions button[type="submit"]',
    'page'   => 'project-create',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step5->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step5->desc
);

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-project-browse .dtable-body div[data-row="2"] a',
    'page'   => 'project-browse',
    'url'    => array('project', 'browse'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step6->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step6->desc
);

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'settings',
    'page'   => 'project-index',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step7->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step7->desc
);

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'clickMainNavbar',
    'target' => 'members',
    'page'   => 'project-view',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step8->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step8->desc
);

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#mainContainer #mainMenu #actionBar a',
    'page'   => 'project-team',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step9->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step9->desc
);

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'form',
    'target' => '#teamForm table',
    'page'   => 'project-manageMembers',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step10->name,
);

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#teamForm #saveButton',
    'page'   => 'project-manageMembers',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step11->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step11->desc
);

$waterfallProjectManage->tasks['setStage'] = array();
$waterfallProjectManage->tasks['setStage']['name']     = 'setStage';
$waterfallProjectManage->tasks['setStage']['title']    = $lang->tutorial->waterfallProjectManage->setStage->title;
$waterfallProjectManage->tasks['setStage']['startUrl'] = array('project', 'index', 'project=2');
$waterfallProjectManage->tasks['setStage']['steps']    = array();

$waterfallProjectManage->tasks['setStage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'execution',
    'page'   => 'project-index',
    'title'  => $lang->tutorial->waterfallProjectManage->setStage->step1->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->setStage->step1->desc
);

$waterfallProjectManage->tasks['setStage']['steps'][] = array(
    'type'   => 'click',
    'target' => in_array($config->edition, array('max', 'ipd')) ? '#actionBar a.programplan-create-btn' : '#actionBar a.create-execution-btn',
    'page'   => in_array($config->edition, array('max', 'ipd')) ? 'programplan-browse' : 'project-execution',
    'url'    => in_array($config->edition, array('max', 'ipd')) ? array('programplan', 'browse', 'projectID=2') : array('project', 'execution', 'status=all&projectID=2'),
    'title'  => $lang->tutorial->waterfallProjectManage->setStage->step2->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->setStage->step2->desc
);

$waterfallProjectManage->tasks['setStage']['steps'][] = array(
    'type'   => 'form',
    'target' => '#dataform div.form-batch-container',
    'page'   => 'programplan-create',
    'title'  => $lang->tutorial->waterfallProjectManage->setStage->step3->name
);

$waterfallProjectManage->tasks['setStage']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#dataform button[type="submit"]',
    'page'   => 'programplan-create',
    'title'  => $lang->tutorial->waterfallProjectManage->setStage->step4->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->setStage->step4->desc
);

if(in_array($config->edition, array('max', 'ipd')))
{
    $waterfallProjectManage->tasks['setStage']['steps'][] = array(
        'type'   => 'click',
        'target' => '#actionBar a.switchBtn',
        'page'   => 'programplan-browse',
        'url'    => array('programplan', 'browse', 'projectID=2'),
        'title'  => $lang->tutorial->waterfallProjectManage->setStage->step5->name,
        'desc'   => $lang->tutorial->waterfallProjectManage->setStage->step5->desc
    );
}

$waterfallProjectManage->tasks['setStage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-project-execution div[data-col="nameCol"][data-row="pid3"] a',
    'page'   => 'project-execution',
    'url'    => array('project', 'execution', 'status=all&projectID=2'),
    'title'  => $lang->tutorial->waterfallProjectManage->setStage->step6->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->setStage->step6->desc
);

$waterfallProjectManage->tasks['setStage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'burn',
    'page'   => 'execution-task',
    'app'    => 'execution',
    'url'    => array('execution', 'task', 'executionID=3'),
    'title'  => $lang->tutorial->waterfallProjectManage->setStage->step7->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->setStage->step7->desc
);

$waterfallProjectManage->tasks['manageTask'] = array();
$waterfallProjectManage->tasks['manageTask'] = $scrumProjectManage->tasks['manageTask'];

$waterfallProjectManage->tasks['manageTest'] = array();
$waterfallProjectManage->tasks['manageTest'] = $scrumProjectManage->tasks['manageTest'];

$config->tutorial->guides[$waterfallProjectManage->name] = $waterfallProjectManage;