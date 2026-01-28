#!/usr/bin/env php
<?php

/**

title=测试 commonModel::checkIP();
timeout=0
cid=15656

- 步骤1：通配符允许所有IP @1
- 步骤2：精确匹配当前IP @1
- 步骤3：多个IP白名单匹配 @1
- 步骤4：IP范围匹配测试 @1
- 步骤5：通配符IP段匹配 @1
- 步骤6：CIDR格式匹配测试 @1
- 步骤7：不匹配IP测试 @0
- 步骤8：空字符串输入测试（使用默认配置） @1
- 步骤9：更复杂通配符测试 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 设置测试环境的IP地址，模拟客户端IP
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

$commonTest = new commonModelTest();

r($commonTest->checkIPTest('*')) && p() && e('1');                            // 步骤1：通配符允许所有IP
r($commonTest->checkIPTest('127.0.0.1')) && p() && e('1');                 // 步骤2：精确匹配当前IP
r($commonTest->checkIPTest('192.168.1.1,127.0.0.1,10.0.0.1')) && p() && e('1'); // 步骤3：多个IP白名单匹配
r($commonTest->checkIPTest('127.0.0.1-127.0.0.255')) && p() && e('1');     // 步骤4：IP范围匹配测试
r($commonTest->checkIPTest('127.0.0.*')) && p() && e('1');                 // 步骤5：通配符IP段匹配
r($commonTest->checkIPTest('127.0.0.0/24')) && p() && e('1');              // 步骤6：CIDR格式匹配测试
r($commonTest->checkIPTest('192.168.1.1')) && p() && e('0');               // 步骤7：不匹配IP测试
r($commonTest->checkIPTest('')) && p() && e('1');                          // 步骤8：空字符串输入测试（使用默认配置）
r($commonTest->checkIPTest('127.*')) && p() && e('1');                     // 步骤9：更复杂通配符测试