#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::getTriggerGroup();
timeout=0
cid=0

- 测试tag触发类型分组(无数据) @0
- 测试commit触发类型分组(无数据) @0
- 测试schedule触发类型分组(无数据) @0
- 测试不存在的触发类型(无数据) @0
- 测试空触发类型(无数据) @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
su('admin');

$tester = new pipelineModelTest();

$r1 = $tester->getTriggerGroupTest('tag', array());
$r2 = $tester->getTriggerGroupTest('commit', array());
$r3 = $tester->getTriggerGroupTest('schedule', array());
$r4 = $tester->getTriggerGroupTest('nonexistent', array());
$r5 = $tester->getTriggerGroupTest('', array());

r(is_array($r1)) && p() && e('1');
r(is_array($r2)) && p() && e('1');
r(is_array($r3)) && p() && e('1');
r(is_array($r4)) && p() && e('1');
r(is_array($r5)) && p() && e('1');
