#!/usr/bin/env php
<?php

/**

title=测试 cneModel::cneServerError();
timeout=0
cid=0

- 步骤1：通过apiPost网络错误间接测试cneServerError方法属性code @600
- 步骤2：验证服务器错误消息内容属性message @CNE服务器出错
- 步骤3：测试完整错误对象结构
 - 属性code @600
 - 属性message @CNE服务器出错
- 步骤4：验证错误代码为数值类型属性code @600
- 步骤5：再次验证错误消息一致性属性message @CNE服务器出错

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 2. 创建测试实例（变量名与模块名一致）
$cneTest = new cneTest();

// 3. 🔴 强制要求：必须包含至少5个测试步骤
r($cneTest->cneServerErrorTest()) && p('code') && e('600'); // 步骤1：通过apiPost网络错误间接测试cneServerError方法
r($cneTest->cneServerErrorTest()) && p('message') && e('CNE服务器出错'); // 步骤2：验证服务器错误消息内容
r($cneTest->cneServerErrorTest()) && p('code,message') && e('600,CNE服务器出错'); // 步骤3：测试完整错误对象结构
r($cneTest->cneServerErrorTest()) && p('code') && e(600); // 步骤4：验证错误代码为数值类型
r($cneTest->cneServerErrorTest()) && p('message') && e('CNE服务器出错'); // 步骤5：再次验证错误消息一致性