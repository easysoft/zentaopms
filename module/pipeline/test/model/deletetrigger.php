#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::deleteTrigger();
timeout=0
cid=0

- 测试删除存在的触发器 >> 不应报错 @1
- 测试删除不存在的触发器 >> 不应报错 @1
- 测试删除triggerID=0 >> 不应报错 @1
- 测试删除负数ID >> 不应报错 @1
- 测试删除高ID >> 不应报错 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
su('admin');

$tester = new pipelineModelTest();

$ok = true;
$tester->instance->deleteTrigger(1);
$tester->instance->deleteTrigger(99999);
$tester->instance->deleteTrigger(0);
$tester->instance->deleteTrigger(-1);
$tester->instance->deleteTrigger(9999);

r($ok) && p() && e('1');
r($ok) && p() && e('1');
r($ok) && p() && e('1');
r($ok) && p() && e('1');
r($ok) && p() && e('1');
