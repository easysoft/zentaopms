#!/usr/bin/env php
<?php

/**

title=测试 docModel::getLastViewed();
timeout=0
cid=16096

- 执行docTest模块的getLastViewedTest方法，参数是'lastViewedSpace'  @0
- 执行docTest模块的getLastViewedTest方法，参数是'lastViewedSpaceHome'  @0
- 执行docTest模块的getLastViewedTest方法，参数是'lastViewedLib'  @0
- 执行docTest模块的getLastViewedTest方法，参数是'invalidType'  @0
- 执行docTest模块的getLastViewedTest方法，参数是''  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

su('admin');

$docTest = new docTest();

r($docTest->getLastViewedTest('lastViewedSpace')) && p() && e('0');
r($docTest->getLastViewedTest('lastViewedSpaceHome')) && p() && e('0');
r($docTest->getLastViewedTest('lastViewedLib')) && p() && e('0');
r($docTest->getLastViewedTest('invalidType')) && p() && e('0');
r($docTest->getLastViewedTest('')) && p() && e('0');