#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('instance')->gen(1);
/**

title=instanceModel->printCpuUsage();
timeout=0
cid=1

- 查看获取到的第一条instance
 - 属性id @1
 - 属性name @Subversion
 - 属性chart @subversion
- 查看获取到的第一条instance的space
 - 属性id @1
 - 属性name @默认空间1
- 查看获取到的第一条instance的solution
 - 属性id @1
 - 属性name @解决方案1
- 查看获取到的第二条instance
 - 属性id @2
 - 属性name @禅道开源版
 - 属性chart @zentao
- 查看获取到的第二条instance的space
 - 属性id @2
 - 属性name @默认空间2
- 查看获取到的第二条instance的solution
 - 属性id @2
 - 属性name @解决方案2
- 查看不存在的instance @0

*/

global $tester;
$kanbanModal = $tester->loadModel('instance');

$instance = $kanbanModal->getById(1);
$metrics = new stdClass();
$metrics->rate  = 10;
$metrics->usage = 10;
$metrics->limit = 100;

$result = instanceModel::printCpuUsage($instance, $metrics);

r(count($result)) && p()        && e('5');              // 查看获取到的数量
r($result)        && p('color') && e('secondary');      // 查看获取到的颜色
r($result)        && p('tip')   && e('10% = 10 / 100'); // 查看获取到的提示
