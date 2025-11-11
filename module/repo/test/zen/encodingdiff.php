#!/usr/bin/env php
<?php

/**

title=测试 repoZen::encodingDiff();
timeout=0
cid=0

- 步骤1:空数组 @0
- 步骤2:文件名UTF-8第0条的fileName属性 @test.php
- 步骤3:GBK编码转换第0条的fileName属性 @index.php
- 步骤4:多层嵌套第0条的fileName属性 @utils.php
- 步骤5:空contents第0条的fileName属性 @empty.php
- 步骤6:空lines第0条的fileName属性 @noline.php
- 步骤7:空line字段第0条的fileName属性 @emptyline.php

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';
su('admin');

$repoTest = new repoZenTest();

// 测试步骤1: 空diff数组输入
$emptyDiffs = array();
r($repoTest->encodingDiffTest($emptyDiffs, 'UTF-8')) && p() && e('0'); // 步骤1:空数组

// 测试步骤2: 单个diff文件名编码转换
$diff1 = new stdClass();
$diff1->fileName = 'test.php';
$diff1->contents = array();
$diffs1 = array($diff1);
r($repoTest->encodingDiffTest($diffs1, 'UTF-8')) && p('0:fileName') && e('test.php'); // 步骤2:文件名UTF-8

// 测试步骤3: 包含内容行的diff编码转换
$line1 = new stdClass();
$line1->line = 'echo "Hello World";';
$content1 = new stdClass();
$content1->lines = array($line1);
$diff2 = new stdClass();
$diff2->fileName = 'index.php';
$diff2->contents = array($content1);
$diffs2 = array($diff2);
r($repoTest->encodingDiffTest($diffs2, 'GBK')) && p('0:fileName') && e('index.php'); // 步骤3:GBK编码转换

// 测试步骤4: 多层嵌套结构的diff
$line2a = new stdClass();
$line2a->line = 'function test() {';
$line2b = new stdClass();
$line2b->line = '    return true;';
$content2a = new stdClass();
$content2a->lines = array($line2a);
$content2b = new stdClass();
$content2b->lines = array($line2b);
$diff3 = new stdClass();
$diff3->fileName = 'utils.php';
$diff3->contents = array($content2a, $content2b);
$diffs3 = array($diff3);
$result3 = $repoTest->encodingDiffTest($diffs3, 'UTF-8');
r($result3) && p('0:fileName') && e('utils.php'); // 步骤4:多层嵌套

// 测试步骤5: 包含空contents的diff
$diff4 = new stdClass();
$diff4->fileName = 'empty.php';
$diff4->contents = null;
$diffs4 = array($diff4);
r($repoTest->encodingDiffTest($diffs4, 'UTF-8')) && p('0:fileName') && e('empty.php'); // 步骤5:空contents

// 测试步骤6: 包含空lines的content
$content3 = new stdClass();
$content3->lines = null;
$diff5 = new stdClass();
$diff5->fileName = 'noline.php';
$diff5->contents = array($content3);
$diffs5 = array($diff5);
r($repoTest->encodingDiffTest($diffs5, 'UTF-8')) && p('0:fileName') && e('noline.php'); // 步骤6:空lines

// 测试步骤7: 包含空line字段的lines
$line3 = new stdClass();
$line3->line = '';
$content4 = new stdClass();
$content4->lines = array($line3);
$diff6 = new stdClass();
$diff6->fileName = 'emptyline.php';
$diff6->contents = array($content4);
$diffs6 = array($diff6);
r($repoTest->encodingDiffTest($diffs6, 'UTF-8')) && p('0:fileName') && e('emptyline.php'); // 步骤7:空line字段