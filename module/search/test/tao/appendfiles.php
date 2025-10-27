#!/usr/bin/env php
<?php

/**

title=测试 searchTao::appendFiles();
timeout=0
cid=0

- 步骤1：无关联文件情况属性title @Test Document 4
- 步骤2：空文件ID情况属性title @Test Document 5
- 步骤3：已有comment的情况属性comment @Existing comment
- 步骤4：不存在的文档ID属性title @Non-existent Document
- 步骤5：没有设置comment字段的情况属性id @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 2. zendata数据准备
$docTable = zenData('doc');
$docTable->id->range('1-5');
$docTable->title->range('Test Document {1-5}');
$docTable->keywords->range('test,document{5}');
$docTable->type->range('text{3}, file{2}');
$docTable->status->range('normal{5}');
$docTable->gen(5);

$docContentTable = zenData('doccontent');
$docContentTable->id->range('1-5');
$docContentTable->doc->range('1-5');
$docContentTable->files->range('1,2,3{1}, 4,5{1}, 6{1}, []{2}');
$docContentTable->version->range('1{5}');
$docContentTable->gen(5);

$fileTable = zenData('file');
$fileTable->id->range('1-6');
$fileTable->pathname->range('/tmp/zentao_test_file{1-6}.txt');
$fileTable->title->range('Test File {1-6}');
$fileTable->extension->range('txt{3}, docx{2}, doc{1}');
$fileTable->objectType->range('doc{6}');
$fileTable->objectID->range('1-5{6}');
$fileTable->gen(6);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$searchTest = new searchTest();

// 5. 强制要求：必须包含至少5个测试步骤

// 步骤1：测试无关联文件的文档对象
$docObject1 = new stdClass();
$docObject1->id = 4;
$docObject1->title = 'Test Document 4';
r($searchTest->appendFilesTest($docObject1)) && p('title') && e('Test Document 4'); // 步骤1：无关联文件情况

// 步骤2：测试空文件ID的文档对象
$docObject2 = new stdClass();
$docObject2->id = 5;
$docObject2->title = 'Test Document 5';
r($searchTest->appendFilesTest($docObject2)) && p('title') && e('Test Document 5'); // 步骤2：空文件ID情况

// 步骤3：测试文档对象已有comment内容
$docObject3 = new stdClass();
$docObject3->id = 2;
$docObject3->title = 'Test Document 2';
$docObject3->comment = 'Existing comment';
r($searchTest->appendFilesTest($docObject3)) && p('comment') && e('Existing comment'); // 步骤3：已有comment的情况

// 步骤4：测试不存在的文档ID
$docObject4 = new stdClass();
$docObject4->id = 999;
$docObject4->title = 'Non-existent Document';
r($searchTest->appendFilesTest($docObject4)) && p('title') && e('Non-existent Document'); // 步骤4：不存在的文档ID

// 步骤5：测试文档对象没有设置comment字段的情况
$docObject5 = new stdClass();
$docObject5->id = 1;
$docObject5->title = 'Test Document 1';
r($searchTest->appendFilesTest($docObject5)) && p('id') && e('1'); // 步骤5：没有设置comment字段的情况