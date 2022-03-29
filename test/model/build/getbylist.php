#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/build.class.php';
su('admin');

/**

title=测试 buildModel->getByList();
cid=1
pid=1

buildid列表查询 >> 17,项目版本版本7
单独id查询 >> 107,执行版本版本17
buildid列表查询统计 >> 2
单独id查询统计 >> 1
不输入id列表查询 >> 0

*/

$buildIDList = array('7', '17');
$count       = array('0', '1');

$build = new buildTest();

r($build->getByListTest($buildIDList, $count[0]))    && p('7:project,name')    && e('17,项目版本版本7');  //buildid列表查询
r($build->getByListTest($buildIDList[1], $count[0])) && p('17:execution,name') && e('107,执行版本版本17'); //单独id查询
r($build->getByListTest($buildIDList, $count[1]))    && p()                    && e('2');                 //buildid列表查询统计
r($build->getByListTest($buildIDList[1], $count[1])) && p()                    && e('1');                 //单独id查询统计
r($build->getByListTest('', $count[0]))              && p()                    && e('0');                 //不输入id列表查询