#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::parseTriggers();
timeout=0
cid=0

- 测试步骤1：cron和events均为空 @0
- 测试步骤2：仅events输入验证type @event
- 测试步骤3：仅events输入验证value @push
- 测试步骤4：仅cron按月触发验证type @month
- 测试步骤5：仅cron按周触发验证type @week

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pipelineTester = new pipelineModelTest();

r(count($pipelineTester->parseTriggersTest('', ''))) && p() && e(0);
r(current($pipelineTester->parseTriggersTest('', 'push'))) && p('type') && e('event');
r(current($pipelineTester->parseTriggersTest('', 'push'))) && p('value') && e('push');
r(current($pipelineTester->parseTriggersTest('30 08 15 * *', ''))) && p('type') && e('month');
r(current($pipelineTester->parseTriggersTest('0 09 * * 1', ''))) && p('type') && e('week');
