<?php
global $lang;

$productManage = new stdClass();
$productManage->basic = new stdClass();
$productManage->basic->name    = 'productManageBasic';
$productManage->basic->title   = $lang->tutorial->productManage->title;
$productManage->basic->icon    = 'product text-warning text-lg';
$productManage->basic->type    = 'basic';
$productManage->basic->modules = 'product,tree,story,productplan,release,branch';
$productManage->basic->app     = 'product';
$productManage->basic->tasks   = array();

$productManage->basic->tasks['addProduct'] = array();
$productManage->basic->tasks['addProduct']['name']     = 'addProduct';
$productManage->basic->tasks['addProduct']['title']    = $lang->tutorial->productManage->addProduct->title;
$productManage->basic->tasks['addProduct']['startUrl'] = array('product', 'all');
$productManage->basic->tasks['addProduct']['steps']    = array();

$productManage->basic->tasks['addProduct']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.create-product-btn',
    'page'   => 'product-all',
    'title'  => $lang->tutorial->productManage->addProduct->step1->name,
    'desc'   => $lang->tutorial->productManage->addProduct->step1->desc
);

$productManage->basic->tasks['addProduct']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'product-create',
    'title'  => $lang->tutorial->productManage->addProduct->step2->name
);

$productManage->basic->tasks['addProduct']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'product-create',
    'title'  => $lang->tutorial->productManage->addProduct->step3->name,
    'desc'   => $lang->tutorial->productManage->addProduct->step3->desc
);

$productManage->basic->tasks['moduleManage'] = array();
$productManage->basic->tasks['moduleManage']['name']     = 'moduleManage';
$productManage->basic->tasks['moduleManage']['title']    = $lang->tutorial->productManage->moduleManage->title;
$productManage->basic->tasks['moduleManage']['startUrl'] = array('product', 'all');
$productManage->basic->tasks['moduleManage']['steps']    = array();

$productManage->basic->tasks['moduleManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#products div.dtable-body div[data-col="name"][data-row="1"] a',
    'page'   => 'product-all',
    'title'  => $lang->tutorial->productManage->moduleManage->step1->name,
    'desc'   => $lang->tutorial->productManage->moduleManage->step1->desc
);

$productManage->basic->tasks['moduleManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#moduleMenu a[href*="tree"]',
    'page'   => 'product-browse',
    'title'  => $lang->tutorial->productManage->moduleManage->step2->name,
    'desc'   => $lang->tutorial->productManage->moduleManage->step2->desc
);

$productManage->basic->tasks['moduleManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'tree-browse',
    'title'  => $lang->tutorial->productManage->moduleManage->step3->name
);

$productManage->basic->tasks['moduleManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'tree-browse',
    'title'  => $lang->tutorial->productManage->moduleManage->step4->name,
    'desc'   => $lang->tutorial->productManage->moduleManage->step4->desc
);

$productManage->basic->tasks['storyManage'] = array();
$productManage->basic->tasks['storyManage']['name']     = 'storyManage';
$productManage->basic->tasks['storyManage']['title']    = $lang->tutorial->productManage->storyManage->title;
$productManage->basic->tasks['storyManage']['startUrl'] = array('product', 'dashboard', 'productID=1');
$productManage->basic->tasks['storyManage']['steps']    = array();

$productManage->basic->tasks['storyManage']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'product',
    'title' => $lang->tutorial->productManage->lineManage->step1->name,
    'desc'  => $lang->tutorial->productManage->lineManage->step1->desc
);

$productManage->basic->tasks['storyManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#products div.dtable-body div[data-col="name"][data-row="1"] a',
    'page'   => 'product-all',
    'title'  => $lang->tutorial->productManage->moduleManage->step1->name,
    'desc'   => $lang->tutorial->productManage->moduleManage->step1->desc
);

$productManage->basic->tasks['storyManage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'epic',
    'page'   => 'product-browse',
    'url'    => array('product', 'browse', 'productID=1'),
    'title'  => $lang->tutorial->productManage->storyManage->step1->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step1->desc
);

