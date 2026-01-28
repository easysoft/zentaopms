#!/usr/bin/env php
<?php

/**

title=测试 zahostModel::checkAddress();
timeout=0
cid=19741

- 步骤1：测试有效域名 @1
- 步骤2：测试有效IP地址 @1
- 步骤3：测试带协议前缀的地址 @1
- 步骤4：测试无效域名 @0
- 步骤5：测试空字符串 @0
- 步骤6：测试非法IP格式 @0
- 步骤7：测试localhost @1
- 步骤8：测试特殊字符地址 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$zahostTest = new zahostModelTest();

r($zahostTest->checkAddressTest('www.baidu.com')) && p() && e('1');         // 步骤1：测试有效域名
r($zahostTest->checkAddressTest('127.0.0.1')) && p() && e('1');             // 步骤2：测试有效IP地址
r($zahostTest->checkAddressTest('https://www.google.com')) && p() && e('1'); // 步骤3：测试带协议前缀的地址
r($zahostTest->checkAddressTest('invalid-domain-name')) && p() && e('0');   // 步骤4：测试无效域名
r($zahostTest->checkAddressTest('')) && p() && e('0');                      // 步骤5：测试空字符串
r($zahostTest->checkAddressTest('999.999.999.999')) && p() && e('0');       // 步骤6：测试非法IP格式
r($zahostTest->checkAddressTest('localhost')) && p() && e('1');              // 步骤7：测试localhost
r($zahostTest->checkAddressTest('test@#$%')) && p() && e('0');              // 步骤8：测试特殊字符地址