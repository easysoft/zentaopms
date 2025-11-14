#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getLibTree();
timeout=0
cid=19443

- 执行tutorialTest模块的getLibTreeTest方法  @1
- 执行tutorialTest模块的getLibTreeTest方法 
 - 第0条的id属性 @2
 - 第0条的name属性 @Test Doc Lib
- 执行tutorialTest模块的getLibTreeTest方法 
 - 第0条的type属性 @docLib
 - 第0条的parent属性 @1
- 执行tutorialTest模块的getLibTreeTest方法 
 - 第0条的objectType属性 @custom
 - 第0条的active属性 @1
- 执行tutorialTest模块的getLibTreeTest方法 
 - 第0条的order属性 @0
 - 第0条的main属性 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

su('admin');

$tutorialTest = new tutorialTest();

r(count($tutorialTest->getLibTreeTest())) && p() && e('1');
r($tutorialTest->getLibTreeTest()) && p('0:id,name') && e('2,Test Doc Lib');
r($tutorialTest->getLibTreeTest()) && p('0:type,parent') && e('docLib,1');
r($tutorialTest->getLibTreeTest()) && p('0:objectType,active') && e('custom,1');
r($tutorialTest->getLibTreeTest()) && p('0:order,main') && e('0,0');