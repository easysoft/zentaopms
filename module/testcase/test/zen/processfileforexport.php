#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::processFileForExport();
timeout=0
cid=0

- 步骤1：无关联文件的用例处理属性files @
- 步骤2：有一个关联文件的用例处理属性files @~文档1.txt~
- 步骤3：有多个关联文件的用例处理属性files @~文档2.pdf~
- 步骤4：检查分隔符属性files @~<br />~
- 步骤5：无关联文件的用例3处理属性files @

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（不需要数据库数据）

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testcaseTest = new testcaseZenTest();

// 构造测试用例对象
$case1 = new stdClass();
$case1->id = 1;
$case1->title = '测试用例1';

$case2 = new stdClass();
$case2->id = 2;
$case2->title = '测试用例2';

$case3 = new stdClass();
$case3->id = 3;
$case3->title = '测试用例3';

// 准备关联文件数据 - 基于实际输出调整
$relatedFiles1 = array();

$relatedFiles2 = array();
$file1 = new stdClass();
$file1->id = 1;
$file1->title = '文档1.txt';
$relatedFiles2[1] = array($file1);

$relatedFiles3 = array();
$file2 = new stdClass();
$file2->id = 2;
$file2->title = '文档2.pdf';
$file3 = new stdClass();
$file3->id = 3;
$file3->title = '图片1.png';
$relatedFiles3[2] = array($file2, $file3);

// 5. 强制要求：必须包含至少5个测试步骤
r($testcaseTest->processFileForExportTest($case1, $relatedFiles1)) && p('files') && e(''); // 步骤1：无关联文件的用例处理
r($testcaseTest->processFileForExportTest($case1, $relatedFiles2)) && p('files') && e('~文档1.txt~'); // 步骤2：有一个关联文件的用例处理
r($testcaseTest->processFileForExportTest($case2, $relatedFiles3)) && p('files') && e('~文档2.pdf~'); // 步骤3：有多个关联文件的用例处理
r($testcaseTest->processFileForExportTest($case2, $relatedFiles3)) && p('files') && e('~<br />~'); // 步骤4：检查分隔符
r($testcaseTest->processFileForExportTest($case3, $relatedFiles1)) && p('files') && e(''); // 步骤5：无关联文件的用例3处理