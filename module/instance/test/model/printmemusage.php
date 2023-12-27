#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('instance')->gen(1);

/**

title=instanceModel->printMemUsage();
timeout=0
cid=1

- 查看获取到的数量 @5
- 查看获取到的颜色属性color @secondary
- 查看获取到的提示属性tip @10% = 10 / 100

*/

global $tester;
$kanbanModal = $tester->loadModel('instance');

$instance = $kanbanModal->getById(1);
$metrics = new stdClass();
$metrics->rate  = 10;
$metrics->usage = 10;
$metrics->limit = 100;

$result = instanceModel::printMemUsage($instance, $metrics);

r(count($result)) && p()        && e('5');              // 查看获取到的数量
r($result)        && p('color') && e('secondary');      // 查看获取到的颜色
r($result)        && p('tip')   && e('10% = 10 / 100'); // 查看获取到的提示