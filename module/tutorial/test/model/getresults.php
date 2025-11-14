#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getResults();
timeout=0
cid=19466

- 执行tutorialTest模块的getResultsTest方法  @1
- 执行tutorialTest模块的getResultsTest方法 第1条的id属性 @1
- 执行tutorialTest模块的getResultsTest方法 第1条的caseResult属性 @fail
- 执行tutorialTest模块的getResultsTest方法 第1条的lastRunner属性 @admin
- 执行getResultsTest()[1]模块的stepResults方法  @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

su('admin');

$tutorialTest = new tutorialTest();

r(count($tutorialTest->getResultsTest())) && p() && e('1');
r($tutorialTest->getResultsTest()) && p('1:id') && e('1');
r($tutorialTest->getResultsTest()) && p('1:caseResult') && e('fail');
r($tutorialTest->getResultsTest()) && p('1:lastRunner') && e('admin');
r(count($tutorialTest->getResultsTest()[1]->stepResults)) && p() && e('2');