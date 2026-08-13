#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::updateTriggerField();
timeout=0
cid=0

- 测试更新触发器事件字段 >> 不应报错 @1
- 测试更新触发器cron字段 >> 不应报错 @1
- 测试更新触发器注释字段 >> 不应报错 @1
- 测试更新不存在的触发器 >> 不应报错 @1
- 测试更新空字段值 >> 不应报错 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
su('admin');

$tester = new pipelineModelTest();

$ok = true;
$tester->instance->updateTriggerField(1, 'event', 'tag_push');
$tester->instance->updateTriggerField(1, 'cron', '0 09 * * 1');
$tester->instance->updateTriggerField(1, 'comment', 'fix #bug');
$tester->instance->updateTriggerField(99999, 'event', 'push');
$tester->instance->updateTriggerField(1, 'comment', '');

r($ok) && p() && e('1');
r($ok) && p() && e('1');
r($ok) && p() && e('1');
r($ok) && p() && e('1');
r($ok) && p() && e('1');
