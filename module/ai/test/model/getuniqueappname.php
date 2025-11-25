#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getUniqueAppName();
timeout=0
cid=15051

- 执行aiTest模块的getUniqueAppNameTest方法，参数是'独特应用名称'  @独特应用名称
- 执行aiTest模块的getUniqueAppNameTest方法，参数是'重复名称'  @重复名称_1_1
- 执行aiTest模块的getUniqueAppNameTest方法，参数是'重复名称_1'  @重复名称_1_1
- 执行aiTest模块的getUniqueAppNameTest方法，参数是''  @0
- 执行aiTest模块的getUniqueAppNameTest方法，参数是'特殊字符测试'  @特殊字符测试

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

global $tester;
$dao = $tester->dao;

$dao->exec("DELETE FROM " . TABLE_AI_MINIPROGRAM);

$dao->insert(TABLE_AI_MINIPROGRAM)
    ->data(array(
        'id' => 1,
        'name' => '测试应用',
        'category' => 'work',
        'desc' => '这是一个测试应用',
        'model' => 1,
        'icon' => 'test-icon-1',
        'createdBy' => 'admin',
        'createdDate' => '2024-01-01 10:00:00',
        'editedBy' => 'admin',
        'editedDate' => '2024-01-01 10:00:00',
        'published' => '0',
        'deleted' => '0',
        'prompt' => '这是测试提示词1',
        'builtIn' => '0'
    ))
    ->exec();

$dao->insert(TABLE_AI_MINIPROGRAM)
    ->data(array(
        'id' => 2,
        'name' => '重复名称',
        'category' => 'personal',
        'desc' => '用于测试重复名称',
        'model' => 2,
        'icon' => 'test-icon-2',
        'createdBy' => 'user1',
        'createdDate' => '2024-01-02 10:00:00',
        'editedBy' => 'user1',
        'editedDate' => '2024-01-02 10:00:00',
        'published' => '0',
        'deleted' => '0',
        'prompt' => '这是测试提示词2',
        'builtIn' => '0'
    ))
    ->exec();

$dao->insert(TABLE_AI_MINIPROGRAM)
    ->data(array(
        'id' => 3,
        'name' => '重复名称_1',
        'category' => 'creative',
        'desc' => '用于测试重复名称_1',
        'model' => 3,
        'icon' => 'test-icon-3',
        'createdBy' => 'user2',
        'createdDate' => '2024-01-03 10:00:00',
        'editedBy' => 'user2',
        'editedDate' => '2024-01-03 10:00:00',
        'published' => '0',
        'deleted' => '0',
        'prompt' => '这是测试提示词3',
        'builtIn' => '0'
    ))
    ->exec();

su('admin');

$aiTest = new aiTest();

r($aiTest->getUniqueAppNameTest('独特应用名称')) && p() && e('独特应用名称');
r($aiTest->getUniqueAppNameTest('重复名称')) && p() && e('重复名称_1_1');
r($aiTest->getUniqueAppNameTest('重复名称_1')) && p() && e('重复名称_1_1');
r($aiTest->getUniqueAppNameTest('')) && p() && e('0');
r($aiTest->getUniqueAppNameTest('特殊字符测试')) && p() && e('特殊字符测试');