#!/usr/bin/env php
<?php

/**

title=测试 docModel::setLastViewed();
timeout=0
cid=16152

- 执行docTest模块的setLastViewedTest方法，参数是array  @1
- 执行docTest模块的setLastViewedTest方法，参数是array  @1
- 执行docTest模块的setLastViewedTest方法，参数是array  @1
- 执行docTest模块的setLastViewedTest方法，参数是array  @1
- 执行docTest模块的setLastViewedTest方法，参数是array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$docTest = new docModelTest();

r($docTest->setLastViewedTest(array('lastViewedSpace' => '1', 'lastViewedSpaceHome' => '2', 'lastViewedLib' => '3'))) && p() && e('1');
r($docTest->setLastViewedTest(array('lastViewedSpace' => '4', 'otherKey' => 'ignore'))) && p() && e('1');
r($docTest->setLastViewedTest(array())) && p() && e('1');
r($docTest->setLastViewedTest(array('invalidKey' => 'value', 'lastViewedLib' => '5'))) && p() && e('1');
r($docTest->setLastViewedTest(array('lastViewedSpace' => 'special<>&"value', 'lastViewedSpaceHome' => '6'))) && p() && e('1');