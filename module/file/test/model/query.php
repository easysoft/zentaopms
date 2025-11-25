#!/usr/bin/env php
<?php

/**

title=测试 fileModel::query();
timeout=0
cid=16522

- 步骤1：查询task类型文件
 - 属性id @1
 - 属性objectType @task
- 步骤2：查询指定objectID的story文件
 - 属性id @3
 - 属性objectID @3
- 步骤3：查询指定objectType和title的文件属性title @文件标题2
- 步骤4：查询指定objectType和extra的文件属性extra @editor
- 步骤5：查询不存在的文件 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/file.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$file = zenData('file');
$file->pathname->range('202409/081234561.txt,202409/081234562.png,202409/081234563.doc,202409/081234564.pdf,202409/081234565.jpg,202409/081234566.txt,202409/081234567.png,202409/081234568.doc,202409/081234569.pdf,202409/0812345610.jpg');
$file->extra->range(',editor,import,export,,editor,import,export,,');
$file->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$fileTest = new fileTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($fileTest->queryTest('task')) && p('id,objectType') && e('1,task'); // 步骤1：查询task类型文件
r($fileTest->queryTest('story', 3)) && p('id,objectID') && e('3,3'); // 步骤2：查询指定objectID的story文件
r($fileTest->queryTest('bug', 0, '文件标题2')) && p('title') && e('文件标题2'); // 步骤3：查询指定objectType和title的文件
r($fileTest->queryTest('task', 0, '', 'editor')) && p('extra') && e('editor'); // 步骤4：查询指定objectType和extra的文件
r($fileTest->queryTest('nonexistent')) && p() && e('0'); // 步骤5：查询不存在的文件