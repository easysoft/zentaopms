<?php
global $lang;

$productManage = new stdClass();
$productManage->name    = 'productManage';
$productManage->title   = $lang->tutorial->productManage->title;
$productManage->icon    = 'product text-special';
$productManage->type    = 'basic';
$productManage->modules = 'product,tree,story,plan,release';
$productManage->app     = 'product';
$productManage->tasks   = array();

$productManage->tasks['addProduct'] = array();
$productManage->tasks['addProduct']['name']     = 'addProduct';
$productManage->tasks['addProduct']['title']    = $lang->tutorial->productManage->addProduct->title;
$productManage->tasks['addProduct']['startUrl'] = array('product', 'all');
$productManage->tasks['addProduct']['steps']    = array();

$productManage->tasks['addProduct']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.create-product-btn',
    'page'   => 'product-all',
    'title'  => $lang->tutorial->productManage->addProduct->step1->name,
    'desc'   => $lang->tutorial->productManage->addProduct->step1->desc
);

$productManage->tasks['addProduct']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'product-create',
    'title'  => $lang->tutorial->productManage->addProduct->step2->name
);

$productManage->tasks['addProduct']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'product-create',
    'title'  => $lang->tutorial->productManage->addProduct->step3->name,
    'desc'   => $lang->tutorial->productManage->addProduct->step3->desc
);

$productManage->tasks['moduleManage'] = array();
$productManage->tasks['moduleManage']['name']     = 'moduleManage';
$productManage->tasks['moduleManage']['title']    = $lang->tutorial->productManage->moduleManage->title;
$productManage->tasks['moduleManage']['startUrl'] = array('product', 'all');
$productManage->tasks['moduleManage']['steps']    = array();

$productManage->tasks['moduleManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#products div.dtable-body div[data-col="name"][data-row="1"] a',
    'page'   => 'product-all',
    'title'  => $lang->tutorial->productManage->moduleManage->step1->name,
    'desc'   => $lang->tutorial->productManage->moduleManage->step1->desc
);

$productManage->tasks['moduleManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#moduleMenu a[href*="tree"]',
    'page'   => 'product-browse',
    'title'  => $lang->tutorial->productManage->moduleManage->step2->name,
    'desc'   => $lang->tutorial->productManage->moduleManage->step2->desc
);

$productManage->tasks['moduleManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'tree-browse',
    'title'  => $lang->tutorial->productManage->moduleManage->step3->name
);

$productManage->tasks['moduleManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'tree-browse',
    'title'  => $lang->tutorial->productManage->moduleManage->step4->name,
    'desc'   => $lang->tutorial->productManage->moduleManage->step4->desc
);

$productManage->tasks['storyManage'] = array();
$productManage->tasks['storyManage']['name']     = 'storyManage';
$productManage->tasks['storyManage']['title']    = $lang->tutorial->productManage->storyManage->title;
$productManage->tasks['storyManage']['startUrl'] = array('product', 'dashboard', 'productID=1');
$productManage->tasks['storyManage']['steps']    = array();

$productManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'epic',
    'page'   => 'product-dashboard',
    'title'  => $lang->tutorial->productManage->storyManage->step1->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step1->desc
);

$productManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.create-story-btn',
    'page'   => 'product-browse',
    'title'  => $lang->tutorial->productManage->storyManage->step2->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step2->desc
);

$productManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'story-create',
    'title'  => $lang->tutorial->productManage->storyManage->step3->name
);

$productManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'story-create',
    'title'  => $lang->tutorial->productManage->storyManage->step4->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step4->desc
);

$productManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="actions"][data-row="1"] a.epic-createRequirement-btn',
    'page'   => 'product-browse',
    'url'    => array('product', 'browse', 'productID=1&branch=&browseType=all&param=0&storyType=epic'),
    'title'  => $lang->tutorial->productManage->storyManage->step5->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step5->desc
);

$productManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'form',
    'target' => 'div.panel-body div.form-batch-container',
    'page'   => 'requirement-batchCreate',
    'title'  => $lang->tutorial->productManage->storyManage->step6->name
);

$productManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'requirement-batchCreate',
    'title'  => $lang->tutorial->productManage->storyManage->step7->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step7->desc
);

$productManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="actions"][data-row="2"] a.requirement-createStory-btn',
    'page'   => 'product-browse',
    'url'    => array('product', 'browse', 'productID=1&branch=&browseType=all&param=0&storyType=epic'),
    'title'  => $lang->tutorial->productManage->storyManage->step8->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step8->desc
);

$productManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'form',
    'target' => 'div.panel-body div.form-batch-container',
    'page'   => 'story-batchCreate',
    'title'  => $lang->tutorial->productManage->storyManage->step9->name
);

$productManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'story-batchCreate',
    'title'  => $lang->tutorial->productManage->storyManage->step10->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step10->desc
);

$productManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="actions"][data-row="4"] a.story-review-btn',
    'page'   => 'product-browse',
    'url'    => array('product', 'browse', 'productID=1&branch=&browseType=all&param=0&storyType=epic'),
    'title'  => $lang->tutorial->productManage->storyManage->step11->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step11->desc
);

$productManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'story-review',
    'title'  => $lang->tutorial->productManage->storyManage->step12->name
);

$productManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'story-review',
    'title'  => $lang->tutorial->productManage->storyManage->step13->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step13->desc
);

$productManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="actions"][data-row="3"] a.story-change-btn',
    'page'   => 'product-browse',
    'url'    => array('product', 'browse', 'productID=1&branch=&browseType=all&param=0&storyType=epic'),
    'title'  => $lang->tutorial->productManage->storyManage->step14->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step14->desc
);

$productManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'story-change',
    'title'  => $lang->tutorial->productManage->storyManage->step15->name
);

$productManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'story-change',
    'title'  => $lang->tutorial->productManage->storyManage->step16->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step16->desc
);

$productManage->tasks['storyManage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'track',
    'page'   => 'product-browse',
    'url'    => array('product', 'browse', 'productID=1&branch=&browseType=all&param=0&storyType=epic'),
    'title'  => $lang->tutorial->productManage->storyManage->step17->name,
    'desc'   => $lang->tutorial->productManage->storyManage->step17->desc
);
