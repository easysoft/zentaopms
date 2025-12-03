#!/usr/bin/env php
<?php

/**

title=测试 aiModel::assemblePrompt();
timeout=0
cid=14995

- 执行aiTest模块的assemblePromptTest方法，参数是$prompt1, 'Test1'), 'Test1') === 0  @1
- 执行aiTest模块的assemblePromptTest方法，参数是$prompt2, 'Test2'), 'Test2') === 0  @1
- 执行aiTest模块的assemblePromptTest方法，参数是$prompt3, ''), 'Role3') === 0  @1
- 执行aiTest模块的assemblePromptTest方法，参数是$prompt4, 'OnlyData') === "OnlyData\n"  @1
- 执行aiTest模块的assemblePromptTest方法，参数是$prompt5, 'Special'), 'Special') === 0  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

su('admin');

$aiTest = new aiTest();

$prompt1 = new stdClass();
$prompt1->role = 'Role1';
$prompt1->characterization = 'Char1';
$prompt1->purpose = 'Purpose1';
$prompt1->elaboration = 'Elab1';

$prompt2 = new stdClass();
$prompt2->role = 'Role2';
$prompt2->characterization = '';
$prompt2->purpose = 'Purpose2';
$prompt2->elaboration = '';

$prompt3 = new stdClass();
$prompt3->role = 'Role3';
$prompt3->characterization = 'Char3';
$prompt3->purpose = 'Purpose3';
$prompt3->elaboration = 'Elab3';

$prompt4 = new stdClass();
$prompt4->role = '';
$prompt4->characterization = '';
$prompt4->purpose = '';
$prompt4->elaboration = '';

$prompt5 = new stdClass();
$prompt5->role = 'Role5!';
$prompt5->characterization = 'Char5;';
$prompt5->purpose = 'Purpose5:test';
$prompt5->elaboration = 'Elab5!';

r(strpos($aiTest->assemblePromptTest($prompt1, 'Test1'), 'Test1') === 0) && p() && e('1');
r(strpos($aiTest->assemblePromptTest($prompt2, 'Test2'), 'Test2') === 0) && p() && e('1');
r(strpos($aiTest->assemblePromptTest($prompt3, ''), 'Role3') === 0) && p() && e('1');
r($aiTest->assemblePromptTest($prompt4, 'OnlyData') === "OnlyData\n") && p() && e('1');
r(strpos($aiTest->assemblePromptTest($prompt5, 'Special'), 'Special') === 0) && p() && e('1');
