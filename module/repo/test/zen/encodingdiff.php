#!/usr/bin/env php
<?php

/**

title=测试 repoZen::encodingDiff();
timeout=0
cid=0

- 步骤1：正常的diff数据和UTF-8编码第0条的fileName属性 @test.php
- 步骤2：包含中文文件名的diff数据和GBK编码第0条的fileName属性 @测试文件.php
- 步骤3：空的diff数组和任意编码 @0
- 步骤4：diff对象没有contents属性时的处理第0条的fileName属性 @nocontent.php
- 步骤5：contents为空数组时的处理第0条的fileName属性 @empty.php

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 5. 强制要求：必须包含至少5个测试步骤

// 创建测试数据
$normalDiff = new stdClass();
$normalDiff->fileName = 'test.php';
$normalDiff->contents = array();

$content1 = new stdClass();
$content1->lines = array();

$line1 = new stdClass();
$line1->line = 'echo "Hello World";';
$content1->lines[] = $line1;

$line2 = new stdClass();
$line2->line = 'function test() { return true; }';
$content1->lines[] = $line2;

$normalDiff->contents[] = $content1;

$chineseDiff = new stdClass();
$chineseDiff->fileName = '测试文件.php';
$chineseDiff->contents = array();

$content2 = new stdClass();
$content2->lines = array();

$line3 = new stdClass();
$line3->line = '// 这是一个测试文件';
$content2->lines[] = $line3;

$chineseDiff->contents[] = $content2;

$emptyContentDiff = new stdClass();
$emptyContentDiff->fileName = 'empty.php';
$emptyContentDiff->contents = array();

$noContentDiff = new stdClass();
$noContentDiff->fileName = 'nocontent.php';

// 步骤1：正常情况
r($repoTest->encodingDiffTest(array($normalDiff), 'UTF-8')) && p('0:fileName') && e('test.php'); // 步骤1：正常的diff数据和UTF-8编码

// 步骤2：中文文件名编码转换
r($repoTest->encodingDiffTest(array($chineseDiff), 'GBK')) && p('0:fileName') && e('测试文件.php'); // 步骤2：包含中文文件名的diff数据和GBK编码

// 步骤3：空diff数组
r($repoTest->encodingDiffTest(array(), 'UTF-8')) && p() && e('0'); // 步骤3：空的diff数组和任意编码

// 步骤4：没有contents属性的diff对象
r($repoTest->encodingDiffTest(array($noContentDiff), 'UTF-8')) && p('0:fileName') && e('nocontent.php'); // 步骤4：diff对象没有contents属性时的处理

// 步骤5：contents为空数组的情况
r($repoTest->encodingDiffTest(array($emptyContentDiff), 'UTF-8')) && p('0:fileName') && e('empty.php'); // 步骤5：contents为空数组时的处理