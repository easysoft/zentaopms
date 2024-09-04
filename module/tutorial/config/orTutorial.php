<?php
global $lang,$config;

$orTutorial = new stdClass();
$orTutorial->demandManage = new stdClass();
$orTutorial->demandManage->name    = 'demandManage';
$orTutorial->demandManage->title   = $lang->tutorial->orTutorial->demandManage->title;
$orTutorial->demandManage->icon    = 'bars text-special';
$orTutorial->demandManage->type    = 'basic';
$orTutorial->demandManage->modules = 'demandpool';
$orTutorial->demandManage->app     = 'demandpool';
$orTutorial->demandManage->tasks   = array();

$orTutorial->demandManage->tasks['demandManage'] = array();
$orTutorial->demandManage->tasks['demandManage']['name']     = 'demandManage';
$orTutorial->demandManage->tasks['demandManage']['title']    = $lang->tutorial->orTutorial->demandManage->title;
$orTutorial->demandManage->tasks['demandManage']['startUrl'] = array('demandpool', 'browse');
$orTutorial->demandManage->tasks['demandManage']['steps']    = array();

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
