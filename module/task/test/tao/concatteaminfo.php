#!/usr/bin/env php
<?php

/**

title=测试 taskTao::concatTeamInfo();
timeout=0
cid=0

- 测试步骤1：正常团队信息拼接包含两个成员 @团队成员: 管理员, 预计: 8, 消耗: 2.5, 剩余: 5.5\n团队成员: 用户一, 预计: 6, 消耗: 3, 剩余: 3\n

- 测试步骤2：单个团队成员信息 @团队成员: 开发者, 预计: 10, 消耗: 0, 剩余: 10\n

- 测试步骤3：空团队信息列表 @
- 测试步骤4：用户不存在的情况 @团队成员: unknown, 预计: 5, 消耗: 1, 剩余: 4\n

- 测试步骤5：包含数字类型的工时信息 @团队成员: 测试员, 预计: 12, 消耗: 4.75, 剩余: 7.25\n

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$task = new taskTest();

// 4. 准备测试数据

// 测试数据1：正常团队信息拼接包含两个成员
$teamInfoList1 = array();
$teamInfo1 = new stdclass();
$teamInfo1->account = 'admin';
$teamInfo1->estimate = 8.0;
$teamInfo1->consumed = 2.5;
$teamInfo1->left = 5.5;
$teamInfoList1[] = $teamInfo1;

$teamInfo2 = new stdclass();
$teamInfo2->account = 'user1';
$teamInfo2->estimate = 6.0;
$teamInfo2->consumed = 3.0;
$teamInfo2->left = 3.0;
$teamInfoList1[] = $teamInfo2;

$userPairs1 = array('admin' => '管理员', 'user1' => '用户一');

// 测试数据2：单个团队成员信息
$teamInfoList2 = array();
$singleInfo = new stdclass();
$singleInfo->account = 'developer';
$singleInfo->estimate = 10.0;
$singleInfo->consumed = 0.0;
$singleInfo->left = 10.0;
$teamInfoList2[] = $singleInfo;

$userPairs2 = array('developer' => '开发者');

// 测试数据3：空团队信息列表
$teamInfoList3 = array();
$userPairs3 = array();

// 测试数据4：用户不存在的情况
$teamInfoList4 = array();
$unknownInfo = new stdclass();
$unknownInfo->account = 'unknown';
$unknownInfo->estimate = 5.0;
$unknownInfo->consumed = 1.0;
$unknownInfo->left = 4.0;
$teamInfoList4[] = $unknownInfo;

$userPairs4 = array();

// 测试数据5：包含数字类型的工时信息
$teamInfoList5 = array();
$numericInfo = new stdclass();
$numericInfo->account = 'tester';
$numericInfo->estimate = '12';
$numericInfo->consumed = '4.75';
$numericInfo->left = '7.25';
$teamInfoList5[] = $numericInfo;

$userPairs5 = array('tester' => '测试员');

// 预先执行所有测试
$result1 = $task->concatTeamInfoTest($teamInfoList1, $userPairs1);
$result2 = $task->concatTeamInfoTest($teamInfoList2, $userPairs2);
$result3 = $task->concatTeamInfoTest($teamInfoList3, $userPairs3);
$result4 = $task->concatTeamInfoTest($teamInfoList4, $userPairs4);
$result5 = $task->concatTeamInfoTest($teamInfoList5, $userPairs5);

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($result1) && p() && e("团队成员: 管理员, 预计: 8, 消耗: 2.5, 剩余: 5.5\n团队成员: 用户一, 预计: 6, 消耗: 3, 剩余: 3\n");  // 测试步骤1：正常团队信息拼接包含两个成员
r($result2) && p() && e("团队成员: 开发者, 预计: 10, 消耗: 0, 剩余: 10\n");                                              // 测试步骤2：单个团队成员信息
r($result3) && p() && e('');                                                                                         // 测试步骤3：空团队信息列表
r($result4) && p() && e("团队成员: unknown, 预计: 5, 消耗: 1, 剩余: 4\n");                                              // 测试步骤4：用户不存在的情况
r($result5) && p() && e("团队成员: 测试员, 预计: 12, 消耗: 4.75, 剩余: 7.25\n");                                        // 测试步骤5：包含数字类型的工时信息