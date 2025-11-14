#!/usr/bin/env php
<?php

/**

title=测试 searchTao::getObjectList();
timeout=0
cid=18326

- 执行searchTest模块的getObjectListTest方法，参数是array  @array
- 执行searchTest模块的getObjectListTest方法，参数是array  @array
- 执行searchTest模块的getObjectListTest方法，参数是array  @array
- 执行searchTest模块的getObjectListTest方法，参数是array  @0
- 执行searchTest模块的getObjectListTest方法，参数是array  @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

$searchTest = new searchTest();

r($searchTest->getObjectListTest(array('project' => array(1, 2)))) && p() && e('array');
r($searchTest->getObjectListTest(array('execution' => array(3, 4, 5)))) && p() && e('array');
r($searchTest->getObjectListTest(array('story' => array(1, 2)))) && p() && e('array');
r($searchTest->getObjectListTest(array())) && p() && e('0');
r($searchTest->getObjectListTest(array('nonexistent' => array(1, 2)))) && p() && e('array');