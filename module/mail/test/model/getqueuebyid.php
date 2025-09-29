#!/usr/bin/env php
<?php

/**

title=测试 mailModel::getQueueById();
cid=0

- 步骤1：正常获取ID为1的队列 >> 期望返回队列对象，id为1，objectType为mail
- 步骤2：正常获取ID为5的队列 >> 期望返回队列对象，id为5，objectType为mail
- 步骤3：无效ID为0的情况 >> 期望返回false
- 步骤4：不存在的ID情况 >> 期望返回false
- 步骤5：负数ID的情况 >> 期望返回false

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

// 2. zendata数据准备
zendata('notify')->loadYaml('notify_getqueuebyid', false, 2)->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$mailTest = new mailTest();

// 5. 执行测试步骤（至少5个）
r($mailTest->getQueueByIdTest(1)) && p('id,objectType') && e('1,mail'); // 步骤1：正常获取ID为1的队列
r($mailTest->getQueueByIdTest(5)) && p('id,objectType') && e('5,mail'); // 步骤2：正常获取ID为5的队列
r($mailTest->getQueueByIdTest(0)) && p() && e(false); // 步骤3：无效ID为0
r($mailTest->getQueueByIdTest(999)) && p() && e(false); // 步骤4：不存在的ID
r($mailTest->getQueueByIdTest(-1)) && p() && e(false); // 步骤5：负数ID