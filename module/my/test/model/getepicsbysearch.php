#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

zenData('story')->loadYaml('epic')->gen('20');
zenData('storyreview')->gen('0');
zenData('product')->gen('10');
zenData('productplan')->gen('15');
zenData('planstory')->gen('20');
zenData('userquery')->loadYaml('userquery')->gen('2');
zenData('user')->gen('1');
zenData('action')->gen('0');

su('admin');

/**

title=测试 myModel->getEpicsBySearch();
timeout=0
cid=17283

- 测试获取 queryID 0 类型 other 排序 id_desc 的需求。 @15,13,11

- 测试获取 queryID 0 类型 other 排序 id_asc 的需求。 @11,13,15

- 测试获取 queryID 1 类型 contribute 排序 id_desc 的需求。 @13
- 测试获取 queryID 1 类型 contribute 排序 id_asc 的需求。 @13
- 测试获取 queryID 1 类型 other 排序 id_desc 的需求。 @15,13,11

- 测试获取 queryID 1 类型 other 排序 id_asc 的需求。 @11,13,15

*/

$_SESSION['contributeEpicQuery'] = '';
$_SESSION['workEpicQuery'] = '';

$my       = new myTest();
$queryID  = array(0, 2);
$typeList = array('contribute', 'other');
$orderBy  = array('id_desc', 'id_asc');
r($my->getEpicsBySearchTest($queryID[0], $typeList[1], $orderBy[0])) && p() && e('15,13,11'); // 测试获取 queryID 0 类型 other 排序 id_desc 的需求。
r($my->getEpicsBySearchTest($queryID[0], $typeList[1], $orderBy[1])) && p() && e('11,13,15'); // 测试获取 queryID 0 类型 other 排序 id_asc 的需求。
r($my->getEpicsBySearchTest($queryID[1], $typeList[0], $orderBy[0])) && p() && e('13');       // 测试获取 queryID 1 类型 contribute 排序 id_desc 的需求。
r($my->getEpicsBySearchTest($queryID[1], $typeList[0], $orderBy[1])) && p() && e('13');       // 测试获取 queryID 1 类型 contribute 排序 id_asc 的需求。
r($my->getEpicsBySearchTest($queryID[1], $typeList[1], $orderBy[0])) && p() && e('15,13,11'); // 测试获取 queryID 1 类型 other 排序 id_desc 的需求。
r($my->getEpicsBySearchTest($queryID[1], $typeList[1], $orderBy[1])) && p() && e('11,13,15'); // 测试获取 queryID 1 类型 other 排序 id_asc 的需求。