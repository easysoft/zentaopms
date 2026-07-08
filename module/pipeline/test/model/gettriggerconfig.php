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
- 测试步骤5：多种触发类型组合 @打标签; 定时计划(星期日, 20)
- 测试步骤6：不存在的job ID返回空字符串长度 @0
- 测试步骤7：未命中触发类型返回空字符串长度 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 3) . '/model.php';

su('admin');

$jobModel = new jobModel();

$triggerWithDir = (object)array('id' => 1, 'triggerType' => 'tag',                  'svnDir' => '/module/caselib', 'comment' => '',    'atDay' => '0', 'atTime' => '20');
$tagTrigger     = (object)array('id' => 2, 'triggerType' => 'tag',                  'svnDir' => '',                'comment' => '',    'atDay' => '0', 'atTime' => '20');
$commitTrigger  = (object)array('id' => 3, 'triggerType' => 'commit',               'svnDir' => '',                'comment' => 'bug', 'atDay' => '0', 'atTime' => '20');
$scheduleTrigger= (object)array('id' => 4, 'triggerType' => 'schedule',             'svnDir' => '',                'comment' => '',    'atDay' => '0', 'atTime' => '20');
$emptyTrigger   = (object)array('id' => 5, 'triggerType' => 'none',                 'svnDir' => '',                'comment' => '',    'atDay' => '0', 'atTime' => '20');
$mixedTrigger   = (object)array('id' => 6, 'triggerType' => 'tag|schedule',         'svnDir' => '',                'comment' => '',    'atDay' => '0', 'atTime' => '20');
$getTriggerConfig = function(?object $job) use ($jobModel): string
{
    if(empty($job) || empty($job->id)) return '';
    return $jobModel->getTriggerConfig($job);
};

r($getTriggerConfig($triggerWithDir)) && p() && e('目录改动(/module/caselib)'); // 测试步骤1：tag触发类型且有svnDir
r($getTriggerConfig($tagTrigger))     && p() && e('打标签');                    // 测试步骤2：tag触发类型但无svnDir
r($getTriggerConfig($commitTrigger))  && p() && e('提交注释包含关键字(bug)');   // 测试步骤3：commit触发类型
r($getTriggerConfig($scheduleTrigger))&& p() && e('定时计划(星期日, 20)');      // 测试步骤4：schedule触发类型
r($getTriggerConfig($mixedTrigger))   && p() && e('打标签; 定时计划(星期日, 20)'); // 测试步骤5：多种触发类型组合
r(strlen($getTriggerConfig((object)array()))) && p() && e('0');                 // 测试步骤6：不存在的job ID处理
r(strlen($getTriggerConfig($emptyTrigger)))   && p() && e('0');                 // 测试步骤7：空triggerType处理
