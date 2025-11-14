#!/usr/bin/env php
<?php

/**

title=测试 mailModel::getConfigFromProvider();
timeout=0
cid=17008

- 步骤1：测试qq.com邮件服务商配置获取属性host @smtp.qq.com
- 步骤2：测试qq.com端口和安全配置
 - 属性port @465
 - 属性secure @ssl
- 步骤3：测试gmail.com邮件服务商配置
 - 属性host @smtp.gmail.com
 - 属性mta @smtp
 - 属性auth @1
- 步骤4：测试163.com基础配置
 - 属性host @smtp.163.com
 - 属性port @25
- 步骤5：测试不存在的域名 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$mailTest = new mailTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($mailTest->getConfigFromProviderTest('qq.com', 'test@qq.com')) && p('host') && e('smtp.qq.com'); // 步骤1：测试qq.com邮件服务商配置获取
r($mailTest->getConfigFromProviderTest('qq.com', 'test@qq.com')) && p('port,secure') && e('465,ssl'); // 步骤2：测试qq.com端口和安全配置
r($mailTest->getConfigFromProviderTest('gmail.com', 'user@gmail.com')) && p('host,mta,auth') && e('smtp.gmail.com,smtp,1'); // 步骤3：测试gmail.com邮件服务商配置
r($mailTest->getConfigFromProviderTest('163.com', 'test@163.com')) && p('host,port') && e('smtp.163.com,25'); // 步骤4：测试163.com基础配置
r($mailTest->getConfigFromProviderTest('nonexistent.com', 'test@nonexistent.com')) && p() && e('0'); // 步骤5：测试不存在的域名