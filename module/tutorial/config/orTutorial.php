<?php
global $lang,$config;

$orTutorial = new stdClass();
$orTutorial->demandpoolManage = new stdClass();
$orTutorial->demandpoolManage->name    = 'demandManage';
$orTutorial->demandpoolManage->title   = $lang->tutorial->orTutorial->demandpoolManage->title;
$orTutorial->demandpoolManage->icon    = 'bars text-special';
$orTutorial->demandpoolManage->type    = 'basic';
$orTutorial->demandpoolManage->modules = 'demandpool,demand,marketresearch';
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

$orTutorial->marketManage->tasks['researchManage'] = array();
$orTutorial->marketManage->tasks['researchManage']['name']     = 'researchManage';
$orTutorial->marketManage->tasks['researchManage']['title']    = $lang->tutorial->orTutorial->marketManage->researchManage->title;
$orTutorial->marketManage->tasks['researchManage']['startUrl'] = array('marketresearch', 'all');
$orTutorial->marketManage->tasks['researchManage']['steps']    = array();

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a',
    'page'   => 'marketresearch-all',
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step3->name,
    'desc'   => $lang->tutorial->orTutorial->marketManage->researchManage->step3->desc
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'marketresearch-create',
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step4->name
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'marketresearch-create',
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step5->name,
    'desc'   => $lang->tutorial->orTutorial->marketManage->researchManage->step5->desc
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="name"][data-row="2"] a',
    'page'   => 'marketresearch-all',
    'url'    => array('marketresearch', 'all'),
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step6->name,
    'desc'   => $lang->tutorial->orTutorial->marketManage->researchManage->step6->desc
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.createStage-btn',
    'page'   => 'marketresearch-task',
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step7->name,
    'desc'   => $lang->tutorial->orTutorial->marketManage->researchManage->step7->desc
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'marketresearch-createStage',
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step8->name
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'marketresearch-createStage',
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step9->name,
    'desc'   => $lang->tutorial->orTutorial->marketManage->researchManage->step9->desc
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.researchtask-create-btn',
    'page'   => 'marketresearch-task',
    'url'    => array('marketresearch', 'task', 'researchID=1'),
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step10->name,
    'desc'   => $lang->tutorial->orTutorial->marketManage->researchManage->step10->desc
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'researchtask-create',
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step11->name
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'researchtask-create',
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step12->name,
    'desc'   => $lang->tutorial->orTutorial->marketManage->researchManage->step12->desc
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-marketresearch-task div[data-row="1"] a.researchtask-start-btn',
    'page'   => 'marketresearch-task',
    'url'    => array('marketresearch', 'task', 'researchID=2'),
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step13->name,
    'desc'   => $lang->tutorial->orTutorial->marketManage->researchManage->step13->desc
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'marketresearch-task',
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step14->name,
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'form button[type="submit"]',
    'page'   => 'marketresearch-task',
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step15->name,
    'desc'   => $lang->tutorial->orTutorial->marketManage->researchManage->step15->desc
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-marketresearch-task div[data-row="1"] a.researchtask-recordWorkhour-btn',
    'page'   => 'marketresearch-task',
    'url'    => array('marketresearch', 'task', 'researchID=2'),
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step16->name,
    'desc'   => $lang->tutorial->orTutorial->marketManage->researchManage->step16->desc
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'marketresearch-task',
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step17->name,
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'form button[type="submit"]',
    'page'   => 'marketresearch-task',
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step18->name,
    'desc'   => $lang->tutorial->orTutorial->marketManage->researchManage->step18->desc
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-marketresearch-task div[data-row="1"] a.researchtask-finish-btn',
    'page'   => 'marketresearch-task',
    'url'    => array('marketresearch', 'task', 'researchID=3'),
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step19->name,
    'desc'   => $lang->tutorial->orTutorial->marketManage->researchManage->step19->desc
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'marketresearch-task',
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step20->name,
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => 'form button[type="submit"]',
    'page'   => 'marketresearch-task',
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step21->name,
    'desc'   => $lang->tutorial->orTutorial->marketManage->researchManage->step21->desc
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-marketresearch-task div[data-row="2"] a.researchtask-close-btn',
    'page'   => 'marketresearch-task',
    'url'    => array('marketresearch', 'task', 'researchID=3'),
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step22->name,
    'desc'   => $lang->tutorial->orTutorial->marketManage->researchManage->step22->desc
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'marketresearch-task',
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step23->name,
);

