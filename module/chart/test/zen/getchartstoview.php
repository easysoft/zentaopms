#!/usr/bin/env php
<?php

/**

title=测试 chartZen::getChartsToView();
timeout=0
cid=15584

- 执行chartTest模块的getChartsToViewTest方法，参数是array  @0
- 执行chartTest模块的getChartsToViewTest方法，参数是array
 - 第0条的id属性 @1
 - 第0条的currentGroup属性 @5
- 执行chartTest模块的getChartsToViewTest方法，参数是array  @3
- 执行chartTest模块的getChartsToViewTest方法，参数是array  @0
- 执行chartTest模块的getChartsToViewTest方法，参数是array  @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zendata('chart')->loadYaml('getchartstoview', false, 2)->gen(10);

su('admin');

$chartTest = new chartZenTest();

r(count($chartTest->getChartsToViewTest(array()))) && p() && e('0');
r($chartTest->getChartsToViewTest(array(array('chartID' => 1, 'groupID' => 5)))) && p('0:id,currentGroup') && e('1,5');
r(count($chartTest->getChartsToViewTest(array(array('chartID' => 1, 'groupID' => 5), array('chartID' => 2, 'groupID' => 6), array('chartID' => 3, 'groupID' => 7))))) && p() && e('3');
r(count($chartTest->getChartsToViewTest(array(array('chartID' => 999, 'groupID' => 5))))) && p() && e('0');
r(count($chartTest->getChartsToViewTest(array(array('chartID' => 1, 'groupID' => 5), array('chartID' => 999, 'groupID' => 6), array('chartID' => 2, 'groupID' => 7))))) && p() && e('2');