$productManage->basic->tasks['storyManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.create-story-btn',
    'page'   => 'product-browse',
    'url'    => array('product', 'browse', 'productID=1&branch=&browseType=all&param=0&storyType=epic'),
    'title'  => $lang->tutorial->productManage->storyManage->step2->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step2->desc
);

$productManage->basic->tasks['storyManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'story-create',
    'title'  => $lang->tutorial->productManage->storyManage->step3->name
);

$productManage->basic->tasks['storyManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'story-create',
    'title'  => $lang->tutorial->productManage->storyManage->step4->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step4->desc
);

$productManage->basic->tasks['storyManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="actions"][data-row="1"] a.batchCreateStoryBtn',
    'page'   => 'product-browse',
    'url'    => array('product', 'browse', 'productID=1&branch=&browseType=all&param=0&storyType=epic'),
    'title'  => $lang->tutorial->productManage->storyManage->step5->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step5->desc
);

$productManage->basic->tasks['storyManage']['steps'][] = array(
    'type'   => 'form',
    'target' => 'div.panel-body div.form-batch-container',
    'page'   => 'story-batchCreate',
    'title'  => $lang->tutorial->productManage->storyManage->step6->name
);

$productManage->basic->tasks['storyManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'story-batchCreate',
    'title'  => $lang->tutorial->productManage->storyManage->step7->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step7->desc
);

$productManage->basic->tasks['storyManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="actions"][data-row="2"] a.batchCreateStoryBtn',
    'page'   => 'product-browse',
    'url'    => array('product', 'browse', 'productID=1&branch=&browseType=all&param=0&storyType=epic'),
    'title'  => $lang->tutorial->productManage->storyManage->step8->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step8->desc
);

$productManage->basic->tasks['storyManage']['steps'][] = array(
    'type'   => 'form',
    'target' => 'div.panel-body div.form-batch-container',
    'page'   => 'story-batchCreate',
    'title'  => $lang->tutorial->productManage->storyManage->step9->name
);

$productManage->basic->tasks['storyManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'story-batchCreate',
    'title'  => $lang->tutorial->productManage->storyManage->step10->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step10->desc
);

$productManage->basic->tasks['storyManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="actions"][data-row="4"] a.story-review-btn',
    'page'   => 'product-browse',
    'url'    => array('product', 'browse', 'productID=1&branch=&browseType=all&param=0&storyType=epic'),
    'title'  => $lang->tutorial->productManage->storyManage->step11->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step11->desc
);

$productManage->basic->tasks['storyManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'story-review',
    'title'  => $lang->tutorial->productManage->storyManage->step12->name
);

$productManage->basic->tasks['storyManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'story-review',
    'title'  => $lang->tutorial->productManage->storyManage->step13->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step13->desc
);

$productManage->basic->tasks['storyManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="actions"][data-row="3"] a.story-change-btn',
    'page'   => 'product-browse',
    'url'    => array('product', 'browse', 'productID=1&branch=&browseType=all&param=0&storyType=epic'),
    'title'  => $lang->tutorial->productManage->storyManage->step14->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step14->desc
);

$productManage->basic->tasks['storyManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'story-change',
    'title'  => $lang->tutorial->productManage->storyManage->step15->name
);

$productManage->basic->tasks['storyManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'story-change',
    'title'  => $lang->tutorial->productManage->storyManage->step16->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step16->desc
);

$productManage->basic->tasks['storyManage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'track',
    'page'   => 'product-browse',
    'url'    => array('product', 'browse', 'productID=1&branch=&browseType=all&param=0&storyType=epic'),
    'title'  => $lang->tutorial->productManage->storyManage->step17->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step17->desc
);

