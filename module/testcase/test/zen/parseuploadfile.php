#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::parseUploadFile();
timeout=0
cid=0

- 步骤1：正常产品ID但无文件上传 @ given
- 步骤2：指定分支但无文件上传 @ given
- 步骤3：无效产品ID但无文件上传 @ given
- 步骤4：负数产品ID但无文件上传 @ given
- 步骤5：不存在的产品ID但无文件上传 @ given

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-5');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->status->range('normal{5}');
$table->type->range('normal{5}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(substr($testcaseTest->parseUploadFileTest(1, 'all', array()), -6)) && p() && e(' given'); // 步骤1：正常产品ID但无文件上传
r(substr($testcaseTest->parseUploadFileTest(2, '0', array()), -6)) && p() && e(' given'); // 步骤2：指定分支但无文件上传
r(substr($testcaseTest->parseUploadFileTest(0, 'all', array()), -6)) && p() && e(' given'); // 步骤3：无效产品ID但无文件上传
r(substr($testcaseTest->parseUploadFileTest(-1, 'all', array()), -6)) && p() && e(' given'); // 步骤4：负数产品ID但无文件上传
r(substr($testcaseTest->parseUploadFileTest(999, 'all', array()), -6)) && p() && e(' given'); // 步骤5：不存在的产品ID但无文件上传