#!/usr/bin/env php
<?php

/**

title=测试 mailModel::autoDetect();
timeout=0
cid=0

- 步骤1：正常QQ邮箱配置检测属性host @smtp.qq.com
- 步骤2：正常163邮箱配置检测属性host @smtp.163.com
- 步骤3：正常搜狐邮箱配置检测属性host @smtp.sohu.com
- 步骤4：正常搜狐VIP邮箱配置检测属性host @smtp.vip.sohu.com
- 步骤5：无效邮箱地址检测 @没有检测到相关信息
- 步骤6：空字符串邮箱地址检测 @没有检测到相关信息
- 步骤7：未知域名邮箱地址检测属性host @smtp.unknown.com
- 步骤8：QQ邮箱端口配置验证属性port @465
- 步骤9：QQ邮箱加密方式验证属性secure @ssl

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

su('admin');

$mailTest = new mailTest();

r($mailTest->autoDetectTest('test@qq.com')) && p('host') && e('smtp.qq.com');        // 步骤1：正常QQ邮箱配置检测
r($mailTest->autoDetectTest('test@163.com')) && p('host') && e('smtp.163.com');       // 步骤2：正常163邮箱配置检测
r($mailTest->autoDetectTest('test@sohu.com')) && p('host') && e('smtp.sohu.com');      // 步骤3：正常搜狐邮箱配置检测
r($mailTest->autoDetectTest('test@vip.sohu.com')) && p('host') && e('smtp.vip.sohu.com');  // 步骤4：正常搜狐VIP邮箱配置检测
r($mailTest->autoDetectTest('testm')) && p() && e('没有检测到相关信息'); // 步骤5：无效邮箱地址检测
r($mailTest->autoDetectTest('')) && p() && e('没有检测到相关信息');                 // 步骤6：空字符串邮箱地址检测
r($mailTest->autoDetectTest('test@unknown.com')) && p('host') && e('smtp.unknown.com'); // 步骤7：未知域名邮箱地址检测
r($mailTest->autoDetectTest('test@qq.com')) && p('port') && e('465');                // 步骤8：QQ邮箱端口配置验证
r($mailTest->autoDetectTest('test@qq.com')) && p('secure') && e('ssl');              // 步骤9：QQ邮箱加密方式验证