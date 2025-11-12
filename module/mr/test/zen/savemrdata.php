#!/usr/bin/env php
<?php

/**

title=测试 mrZen::saveMrData();
timeout=0
cid=0

- 执行mrTest模块的saveMrDataTest方法，参数是$repo, array  @1
- 执行mrTest模块的saveMrDataTest方法，参数是$repo, $rawMRList  @1
- 执行mrTest模块的saveMrDataTest方法，参数是$repo, array  @1
- 执行mrTest模块的saveMrDataTest方法，参数是$repo, array  @1
- 执行mrTest模块的saveMrDataTest方法，参数是$repo, array  @1
- 执行$savedMR属性status @opened
- 执行$actionCount > 0 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $app, $tester;
$app->setMethodName('view');

zenData('repo')->loadYaml('savemrdata/repo', false, 2)->gen(10);
zenData('mr')->loadYaml('savemrdata/mr', false, 2)->gen(0);
zenData('action')->loadYaml('savemrdata/action', false, 2)->gen(0);

su('admin');

$mrTest = new mrZenTest();

// 测试步骤1: 保存单个普通MR数据(状态为open,需转换为opened)
$repo = new stdclass();
$repo->id = 1;
$repo->serviceHost = 1;
$repo->SCM = 'Gitlab';

$rawMR = new stdclass();
$rawMR->iid = 100;
$rawMR->source_project_id = '10';
$rawMR->source_branch = 'feature-test';
$rawMR->target_project_id = '1';
$rawMR->target_branch = 'main';
$rawMR->title = 'Test MR 1';
$rawMR->created_at = '2025-11-10T10:00:00Z';
$rawMR->updated_at = '2025-11-10T12:00:00Z';
$rawMR->merge_status = 'can_be_merged';
$rawMR->state = 'open';
r($mrTest->saveMrDataTest($repo, array($rawMR))) && p() && e('1');

// 测试步骤2: 保存多个MR数据(包含3个MR)
$rawMRList = array();
for($i = 1; $i <= 3; $i++)
{
    $rawMR = new stdclass();
    $rawMR->iid = 200 + $i;
    $rawMR->source_project_id = (string)(10 + $i);
    $rawMR->source_branch = 'feature-' . $i;
    $rawMR->target_project_id = '1';
    $rawMR->target_branch = 'main';
    $rawMR->title = 'Test MR ' . $i;
    $rawMR->created_at = '2025-11-10T10:00:00Z';
    $rawMR->updated_at = '2025-11-10T12:00:00Z';
    $rawMR->merge_status = 'can_be_merged';
    $rawMR->state = 'opened';
    $rawMRList[] = $rawMR;
}
r($mrTest->saveMrDataTest($repo, $rawMRList)) && p() && e('1');

// 测试步骤3: 保存pullreq类型数据(flow字段存在)
$rawMR = new stdclass();
$rawMR->iid = 300;
$rawMR->source_project_id = '20';
$rawMR->source_branch = 'feature-flow';
$rawMR->target_project_id = '1';
$rawMR->target_branch = 'main';
$rawMR->title = 'Test Flow MR';
$rawMR->created_at = '2025-11-10T10:00:00Z';
$rawMR->updated_at = '2025-11-10T12:00:00Z';
$rawMR->merge_status = 'can_be_merged';
$rawMR->state = 'opened';
$rawMR->flow = 1;
r($mrTest->saveMrDataTest($repo, array($rawMR))) && p() && e('1');

// 测试步骤4: 保存使用毫秒时间戳的MR数据
$rawMR = new stdclass();
$rawMR->iid = 400;
$rawMR->source_project_id = '30';
$rawMR->source_branch = 'feature-timestamp';
$rawMR->target_project_id = '1';
$rawMR->target_branch = 'main';
$rawMR->title = 'Test Timestamp MR';
$rawMR->created = 1699603200000;
$rawMR->updated = 1699610400000;
$rawMR->merge_status = 'can_be_merged';
$rawMR->state = 'opened';
r($mrTest->saveMrDataTest($repo, array($rawMR))) && p() && e('1');

// 测试步骤5: 保存空的MR列表
r($mrTest->saveMrDataTest($repo, array())) && p() && e('1');

// 测试步骤6: 验证保存的MR状态已转换(open -> opened)
$savedMR = $tester->dao->select('*')->from(TABLE_MR)->where('mriid')->eq(100)->fetch();
r($savedMR) && p('status') && e('opened');

// 测试步骤7: 验证action记录已创建
$actionCount = $tester->dao->select('count(*) as count')->from(TABLE_ACTION)->where('objectType')->in('mr,pullreq')->fetch('count');
r($actionCount > 0) && p() && e('1');