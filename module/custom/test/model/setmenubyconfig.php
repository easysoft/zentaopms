#!/usr/bin/env php
<?php

/**

title=测试 customModel::setMenuByConfig();
timeout=0
cid=0

- 测试步骤1：使用标准对象菜单和空自定义菜单测试主模块 @array
- 测试步骤2：使用数组菜单和JSON字符串自定义菜单测试 @array
- 测试步骤3：测试空菜单对象的情况 @array
- 测试步骤4：测试带有分割线配置的菜单 @array
- 测试步骤5：测试无效模块菜单处理 @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

ob_start();
zenData('lang')->loadYaml('lang')->gen(15);
zenData('user')->gen(5);
ob_end_clean();
su('admin');

$customTester = new customTest();

r($customTester->setMenuByConfigTest((object)array('home' => 'Home|index|index', 'project' => 'Project|project|index'), array(), 'main')) && p() && e('array'); // 测试步骤1：使用标准对象菜单和空自定义菜单测试主模块
r($customTester->setMenuByConfigTest(array('task' => 'Task|task|browse', 'bug' => 'Bug|bug|browse'), '[{"name":"task","order":1},{"name":"bug","order":2}]', 'product')) && p() && e('array'); // 测试步骤2：使用数组菜单和JSON字符串自定义菜单测试
r($customTester->setMenuByConfigTest(new stdclass(), array(), '')) && p() && e('array'); // 测试步骤3：测试空菜单对象的情况
r($customTester->setMenuByConfigTest((object)array('index' => 'Home|index|index', 'divider' => '-', 'project' => 'Project|project|index'), array(), 'main')) && p() && e('array'); // 测试步骤4：测试带有分割线配置的菜单
r($customTester->setMenuByConfigTest((object)array('custom' => 'Custom|custom|index'), array(), 'invalid')) && p() && e('array'); // 测试步骤5：测试无效模块菜单处理

zenData('lang')->gen(0);