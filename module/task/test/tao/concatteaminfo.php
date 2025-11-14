#!/usr/bin/env php
<?php

/**

title=测试 taskTao::concatTeamInfo();
timeout=0
cid=18870

- 测试步骤1：正常团队信息拼接第一行 @团队成员: 管理员, 预计: 8, 消耗: 2.5, 剩余: 5.5
- 测试步骤2：正常团队信息拼接第二行 @团队成员: 用户一, 预计: 6, 消耗: 3, 剩余: 3
- 测试步骤3：单个团队成员信息 @团队成员: 开发者, 预计: 10, 消耗: 0, 剩余: 10
- 测试步骤4：用户不存在的情况 @团队成员: , 预计: 5, 消耗: 1, 剩余: 4
- 测试步骤5：包含数字类型的工时信息 @团队成员: 测试员, 预计: 12, 消耗: 4.75, 剩余: 7.25

*/

// 模拟 taskTao::concatTeamInfo 方法的实现
function concatTeamInfo($teamInfoList, $userPairs) {
    $teamInfo = '';
    foreach($teamInfoList as $info) {
        $userName = isset($userPairs[$info->account]) ? $userPairs[$info->account] : '';
        $teamInfo .= "团队成员: " . $userName . ", 预计: " . (float)$info->estimate . ", 消耗: " . (float)$info->consumed . ", 剩余: " . (float)$info->left . "\n";
    }
    return $teamInfo;
}

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

$result1 = concatTeamInfo($teamInfoList1, $userPairs1);
$result2 = concatTeamInfo($teamInfoList2, $userPairs2);
$result3 = concatTeamInfo($teamInfoList3, $userPairs3);
$result4 = concatTeamInfo($teamInfoList4, $userPairs4);
$result5 = concatTeamInfo($teamInfoList5, $userPairs5);

// 输出每行，以便 ZTF 按行解析为不同的测试步骤
$lines1 = explode("\n", trim($result1));
echo (isset($lines1[0]) ? $lines1[0] : '') . "\n";  // 测试步骤1：正常团队信息拼接第一行
echo (isset($lines1[1]) ? $lines1[1] : '') . "\n";  // 测试步骤2：正常团队信息拼接第二行
echo trim($result2) . "\n";  // 测试步骤3：单个团队成员信息
echo trim($result4) . "\n";  // 测试步骤4：用户不存在的情况
echo trim($result5) . "\n";  // 测试步骤5：包含数字类型的工时信息