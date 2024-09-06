<?php
global $lang;

$taskManage = new stdClass();
$taskManage->name    = 'taskManage';
$taskManage->title   = $lang->tutorial->taskManage->title;
$taskManage->icon    = 'calendar text-primary';
$taskManage->type    = 'basic';
$taskManage->modules = 'project,execution,task';
$taskManage->app     = 'project';
$taskManage->tasks   = array();

$taskManage->tasks['taskManage'] = array();
$taskManage->tasks['taskManage']['name']     = 'taskManage';
$taskManage->tasks['taskManage']['title']    = $lang->tutorial->taskManage->title;
$taskManage->tasks['taskManage']['startUrl'] = array('project', 'browse');
$taskManage->tasks['taskManage']['steps']   = array();

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'project',
    'title' => $lang->tutorial->taskManage->step1->name,
    'desc'  => $lang->tutorial->taskManage->step1->desc
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar .create-project-btn',
    'page'   => 'project-browse',
    'title'  => $lang->tutorial->taskManage->step2->name,
    'desc'   => $lang->tutorial->taskManage->step2->desc
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#modelList div.scrum div.model-item',
    'page'   => 'project-browse',
    'title'  => $lang->tutorial->taskManage->step3->name,
    'desc'   => $lang->tutorial->taskManage->step3->desc
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'project-create',
    'title'  => $lang->tutorial->taskManage->step4->name
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'project-create',
    'title'  => $lang->tutorial->taskManage->step5->name,
    'desc'   => $lang->tutorial->taskManage->step5->desc
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-project-browse .dtable-body div[data-col="name"][data-row="2"] a',
    'page'   => 'project-browse',
    'url'    => array('project', 'browse'),
    'title'  => $lang->tutorial->taskManage->step6->name,
    'desc'   => $lang->tutorial->taskManage->step6->desc
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'a.createTask-btn',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->taskManage->step7->name,
    'desc'   => $lang->tutorial->taskManage->step7->desc
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'task-create',
    'title'  => $lang->tutorial->taskManage->step8->name
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'task-create',
    'title'  => $lang->tutorial->taskManage->step9->name,
    'desc'   => $lang->tutorial->taskManage->step9->desc
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-execution-task div[data-row="1"] a.dtable-assign-btn',
    'page'   => 'execution-task',
    'url'    => array('execution', 'task', 'executionID=3'),
    'title'  => $lang->tutorial->taskManage->step10->name,
    'desc'   => $lang->tutorial->taskManage->step10->desc
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->taskManage->step11->name,
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'form button[type="submit"]',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->taskManage->step12->name,
    'desc'   => $lang->tutorial->taskManage->step12->desc
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-execution-task div[data-row="1"] a.task-start-btn',
    'page'   => 'execution-task',
    'url'    => array('execution', 'task', 'executionID=3'),
    'title'  => $lang->tutorial->taskManage->step13->name,
    'desc'   => $lang->tutorial->taskManage->step13->desc
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->taskManage->step14->name,
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'form button[type="submit"]',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->taskManage->step15->name,
    'desc'   => $lang->tutorial->taskManage->step15->desc
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-execution-task div[data-row="1"] a.task-record-btn',
    'page'   => 'execution-task',
    'url'    => array('execution', 'task', 'executionID=3'),
    'title'  => $lang->tutorial->taskManage->step16->name,
    'desc'   => $lang->tutorial->taskManage->step16->desc
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->taskManage->step17->name,
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'form button[type="submit"]',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->taskManage->step18->name,
    'desc'   => $lang->tutorial->taskManage->step18->desc
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-execution-task div[data-row="1"] a.task-finish-btn',
    'page'   => 'execution-task',
    'url'    => array('execution', 'task', 'executionID=3'),
    'title'  => $lang->tutorial->taskManage->step19->name,
    'desc'   => $lang->tutorial->taskManage->step19->desc
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->taskManage->step20->name,
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => 'form button[type="submit"]',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->taskManage->step21->name,
    'desc'   => $lang->tutorial->taskManage->step21->desc
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-execution-task div[data-row="2"] a.task-close-btn',
    'page'   => 'execution-task',
    'url'    => array('execution', 'task', 'executionID=3'),
    'title'  => $lang->tutorial->taskManage->step22->name,
    'desc'   => $lang->tutorial->taskManage->step22->desc
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->taskManage->step23->name,
);

$taskManage->tasks['taskManage']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => 'form button[type="submit"]',
    'page'   => 'execution-task',
    'title'  => $lang->tutorial->taskManage->step24->name,
    'desc'   => $lang->tutorial->taskManage->step24->desc
);
