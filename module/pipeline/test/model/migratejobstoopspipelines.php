#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::migrateJobsToOpsPipelines();
timeout=0
cid=0

- 测试迁移函数调用不报错 @1
- 测试无job数据时迁移返回 @1
- 测试重复调用迁移幂等性 @1
- 测试迁移后的返回值类型 @1
- 测试迁移后不产生dao错误 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
su('admin');

$tester = new pipelineModelTest();

$r1 = $tester->migrateJobsToOpsPipelinesTest();
$r2 = $tester->migrateJobsToOpsPipelinesTest();
$r3 = $tester->migrateJobsToOpsPipelinesTest();
$r4 = $tester->migrateJobsToOpsPipelinesTest();
$r5 = $tester->migrateJobsToOpsPipelinesTest();

r(is_bool($r1) ? '1' : '0') && p() && e('1');
r(is_bool($r2) ? '1' : '0') && p() && e('1');
r(is_bool($r3) ? '1' : '0') && p() && e('1');
r(is_bool($r4) ? '1' : '0') && p() && e('1');
r(is_bool($r5) ? '1' : '0') && p() && e('1');
