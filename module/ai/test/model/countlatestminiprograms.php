#!/usr/bin/env php
<?php

/**

title=测试 aiModel::countLatestMiniPrograms();
timeout=0
cid=0

- 执行aiTest模块的countLatestMiniProgramsTest方法  @0
- 执行aiTest模块的countLatestMiniProgramsTest方法  @3
- 执行aiTest模块的countLatestMiniProgramsTest方法  @3
- 执行aiTest模块的countLatestMiniProgramsTest方法  @3
- 执行aiTest模块的countLatestMiniProgramsTest方法  @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

su('admin');
$aiTest = new aiTest();

// 清空数据表，测试空数据库情况
global $tester;
$tester->dao->delete()->from(TABLE_AI_MINIPROGRAM)->exec();

r($aiTest->countLatestMiniProgramsTest()) && p() && e('0');

// 插入测试数据
$data = array();
// 最近15天的3条符合条件记录
for($i = 1; $i <= 3; $i++) {
    $data[] = array(
        'id' => $i,
        'name' => '最近小程序' . $i,
        'category' => 'work',
        'desc' => '测试描述',
        'model' => 1,
        'icon' => 'writinghand-7',
        'createdBy' => 'admin',
        'createdDate' => date('Y-m-d H:i:s', strtotime('-15 days')),
        'editedBy' => 'admin',
        'editedDate' => date('Y-m-d H:i:s', strtotime('-15 days')),
        'published' => '1',
        'publishedDate' => date('Y-m-d H:i:s', strtotime('-15 days')),
        'deleted' => '0',
        'prompt' => '测试提示词',
        'builtIn' => '0'
    );
}

// 2个月前的3条记录（不符合时间条件）
for($i = 4; $i <= 6; $i++) {
    $data[] = array(
        'id' => $i,
        'name' => '过期小程序' . ($i-3),
        'category' => 'work',
        'desc' => '测试描述',
        'model' => 1,
        'icon' => 'writinghand-7',
        'createdBy' => 'admin',
        'createdDate' => date('Y-m-d H:i:s', strtotime('-2 months')),
        'editedBy' => 'admin',
        'editedDate' => date('Y-m-d H:i:s', strtotime('-2 months')),
        'published' => '1',
        'publishedDate' => date('Y-m-d H:i:s', strtotime('-2 months')),
        'deleted' => '0',
        'prompt' => '测试提示词',
        'builtIn' => '0'
    );
}

// 未发布的2条记录（不符合发布条件）
for($i = 7; $i <= 8; $i++) {
    $data[] = array(
        'id' => $i,
        'name' => '未发布小程序' . ($i-6),
        'category' => 'work',
        'desc' => '测试描述',
        'model' => 1,
        'icon' => 'writinghand-7',
        'createdBy' => 'admin',
        'createdDate' => date('Y-m-d H:i:s', strtotime('-15 days')),
        'editedBy' => 'admin',
        'editedDate' => date('Y-m-d H:i:s', strtotime('-15 days')),
        'published' => '0',
        'publishedDate' => null,
        'deleted' => '0',
        'prompt' => '测试提示词',
        'builtIn' => '0'
    );
}

// 已删除的2条记录（不符合删除条件）
for($i = 9; $i <= 10; $i++) {
    $data[] = array(
        'id' => $i,
        'name' => '已删除小程序' . ($i-8),
        'category' => 'work',
        'desc' => '测试描述',
        'model' => 1,
        'icon' => 'writinghand-7',
        'createdBy' => 'admin',
        'createdDate' => date('Y-m-d H:i:s', strtotime('-15 days')),
        'editedBy' => 'admin',
        'editedDate' => date('Y-m-d H:i:s', strtotime('-15 days')),
        'published' => '1',
        'publishedDate' => date('Y-m-d H:i:s', strtotime('-15 days')),
        'deleted' => '1',
        'prompt' => '测试提示词',
        'builtIn' => '0'
    );
}

foreach($data as $record) {
    $tester->dao->insert(TABLE_AI_MINIPROGRAM)->data($record)->exec();
}

r($aiTest->countLatestMiniProgramsTest()) && p() && e('3');
r($aiTest->countLatestMiniProgramsTest()) && p() && e('3');
r($aiTest->countLatestMiniProgramsTest()) && p() && e('3');
r($aiTest->countLatestMiniProgramsTest()) && p() && e('3');