$productManage->basic->tasks['planManage'] = array();
$productManage->basic->tasks['planManage']['name']     = 'planManage';
$productManage->basic->tasks['planManage']['title']    = $lang->tutorial->productManage->planManage->title;
$productManage->basic->tasks['planManage']['startUrl'] = array('product', 'browse', 'productID=1&branch=&browseType=all&param=0&storyType=epic');
$productManage->basic->tasks['planManage']['steps']    = array();

$productManage->basic->tasks['planManage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'plan',
    'page'   => 'product-browse',
    'title'  => $lang->tutorial->productManage->planManage->step1->name,
    'desc'   => $lang->tutorial->productManage->planManage->step1->desc
);

$productManage->basic->tasks['planManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.plan-create-btn',
    'page'   => 'productplan-browse',
    'url'    => array('productplan', 'browse', 'productID=1'),
    'title'  => $lang->tutorial->productManage->planManage->step2->name,
    'desc'   => $lang->tutorial->productManage->planManage->step2->desc
);

$productManage->basic->tasks['planManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'productplan-create',
    'title'  => $lang->tutorial->productManage->planManage->step3->name
);

$productManage->basic->tasks['planManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'productplan-create',
    'title'  => $lang->tutorial->productManage->planManage->step4->name,
    'desc'   => $lang->tutorial->productManage->planManage->step4->desc
);

$productManage->basic->tasks['planManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="title"][data-row="1"] a',
    'page'   => 'productplan-browse',
    'url'    => array('productplan', 'browse', 'productID=1'),
    'title'  => $lang->tutorial->productManage->planManage->step5->name,
    'desc'   => $lang->tutorial->productManage->planManage->step5->desc
);

$productManage->basic->tasks['planManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#stories button.linkStory-btn',
    'page'   => 'productplan-view',
    'title'  => $lang->tutorial->productManage->planManage->step6->name,
    'desc'   => $lang->tutorial->productManage->planManage->step6->desc
);

$productManage->basic->tasks['planManage']['steps'][] = array(
    'type'   => 'selectRow',
    'target' => 'div.dtable div[data-col="id"][data-row="2"]',
    'page'   => 'productplan-view',
    'title'  => $lang->tutorial->productManage->planManage->step7->name
);

$productManage->basic->tasks['planManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable-footer button.linkObjectBtn',
    'page'   => 'productplan-view',
    'title'  => $lang->tutorial->productManage->planManage->step8->name,
    'desc'   => $lang->tutorial->productManage->planManage->step8->desc
);

$productManage->basic->tasks['planManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'li[data-key="bugs"] a',
    'page'   => 'productplan-view',
    'title'  => $lang->tutorial->productManage->planManage->step9->name,
    'desc'   => $lang->tutorial->productManage->planManage->step9->desc
);

$productManage->basic->tasks['planManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#bugs button.linkBug-btn',
    'page'   => 'productplan-view',
    'url'    => array('productplan', 'view', 'planID=1&type=bug'),
    'title'  => $lang->tutorial->productManage->planManage->step10->name,
    'desc'   => $lang->tutorial->productManage->planManage->step10->desc
);

$productManage->basic->tasks['planManage']['steps'][] = array(
    'type'   => 'selectRow',
    'target' => 'div.dtable div[data-col="id"][data-row="1"]',
    'page'   => 'productplan-view',
    'title'  => $lang->tutorial->productManage->planManage->step11->name
);

$productManage->basic->tasks['planManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable-footer button.linkObjectBtn',
    'page'   => 'productplan-view',
    'title'  => $lang->tutorial->productManage->planManage->step12->name,
    'desc'   => $lang->tutorial->productManage->planManage->step12->desc
);

$productManage->basic->tasks['releaseManage'] = array();
$productManage->basic->tasks['releaseManage']['name']     = 'releaseManage';
$productManage->basic->tasks['releaseManage']['title']    = $lang->tutorial->productManage->releaseManage->title;
$productManage->basic->tasks['releaseManage']['startUrl'] = array('product', 'browse', 'productID=1&branch=&browseType=all&param=0&storyType=epic');
$productManage->basic->tasks['releaseManage']['steps']    = array();

