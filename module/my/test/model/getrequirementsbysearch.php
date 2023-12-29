#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/my.class.php';

zdTable('story')->config('story')->gen('20');
zdTable('product')->gen('10');
zdTable('productplan')->gen('15');
zdTable('planstory')->gen('20');
zdTable('userquery')->config('userquery')->gen('2');
zdTable('user')->gen('1');
zdTable('action')->gen('0');

su('admin');

/**

title=测试 myModel->getRequirementsBySearch();
cid=1
pid=1

*/

$_SESSION['contributeRequirementQuery'] = '';
$_SESSION['workRequirementQuery'] = '';

$my       = new myTest();
$queryID  = array(0, 2);
$typeList = array('contribute', 'other');
$orderBy  = array('id_desc', 'id_asc');
r($my->getRequirementsBySearchTest($queryID[0], $typeList[0], $orderBy[0])) && p() && e('9,5');         // 测试获取 queryID 0 类型 contribute 排序 id_desc 的需求。
r($my->getRequirementsBySearchTest($queryID[0], $typeList[0], $orderBy[1])) && p() && e('5,9');         // 测试获取 queryID 0 类型 contribute 排序 id_asc 的需求。
r($my->getRequirementsBySearchTest($queryID[0], $typeList[1], $orderBy[0])) && p() && e('19,10,5,4');   // 测试获取 queryID 0 类型 other 排序 id_desc 的需求。
r($my->getRequirementsBySearchTest($queryID[0], $typeList[1], $orderBy[1])) && p() && e('4,5,10,19');   // 测试获取 queryID 0 类型 other 排序 id_asc 的需求。
r($my->getRequirementsBySearchTest($queryID[1], $typeList[0], $orderBy[0])) && p() && e('0');           // 测试获取 queryID 1 类型 contribute 排序 id_desc 的需求。
r($my->getRequirementsBySearchTest($queryID[1], $typeList[0], $orderBy[1])) && p() && e('0');           // 测试获取 queryID 1 类型 contribute 排序 id_asc 的需求。
r($my->getRequirementsBySearchTest($queryID[1], $typeList[1], $orderBy[0])) && p() && e('19,10');       // 测试获取 queryID 1 类型 other 排序 id_desc 的需求。
r($my->getRequirementsBySearchTest($queryID[1], $typeList[1], $orderBy[1])) && p() && e('10,19');       // 测试获取 queryID 1 类型 other 排序 id_asc 的需求。