$orTutorial->marketManage->tasks['researchManage']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => 'form button[type="submit"]',
    'page'   => 'marketresearch-task',
    'title'  => $lang->tutorial->orTutorial->marketManage->researchManage->step24->name,
    'desc'   => $lang->tutorial->orTutorial->marketManage->researchManage->step24->desc
);

$orTutorial->roadmapManage = new stdClass();
$orTutorial->roadmapManage->name    = 'roadmapManage';
$orTutorial->roadmapManage->title   = $lang->tutorial->orTutorial->roadmapManage->title;
$orTutorial->roadmapManage->icon    = 'product text-special';
$orTutorial->roadmapManage->type    = 'basic';
$orTutorial->roadmapManage->modules = 'product';
$orTutorial->roadmapManage->app     = 'product';
$orTutorial->roadmapManage->tasks   = array();

$orTutorial->roadmapManage->tasks['lineManage'] = array();
$orTutorial->roadmapManage->tasks['lineManage']['name']     = 'lineManage';
$orTutorial->roadmapManage->tasks['lineManage']['title']    = $lang->tutorial->orTutorial->roadmapManage->lineManage->title;
$orTutorial->roadmapManage->tasks['lineManage']['startUrl'] = array('product', 'all');
$orTutorial->roadmapManage->tasks['lineManage']['steps']    = array();

$orTutorial->roadmapManage->tasks['lineManage']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'product',
    'title' => $lang->tutorial->orTutorial->roadmapManage->lineManage->step1->name,
    'desc'  => $lang->tutorial->orTutorial->roadmapManage->lineManage->step1->desc
);

$orTutorial->roadmapManage->tasks['lineManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar button[data-id="manageLineModal"]',
    'page'   => 'product-all',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->lineManage->step2->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->lineManage->step2->desc
);

$orTutorial->roadmapManage->tasks['lineManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'product-all',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->lineManage->step3->name
);

$orTutorial->roadmapManage->tasks['lineManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'product-all',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->lineManage->step4->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->lineManage->step4->desc
);

$orTutorial->roadmapManage->tasks['addProduct'] = array();
$orTutorial->roadmapManage->tasks['addProduct']['name']     = 'addProduct';
$orTutorial->roadmapManage->tasks['addProduct']['title']    = $lang->tutorial->orTutorial->roadmapManage->addProduct->title;
$orTutorial->roadmapManage->tasks['addProduct']['startUrl'] = array('product', 'all');
$orTutorial->roadmapManage->tasks['addProduct']['steps']    = array();

$orTutorial->roadmapManage->tasks['addProduct']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.create-product-btn',
    'page'   => 'product-all',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->addProduct->step1->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->addProduct->step1->desc
);

$orTutorial->roadmapManage->tasks['addProduct']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'product-create',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->addProduct->step2->name
);

$orTutorial->roadmapManage->tasks['addProduct']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'product-create',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->addProduct->step3->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->addProduct->step3->desc
);

$orTutorial->roadmapManage->tasks['moduleManage'] = array();
$orTutorial->roadmapManage->tasks['moduleManage']['name']     = 'moduleManage';
$orTutorial->roadmapManage->tasks['moduleManage']['title']    = $lang->tutorial->orTutorial->roadmapManage->moduleManage->title;
$orTutorial->roadmapManage->tasks['moduleManage']['startUrl'] = array('product', 'all');
$orTutorial->roadmapManage->tasks['moduleManage']['steps']    = array();

$orTutorial->roadmapManage->tasks['moduleManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#products div.dtable-body div[data-col="name"][data-row="1"] a',
    'page'   => 'product-all',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->moduleManage->step1->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->moduleManage->step1->desc
);

$orTutorial->roadmapManage->tasks['moduleManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#moduleMenu a[href*="tree"]',
    'page'   => 'product-browse',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->moduleManage->step2->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->moduleManage->step2->desc
);

$orTutorial->roadmapManage->tasks['moduleManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'tree-browse',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->moduleManage->step3->name
);

$orTutorial->roadmapManage->tasks['moduleManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'tree-browse',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->moduleManage->step4->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->moduleManage->step4->desc
);

