#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkPriv();
timeout=0
cid=0

- 执行searchTest模块的checkPrivTest方法，参数是array  @0
- 执行searchTest模块的checkPrivTest方法，参数是array  @2
- 执行searchTest模块的checkPrivTest方法，参数是array  @1
- 执行searchTest模块的checkPrivTest方法，参数是array  @2
- 执行searchTest模块的checkPrivTest方法，参数是array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->shadow->range('0{4},1');
$product->gen(5);

su('admin');

$searchTest = new searchTest();

r($searchTest->checkPrivTest(array(), array(), true)) && p() && e('0');
r($searchTest->checkPrivTest(array((object)array('id' => 1, 'title' => 'test1'), (object)array('id' => 2, 'title' => 'test2')), array(), true)) && p() && e('2');
r($searchTest->checkPrivTest(array((object)array('id' => 1, 'title' => 'test1', 'objectType' => '', 'objectID' => '')), array(), false)) && p() && e('1');
r($searchTest->checkPrivTest(array((object)array('id' => 1, 'objectType' => 'story', 'objectID' => 1), (object)array('id' => 2, 'objectType' => 'story', 'objectID' => 2)), array('story' => array(1 => 1, 2 => 2)), false, '1,2', '1,2')) && p() && e('2');
r($searchTest->checkPrivTest(array((object)array('id' => 1, 'objectType' => 'story', 'objectID' => 1)), array('story' => array(1 => 1)), false, '')) && p() && e('1');