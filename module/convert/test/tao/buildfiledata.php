#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildFileData();
timeout=0
cid=15812

- 步骤1：正常完整数据
 - 属性issueid @1001
 - 属性id @101
 - 属性filename @test.jpg
- 步骤2：必填字段数据
 - 属性issueid @1002
 - 属性id @102
- 步骤3：部分字段缺失
 - 属性issueid @1003
 - 属性id @103
 - 属性filename @doc.pdf
 - 属性author @user1
- 步骤4：空数据
 - 属性issueid @0
 - 属性id @0
- 步骤5：数据类型验证
 - 属性issueid @1005
 - 属性id @105
 - 属性filename @archive.zip
 - 属性mimetype @application/zip
 - 属性filesize @5120

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($convertTest->buildFileDataTest(array('issue' => 1001, 'id' => 101, 'filename' => 'test.jpg', 'mimetype' => 'image/jpeg', 'filesize' => 2048, 'created' => '2023-01-01 12:00:00', 'author' => 'admin'))) && p('issueid,id,filename') && e('1001,101,test.jpg'); // 步骤1：正常完整数据
r($convertTest->buildFileDataTest(array('issue' => 1002, 'id' => 102))) && p('issueid,id') && e('1002,102'); // 步骤2：必填字段数据
r($convertTest->buildFileDataTest(array('issue' => 1003, 'id' => 103, 'filename' => 'doc.pdf', 'author' => 'user1'))) && p('issueid,id,filename,author') && e('1003,103,doc.pdf,user1'); // 步骤3：部分字段缺失
r($convertTest->buildFileDataTest(array('issue' => 0, 'id' => 0))) && p('issueid,id') && e('0,0'); // 步骤4：空数据
r($convertTest->buildFileDataTest(array('issue' => 1005, 'id' => 105, 'filename' => 'archive.zip', 'mimetype' => 'application/zip', 'filesize' => 5120))) && p('issueid,id,filename,mimetype,filesize') && e('1005,105,archive.zip,application/zip,5120'); // 步骤5：数据类型验证