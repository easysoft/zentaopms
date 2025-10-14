#!/usr/bin/env php
<?php

/**

title=测试 instanceZen::checkForInstall();
timeout=0
cid=0

- 步骤1：正常情况属性result @success
- 步骤2：保留域名属性result @fail
- 步骤3：名称为空属性result @fail
- 步骤4：域名过长属性result @fail
- 步骤5：非法字符属性result @fail

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('instance');
$table->id->range('1-10');
$table->name->range('test-app{2}, existing-app{3}, another-app{5}');
$table->domain->range('testapp{2}, existingapp{3}, anotherapp{5}');
$table->status->range('running{5}, stopped{5}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 测试步骤1：正常的域名和名称输入，验证通过
$validCustomData = new stdClass();
$validCustomData->customDomain = 'myapp';
$validCustomData->customName = 'My Test App';
r($instanceTest->checkForInstallTest($validCustomData)) && p('result') && e('success'); // 步骤1：正常情况

// 测试步骤2：域名为保留域名console，应该返回域名已被占用错误
$reservedDomainData = new stdClass();
$reservedDomainData->customDomain = 'console';
$reservedDomainData->customName = 'Console App';
r($instanceTest->checkForInstallTest($reservedDomainData)) && p('result') && e('fail'); // 步骤2：保留域名

// 测试步骤3：应用名称为空，应该返回名称不能为空错误
$emptyNameData = new stdClass();
$emptyNameData->customDomain = 'validapp';
$emptyNameData->customName = '';
r($instanceTest->checkForInstallTest($emptyNameData)) && p('result') && e('fail'); // 步骤3：名称为空

// 测试步骤4：域名长度超过20字符，应该返回域名长度错误
$longDomainData = new stdClass();
$longDomainData->customDomain = 'verylongdomainnameover20chars';
$longDomainData->customName = 'Valid App Name';
r($instanceTest->checkForInstallTest($longDomainData)) && p('result') && e('fail'); // 步骤4：域名过长

// 测试步骤5：域名包含非法字符（大写字母和特殊符号），应该返回域名字符错误
$invalidCharData = new stdClass();
$invalidCharData->customDomain = 'App-Name_123';
$invalidCharData->customName = 'Valid App Name';
r($instanceTest->checkForInstallTest($invalidCharData)) && p('result') && e('fail'); // 步骤5：非法字符