<?php
global $lang,$config;

$testManage = new stdClass();
$testManage->name    = 'testManage';
$testManage->title   = $lang->tutorial->testManage->title;
$testManage->icon    = 'sitemap text-special';
$testManage->type    = 'basic';
$testManage->modules = 'testcase,testreport,testtask,bug';
$testManage->app     = 'project';
$testManage->tasks   = array();

$testManage->tasks['testManage'] = array();
$testManage->tasks['testManage']['name']     = 'testManage';
$testManage->tasks['testManage']['title']    = $lang->tutorial->testManage->title;
$testManage->tasks['testManage']['startUrl'] = array('qa', 'index');
$testManage->tasks['testManage']['steps']    = array();

$testManage->tasks['testManage']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'qa',
    'title' => $lang->tutorial->testManage->step1->name,
    'desc'  => $lang->tutorial->testManage->step1->desc
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'testcase',
    'page'   => 'qa-index',
    'title'  => $lang->tutorial->testManage->step2->name,
    'desc'   => $lang->tutorial->testManage->step2->desc
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.createBtn',
    'page'   => 'testcase-browse',
    'url'    => array('testcase', 'browse', 'productID=1'),
    'title'  => $lang->tutorial->testManage->step3->name,
    'desc'   => $lang->tutorial->testManage->step3->desc
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'testcase-create',
    'title'  => $lang->tutorial->testManage->step4->name
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'testcase-create',
    'title'  => $lang->tutorial->testManage->step5->name,
    'desc'   => $lang->tutorial->testManage->step5->desc
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'testtask',
    'page'   => 'testcase-browse',
    'url'    => array('testcase', 'browse', 'productID=1'),
    'title'  => $lang->tutorial->testManage->step6->name,
    'desc'   => $lang->tutorial->testManage->step6->desc
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a',
    'page'   => 'testtask-browse',
    'title'  => $lang->tutorial->testManage->step7->name,
    'desc'   => $lang->tutorial->testManage->step7->desc
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'testtask-create',
    'title'  => $lang->tutorial->testManage->step8->name
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'testtask-create',
    'title'  => $lang->tutorial->testManage->step9->name,
    'desc'   => $lang->tutorial->testManage->step9->desc
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="name"][data-row="1"] a',
    'page'   => 'testtask-browse',
    'url'    => array('testtask', 'browse', 'productID=1'),
    'title'  => $lang->tutorial->testManage->step10->name,
    'desc'   => $lang->tutorial->testManage->step10->desc
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.linkCase-btn',
    'page'   => 'testtask-cases',
    'title'  => $lang->tutorial->testManage->step11->name,
    'desc'   => $lang->tutorial->testManage->step11->desc
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'selectRow',
    'target' => 'div.dtable div.dtable-body div[data-col="id"]',
    'page'   => 'testtask-linkCase',
    'title'  => $lang->tutorial->testManage->step12->name
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable-footer nav.toolbar button',
    'page'   => 'testtask-linkCase',
    'title'  => $lang->tutorial->testManage->step13->name,
    'desc'   => $lang->tutorial->testManage->step13->desc
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="actions"][data-row="1"] a.testtask-runCase-btn',
    'page'   => 'testtask-cases',
    'url'    => array('testtask', 'cases', 'taskID=1'),
    'title'  => $lang->tutorial->testManage->step14->name,
    'desc'   => $lang->tutorial->testManage->step14->desc
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'testtask-cases',
    'title'  => $lang->tutorial->testManage->step15->name
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'testtask-cases',
    'title'  => $lang->tutorial->testManage->step16->name,
    'desc'   => $lang->tutorial->testManage->step16->desc
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="actions"][data-row="1"] a.testtask-results-btn',
    'page'   => 'testtask-cases',
    'url'    => array('testtask', 'cases', 'taskID=1'),
    'title'  => $lang->tutorial->testManage->step17->name,
    'desc'   => $lang->tutorial->testManage->step17->desc
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'form',
    'target' => '.resultSteps div.steps-body',
    'page'   => 'testtask-cases',
    'title'  => $lang->tutorial->testManage->step18->name
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => 'div.resultSteps button.to-bug-button',
    'page'   => 'testtask-cases',
    'title'  => $lang->tutorial->testManage->step19->name,
    'desc'   => $lang->tutorial->testManage->step19->desc
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'bug-create',
    'url'    => array('bug', 'create', 'productID=1'),
    'title'  => $lang->tutorial->testManage->step20->name
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'bug-create',
    'title'  => $lang->tutorial->testManage->step21->name
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'testtask',
    'page'   => 'bug-browse',
    'url'    => array('bug', 'browse', 'productID=1'),
    'title'  => $lang->tutorial->testManage->step22->name
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="actions"] a.testreport-browse-btn',
    'page'   => 'testtask-browse',
    'title'  => $lang->tutorial->testManage->step23->name,
    'desc'   => $lang->tutorial->testManage->step23->desc
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'testreport-create',
    'url'    => array('testreport', 'create', 'executionID=3'),
    'title'  => $lang->tutorial->testManage->step24->name
);

$testManage->tasks['testManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'testreport-create',
    'title'  => $lang->tutorial->testManage->step25->name,
    'desc'   => $lang->tutorial->testManage->step25->desc
);
