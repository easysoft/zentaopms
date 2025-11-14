#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getSettingsMapping();
timeout=0
cid=15623

- 执行cneTest模块的getSettingsMappingTest方法 属性admin_username @admin
- 执行cneTest模块的getSettingsMappingTest方法，参数是array 属性z_username @zentao_user
- 执行cneTest模块的getSettingsMappingTest方法，参数是array 属性custom_username @test_value_for_custom_username
- 执行cneTest模块的getSettingsMappingTest方法，参数是array 属性db_username @test_value_for_db_username
- 执行cneTest模块的getSettingsMappingTest方法，参数是array 属性invalid_key @test_value_for_invalid_key

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$cneTest = new cneTest();

// 4. 执行测试步骤
r($cneTest->getSettingsMappingTest()) && p('admin_username') && e('admin');
r($cneTest->getSettingsMappingTest(array())) && p('z_username') && e('zentao_user');
r($cneTest->getSettingsMappingTest(array(array('key' => 'custom_username', 'type' => 'helm', 'path' => 'auth.custom_username')))) && p('custom_username') && e('test_value_for_custom_username');
r($cneTest->getSettingsMappingTest(array(array('key' => 'db_username', 'type' => 'secret', 'path' => 'database.username'), array('key' => 'db_password', 'type' => 'secret', 'path' => 'database.password')))) && p('db_username') && e('test_value_for_db_username');
r($cneTest->getSettingsMappingTest(array(array('key' => 'invalid_key', 'type' => 'invalid_type', 'path' => 'invalid.path')))) && p('invalid_key') && e('test_value_for_invalid_key');