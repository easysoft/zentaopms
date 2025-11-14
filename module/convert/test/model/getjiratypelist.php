#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getJiraTypeList();
timeout=0
cid=15784

- 执行convertTest模块的getJiraTypeListTest方法  @0
- 执行convertTest模块的getJiraTypeListTest方法  @0
- 执行convertTest模块的getJiraTypeListTest方法  @0
- 执行convertTest模块的getJiraTypeListTest方法  @0
- 执行convertTest模块的getJiraTypeListTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

su('admin');

$convertTest = new convertTest();

// 设置测试环境
global $app;
$originalJiraMethod = $app->session->jiraMethod ?? null;

// 测试步骤1：使用file方法获取类型列表
$app->session->set('jiraMethod', 'file');
r($convertTest->getJiraTypeListTest()) && p() && e('0');

// 测试步骤2：使用file方法但无有效文件时
$app->session->set('jiraMethod', 'file');
r($convertTest->getJiraTypeListTest()) && p() && e('0');

// 测试步骤3：清除jiraMethod后的处理
unset($_SESSION['jiraMethod']);
r($convertTest->getJiraTypeListTest()) && p() && e('0');

// 测试步骤4：重新设置有效的jiraMethod
$app->session->set('jiraMethod', 'file');
r($convertTest->getJiraTypeListTest()) && p() && e('0');

// 测试步骤5：处理边界情况
$app->session->set('jiraMethod', 'invalid');
r($convertTest->getJiraTypeListTest()) && p() && e('0');

// 恢复原始session数据
if($originalJiraMethod !== null) {
    $app->session->set('jiraMethod', $originalJiraMethod);
} else {
    unset($_SESSION['jiraMethod']);
}