#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getPlan();
timeout=0
cid=19446

- 执行tutorialTest模块的getPlanTest方法 属性id @1
- 执行tutorialTest模块的getPlanTest方法 属性title @Test plan
- 执行tutorialTest模块的getPlanTest方法 属性status @wait
- 执行tutorialTest模块的getPlanTest方法 属性product @1
- 执行tutorialTest模块的getPlanTest方法 属性createdBy @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$tutorialTest = new tutorialModelTest();

r($tutorialTest->getPlanTest()) && p('id') && e('1');
r($tutorialTest->getPlanTest()) && p('title') && e('Test plan');
r($tutorialTest->getPlanTest()) && p('status') && e('wait');
r($tutorialTest->getPlanTest()) && p('product') && e('1');
r($tutorialTest->getPlanTest()) && p('createdBy') && e('admin');