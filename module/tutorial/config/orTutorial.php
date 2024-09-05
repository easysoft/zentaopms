<?php
global $lang,$config;

$orTutorial = new stdClass();
$orTutorial->demandpoolManage = new stdClass();
$orTutorial->demandpoolManage->name    = 'demandManage';
$orTutorial->demandpoolManage->title   = $lang->tutorial->orTutorial->demandpoolManage->title;
$orTutorial->demandpoolManage->icon    = 'bars text-special';
$orTutorial->demandpoolManage->type    = 'basic';
$orTutorial->demandpoolManage->modules = 'demandpool,demand';
$orTutorial->demandpoolManage->app     = 'demandpool';
$orTutorial->demandpoolManage->tasks   = array();

$orTutorial->demandpoolManage->tasks['demandManage'] = array();
$orTutorial->demandpoolManage->tasks['demandManage']['name']     = 'demandManage';
$orTutorial->demandpoolManage->tasks['demandManage']['title']    = $lang->tutorial->orTutorial->demandpoolManage->demandManage->title;
$orTutorial->demandpoolManage->tasks['demandManage']['startUrl'] = array('demand', 'browse', 'poolID=1');
$orTutorial->demandpoolManage->tasks['demandManage']['steps']    = array();

$orTutorial->demandpoolManage->tasks['demandManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.create-demand-btn',
    'page'   => 'demand-browse',
    'title'  => $lang->tutorial->orTutorial->demandpoolManage->demandManage->step1->name,
    'desc'   => $lang->tutorial->orTutorial->demandpoolManage->demandManage->step1->desc
);

$orTutorial->demandpoolManage->tasks['demandManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'demand-create',
    'title'  => $lang->tutorial->orTutorial->demandpoolManage->demandManage->step2->name
);

$orTutorial->demandpoolManage->tasks['demandManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'demand-create',
    'title'  => $lang->tutorial->orTutorial->demandpoolManage->demandManage->step3->name,
    'desc'   => $lang->tutorial->orTutorial->demandpoolManage->demandManage->step3->desc
);

$orTutorial->demandpoolManage->tasks['demandManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="actions"][data-row="2"] a.demand-review-btn',
    'page'   => 'demand-browse',
    'url'    => array('demand', 'browse', 'poolID=1&browseType=all'),
    'title'  => $lang->tutorial->orTutorial->demandpoolManage->demandManage->step4->name,
    'desc'   => $lang->tutorial->orTutorial->demandpoolManage->demandManage->step4->desc
);

$orTutorial->demandpoolManage->tasks['demandManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'demand-review',
    'title'  => $lang->tutorial->orTutorial->demandpoolManage->demandManage->step5->name,
);

$orTutorial->demandpoolManage->tasks['demandManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'demand-review',
    'title'  => $lang->tutorial->orTutorial->demandpoolManage->demandManage->step6->name,
    'desc'   => $lang->tutorial->orTutorial->demandpoolManage->demandManage->step6->desc
);

$orTutorial->demandpoolManage->tasks['demandManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="actions"][data-row="1"] a.demand-change-btn',
    'page'   => 'demand-browse',
    'url'    => array('demand', 'browse', 'poolID=1&browseType=all'),
    'title'  => $lang->tutorial->orTutorial->demandpoolManage->demandManage->step7->name,
    'desc'   => $lang->tutorial->orTutorial->demandpoolManage->demandManage->step7->desc
);

$orTutorial->demandpoolManage->tasks['demandManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'demand-change',
    'title'  => $lang->tutorial->orTutorial->demandpoolManage->demandManage->step8->name,
);

$orTutorial->demandpoolManage->tasks['demandManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'demand-change',
    'title'  => $lang->tutorial->orTutorial->demandpoolManage->demandManage->step9->name,
    'desc'   => $lang->tutorial->orTutorial->demandpoolManage->demandManage->step9->desc
);

$orTutorial->demandpoolManage->tasks['demandManage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'track',
    'page'   => 'demand-browse',
    'url'    => array('demand', 'browse', 'poolID=1&browseType=all'),
    'title'  => $lang->tutorial->orTutorial->demandpoolManage->demandManage->step10->name,
    'desc'   => $lang->tutorial->orTutorial->demandpoolManage->demandManage->step10->desc
);

$orTutorial->marketManage = new stdClass();
$orTutorial->marketManage->name    = 'marketManage';
$orTutorial->marketManage->title   = $lang->tutorial->orTutorial->marketManage->title;
$orTutorial->marketManage->icon    = 'market text-special';
$orTutorial->marketManage->type    = 'basic';
$orTutorial->marketManage->modules = 'marketreport';
$orTutorial->marketManage->app     = 'market';
$orTutorial->marketManage->tasks   = array();

$orTutorial->marketManage->tasks['marketManage'] = array();
$orTutorial->marketManage->tasks['marketManage']['name']     = 'marketManage';
$orTutorial->marketManage->tasks['marketManage']['title']    = $lang->tutorial->orTutorial->marketManage->title;
$orTutorial->marketManage->tasks['marketManage']['startUrl'] = array('marketreport', 'all');
$orTutorial->marketManage->tasks['marketManage']['steps']    = array();

$orTutorial->roadmapManage = new stdClass();
$orTutorial->roadmapManage->name    = 'roadmapManage';
$orTutorial->roadmapManage->title   = $lang->tutorial->orTutorial->roadmapManage->title;
$orTutorial->roadmapManage->icon    = 'product text-special';
$orTutorial->roadmapManage->type    = 'basic';
$orTutorial->roadmapManage->modules = 'product';
$orTutorial->roadmapManage->app     = 'product';
$orTutorial->roadmapManage->tasks   = array();

$orTutorial->roadmapManage->tasks['roadmapManage'] = array();
$orTutorial->roadmapManage->tasks['roadmapManage']['name']     = 'roadmapManage';
$orTutorial->roadmapManage->tasks['roadmapManage']['title']    = $lang->tutorial->orTutorial->roadmapManage->title;
$orTutorial->roadmapManage->tasks['roadmapManage']['startUrl'] = array('product', 'all');
$orTutorial->roadmapManage->tasks['roadmapManage']['steps']    = array();

$orTutorial->charterManage = new stdClass();
$orTutorial->charterManage->name    = 'charterManage';
$orTutorial->charterManage->title   = $lang->tutorial->orTutorial->charterManage->title;
$orTutorial->charterManage->icon    = 'project text-special';
$orTutorial->charterManage->type    = 'basic';
$orTutorial->charterManage->modules = 'charter';
$orTutorial->charterManage->app     = 'charter';
$orTutorial->charterManage->tasks   = array();

$orTutorial->charterManage->tasks['charterManage'] = array();
$orTutorial->charterManage->tasks['charterManage']['name']     = 'charterManage';
$orTutorial->charterManage->tasks['charterManage']['title']    = $lang->tutorial->orTutorial->charterManage->title;
$orTutorial->charterManage->tasks['charterManage']['startUrl'] = array('charter', 'browse');
$orTutorial->charterManage->tasks['charterManage']['steps']    = array();
