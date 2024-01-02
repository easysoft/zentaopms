#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 myModel->getStoriesBySearch();
cid=1

- 测试获取 queryID 0 类型 contribute 排序 id_desc 的需求。 @17,13,8,7,6,3,2,1

- 测试获取 queryID 0 类型 contribute 排序 id_asc 的需求。 @1,2,3,6,7,8,13,17

- 测试获取 queryID 0 类型 other 排序 id_desc 的需求。 @16,13,8,7,1

- 测试获取 queryID 0 类型 other 排序 id_asc 的需求。 @1,7,8,13,16

- 测试获取 queryID 1 类型 contribute 排序 id_desc 的需求。 @17,13,1

- 测试获取 queryID 1 类型 contribute 排序 id_asc 的需求。 @1,13,17

- 测试获取 queryID 1 类型 other 排序 id_desc 的需求。 @16,13,1

- 测试获取 queryID 1 类型 other 排序 id_asc 的需求。 @1,13,16

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/my.class.php';

zdTable('story')->config('story')->gen('20');
zdTable('product')->gen('10');
zdTable('productplan')->gen('15');
zdTable('planstory')->gen('20');
zdTable('userquery')->config('userquery')->gen('2');
zdTable('user')->gen('1');

su('admin');

$my       = new myTest();
$queryID  = array(0, 2);
$typeList = array('contribute', 'other');
$orderBy  = array('id_desc', 'id_asc');

r($my->getStoriesBySearchTest($queryID[0], $typeList[0], $orderBy[0])) && p() && e('17,13,8,7,6,3,2,1'); // 测试获取 queryID 0 类型 contribute 排序 id_desc 的需求。
r($my->getStoriesBySearchTest($queryID[0], $typeList[0], $orderBy[1])) && p() && e('1,2,3,6,7,8,13,17'); // 测试获取 queryID 0 类型 contribute 排序 id_asc 的需求。
r($my->getStoriesBySearchTest($queryID[0], $typeList[1], $orderBy[0])) && p() && e('16,13,8,7,1');         // 测试获取 queryID 0 类型 other 排序 id_desc 的需求。
r($my->getStoriesBySearchTest($queryID[0], $typeList[1], $orderBy[1])) && p() && e('1,7,8,13,16');         // 测试获取 queryID 0 类型 other 排序 id_asc 的需求。
r($my->getStoriesBySearchTest($queryID[1], $typeList[0], $orderBy[0])) && p() && e('17,13,1');           // 测试获取 queryID 1 类型 contribute 排序 id_desc 的需求。
r($my->getStoriesBySearchTest($queryID[1], $typeList[0], $orderBy[1])) && p() && e('1,13,17');           // 测试获取 queryID 1 类型 contribute 排序 id_asc 的需求。
r($my->getStoriesBySearchTest($queryID[1], $typeList[1], $orderBy[0])) && p() && e('16,13,1');           // 测试获取 queryID 1 类型 other 排序 id_desc 的需求。
r($my->getStoriesBySearchTest($queryID[1], $typeList[1], $orderBy[1])) && p() && e('1,13,16');           // 测试获取 queryID 1 类型 other 排序 id_asc 的需求。
