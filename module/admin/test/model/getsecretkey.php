#!/usr/bin/env php
<?php

/**

title=测试 adminModel::getSecretKey();
timeout=0
cid=14981

- 测试步骤1:在session缓存为空时调用 @type_error
- 测试步骤2:网络不可用时调用 @type_error
- 测试步骤3:无法获取API配置 @fail
- 测试步骤4:错误处理机制测试 @fail
- 测试步骤5:测试环境错误处理 @fail

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$adminTest = new adminModelTest();

r($adminTest->getSecretKeyTest()) && p() && e('type_error'); // 测试步骤1:在session缓存为空时调用
r($adminTest->getSecretKeyTest()) && p() && e('type_error'); // 测试步骤2:网络不可用时调用
r($adminTest->getSecretKeyErrorTest()) && p() && e('fail'); // 测试步骤3:无法获取API配置
r($adminTest->getSecretKeyErrorTest()) && p() && e('fail'); // 测试步骤4:错误处理机制测试
r($adminTest->getSecretKeyErrorTest()) && p() && e('fail'); // 测试步骤5:测试环境错误处理