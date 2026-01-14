#!/usr/bin/env php
<?php

/**

title=测试 apiModel::deleteRelease();
timeout=0
cid=15100

- 步骤1：删除存在的发布记录ID=1，验证删除成功 @0
- 步骤2：删除另一个存在的发布记录ID=2，验证删除成功 @0
- 步骤3：删除不存在的发布记录ID=999，验证返回空结果 @0
- 步骤4：删除无效ID=0的发布记录，验证返回空结果 @0
- 步骤5：删除负数ID=-1的发布记录，验证返回空结果 @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$table = zenData('api_lib_release');
$table->id->range('1-5');
$table->lib->range('1-3:R');
$table->desc->range('测试发布版本{5}');
$table->version->range('v1.0,v1.1,v1.2,v2.0,v2.1');
$table->addedBy->range('admin{5}');
$table->addedDate->range('`2023-01-01 00:00:00`');
$table->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$apiTest = new apiModelTest();

// 5. 测试步骤（至少5个）
r($apiTest->deleteReleaseTest(1, 1)) && p() && e('0'); // 步骤1：删除存在的发布记录ID=1，验证删除成功
r($apiTest->deleteReleaseTest(2, 1)) && p() && e('0'); // 步骤2：删除另一个存在的发布记录ID=2，验证删除成功
r($apiTest->deleteReleaseTest(999, 1)) && p() && e('0'); // 步骤3：删除不存在的发布记录ID=999，验证返回空结果
r($apiTest->deleteReleaseTest(0, 1)) && p() && e('0'); // 步骤4：删除无效ID=0的发布记录，验证返回空结果
r($apiTest->deleteReleaseTest(-1, 1)) && p() && e('0'); // 步骤5：删除负数ID=-1的发布记录，验证返回空结果