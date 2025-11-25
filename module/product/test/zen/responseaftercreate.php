#!/usr/bin/env php
<?php

/**

title=测试 productZen::responseAfterCreate();
timeout=0
cid=17602

- 步骤1:正常情况,无项目集
 - 属性result @success
 - 属性message @保存成功
- 步骤2:有项目集
 - 属性result @success
 - 属性message @保存成功
- 步骤3:JSON视图
 - 属性result @success
 - 属性message @保存成功
 - 属性id @1
- 步骤4:正常创建返回
 - 属性result @success
 - 属性message @保存成功
- 步骤5:产品ID为0
 - 属性result @success
 - 属性message @保存成功

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$productTest = new productZenTest();

// 4. 测试步骤
r($productTest->responseAfterCreateTest(1, 0, '')) && p('result,message') && e('success,保存成功'); // 步骤1:正常情况,无项目集
r($productTest->responseAfterCreateTest(1, 1, '')) && p('result,message') && e('success,保存成功'); // 步骤2:有项目集
r($productTest->responseAfterCreateTest(1, 0, 'json')) && p('result,message,id') && e('success,保存成功,1'); // 步骤3:JSON视图
r($productTest->responseAfterCreateTest(2, 1, '')) && p('result,message') && e('success,保存成功'); // 步骤4:正常创建返回
r($productTest->responseAfterCreateTest(0, 0, '')) && p('result,message') && e('success,保存成功'); // 步骤5:产品ID为0