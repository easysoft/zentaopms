#!/usr/bin/env php
<?php

/**

title=测试 ciModel::checkCompileStatus();
timeout=0
cid=15587

- 测试步骤1：不指定compileID检查所有未完成的编译任务
 - 第1条的name属性 @构建1
 - 第2条的status属性 @created
- 测试步骤2：指定存在的compileID检查特定编译任务属性status @created
- 测试步骤3：指定不存在的compileID检查编译任务 @0
- 测试步骤4：检查已完成状态的编译任务不被处理属性status @success
- 测试步骤5：检查过期编译任务不被处理属性status @success
- 测试步骤6：验证方法返回值类型属性status @created
- 测试步骤7：测试边界值compileID为0的情况第4条的status属性 @pending

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('compile');
$table->id->range('1-10');
$table->name->range('构建1,构建2,构建3,构建4,构建5,构建6,构建7,构建8,构建9,构建10');
$table->job->range('1-10');
$table->queue->range('100-110');
$table->status->range('created{3},pending{2},running{2},success{2},failure{1}');
$table->gen(10);

zenData('pipeline')->gen(5);
zenData('job')->loadYaml('job')->gen(10);
zenData('mr')->gen(0);

su('admin');

$ci = new ciModelTest();

r($ci->checkCompileStatusTest(0)) && p('1:name;2:status') && e('构建1,created');     // 测试步骤1：不指定compileID检查所有未完成的编译任务
r($ci->checkCompileStatusTest(1)) && p('status') && e('created');                   // 测试步骤2：指定存在的compileID检查特定编译任务
r($ci->checkCompileStatusTest(99)) && p() && e('0');                               // 测试步骤3：指定不存在的compileID检查编译任务
r($ci->checkCompileStatusTest(8)) && p('status') && e('success');                   // 测试步骤4：检查已完成状态的编译任务不被处理
r($ci->checkCompileStatusTest(9)) && p('status') && e('success');                   // 测试步骤5：检查过期编译任务不被处理
r($ci->checkCompileStatusTest(2)) && p('status') && e('created');                   // 测试步骤6：验证方法返回值类型
r($ci->checkCompileStatusTest(0)) && p('4:status') && e('pending');                 // 测试步骤7：测试边界值compileID为0的情况