$orTutorial->roadmapManage->tasks['storyManage'] = array();
$orTutorial->roadmapManage->tasks['storyManage']['name']     = 'storyManage';
$orTutorial->roadmapManage->tasks['storyManage']['title']    = $lang->tutorial->orTutorial->roadmapManage->storyManage->title;
$orTutorial->roadmapManage->tasks['storyManage']['startUrl'] = array('product', 'browse', 'productID=1&branch=&browseType=all&param=0&storyType=epic');
$orTutorial->roadmapManage->tasks['storyManage']['steps']    = array();

$orTutorial->roadmapManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.create-story-btn',
    'page'   => 'product-browse',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->storyManage->step2->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->storyManage->step2->desc
);

$orTutorial->roadmapManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'story-create',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->storyManage->step3->name
);

$orTutorial->roadmapManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'story-create',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->storyManage->step4->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->storyManage->step4->desc
);

$orTutorial->roadmapManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="actions"][data-row="1"] a.batchCreateStoryBtn',
    'page'   => 'product-browse',
    'url'    => array('product', 'browse', 'productID=1&branch=&browseType=all&param=0&storyType=epic'),
    'title'  => $lang->tutorial->orTutorial->roadmapManage->storyManage->step5->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->storyManage->step5->desc
);

$orTutorial->roadmapManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'form',
    'target' => 'div.panel-body div.form-batch-container',
    'page'   => 'story-batchCreate',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->storyManage->step6->name
);

$orTutorial->roadmapManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'story-batchCreate',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->storyManage->step7->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->storyManage->step7->desc
);

$orTutorial->roadmapManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="actions"][data-row="5"] a.story-review-btn',
    'page'   => 'product-browse',
    'url'    => array('product', 'browse', 'productID=1&branch=&browseType=all&param=0&storyType=epic'),
    'title'  => $lang->tutorial->orTutorial->roadmapManage->storyManage->step11->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->storyManage->step11->desc
);

$orTutorial->roadmapManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'story-review',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->storyManage->step12->name
);

$orTutorial->roadmapManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'story-review',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->storyManage->step13->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->storyManage->step13->desc
);

$orTutorial->roadmapManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="actions"][data-row="2"] a.story-change-btn',
    'page'   => 'product-browse',
    'url'    => array('product', 'browse', 'productID=1&branch=&browseType=all&param=0&storyType=epic'),
    'title'  => $lang->tutorial->orTutorial->roadmapManage->storyManage->step14->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->storyManage->step14->desc
);

$orTutorial->roadmapManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'story-change',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->storyManage->step15->name
);

$orTutorial->roadmapManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'story-change',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->storyManage->step16->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->storyManage->step16->desc
);

$orTutorial->roadmapManage->tasks['branchManage'] = array();
$orTutorial->roadmapManage->tasks['branchManage']['name']     = 'branchManage';
$orTutorial->roadmapManage->tasks['branchManage']['title']    = $lang->tutorial->orTutorial->roadmapManage->branchManage->title;
$orTutorial->roadmapManage->tasks['branchManage']['startUrl'] = array('product', 'all');
$orTutorial->roadmapManage->tasks['branchManage']['steps']    = array();

$orTutorial->roadmapManage->tasks['branchManage']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'product',
    'title' => $lang->tutorial->orTutorial->roadmapManage->branchManage->step1->name,
    'desc'  => $lang->tutorial->orTutorial->roadmapManage->branchManage->step1->desc
);

$orTutorial->roadmapManage->tasks['branchManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.create-product-btn',
    'page'   => 'product-all',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->branchManage->step2->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->branchManage->step2->desc
);

$orTutorial->roadmapManage->tasks['branchManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'product-create',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->branchManage->step3->name
);

$orTutorial->roadmapManage->tasks['branchManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'product-create',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->branchManage->step4->name
);

$orTutorial->roadmapManage->tasks['branchManage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'settings',
    'page'   => 'product-browse',
    'url'    => array('product', 'browse', 'productID=1'),
    'title'  => $lang->tutorial->orTutorial->roadmapManage->branchManage->step5->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->branchManage->step5->desc
);

$orTutorial->roadmapManage->tasks['branchManage']['steps'][] = array(
    'type'   => 'clickMainNavbar',
    'target' => 'branch',
    'page'   => 'product-view',
    'url'    => array('product', 'view', 'productID=1'),
    'title'  => $lang->tutorial->orTutorial->roadmapManage->branchManage->step6->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->branchManage->step6->desc
);