$productManage->basic->tasks['releaseManage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'release',
    'page'   => 'product-browse',
    'title'  => $lang->tutorial->productManage->releaseManage->step1->name,
    'desc'   => $lang->tutorial->productManage->releaseManage->step1->desc
);

$productManage->basic->tasks['releaseManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a',
    'page'   => 'release-browse',
    'url'    => array('release', 'browse', 'productID=1'),
    'title'  => $lang->tutorial->productManage->releaseManage->step2->name,
    'desc'   => $lang->tutorial->productManage->releaseManage->step2->desc
);

$productManage->basic->tasks['releaseManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'release-create',
    'title'  => $lang->tutorial->productManage->releaseManage->step3->name
);

$productManage->basic->tasks['releaseManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'release-create',
    'title'  => $lang->tutorial->productManage->releaseManage->step4->name,
    'desc'   => $lang->tutorial->productManage->releaseManage->step4->desc
);

$productManage->basic->tasks['releaseManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="name"][data-row="1"] a',
    'page'   => 'release-browse',
    'url'    => array('release', 'browse', 'productID=1'),
    'title'  => $lang->tutorial->productManage->releaseManage->step5->name,
    'desc'   => $lang->tutorial->productManage->releaseManage->step5->desc
);

$productManage->basic->tasks['releaseManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#finishedStory button.linkStory-btn',
    'page'   => 'release-view',
    'title'  => $lang->tutorial->productManage->releaseManage->step6->name,
    'desc'   => $lang->tutorial->productManage->releaseManage->step6->desc
);

$productManage->basic->tasks['releaseManage']['steps'][] = array(
    'type'   => 'selectRow',
    'target' => 'div.dtable div[data-col="id"][data-row="1"]',
    'page'   => 'release-view',
    'title'  => $lang->tutorial->productManage->releaseManage->step7->name
);

$productManage->basic->tasks['releaseManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable-footer button.linkObjectBtn',
    'page'   => 'release-view',
    'title'  => $lang->tutorial->productManage->releaseManage->step8->name,
    'desc'   => $lang->tutorial->productManage->releaseManage->step8->desc
);

$productManage->basic->tasks['releaseManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'li[data-key="resolvedBug"] a',
    'page'   => 'release-view',
    'title'  => $lang->tutorial->productManage->releaseManage->step9->name,
    'desc'   => $lang->tutorial->productManage->releaseManage->step9->desc
);

$productManage->basic->tasks['releaseManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#resolvedBug button.linkBug-btn',
    'page'   => 'release-view',
    'title'  => $lang->tutorial->productManage->releaseManage->step10->name,
    'desc'   => $lang->tutorial->productManage->releaseManage->step10->desc
);

$productManage->basic->tasks['releaseManage']['steps'][] = array(
    'type'   => 'selectRow',
    'target' => 'div.dtable div[data-col="id"][data-row="1"]',
    'page'   => 'release-view',
    'title'  => $lang->tutorial->productManage->releaseManage->step11->name
);

$productManage->basic->tasks['releaseManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable-footer button.linkObjectBtn',
    'page'   => 'release-view',
    'title'  => $lang->tutorial->productManage->releaseManage->step12->name,
    'desc'   => $lang->tutorial->productManage->releaseManage->step12->desc
);

$productManage->basic->tasks['releaseManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'li[data-key="leftBug"] a',
    'page'   => 'release-view',
    'title'  => $lang->tutorial->productManage->releaseManage->step13->name,
    'desc'   => $lang->tutorial->productManage->releaseManage->step13->desc
);

$productManage->basic->tasks['releaseManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#leftBug button.leftBug-btn',
    'page'   => 'release-view',
    'title'  => $lang->tutorial->productManage->releaseManage->step14->name,
    'desc'   => $lang->tutorial->productManage->releaseManage->step14->desc
);

