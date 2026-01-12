#!/usr/bin/env php
<?php

/**

title=测试 bugZen::buildBrowseView();
timeout=0
cid=0

- 执行bugTest模块的buildBrowseViewTest方法，参数是$bugs1, $product1, '0', 'all', 0, $executions, 0, 'id_desc', $pager
 - 属性product @1
 - 属性browseType @all
 - 属性bugsCount @5
- 执行bugTest模块的buildBrowseViewTest方法，参数是$bugs2, $product2, '1', 'bymodule', 1, $executions, 0, 'id_asc', $pager
 - 属性product @3
 - 属性branch @1
 - 属性currentModuleID @1
 - 属性bugsCount @2
- 执行bugTest模块的buildBrowseViewTest方法，参数是$bugs3, $product1, '0', 'assignedto', 0, $executions, 1, 'pri_desc', $pager
 - 属性browseType @assignedto
 - 属性param @1
 - 属性stories @2
 - 属性tasks @1
- 执行bugTest模块的buildBrowseViewTest方法，参数是$bugs4, $product1, '0', 'all', 0, $executions, 0, 'id_desc', $pager 属性bugsCount @0
- 执行bugTest模块的buildBrowseViewTest方法，参数是$bugs5, $product1, '0', 'resolved', 0, $executions, 0, 'status_desc', $pager
 - 属性browseType @resolved
 - 属性orderBy @status_desc
 - 属性bugsCount @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('bug')->loadYaml('buildbrowseview/bug', false, 2)->gen(10);
zenData('product')->loadYaml('buildbrowseview/product', false, 2)->gen(3);
zenData('project')->loadYaml('buildbrowseview/project', false, 2)->gen(4);
zenData('story')->loadYaml('buildbrowseview/story', false, 2)->gen(3);
zenData('task')->loadYaml('buildbrowseview/task', false, 2)->gen(5);

su('admin');

$bugTest = new bugZenTest();

$product1 = new stdClass();
$product1->id = 1;
$product1->name = '产品_1';
$product1->type = 'normal';

$product2 = new stdClass();
$product2->id = 3;
$product2->name = '产品_3';
$product2->type = 'branch';

$pager = new stdClass();
$pager->pageID = 1;

global $tester;
$bugs1 = $tester->loadModel('bug')->getByIdList(array(1, 2, 3, 4, 5));
$bugs2 = $tester->loadModel('bug')->getByIdList(array(9, 10));
$bugs3 = $tester->loadModel('bug')->getByIdList(array(1, 2));
$bugs4 = array();
$bugs5 = $tester->loadModel('bug')->getByIdList(array(6, 7, 8));

$executions = array(11 => '迭代_1', 12 => '迭代_2');

r($bugTest->buildBrowseViewTest($bugs1, $product1, '0', 'all', 0, $executions, 0, 'id_desc', $pager)) && p('product;browseType;bugsCount') && e('1,all,5');
r($bugTest->buildBrowseViewTest($bugs2, $product2, '1', 'bymodule', 1, $executions, 0, 'id_asc', $pager)) && p('product;branch;currentModuleID;bugsCount') && e('3,1,1,2');
r($bugTest->buildBrowseViewTest($bugs3, $product1, '0', 'assignedto', 0, $executions, 1, 'pri_desc', $pager)) && p('browseType;param;stories;tasks') && e('assignedto,1,2,1');
r($bugTest->buildBrowseViewTest($bugs4, $product1, '0', 'all', 0, $executions, 0, 'id_desc', $pager)) && p('bugsCount') && e('0');
r($bugTest->buildBrowseViewTest($bugs5, $product1, '0', 'resolved', 0, $executions, 0, 'status_desc', $pager)) && p('browseType;orderBy;bugsCount') && e('resolved,status_desc,3');
