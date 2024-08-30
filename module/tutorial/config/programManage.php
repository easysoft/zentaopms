<?php
global $lang;

$programManage = new stdClass();
$programManage->name    = 'programManage';
$programManage->title   = $lang->tutorial->programManage->title;
$programManage->icon    = 'program text-special';
$programManage->type    = 'advance';
$programManage->modules = 'program,product,project';
$programManage->app     = 'program';
$programManage->tasks   = array();

$programManage->tasks['addProgram'] = array();
$programManage->tasks['addProgram']['name']     = 'addProgram';
$programManage->tasks['addProgram']['title']    = $lang->tutorial->programManage->addProgram->title;
$programManage->tasks['addProgram']['startUrl'] = array('program', 'browse');
$programManage->tasks['addProgram']['steps']    = array();

$programManage->tasks['addProgram']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'program',
    'title' => $lang->tutorial->programManage->addProgram->step1->name,
    'desc'  => $lang->tutorial->programManage->addProgram->step1->desc
);

$programManage->tasks['addProgram']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.create-program-btn',
    'page'   => 'program-browse',
    'title'  => $lang->tutorial->programManage->addProgram->step2->name,
    'desc'   => $lang->tutorial->programManage->addProgram->step2->desc
);

$programManage->tasks['addProgram']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'program-create',
    'title'  => $lang->tutorial->programManage->addProgram->step3->name
);

$programManage->tasks['addProgram']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'program-create',
    'title'  => $lang->tutorial->programManage->addProgram->step4->name,
    'desc'   => $lang->tutorial->programManage->addProgram->step4->desc
);

$programManage->tasks['addProgram']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.create-project-btn',
    'page'   => 'program-browse',
    'url'    => array('program', 'browse'),
    'title'  => $lang->tutorial->programManage->addProgram->step5->name,
    'desc'   => $lang->tutorial->programManage->addProgram->step5->desc
);

$programManage->tasks['addProgram']['steps'][] = array(
    'type'   => 'click',
    'target' => '#modelList div.scrum div.model-item',
    'page'   => 'program-browse',
    'title'  => $lang->tutorial->programManage->addProgram->step6->name,
    'desc'   => $lang->tutorial->programManage->addProgram->step6->desc
);

$programManage->tasks['addProgram']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'project-create',
    'url'    => array('project', 'create', 'model=scrum&programID=1'),
    'title'  => $lang->tutorial->programManage->addProgram->step7->name
);

$programManage->tasks['addProgram']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'project-create',
    'title'  => $lang->tutorial->programManage->addProgram->step8->name,
    'desc'   => $lang->tutorial->programManage->addProgram->step8->desc
);

$programManage->tasks['addProgram']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'productView',
    'page'   => 'program-browse',
    'url'    => array('program', 'browse'),
    'title'  => $lang->tutorial->programManage->addProgram->step9->name,
    'desc'   => $lang->tutorial->programManage->addProgram->step9->desc
);

$programManage->tasks['addProgram']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar div.btn-group button[type="button"]',
    'page'   => 'program-productview',
    'title'  => $lang->tutorial->programManage->addProgram->step10->name
);

$programManage->tasks['addProgram']['steps'][] = array(
    'type'   => 'click',
    'target' => 'li.product-create-btn a',
    'page'   => 'program-productview',
    'title'  => $lang->tutorial->programManage->addProgram->step11->name,
    'desc'   => $lang->tutorial->programManage->addProgram->step11->desc
);

$programManage->tasks['addProgram']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'product-create',
    'url'    => array('product', 'create', 'programID=1'),
    'title'  => $lang->tutorial->programManage->addProgram->step12->name
);

$programManage->tasks['addProgram']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'product-create',
    'title'  => $lang->tutorial->programManage->addProgram->step13->name,
    'desc'   => $lang->tutorial->programManage->addProgram->step13->desc
);