$productManage->basic->tasks['releaseManage']['steps'][] = array(
    'type'   => 'selectRow',
    'target' => 'div.dtable div[data-col="id"][data-row="1"]',
    'page'   => 'release-view',
    'title'  => $lang->tutorial->productManage->releaseManage->step15->name
);

$productManage->basic->tasks['releaseManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable-footer button.linkObjectBtn',
    'page'   => 'release-view',
    'title'  => $lang->tutorial->productManage->releaseManage->step16->name,
    'desc'   => $lang->tutorial->productManage->releaseManage->step16->desc
);

$productManage->basic->tasks['releaseManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.detail-header a.publish-btn',
    'page'   => 'release-view',
    'title'  => $lang->tutorial->productManage->releaseManage->step17->name,
    'desc'   => $lang->tutorial->productManage->releaseManage->step17->desc
);

$productManage->basic->tasks['releaseManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'release-view',
    'title'  => $lang->tutorial->productManage->releaseManage->step18->name
);

$productManage->basic->tasks['releaseManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'release-view',
    'title'  => $lang->tutorial->productManage->releaseManage->step19->name,
    'desc'   => $lang->tutorial->productManage->releaseManage->step19->desc
);

$productManage->advance = new stdClass();
$productManage->advance->name    = 'productManageAdvance';
$productManage->advance->title   = $lang->tutorial->productManage->title;
$productManage->advance->icon    = 'product text-warning text-lg';
$productManage->advance->type    = 'advance';
$productManage->advance->modules = 'product,tree,story,productplan,release';
$productManage->advance->app     = 'product';
$productManage->advance->tasks   = array();

$productManage->advance->tasks['lineManage'] = array();
$productManage->advance->tasks['lineManage']['name']     = 'lineManage';
$productManage->advance->tasks['lineManage']['title']    = $lang->tutorial->productManage->lineManage->title;
$productManage->advance->tasks['lineManage']['startUrl'] = array('product', 'all');
$productManage->advance->tasks['lineManage']['steps']    = array();

$productManage->advance->tasks['lineManage']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'product',
    'title' => $lang->tutorial->productManage->lineManage->step1->name,
    'desc'  => $lang->tutorial->productManage->lineManage->step1->desc
);

$productManage->advance->tasks['lineManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar button[data-id="manageLineModal"]',
    'page'   => 'product-all',
    'title'  => $lang->tutorial->productManage->lineManage->step2->name,
    'desc'   => $lang->tutorial->productManage->lineManage->step2->desc
);

$productManage->advance->tasks['lineManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'product-all',
    'title'  => $lang->tutorial->productManage->lineManage->step3->name
);

$productManage->advance->tasks['lineManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'product-all',
    'title'  => $lang->tutorial->productManage->lineManage->step4->name,
    'desc'   => $lang->tutorial->productManage->lineManage->step4->desc
);

$productManage->advance->tasks['addProduct']    = $productManage->basic->tasks['addProduct'];
$productManage->advance->tasks['moduleManage']  = $productManage->basic->tasks['moduleManage'];
$productManage->advance->tasks['storyManage']   = $productManage->basic->tasks['storyManage'];
$productManage->advance->tasks['planManage']    = $productManage->basic->tasks['planManage'];
$productManage->advance->tasks['releaseManage'] = $productManage->basic->tasks['releaseManage'];

$productManage->advance->tasks['branchManage'] = array();
$productManage->advance->tasks['branchManage']['name']     = 'branchManage';
$productManage->advance->tasks['branchManage']['title']    = $lang->tutorial->productManage->branchManage->title;
$productManage->advance->tasks['branchManage']['startUrl'] = array('product', 'all');
$productManage->advance->tasks['branchManage']['steps']    = array();

$productManage->advance->tasks['branchManage']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'product',
    'title' => $lang->tutorial->productManage->branchManage->step1->name,
    'desc'  => $lang->tutorial->productManage->branchManage->step1->desc
);

