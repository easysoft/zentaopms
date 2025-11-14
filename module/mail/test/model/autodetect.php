#!/usr/bin/env php
<?php

/**

title=测试 mailModel::autoDetect();
timeout=0
cid=17003

- 步骤1：正常QQ邮箱配置检测属性host >> smtp.qq.com
- 步骤2：正常163邮箱配置检测属性host >> smtp.163.com
- 步骤3：正常搜狐邮箱配置检测属性host >> smtp.sohu.com
- 步骤4：正常Gmail邮箱配置检测属性host >> smtp.gmail.com
- 步骤5：无效邮箱地址检测返回空host >> 空字符串
- 步骤6：空字符串邮箱地址检测返回空host >> 空字符串
- 步骤7：QQ邮箱端口配置验证属性port >> 465
- 步骤8：QQ邮箱加密方式验证属性secure >> ssl
- 步骤9：Gmail邮箱端口和加密配置验证属性port,secure >> 465,ssl

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

su('admin');

$mailTest = new mailTest();

r($mailTest->autoDetectTest('test@qq.com')) && p('host') && e('smtp.qq.com');        // 步骤1：正常QQ邮箱配置检测
r($mailTest->autoDetectTest('test@163.com')) && p('host') && e('smtp.163.com');       // 步骤2：正常163邮箱配置检测
r($mailTest->autoDetectTest('test@sohu.com')) && p('host') && e('smtp.sohu.com');      // 步骤3：正常搜狐邮箱配置检测
r($mailTest->autoDetectTest('test@gmail.com')) && p('host') && e('smtp.gmail.com');  // 步骤4：正常Gmail邮箱配置检测
r($mailTest->autoDetectTest('invalid-email')) && p('host') && e('');        // 步骤5：无效邮箱地址检测
r($mailTest->autoDetectTest('')) && p('host') && e('');                          // 步骤6：空字符串邮箱地址检测
r($mailTest->autoDetectTest('test@qq.com')) && p('port') && e('465');                // 步骤7：QQ邮箱端口配置验证
r($mailTest->autoDetectTest('test@qq.com')) && p('secure') && e('ssl');              // 步骤8：QQ邮箱加密方式验证
r($mailTest->autoDetectTest('test@gmail.com')) && p('port,secure') && e('465,ssl');  // 步骤9：Gmail邮箱端口和加密配置验证