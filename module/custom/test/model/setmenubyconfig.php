#!/usr/bin/env php
<?php

/**

title=测试 customModel::setMenuByConfig();
timeout=0
cid=15926

- 测试步骤1：使用标准对象菜单和空自定义菜单 @array
- 测试步骤2：使用数组菜单和JSON字符串自定义菜单 @array
- 测试步骤3：测试空菜单对象的处理 @array
- 测试步骤4：测试null参数的边界情况 @array
- 测试步骤5：测试复杂菜单配置场景 @array
- 测试步骤6：测试JSON格式自定义菜单配置 @array
- 测试步骤7：测试带有分割线的主菜单配置 @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

ob_start();
zenData('lang')->loadYaml('lang')->gen(15);
zenData('user')->gen(5);
ob_end_clean();
su('admin');

$customTester = new customTest();

r($customTester->setMenuByConfigTest((object)array('home' => 'Home|index|index', 'project' => 'Project|project|index'), array(), 'main')) && p() && e('array'); // 测试步骤1：使用标准对象菜单和空自定义菜单
r($customTester->setMenuByConfigTest(array('task' => 'Task|task|browse', 'bug' => 'Bug|bug|browse'), '[{"name":"task","order":1},{"name":"bug","order":2}]', 'product')) && p() && e('array'); // 测试步骤2：使用数组菜单和JSON字符串自定义菜单
r($customTester->setMenuByConfigTest(new stdclass(), array(), '')) && p() && e('array'); // 测试步骤3：测试空菜单对象的处理
r($customTester->setMenuByConfigTest(null, null, 'main')) && p() && e('array'); // 测试步骤4：测试null参数的边界情况
r($customTester->setMenuByConfigTest((object)array('index' => 'Home|index|index', 'company' => 'Company|company|index', 'admin' => 'Admin|admin|index'), '', 'main')) && p() && e('array'); // 测试步骤5：测试复杂菜单配置场景
r($customTester->setMenuByConfigTest(array('story' => 'Story|story|browse', 'plan' => 'Plan|productplan|browse'), '{"story":{"order":10},"plan":{"order":20}}', 'product')) && p() && e('array'); // 测试步骤6：测试JSON格式自定义菜单配置
r($customTester->setMenuByConfigTest((object)array('index' => 'Home|index|index', 'project' => 'Project|project|index', 'product' => 'Product|product|index'), array(), 'main')) && p() && e('array'); // 测试步骤7：测试带有分割线的主菜单配置

zenData('lang')->gen(0);