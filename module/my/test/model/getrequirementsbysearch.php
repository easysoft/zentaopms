#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

zenData('story')->loadYaml('story')->gen('20');
zenData('storyreview')->gen('0');
zenData('product')->gen('10');
zenData('productplan')->gen('15');
zenData('planstory')->gen('20');
zenData('userquery')->loadYaml('userquery')->gen('2');
zenData('user')->gen('1');
zenData('action')->gen('0');

su('admin');

/**

title=测试 myModel->getRequirementsBySearch();
timeout=0
cid=17288

- 测试获取 queryID 0 类型 other 排序 id_desc 的需求。 @19,10,4

- 测试获取 queryID 0 类型 other 排序 id_asc 的需求。 @4,10,19

- 测试获取 queryID 1 类型 contribute 排序 id_desc 的需求。 @0
- 测试获取 queryID 1 类型 contribute 排序 id_asc 的需求。 @0
- 测试获取 queryID 1 类型 other 排序 id_desc 的需求。 @19,10

- 测试获取 queryID 1 类型 other 排序 id_asc 的需求。 @10,19

*/

$_SESSION['contributeRequirementQuery'] = '';
$_SESSION['workRequirementQuery'] = '';

$my       = new myTest();
$queryID  = array(0, 2);
$typeList = array('contribute', 'other');
$orderBy  = array('id_desc', 'id_asc');
r($my->getRequirementsBySearchTest($queryID[0], $typeList[1], $orderBy[0])) && p() && e('19,10,4');   // 测试获取 queryID 0 类型 other 排序 id_desc 的需求。
r($my->getRequirementsBySearchTest($queryID[0], $typeList[1], $orderBy[1])) && p() && e('4,10,19');   // 测试获取 queryID 0 类型 other 排序 id_asc 的需求。
r($my->getRequirementsBySearchTest($queryID[1], $typeList[0], $orderBy[0])) && p() && e('0');           // 测试获取 queryID 1 类型 contribute 排序 id_desc 的需求。
r($my->getRequirementsBySearchTest($queryID[1], $typeList[0], $orderBy[1])) && p() && e('0');           // 测试获取 queryID 1 类型 contribute 排序 id_asc 的需求。
r($my->getRequirementsBySearchTest($queryID[1], $typeList[1], $orderBy[0])) && p() && e('19,10');       // 测试获取 queryID 1 类型 other 排序 id_desc 的需求。
r($my->getRequirementsBySearchTest($queryID[1], $typeList[1], $orderBy[1])) && p() && e('10,19');       // 测试获取 queryID 1 类型 other 排序 id_asc 的需求。