$orTutorial->roadmapManage->tasks['branchManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.branch-create-btn',
    'page'   => 'branch-manage',
    'url'    => array('branch', 'manage', 'productID=1'),
    'title'  => $lang->tutorial->orTutorial->roadmapManage->branchManage->step7->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->branchManage->step7->desc
);

$orTutorial->roadmapManage->tasks['branchManage']['steps'][] = array(
    'type'   => 'form',
    'target' => '#createBranchForm',
    'page'   => 'branch-manage',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->branchManage->step8->name
);

$orTutorial->roadmapManage->tasks['branchManage']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#createBranchForm button[type="submit"]',
    'page'   => 'branch-manage',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->branchManage->step9->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->branchManage->step9->desc
);

$orTutorial->roadmapManage->tasks['branchManage']['steps'][] = array(
    'type'   => 'selectRow',
    'target' => 'div.dtable div[data-row="1"][data-type="checkID"]',
    'page'   => 'branch-manage',
    'url'    => array('branch', 'manage', 'productID=1'),
    'title'  => $lang->tutorial->orTutorial->roadmapManage->branchManage->step10->name
);

$orTutorial->roadmapManage->tasks['branchManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable-footer #mergeBranch',
    'page'   => 'branch-manage',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->branchManage->step11->name
);

$orTutorial->roadmapManage->tasks['branchManage']['steps'][] = array(
    'type'   => 'form',
    'target' => '#mergeForm',
    'page'   => 'branch-manage',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->branchManage->step12->name
);

$orTutorial->roadmapManage->tasks['branchManage']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#mergeForm button[type="submit"]',
    'page'   => 'branch-manage',
    'title'  => $lang->tutorial->orTutorial->roadmapManage->branchManage->step13->name,
    'desc'   => $lang->tutorial->orTutorial->roadmapManage->branchManage->step13->desc
);

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

$orTutorial->charterManage->tasks['charterManage']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'charter',
    'title' => $lang->tutorial->orTutorial->charterManage->step1->name,
    'desc'  => $lang->tutorial->orTutorial->charterManage->step1->desc
);

$orTutorial->charterManage->tasks['charterManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.charter-create-btn',
    'page'   => 'charter-browse',
    'title'  => $lang->tutorial->orTutorial->charterManage->step2->name,
    'desc'   => $lang->tutorial->orTutorial->charterManage->step2->desc
);

$orTutorial->charterManage->tasks['charterManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'charter-create',
    'title'  => $lang->tutorial->orTutorial->charterManage->step3->name
);

$orTutorial->charterManage->tasks['charterManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'charter-create',
    'title'  => $lang->tutorial->orTutorial->charterManage->step4->name,
    'desc'   => $lang->tutorial->orTutorial->charterManage->step4->desc
);

$orTutorial->charterManage->tasks['charterManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-row="1"] a.charter-review-btn',
    'page'   => 'charter-browse',
    'url'    => array('charter', 'browse'),
    'title'  => $lang->tutorial->orTutorial->charterManage->step5->name,
    'desc'   => $lang->tutorial->orTutorial->charterManage->step5->desc
);

$orTutorial->charterManage->tasks['charterManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'charter-browse',
    'title'  => $lang->tutorial->orTutorial->charterManage->step6->name
);

$orTutorial->charterManage->tasks['charterManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'charter-browse',
    'title'  => $lang->tutorial->orTutorial->charterManage->step7->name,
    'desc'   => $lang->tutorial->orTutorial->charterManage->step7->desc
);

$orTutorial->charterManage->tasks['charterManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-row="1"] a.charter-close-btn',
    'page'   => 'charter-browse',
    'url'    => array('charter', 'browse'),
    'title'  => $lang->tutorial->orTutorial->charterManage->step8->name,
    'desc'   => $lang->tutorial->orTutorial->charterManage->step8->desc
);

$orTutorial->charterManage->tasks['charterManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'charter-browse',
    'title'  => $lang->tutorial->orTutorial->charterManage->step9->name
);

$orTutorial->charterManage->tasks['charterManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'charter-browse',
    'title'  => $lang->tutorial->orTutorial->charterManage->step10->name,
    'desc'   => $lang->tutorial->orTutorial->charterManage->step10->desc
);
