#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getTargetFormLocation();
timeout=0
cid=15049

- 执行aiTest模块的getTargetFormLocationTest方法，参数是1,   @story-change-storyID=1.html#app=product
- 执行aiTest模块的getTargetFormLocationTest方法，参数是999,  属性1 @1
- 执行aiTest模块的getTargetFormLocationTest方法，参数是0,  属性1 @1
- 执行aiTest模块的getTargetFormLocationTest方法，参数是-1,  属性1 @1
- 执行aiTest模块的getTargetFormLocationTest方法，参数是5,  属性1 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 模拟测试数据，避免zenData调试输出

su('admin');

$aiTest = new aiTest();

r($aiTest->getTargetFormLocationTest(1, (object)array('story' => (object)array('id' => 1, 'status' => 'active', 'type' => 'story')))) && p('0') && e('story-change-storyID=1.html#app=product');
r($aiTest->getTargetFormLocationTest(999, (object)array())) && p('1') && e('1');
r($aiTest->getTargetFormLocationTest(0, (object)array())) && p('1') && e('1');
r($aiTest->getTargetFormLocationTest(-1, (object)array())) && p('1') && e('1');
r($aiTest->getTargetFormLocationTest(5, (object)array())) && p('1') && e('1');