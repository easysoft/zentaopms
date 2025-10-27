#!/usr/bin/env php
<?php

/**

title=测试 executionZen::checkLinkPlan();
timeout=0
cid=0

- 执行executionTest模块的checkLinkPlanTest方法，参数是1, array 属性result @success
- 执行executionTest模块的checkLinkPlanTest方法，参数是2, array 属性result @success
- 执行executionTest模块的checkLinkPlanTest方法，参数是3, array 属性result @success
- 执行executionTest模块的checkLinkPlanTest方法，参数是4, array 属性result @success
- 执行executionTest模块的checkLinkPlanTest方法，参数是5, array 属性result @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

zendata('productplan')->loadYaml('productplan_checklinkplan', false, 2)->gen(10);
zendata('projectproduct')->loadYaml('projectproduct_checklinkplan', false, 2)->gen(5);

su('admin');

$executionTest = new executionZenTest();

r($executionTest->checkLinkPlanTest(1, array(1,2), array('1' => array(3,4)))) && p('result') && e('success');
r($executionTest->checkLinkPlanTest(2, array(1,2), array())) && p('result') && e('success');
r($executionTest->checkLinkPlanTest(3, array(), array())) && p('result') && e('success');
r($executionTest->checkLinkPlanTest(4, array(5,6), array('1' => array(5,6)))) && p('result') && e('success');
r($executionTest->checkLinkPlanTest(5, array(1,2), array('1' => array(1,2,3)))) && p('result') && e('success');