#!/usr/bin/env php
<?php

/**

title=测试 commonModel::checkSafeFile();
timeout=0
cid=15663

- 步骤1：容器环境返回false @0
- 步骤2：有效安全文件返回false @0
- 步骤3：upgrade模块升级中返回false @0
- 步骤4：无安全文件返回路径 @1
- 步骤5：安全文件过期返回路径 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$commonTest = new commonModelTest();

// 4. 执行测试步骤
r($commonTest->checkSafeFileTest('inContainer')) && p() && e('0'); // 步骤1：容器环境返回false
r($commonTest->checkSafeFileTest('validSafeFile')) && p() && e('0'); // 步骤2：有效安全文件返回false
r($commonTest->checkSafeFileTest('upgradeModule')) && p() && e('0'); // 步骤3：upgrade模块升级中返回false
r(strpos($commonTest->checkSafeFileTest('noSafeFile'), 'ok.txt') !== false) && p() && e('1'); // 步骤4：无安全文件返回路径
r(strpos($commonTest->checkSafeFileTest('expiredSafeFile'), 'ok.txt') !== false) && p() && e('1'); // 步骤5：安全文件过期返回路径