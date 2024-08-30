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
