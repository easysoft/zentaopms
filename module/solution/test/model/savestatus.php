#!/usr/bin/env php
<?php

/**

title=测试 solutionModel->saveStatus();
timeout=0
cid=1

- 错误的状态
 - 属性id @1
 - 属性status @notEnoughResource
- 正确的状态
 - 属性id @1
 - 属性status @cneError
- 不存在的解决方案 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/solution.class.php';

zdTable('solution')->config('solution')->gen(1);

$solutionModel = new solutionTest();

r($solutionModel->saveStatusTest(1, 'test'))     && p('id,status') && e('1,notEnoughResource'); // 错误的状态
r($solutionModel->saveStatusTest(1, 'cneError')) && p('id,status') && e('1,cneError');          // 正确的状态

r($solutionModel->saveStatusTest(2, 'cneError')) && p() && e('0'); // 不存在的解决方案