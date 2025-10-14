#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printSingleReleaseBlock();
timeout=0
cid=0

- 执行blockTest模块的printSingleReleaseBlockTest方法，参数是1 
 - 属性sessionSet @1
 - 属性langLoaded @1
- 执行blockTest模块的printSingleReleaseBlockTest方法，参数是2 
 - 属性sessionSet @1
 - 属性langLoaded @1
- 执行blockTest模块的printSingleReleaseBlockTest方法 
 - 属性sessionSet @0
 - 属性langLoaded @0
- 执行blockTest模块的printSingleReleaseBlockTest方法，参数是'invalid' 
 - 属性sessionSet @0
 - 属性langLoaded @0
- 执行blockTest模块的printSingleReleaseBlockTest方法，参数是null 
 - 属性sessionSet @0
 - 属性langLoaded @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

su('admin');

$blockTest = new blockTest();

r($blockTest->printSingleReleaseBlockTest(1)) && p('sessionSet,langLoaded') && e('1,1');
r($blockTest->printSingleReleaseBlockTest(2)) && p('sessionSet,langLoaded') && e('1,1');
r($blockTest->printSingleReleaseBlockTest(0)) && p('sessionSet,langLoaded') && e('0,0');
r($blockTest->printSingleReleaseBlockTest('invalid')) && p('sessionSet,langLoaded') && e('0,0');
r($blockTest->printSingleReleaseBlockTest(null)) && p('sessionSet,langLoaded') && e('0,0');