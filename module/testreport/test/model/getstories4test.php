#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('build')->gen(20);
zenData('story')->gen(50);
zenData('user')->gen(1);

su('admin');

/**

title=测试 testreportModel->getStories4Test();
timeout=0
cid=19123

- 测试查询版本 1 3 5 的需求 @2,4,10,12,18,20

- 测试查询版本 2 4 6 的需求 @6,8,14,16,22,24

- 测试查询版本 1 2 3 的需求 @2,4,6,8,10,12

- 测试查询版本 2 3 4 的需求 @6,8,10,12,14,16

- 测试查询版本 空 的需求 @0

*/

$buildIdList = array('1,3,5', '2,4,6', '1,2,3', '2,3,4', '0');

$testreport = new testreportModelTest();

r($testreport->getStories4TestTest($buildIdList[0])) && p() && e('2,4,10,12,18,20'); // 测试查询版本 1 3 5 的需求
r($testreport->getStories4TestTest($buildIdList[1])) && p() && e('6,8,14,16,22,24'); // 测试查询版本 2 4 6 的需求
r($testreport->getStories4TestTest($buildIdList[2])) && p() && e('2,4,6,8,10,12');   // 测试查询版本 1 2 3 的需求
r($testreport->getStories4TestTest($buildIdList[3])) && p() && e('6,8,10,12,14,16'); // 测试查询版本 2 3 4 的需求
r($testreport->getStories4TestTest($buildIdList[4])) && p() && e('0');               // 测试查询版本 空 的需求
