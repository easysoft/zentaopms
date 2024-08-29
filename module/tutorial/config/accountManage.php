<?php
global $lang;

$accountManage = new stdClass();
$accountManage->name    = 'accountManage';
$accountManage->title   = $lang->tutorial->accountManage->title;
$accountManage->icon    = 'backend text-special';
$accountManage->type    = 'basic';
$accountManage->modules = 'admin,company,dept,group';
$accountManage->app     = 'admin';
$accountManage->tasks   = array();

$accountManage->tasks['deptManage'] = array();
$accountManage->tasks['deptManage']['name']     = 'deptManage';
$accountManage->tasks['deptManage']['title']    = $lang->tutorial->accountManage->deptManage->title;
$accountManage->tasks['deptManage']['startUrl'] = array('admin', 'index');
$accountManage->tasks['deptManage']['steps']   = array();

$accountManage->tasks['deptManage']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'admin',
    'title' => $lang->tutorial->accountManage->deptManage->step1->name,
    'desc'  => $lang->tutorial->accountManage->deptManage->step1->desc
);

$accountManage->tasks['deptManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#settings div[data-id="company"]',
    'page'   => 'admin-index',
    'title'  => $lang->tutorial->accountManage->deptManage->step2->name,
    'desc'   => $lang->tutorial->accountManage->deptManage->step2->desc
);

$accountManage->tasks['deptManage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'dept',
    'page'   => 'company-browse',
    'title'  => $lang->tutorial->accountManage->deptManage->step3->name,
    'desc'   => $lang->tutorial->accountManage->deptManage->step3->desc
);

$accountManage->tasks['deptManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'dept-browse',
    'title'  => $lang->tutorial->accountManage->deptManage->step4->name
);

$accountManage->tasks['deptManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'dept-browse',
    'title'  => $lang->tutorial->accountManage->deptManage->step5->name,
    'desc'   => $lang->tutorial->accountManage->deptManage->step5->desc
);

$accountManage->tasks['addUser'] = array();
$accountManage->tasks['addUser']['name']     = 'addUser';
$accountManage->tasks['addUser']['title']    = $lang->tutorial->accountManage->addUser->title;
$accountManage->tasks['addUser']['startUrl'] = array('dept', 'browse');
$accountManage->tasks['addUser']['steps']   = array();

$accountManage->tasks['addUser']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'browseUser',
    'title'  => $lang->tutorial->accountManage->addUser->step1->name,
    'desc'   => $lang->tutorial->accountManage->addUser->step1->desc
);

$accountManage->tasks['addUser']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.create-user-btn',
    'page'   => 'company-browse',
    'title'  => $lang->tutorial->accountManage->addUser->step2->name,
    'desc'   => $lang->tutorial->accountManage->addUser->step2->desc
);

$accountManage->tasks['addUser']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'user-create',
    'title'  => $lang->tutorial->accountManage->addUser->step3->name
);

$accountManage->tasks['addUser']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'user-create',
    'title'  => $lang->tutorial->accountManage->addUser->step4->name,
    'desc'   => $lang->tutorial->accountManage->addUser->step4->desc
);

$accountManage->tasks['privManage'] = array();
$accountManage->tasks['privManage']['name']     = 'privManage';
$accountManage->tasks['privManage']['title']    = $lang->tutorial->accountManage->privManage->title;
$accountManage->tasks['privManage']['startUrl'] = array('company', 'browse');
$accountManage->tasks['privManage']['steps']    = array();

$accountManage->tasks['privManage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'group',
    'title'  => $lang->tutorial->accountManage->privManage->step1->name,
    'desc'   => $lang->tutorial->accountManage->privManage->step1->desc
);

$accountManage->tasks['privManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.group-create-btn',
    'page'   => 'group-browse',
    'title'  => $lang->tutorial->accountManage->privManage->step2->name,
    'desc'   => $lang->tutorial->accountManage->privManage->step2->desc
);

$accountManage->tasks['privManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'group-browse',
    'title'  => $lang->tutorial->accountManage->privManage->step3->name
);

$accountManage->tasks['privManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'group-browse',
    'title'  => $lang->tutorial->accountManage->privManage->step4->name,
    'desc'   => $lang->tutorial->accountManage->privManage->step4->desc
);

$accountManage->tasks['privManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-row="2"][data-col="actions"] a.group-manageMember-btn',
    'page'   => 'group-browse',
    'url'    => array('group', 'browse'),
    'title'  => $lang->tutorial->accountManage->privManage->step5->name,
    'desc'   => $lang->tutorial->accountManage->privManage->step5->desc
);

$accountManage->tasks['privManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'group-browse',
    'title'  => $lang->tutorial->accountManage->privManage->step6->name
);

$accountManage->tasks['privManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'group-browse',
    'title'  => $lang->tutorial->accountManage->privManage->step7->name,
    'desc'   => $lang->tutorial->accountManage->privManage->step7->desc
);

$accountManage->tasks['privManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-row="2"][data-col="actions"] a.group-managepriv-btn',
    'page'   => 'group-browse',
    'url'    => array('group', 'browse'),
    'title'  => $lang->tutorial->accountManage->privManage->step8->name,
    'desc'   => $lang->tutorial->accountManage->privManage->step8->desc
);

$accountManage->tasks['privManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div[data-module="todo"][data-package="manageTodo"] i.icon',
    'page'   => 'group-managepriv',
    'title'  => $lang->tutorial->accountManage->privManage->step9->name
);

$accountManage->tasks['privManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'group-managepriv',
    'title'  => $lang->tutorial->accountManage->privManage->step10->name,
    'desc'   => $lang->tutorial->accountManage->privManage->step10->desc
);
