#!/usr/bin/env php
<?php

/**

title=测试 customModel->buildCustomMenuMap();
timeout=0
cid=15890

- 查看task菜单的名称第task条的name属性 @task
- 查看kanban菜单的顺序第kanban条的order属性 @2
- 查看doc菜单的隐藏状态第doc条的hidden属性 @~~
- 查看settings菜单的顺序第settings条的order属性 @4
- 查看返回的order @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $lang;

$allMenu         = (object)array('task' => array('link' => '任务|execution|task|executionID=12314', 'subModule' => 'task,tree', 'alias' => 'importtask,importbug', 'exclude' => 'tree-browse'), 'kanban' => array ('link' => '看板|execution|taskkanban|executionID=12314'), 'doc' => array('link' => '文档|execution|doc|objectID=12314', 'subModule' => 'doc'), 'settings' => array('link' => '设置|execution|view|executionID=12314', 'subModule' => 'personnel', 'alias' => 'edit,manageproducts,team,whitelist,addwhitelist,managemembers', 'class' => 'dropdown dropdown-hover', 'subMenu' => (object)array('view' => array('link' => '概况|execution|view|executionID=12314', 'subModule' => 'view', 'alias' => 'edit,start,suspend,putoff,close'), 'team' => array('link' => '团队|execution|team|executionID=12314', 'alias' => 'managemembers')), 'menuOrder' => array ( 5 => 'view', 10 => 'team', 15 => 'whitelist')));
$lang->menuOrder = array( 5 => 'task', 10 => 'kanban', 15 => 'CFD', 20 => 'burn', 25 => 'view', 30 => 'story', 35 => 'qa', 40 => 'repo', 45 => 'effort', 50 => 'devops', 55 => 'doc', 60 => 'build', 65 => 'release', 70 => 'action', 75 => 'other', 80 => 'settings', 85 => 'more');

$customTester  = new customModelTest();
r($customTester->buildCustomMenuMapTest($allMenu)[0]) && p('task:name')      && e('task'); // 查看task菜单的名称
r($customTester->buildCustomMenuMapTest($allMenu)[0]) && p('kanban:order')   && e('2');    // 查看kanban菜单的顺序
r($customTester->buildCustomMenuMapTest($allMenu)[0]) && p('doc:hidden')     && e('~~');   // 查看doc菜单的隐藏状态
r($customTester->buildCustomMenuMapTest($allMenu)[0]) && p('settings:order') && e('4');    // 查看settings菜单的顺序
r($customTester->buildCustomMenuMapTest($allMenu)[1]) && p('')               && e('5');    // 查看返回的order