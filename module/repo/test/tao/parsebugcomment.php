#!/usr/bin/env php
<?php

/**

title=测试 repoTao::parseBugComment();
timeout=0
cid=18120

- 步骤1：正常单个bug修复属性3 @3
- 步骤2：正常多个bug修复
 - 属性3 @3
 - 属性5 @5
 - 属性12 @12
- 步骤3：空字符串输入 @0
- 步骤4：无效格式注释 @0
- 步骤5：非数字ID注释 @0
- 步骤6：复杂格式注释
 - 属性1 @1
 - 属性2 @2
 - 属性5 @5
 - 属性8 @8
 - 属性10 @10
- 步骤7：大小写不敏感属性7 @7

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

su('admin');

$repo = new repoTest();

// 测试数据准备
$normalSingleComment    = 'Fix bug#3';
$normalMultipleComment  = 'Fix bug#3,5,12';
$emptyComment          = '';
$invalidFormatComment  = 'Fixed some issues';
$nonNumericComment     = 'Fix bug#abc';
$complexFormatComment  = 'Fix bug#1,2 Fix bug#5,8,10';
$caseInsensitiveComment = 'fix BUG#7';

r($repo->parseBugCommentTest($normalSingleComment))    && p('3')         && e('3');      // 步骤1：正常单个bug修复
r($repo->parseBugCommentTest($normalMultipleComment))  && p('3,5,12')    && e('3,5,12'); // 步骤2：正常多个bug修复
r($repo->parseBugCommentTest($emptyComment))           && p()            && e('0');      // 步骤3：空字符串输入
r($repo->parseBugCommentTest($invalidFormatComment))   && p()            && e('0');      // 步骤4：无效格式注释
r($repo->parseBugCommentTest($nonNumericComment))      && p()            && e('0');      // 步骤5：非数字ID注释
r($repo->parseBugCommentTest($complexFormatComment))   && p('1,2,5,8,10') && e('1,2,5,8,10'); // 步骤6：复杂格式注释
r($repo->parseBugCommentTest($caseInsensitiveComment)) && p('7')         && e('7');      // 步骤7：大小写不敏感