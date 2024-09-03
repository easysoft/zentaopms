<?php
global $lang,$app;

$docManage = new stdClass();
$docManage->name    = 'docManage';
$docManage->title   = $lang->tutorial->docManage->title;
$docManage->icon    = 'doc text-special';
$docManage->type    = 'advance';
$docManage->modules = 'doc';
$docManage->app     = 'doc';
$docManage->tasks   = array();

$docManage->tasks['docManage'] = array();
$docManage->tasks['docManage']['name']     = 'docManage';
$docManage->tasks['docManage']['title']    = $lang->tutorial->docManage->title;
$docManage->tasks['docManage']['startUrl'] = array('doc', 'mySpace');
$docManage->tasks['docManage']['steps']    = array();

$docManage->tasks['docManage']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'doc',
    'title' => $lang->tutorial->docManage->step1->name,
    'desc'  => $lang->tutorial->docManage->step1->desc
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'team',
    'page'   => 'doc-mySpace',
    'title'  => $lang->tutorial->docManage->step2->name,
    'desc'   => $lang->tutorial->docManage->step2->desc
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar button.more-btn',
    'page'   => 'doc-tablecontents',
    'title'  => $lang->tutorial->docManage->step3->name
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'li.createSpace-btn a',
    'page'   => 'doc-tablecontents',
    'title'  => $lang->tutorial->docManage->step4->name
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'doc-tablecontents',
    'title'  => $lang->tutorial->docManage->step5->name
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'doc-tablecontents',
    'title'  => $lang->tutorial->docManage->step6->name,
    'desc'   => $lang->tutorial->docManage->step6->desc
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.createLib-btn',
    'page'   => 'doc-tablecontents',
    'url'    => array('doc', 'tablecontents'),
    'title'  => $lang->tutorial->docManage->step7->name,
    'desc'   => $lang->tutorial->docManage->step7->desc
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'doc-tablecontents',
    'title'  => $lang->tutorial->docManage->step8->name
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'doc-tablecontents',
    'title'  => $lang->tutorial->docManage->step9->name,
    'desc'   => $lang->tutorial->docManage->step9->desc
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div[data-lib="2"]',
    'page'   => 'doc-tablecontents',
    'url'    => array('doc', 'tablecontents'),
    'title'  => $lang->tutorial->docManage->step10->name
);
