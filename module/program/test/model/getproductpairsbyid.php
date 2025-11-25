#!/usr/bin/env php
<?php

/**

title=测试 programModel::getProductPairsByID();
timeout=0
cid=17694

- 获取项目集ID为1的所有产品键值对
 - 属性1 @产品1
 - 属性2 @产品2
 - 属性3 @产品3
- 获取项目集ID为2的所有产品键值对属性4 @产品4
- 获取不存在的项目集ID的产品键值对 @0
- 获取项目集ID为0的产品键值对 @0
- 获取没有产品的项目集ID的产品键值对 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';
su('admin');

/* 创建项目集测试数据 */
global $tester;
$tester->dao->exec("DELETE FROM " . TABLE_PROJECT . " WHERE type = 'program'");
$tester->dao->exec("INSERT INTO " . TABLE_PROJECT . " (id, name, type, grade, path, begin, end) VALUES
    (1, '项目集1', 'program', 1, ',1,', '2022-01-12', '2022-02-12'),
    (2, '项目集2', 'program', 1, ',2,', '2022-01-12', '2022-02-12'),
    (3, '项目集3', 'program', 1, ',3,', '2022-01-12', '2022-02-12')");

/* 创建产品测试数据 */
$tester->dao->exec("DELETE FROM " . TABLE_PRODUCT);
$tester->dao->exec("INSERT INTO " . TABLE_PRODUCT . " (id, name, program, type, status, deleted, vision) VALUES
    (1, '产品1', 1, 'normal', 'normal', '0', 'rnd'),
    (2, '产品2', 1, 'normal', 'normal', '0', 'rnd'),
    (3, '产品3', 1, 'normal', 'normal', '0', 'rnd'),
    (4, '产品4', 2, 'normal', 'normal', '0', 'rnd')");

$programTest = new programTest();

r($programTest->getProductPairsByIDTest(1)) && p('1,2,3') && e('产品1,产品2,产品3'); // 获取项目集ID为1的所有产品键值对
r($programTest->getProductPairsByIDTest(2)) && p('4') && e('产品4'); // 获取项目集ID为2的所有产品键值对
r($programTest->getProductPairsByIDTest(999)) && p() && e('0'); // 获取不存在的项目集ID的产品键值对
r($programTest->getProductPairsByIDTest(0)) && p() && e('0'); // 获取项目集ID为0的产品键值对
r($programTest->getProductPairsByIDTest(3)) && p() && e('0'); // 获取没有产品的项目集ID的产品键值对