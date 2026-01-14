#!/usr/bin/env php
<?php

/**

title=测试 jobModel::getListByTriggerType();
timeout=0
cid=16846

- 测试步骤1：正常获取tag类型job列表数量 @4
- 测试步骤2：正常获取commit类型job列表数量 @3
- 测试步骤3：正常获取schedule类型job列表数量 @3
- 测试步骤4：测试不存在的触发类型 @0
- 测试步骤5：测试指定版本库ID列表过滤tag类型 @1
- 测试步骤6：测试指定版本库ID列表过滤commit类型 @1
- 测试步骤7：测试空字符串触发类型（匹配所有） @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
zenData('job')->gen(10);

su('admin');

$job = new jobModelTest();

r(count($job->getListByTriggerTypeTest('tag', array()))) && p() && e('4'); // 测试步骤1：正常获取tag类型job列表数量
r(count($job->getListByTriggerTypeTest('commit', array()))) && p() && e('3'); // 测试步骤2：正常获取commit类型job列表数量
r(count($job->getListByTriggerTypeTest('schedule', array()))) && p() && e('3'); // 测试步骤3：正常获取schedule类型job列表数量
r(count($job->getListByTriggerTypeTest('nonexistent', array()))) && p() && e('0'); // 测试步骤4：测试不存在的触发类型
r(count($job->getListByTriggerTypeTest('tag', array(1, 2)))) && p() && e('1'); // 测试步骤5：测试指定版本库ID列表过滤tag类型
r(count($job->getListByTriggerTypeTest('commit', array(2, 3)))) && p() && e('1'); // 测试步骤6：测试指定版本库ID列表过滤commit类型
r(count($job->getListByTriggerTypeTest('', array()))) && p() && e('10'); // 测试步骤7：测试空字符串触发类型（匹配所有）