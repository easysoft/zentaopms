#!/usr/bin/env php
<?php

/**

title=测试 zahostModel::getNodeGroupHost();
timeout=0
cid=19750

- 步骤1：验证返回2个主机分组 @2
- 步骤2：验证宿主机1下有1个执行节点 @1
- 步骤3：验证宿主机2下有1个执行节点 @1
- 步骤4：验证宿主机3下没有执行节点 @0
- 步骤5：验证无效父级节点不在结果中 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zahost.unittest.class.php';

$host = zenData('host');
$host->id->range('1-6');
$host->type->range('zahost{3},node{3}');
$host->name->range('宿主机1,宿主机2,宿主机3,执行节点1,执行节点2,执行节点3');
$host->parent->range('0{3},1,2,999');
$host->deleted->range('0');
$host->gen(6);

su('admin');

$zahost = new zahostTest();
$result = $zahost->getNodeGroupHostTest();

// 将结果存储在变量中以便复用
$resultCount = count($result);
$host1HasNodes = isset($result[1]) ? count($result[1]) : 0;
$host2HasNodes = isset($result[2]) ? count($result[2]) : 0;
$host3HasNodes = isset($result[3]) ? count($result[3]) : 0;
$hostInvalidNodes = isset($result[999]) ? count($result[999]) : 0;

r($resultCount) && p() && e('2');                     // 步骤1：验证返回2个主机分组
r($host1HasNodes) && p() && e('1');                   // 步骤2：验证宿主机1下有1个执行节点
r($host2HasNodes) && p() && e('1');                   // 步骤3：验证宿主机2下有1个执行节点
r($host3HasNodes) && p() && e('0');                   // 步骤4：验证宿主机3下没有执行节点
r($hostInvalidNodes) && p() && e('0');                // 步骤5：验证无效父级节点不在结果中