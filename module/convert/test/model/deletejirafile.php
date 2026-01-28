#!/usr/bin/env php
<?php

/**

title=测试 convertModel::deleteJiraFile();
timeout=0
cid=15770

- 步骤1：正常删除所有预定义文件属性deletedCount @34
- 步骤2：验证预定义文件总数属性totalFiles @34
- 步骤3：验证删除数量和总数一致
 - 属性deletedCount @34
 - 属性totalFiles @34
- 步骤4：目录不存在时的删除操作属性deletedCount @34
- 步骤5：部分文件存在时的删除属性deletedCount @34

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertModelTest();

// 4. 测试步骤：必须包含至少5个测试步骤
r($convertTest->deleteJiraFileTest()) && p('deletedCount') && e('34'); // 步骤1：正常删除所有预定义文件
r($convertTest->deleteJiraFileTest()) && p('totalFiles') && e('34'); // 步骤2：验证预定义文件总数
r($convertTest->deleteJiraFileTest()) && p('deletedCount,totalFiles') && e('34,34'); // 步骤3：验证删除数量和总数一致

// 测试特殊场景：目录不存在
global $app;
$jiraPath = $app->getTmpRoot() . 'jirafile_test/';
if(is_dir($jiraPath)) {
    $files = glob($jiraPath . '*.xml');
    foreach($files as $file) @unlink($file);
    @rmdir($jiraPath);
}
r($convertTest->deleteJiraFileTest()) && p('deletedCount') && e('34'); // 步骤4：目录不存在时的删除操作

// 测试部分文件存在的场景
if(!is_dir($jiraPath)) mkdir($jiraPath, 0777, true);
$partialFiles = array('action.xml', 'project.xml', 'user.xml');
foreach($partialFiles as $file) {
    file_put_contents($jiraPath . $file, '<?xml version="1.0"?><test>content</test>');
}
r($convertTest->deleteJiraFileTest()) && p('deletedCount') && e('34'); // 步骤5：部分文件存在时的删除