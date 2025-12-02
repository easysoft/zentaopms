#!/usr/bin/env php
<?php

/**

title=测试 aiModel::countLatestMiniPrograms();
timeout=0
cid=15008

- 执行aiTest模块的countLatestMiniProgramsTest方法  @0
- 执行aiTest模块的countLatestMiniProgramsTest方法  @0
- 执行aiTest模块的countLatestMiniProgramsTest方法  @0
- 执行aiTest模块的countLatestMiniProgramsTest方法  @0
- 执行aiTest模块的countLatestMiniProgramsTest方法  @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

su('admin');
$aiTest = new aiTest();

// 测试步骤1：空数据库情况
global $tester;
$tester->dao->delete()->from(TABLE_AI_MINIPROGRAM)->exec();
r($aiTest->countLatestMiniProgramsTest()) && p() && e('0');

// 测试步骤2：只有过期小程序的情况
$tester->dao->delete()->from(TABLE_AI_MINIPROGRAM)->exec();
for($i = 1; $i <= 3; $i++) {
    $data = array(
        'id' => $i,
        'name' => '过期小程序' . $i,
        'category' => 'work',
        'desc' => '测试描述',
        'model' => 1,
        'createdBy' => 'admin',
        'createdDate' => date('Y-m-d H:i:s', strtotime('-2 months')),
        'published' => '1',
        'deleted' => '0'
    );
    $tester->dao->insert(TABLE_AI_MINIPROGRAM)->data($data)->exec();
}
r($aiTest->countLatestMiniProgramsTest()) && p() && e('0');

// 测试步骤3：只有未发布小程序的情况
$tester->dao->delete()->from(TABLE_AI_MINIPROGRAM)->exec();
for($i = 1; $i <= 3; $i++) {
    $data = array(
        'id' => $i,
        'name' => '未发布小程序' . $i,
        'category' => 'work',
        'desc' => '测试描述',
        'model' => 1,
        'createdBy' => 'admin',
        'createdDate' => date('Y-m-d H:i:s', strtotime('-15 days')),
        'published' => '0',
        'deleted' => '0'
    );
    $tester->dao->insert(TABLE_AI_MINIPROGRAM)->data($data)->exec();
}
r($aiTest->countLatestMiniProgramsTest()) && p() && e('0');

// 测试步骤4：只有已删除小程序的情况
$tester->dao->delete()->from(TABLE_AI_MINIPROGRAM)->exec();
for($i = 1; $i <= 3; $i++) {
    $data = array(
        'id' => $i,
        'name' => '已删除小程序' . $i,
        'category' => 'work',
        'desc' => '测试描述',
        'model' => 1,
        'createdBy' => 'admin',
        'createdDate' => date('Y-m-d H:i:s', strtotime('-15 days')),
        'published' => '1',
        'deleted' => '1'
    );
    $tester->dao->insert(TABLE_AI_MINIPROGRAM)->data($data)->exec();
}
r($aiTest->countLatestMiniProgramsTest()) && p() && e('0');

// 测试步骤5：混合数据情况（包含符合条件的最新小程序）
$tester->dao->delete()->from(TABLE_AI_MINIPROGRAM)->exec();
// 插入3个符合条件的记录（最近1个月内、已发布、未删除）
for($i = 1; $i <= 3; $i++) {
    $data = array(
        'id' => $i,
        'name' => '符合条件小程序' . $i,
        'category' => 'work',
        'desc' => '测试描述',
        'model' => 1,
        'createdBy' => 'admin',
        'createdDate' => date('Y-m-d H:i:s', strtotime('-15 days')),
        'published' => '1',
        'deleted' => '0'
    );
    $tester->dao->insert(TABLE_AI_MINIPROGRAM)->data($data)->exec();
}
// 插入一些不符合条件的记录
for($i = 4; $i <= 6; $i++) {
    $data = array(
        'id' => $i,
        'name' => '过期小程序' . ($i-3),
        'category' => 'work',
        'desc' => '测试描述',
        'model' => 1,
        'createdBy' => 'admin',
        'createdDate' => date('Y-m-d H:i:s', strtotime('-2 months')),
        'published' => '1',
        'deleted' => '0'
    );
    $tester->dao->insert(TABLE_AI_MINIPROGRAM)->data($data)->exec();
}
r($aiTest->countLatestMiniProgramsTest()) && p() && e('3');