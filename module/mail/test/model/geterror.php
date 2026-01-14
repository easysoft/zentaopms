#!/usr/bin/env php
<?php

/**

title=测试 mailModel::getError();
timeout=0
cid=0

- 执行mailTest模块的getErrorTest方法  @用户邮箱不存在。
- 执行mailTest模块的getErrorTest方法
 -  @SMTP连接失败
 - 属性1 @用户名密码错误
 - 属性2 @端口配置错误
- 执行mailTest模块的getErrorTest方法  @0
- 执行objectModel模块的errors方法  @0
- 执行mailTest模块的getErrorTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$mailTest = new mailModelTest();

// 测试步骤1：获取单个错误信息
$mailTest->objectModel->errors = array('用户邮箱不存在。');
r($mailTest->getErrorTest()) && p('0') && e('用户邮箱不存在。');

// 测试步骤2：获取多个错误信息
$mailTest->objectModel->errors = array('SMTP连接失败', '用户名密码错误', '端口配置错误');
r($mailTest->getErrorTest()) && p('0,1,2') && e('SMTP连接失败,用户名密码错误,端口配置错误');

// 测试步骤3：获取空错误信息
$mailTest->objectModel->errors = array();
r($mailTest->getErrorTest()) && p() && e('0');

// 测试步骤4：验证getError方法会清空errors属性
$mailTest->objectModel->errors = array('测试错误信息');
$result = $mailTest->getErrorTest();
r($mailTest->objectModel->errors) && p() && e('0');

// 测试步骤5：测试连续调用getError方法
$mailTest->objectModel->errors = array('第一次错误信息', '第二次错误信息');
$firstCall = $mailTest->getErrorTest();
r($mailTest->getErrorTest()) && p() && e('0');