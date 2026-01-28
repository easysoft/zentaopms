#!/usr/bin/env php
<?php

/**

title=测试 settingModel::setSN();
timeout=0
cid=18369

- 测试已安装系统且无cookie时的SN设置属性length @32
- 测试已安装系统且cookie存在有效SN时的处理属性length @32
- 测试已安装系统且cookie存在需要更新的SN时的处理属性length @32
- 测试未安装系统时的SN设置属性length @32
- 测试setSN方法返回值格式验证属性isValidMD5 @1
- 测试多个无效SN值的处理第0条的changed属性 @1
- 测试SN配置持久化存储属性snMatch @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$settingTest = new settingModelTest();

r($settingTest->setSNTestForInstalled()) && p('length') && e('32'); // 测试已安装系统且无cookie时的SN设置
r($settingTest->setSNTestWithValidCookie()) && p('length') && e('32'); // 测试已安装系统且cookie存在有效SN时的处理
r($settingTest->setSNTestWithInvalidCookie()) && p('length') && e('32'); // 测试已安装系统且cookie存在需要更新的SN时的处理
r($settingTest->setSNTestForNotInstalled()) && p('length') && e('32'); // 测试未安装系统时的SN设置
r($settingTest->setSNTestFormatValidation()) && p('isValidMD5') && e('1'); // 测试setSN方法返回值格式验证
r($settingTest->setSNTestWithMultipleInvalidSNs()) && p('0:changed') && e('1'); // 测试多个无效SN值的处理
r($settingTest->setSNTestConfigPersistence()) && p('snMatch') && e('1'); // 测试SN配置持久化存储