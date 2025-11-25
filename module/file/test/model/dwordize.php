#!/usr/bin/env php
<?php

/**

title=测试 fileModel::dwordize();
timeout=0
cid=16498

- 步骤1：最大RGB值 @16777215
- 步骤2：全零值 @0
- 步骤3：最小非零值 @1
- 步骤4：绿色分量 @256
- 步骤5：蓝色分量 @65536

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/file.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$fileTest = new fileTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($fileTest->dwordizeTest(chr(255) . chr(255) . chr(255))) && p() && e('16777215'); // 步骤1：最大RGB值
r($fileTest->dwordizeTest(chr(0) . chr(0) . chr(0))) && p() && e('0');             // 步骤2：全零值
r($fileTest->dwordizeTest(chr(1) . chr(0) . chr(0))) && p() && e('1');             // 步骤3：最小非零值
r($fileTest->dwordizeTest(chr(0) . chr(1) . chr(0))) && p() && e('256');           // 步骤4：绿色分量
r($fileTest->dwordizeTest(chr(0) . chr(0) . chr(1))) && p() && e('65536');         // 步骤5：蓝色分量