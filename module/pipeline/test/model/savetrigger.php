#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::saveTrigger();
timeout=0
cid=0

- 测试保存触发器(push事件) >> 不应报错 @1
- 测试保存触发器(tag_push事件) >> 不应报错 @1
- 测试保存触发器(cron定时) >> 不应报错 @1
- 测试保存触发器(空事件和cron) >> 不应报错 @1
- 测试保存触发器(带注释) >> 不应报错 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
su('admin');

$tester = new pipelineModelTest();

$trigger1 = (object)array('pipelineID' => 1, 'event' => 'push', 'cron' => '', 'comment' => '');
$trigger2 = (object)array('pipelineID' => 1, 'event' => 'tag_push', 'cron' => '', 'comment' => '');
$trigger3 = (object)array('pipelineID' => 1, 'event' => '', 'cron' => '0 10 * * *', 'comment' => '');
$trigger4 = (object)array('pipelineID' => 1, 'event' => '', 'cron' => '', 'comment' => '');
$trigger5 = (object)array('pipelineID' => 1, 'event' => 'push', 'cron' => '', 'comment' => 'fix #bug');

$ok = true;
$tester->instance->saveTrigger($trigger1);
$tester->instance->saveTrigger($trigger2);
$tester->instance->saveTrigger($trigger3);
$tester->instance->saveTrigger($trigger4);
$tester->instance->saveTrigger($trigger5);

r($ok) && p() && e('1');
r($ok) && p() && e('1');
r($ok) && p() && e('1');
r($ok) && p() && e('1');
r($ok) && p() && e('1');
