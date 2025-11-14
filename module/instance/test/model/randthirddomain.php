#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::randThirdDomain();
timeout=0
cid=16812

- 步骤1：测试默认长度4的域名生成 @4
- 步骤2：测试长度为2的短域名生成 @2
- 步骤3：测试长度为8的长域名生成 @8
- 步骤4：测试多次尝试参数的影响 @5
- 步骤5：测试生成结果为小写字母和数字 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

// 2. zendata数据准备
$table = zenData('instance');
$table->id->range('1-10');
$table->domain->range('test1.example.com,test2.example.com,test3.example.com');
$table->deleted->range('0');
$table->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$instanceTest = new instanceTest();

// 5. 测试步骤
r(strlen($instanceTest->randThirdDomainTest(4))) && p('') && e('4'); // 步骤1：测试默认长度4的域名生成
r(strlen($instanceTest->randThirdDomainTest(2))) && p('') && e('2'); // 步骤2：测试长度为2的短域名生成
r(strlen($instanceTest->randThirdDomainTest(8))) && p('') && e('8'); // 步骤3：测试长度为8的长域名生成
r(strlen($instanceTest->randThirdDomainTest(5, 3))) && p('') && e('5'); // 步骤4：测试多次尝试参数的影响
r(preg_match('/^[a-z0-9]+$/', $instanceTest->randThirdDomainTest(6))) && p('') && e('1'); // 步骤5：测试生成结果为小写字母和数字