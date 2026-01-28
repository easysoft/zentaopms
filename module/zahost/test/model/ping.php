#!/usr/bin/env php
<?php

/**

title=测试 zahostModel::ping();
timeout=0
cid=19754

- 步骤1：测试本地回环地址 @yes
- 步骤2：测试localhost域名 @yes
- 步骤3：测试无效IP格式 @no
- 步骤4：测试不可达IP地址 @no
- 步骤5：测试空字符串输入 @no

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$zahost = new zahostModelTest();

r($zahost->pingTest('127.0.0.1')) && p() && e('yes');  // 步骤1：测试本地回环地址
r($zahost->pingTest('localhost')) && p() && e('yes');   // 步骤2：测试localhost域名
r($zahost->pingTest('invalid.ip.format')) && p() && e('no');  // 步骤3：测试无效IP格式
r($zahost->pingTest('10.0.0.222')) && p() && e('no');  // 步骤4：测试不可达IP地址
r($zahost->pingTest('')) && p() && e('no');            // 步骤5：测试空字符串输入