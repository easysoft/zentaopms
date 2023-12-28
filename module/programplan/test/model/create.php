#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->create();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

zdTable('project')->gen(10);
zdTable('task')->gen(10);


$names    = array('阶段31', '阶段121', '阶段211', '阶段301', '阶段391', '阶段481', '阶段571', '新增的阶段', '', '', '', '');
$percents = array('0', '0', '0', '0', '0', '0', '0', '0', '', '', '', '');
$begin    = array();
$end      = array();
$idList   = array(11, 12);
$create   = array('name' => $names, 'percent' => $percents, 'begin' => $begin, 'end' => $end, 'id' => $idList);

$programplan = new programplanTest();

$programplan->objectModel->create(array());
r(dao::getError()) && p('message:0') && e('『阶段名称』不能为空。');                   // 测试创建用例 工作量占比非数字

r($programplan->createTest(array(), 0, 0, 1000)) && p('message:0') && e('『阶段名称』不能为空。');                   // 测试创建用例 工作量占比非数字
exit;

r($programplan->createTest())                 && p() && e('7');                                        // 测试正常更新阶段信息 获取阶段数量
r($programplan->createTest($create))          && p() && e('8');                                        // 测试正常创建一条阶段信息 获取阶段数量
