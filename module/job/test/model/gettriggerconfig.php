#!/usr/bin/env php
<?php

/**

title=测试 jobModel::getTriggerConfig();
timeout=0
cid=16849

- 测试步骤1：tag触发类型且有svnDir @目录改动(/module/caselib)
- 测试步骤2：tag触发类型但无svnDir @打标签
- 测试步骤3：commit触发类型 @提交注释包含关键字(bug)
- 测试步骤4：schedule触发类型 @定时计划(星期日, 20)

- 定时计划(星期日, 20)');  测试步骤5：多种触发类型组合 @打标签; 提交注释包含关键字(bug
- 测试步骤6：不存在的job ID处理 @
- 测试步骤7：空triggerType处理 @

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';

// 准备测试数据
$job = zenData('job');
$job->id->range('1-7');
$job->name->range('tag任务1,commit任务2,schedule任务3,tag任务4,空任务5,组合任务6,任务7');
$job->triggerType->range('tag,commit,schedule,tag,,tag|commit|schedule,schedule');
$job->svnDir->range('/module/caselib,{6}');
$job->comment->range(',,,,,bug,');
$job->atDay->range('{2}0,{4}0,');
$job->atTime->range('{2}20,{4}20,');
$job->gen(7);

su('admin');

$jobTest = new jobTest();

r($jobTest->getTriggerConfigTest(1)) && p() && e('目录改动(/module/caselib)');            // 测试步骤1：tag触发类型且有svnDir
r($jobTest->getTriggerConfigTest(4)) && p() && e('打标签');                              // 测试步骤2：tag触发类型但无svnDir
r($jobTest->getTriggerConfigTest(2)) && p() && e('提交注释包含关键字(bug)');               // 测试步骤3：commit触发类型
r($jobTest->getTriggerConfigTest(3)) && p() && e('定时计划(星期日, 20)');                // 测试步骤4：schedule触发类型
r($jobTest->getTriggerConfigTest(6)) && p() && e('打标签; 提交注释包含关键字(bug); 定时计划(星期日, 20)'); // 测试步骤5：多种触发类型组合
r($jobTest->getTriggerConfigTest(999)) && p() && e('');                                 // 测试步骤6：不存在的job ID处理
r($jobTest->getTriggerConfigTest(5)) && p() && e('');                                   // 测试步骤7：空triggerType处理