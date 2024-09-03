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
