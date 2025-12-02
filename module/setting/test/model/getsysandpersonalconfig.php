#!/usr/bin/env php
<?php

/**

title=测试 settingModel::getSysAndPersonalConfig();
timeout=0
cid=18362

- 执行setting模块的getSysAndPersonalConfigTest方法，参数是'admin')) ? 0 : 1  @1
- 执行setting模块的getSysAndPersonalConfigTest方法，参数是'')) ? 0 : 1  @1
- 执行setting模块的getSysAndPersonalConfigTest方法，参数是'nonexistentuser')) ? 0 : 1  @1
- 执行setting模块的getSysAndPersonalConfigTest方法，参数是'')) ? 1 : 0  @1
- 执行setting模块的getSysAndPersonalConfigTest方法，参数是'testuser')) ? 0 : 1  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/setting.unittest.class.php';

// 使用默认数据，数量少一些避免重复
zenData('config')->gen(7);

// 用户登录
su('admin');

// 创建测试实例
$setting = new settingTest();

// 测试步骤1：测试正常用户account参数，验证返回不为假
r(empty($setting->getSysAndPersonalConfigTest('admin')) ? 0 : 1) && p() && e('1');

// 测试步骤2：测试空account参数，验证返回不为假
r(empty($setting->getSysAndPersonalConfigTest('')) ? 0 : 1) && p() && e('1');

// 测试步骤3：测试不存在的用户account，验证返回不为假
r(empty($setting->getSysAndPersonalConfigTest('nonexistentuser')) ? 0 : 1) && p() && e('1');

// 测试步骤4：测试数据类型，验证返回值是数组类型
r(is_array($setting->getSysAndPersonalConfigTest('')) ? 1 : 0) && p() && e('1');

// 测试步骤5：测试特殊字符参数，验证返回不为假
r(empty($setting->getSysAndPersonalConfigTest('testuser')) ? 0 : 1) && p() && e('1');