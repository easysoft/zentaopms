#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testreport.class.php';

zdTable('build')->gen(20);
zdTable('story')->gen(50);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 testreportModel->getStories4Test();
cid=1
pid=1

*/

$buildIdList = array('1,3,5', '2,4,6', '0');

$testreport = new testreportTest();

r($testreport->getStories4TestTest($buildIdList[0])) && p() && e('2,4,10,12,18,20'); // 测试查询版本 1 3 5 的需求
r($testreport->getStories4TestTest($buildIdList[1])) && p() && e('6,8,14,16,22,24'); // 测试查询版本 2 4 6 的需求
r($testreport->getStories4TestTest($buildIdList[2])) && p() && e('0');               // 测试查询版本 空 的需求
