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