$productManage->advance->tasks['branchManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.create-product-btn',
    'page'   => 'product-all',
    'title'  => $lang->tutorial->productManage->branchManage->step2->name,
    'desc'   => $lang->tutorial->productManage->branchManage->step2->desc
);

$productManage->advance->tasks['branchManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'product-create',
    'title'  => $lang->tutorial->productManage->branchManage->step3->name
);

$productManage->advance->tasks['branchManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'product-create',
    'title'  => $lang->tutorial->productManage->branchManage->step4->name
);

$productManage->advance->tasks['branchManage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'settings',
    'page'   => 'product-browse',
    'url'    => array('product', 'browse', 'productID=1'),
    'title'  => $lang->tutorial->productManage->branchManage->step5->name,
    'desc'   => $lang->tutorial->productManage->branchManage->step5->desc
);

$productManage->advance->tasks['branchManage']['steps'][] = array(
    'type'   => 'clickMainNavbar',
    'target' => 'branch',
    'page'   => 'product-view',
    'url'    => array('product', 'view', 'productID=1'),
    'title'  => $lang->tutorial->productManage->branchManage->step6->name,
    'desc'   => $lang->tutorial->productManage->branchManage->step6->desc
);

$productManage->advance->tasks['branchManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.branch-create-btn',
    'page'   => 'branch-manage',
    'url'    => array('branch', 'manage', 'productID=1'),
    'title'  => $lang->tutorial->productManage->branchManage->step7->name,
    'desc'   => $lang->tutorial->productManage->branchManage->step7->desc
);

$productManage->advance->tasks['branchManage']['steps'][] = array(
    'type'   => 'form',
    'target' => '#createBranchForm',
    'page'   => 'branch-manage',
    'title'  => $lang->tutorial->productManage->branchManage->step8->name
);

$productManage->advance->tasks['branchManage']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#createBranchForm button[type="submit"]',
    'page'   => 'branch-manage',
    'title'  => $lang->tutorial->productManage->branchManage->step9->name,
    'desc'   => $lang->tutorial->productManage->branchManage->step9->desc
);

$productManage->advance->tasks['branchManage']['steps'][] = array(
    'type'   => 'selectRow',
    'target' => 'div.dtable div[data-row="1"][data-type="checkID"]',
    'page'   => 'branch-manage',
    'url'    => array('branch', 'manage', 'productID=1'),
    'title'  => $lang->tutorial->productManage->branchManage->step10->name
);

$productManage->advance->tasks['branchManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable-footer #mergeBranch',
    'page'   => 'branch-manage',
    'title'  => $lang->tutorial->productManage->branchManage->step11->name
);

$productManage->advance->tasks['branchManage']['steps'][] = array(
    'type'   => 'form',
    'target' => '#mergeForm',
    'page'   => 'branch-manage',
    'title'  => $lang->tutorial->productManage->branchManage->step12->name
);

$productManage->advance->tasks['branchManage']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#mergeForm button[type="submit"]',
    'page'   => 'branch-manage',
    'title'  => $lang->tutorial->productManage->branchManage->step13->name,
    'desc'   => $lang->tutorial->productManage->branchManage->step13->desc
);

$productManage->advance->tasks['branchManage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'story',
    'page'   => 'branch-manage',
    'url'    => array('branch', 'manage', 'productID=1'),
    'title'  => $lang->tutorial->productManage->branchManage->step14->name,
    'desc'   => $lang->tutorial->productManage->branchManage->step14->desc
);

$productManage->advance->tasks['branchManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.create-story-btn',
    'page'   => 'product-browse',
    'title'  => $lang->tutorial->productManage->branchManage->step15->name,
    'desc'   => $lang->tutorial->productManage->branchManage->step15->desc
);

$productManage->advance->tasks['branchManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'story-create',
    'title'  => $lang->tutorial->productManage->branchManage->step16->name
);

$productManage->advance->tasks['branchManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'story-create',
    'title'  => $lang->tutorial->productManage->branchManage->step17->name,
    'desc'   => $lang->tutorial->productManage->branchManage->step17->desc
);
