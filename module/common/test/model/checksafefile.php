#!/usr/bin/env php
<?php

/**

title=测试 commonModel::checkSafeFile();
timeout=0
cid=0

- 步骤1：测试容器环境下checkSafeFile返回false @0
- 步骤2：测试upgrade模块且upgrading会话时返回false @0
- 步骤3：测试有效安全文件时返回false @0
- 步骤4：测试安全文件不存在时返回文件路径 @/home/z/repo/git/zentaopms/www/data/ok.txt
- 步骤5：测试默认情况（无有效安全文件）返回文件路径 @/home/z/repo/git/zentaopms/www/data/ok.txt

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

// 3. 创建测试实例（变量名与模块名一致）
$commonTest = new commonTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($commonTest->checkSafeFileTest('inContainer')) && p() && e('0'); // 步骤1：测试容器环境下checkSafeFile返回false
r($commonTest->checkSafeFileTest('upgradeModule')) && p() && e('0'); // 步骤2：测试upgrade模块且upgrading会话时返回false
r($commonTest->checkSafeFileTest('validSafeFile')) && p() && e('0'); // 步骤3：测试有效安全文件时返回false
r($commonTest->checkSafeFileTest('noSafeFile')) && p() && e('/home/z/repo/git/zentaopms/www/data/ok.txt'); // 步骤4：测试安全文件不存在时返回文件路径
r($commonTest->checkSafeFileTest()) && p() && e('/home/z/repo/git/zentaopms/www/data/ok.txt'); // 步骤5：测试默认情况（无有效安全文件）返回文件路径