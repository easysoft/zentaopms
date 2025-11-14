#!/usr/bin/env php
<?php

/**

title=测试 bugZen::buildBrowseView();
timeout=0
cid=15433

- 步骤1:正常产品有bugs的情况属性bugsCount @3
- 步骤2:分支产品的情况属性product @2
- 步骤3:bugs包含story的情况属性stories @3
- 步骤4:bugs包含task的情况属性tasks @3
- 步骤5:验证browseType属性browseType @all
- 步骤6:验证moduleID属性currentModuleID @10
- 步骤7:验证executions数量属性executionsCount @2
- 步骤8:验证orderBy属性orderBy @id_desc
- 步骤9:验证用户数据属性users @11

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->gen(5);
zenData('project')->gen(5);
zenData('bug')->gen(10);
zenData('story')->gen(5);
zenData('task')->gen(5);
zenData('build')->gen(5);
zenData('user')->gen(10);
zenData('productplan')->gen(5);

su('admin');

$bugTest = new bugZenTest();

$product1 = (object)array('id' => 1, 'name' => '产品1', 'type' => 'normal', 'shadow' => 0, 'status' => 'normal');
$product2 = (object)array('id' => 2, 'name' => '产品2', 'type' => 'branch', 'shadow' => 0, 'status' => 'normal');

$bug1 = (object)array('id' => 1, 'product' => 1, 'story' => 1, 'task' => 1, 'toTask' => 0);
$bug2 = (object)array('id' => 2, 'product' => 1, 'story' => 2, 'task' => 2, 'toTask' => 0);
$bug3 = (object)array('id' => 3, 'product' => 1, 'story' => 0, 'task' => 0, 'toTask' => 0);

$bugs1 = array(1 => $bug1, 2 => $bug2, 3 => $bug3);
$bugs2 = array(1 => $bug1);
$bugs3 = array();

$execution1 = (object)array('id' => 1, 'name' => '执行1');
$execution2 = (object)array('id' => 2, 'name' => '执行2');
$executions = array(1 => $execution1, 2 => $execution2);

$pager = new stdClass();
$pager->pageID = 1;
$pager->recTotal = 10;

r($bugTest->buildBrowseViewTest($bugs1, $product1, '0', 'all', 0, $executions, 0, 'id_desc', $pager)) && p('bugsCount') && e('3'); // 步骤1:正常产品有bugs的情况
r($bugTest->buildBrowseViewTest($bugs1, $product2, '0', 'all', 0, $executions, 0, 'id_desc', $pager)) && p('product') && e('2'); // 步骤2:分支产品的情况
r($bugTest->buildBrowseViewTest($bugs1, $product1, '0', 'unclosed', 0, $executions, 0, 'id_desc', $pager)) && p('stories') && e('3'); // 步骤3:bugs包含story的情况
r($bugTest->buildBrowseViewTest($bugs1, $product1, '0', 'unresolved', 0, array(), 0, 'id_desc', $pager)) && p('tasks') && e('3'); // 步骤4:bugs包含task的情况
r($bugTest->buildBrowseViewTest($bugs2, $product1, '0', 'all', 0, $executions, 0, 'id_desc', $pager)) && p('browseType') && e('all'); // 步骤5:验证browseType
r($bugTest->buildBrowseViewTest($bugs2, $product1, '1', 'all', 10, $executions, 5, 'id_desc', $pager)) && p('currentModuleID') && e('10'); // 步骤6:验证moduleID
r($bugTest->buildBrowseViewTest($bugs3, $product1, '0', 'all', 0, $executions, 0, 'id_desc', $pager)) && p('executionsCount') && e('2'); // 步骤7:验证executions数量
r($bugTest->buildBrowseViewTest($bugs1, $product1, '0', 'all', 0, array(), 0, 'id_desc', $pager)) && p('orderBy') && e('id_desc'); // 步骤8:验证orderBy
r($bugTest->buildBrowseViewTest($bugs1, $product1, '0', 'all', 0, $executions, 0, 'id_asc', $pager)) && p('users') && e('11'); // 步骤9:验证用户数据