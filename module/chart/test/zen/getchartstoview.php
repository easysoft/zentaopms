#!/usr/bin/env php
<?php

/**

title=测试 chartZen::getChartsToView();
timeout=0
cid=0

- 测试步骤1：正常图表列表输入情况第0条的id属性 @1
- 测试步骤2：空图表列表输入情况 @0
- 测试步骤3：不存在的图表ID输入情况 @0
- 测试步骤4：混合存在和不存在图表ID的情况第0条的id属性 @1
- 测试步骤5：包含重复chartID但groupID不同的情况第1条的currentGroup属性 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

zendata('chart')->loadYaml('zt_chart_getchartstoview', false, 2)->gen(20);
su('admin');

$chartTest = new chartTest();
r($chartTest->getChartsToViewTest(array(array('chartID' => 1, 'groupID' => 1))))                           && p('0:id')  && e('1'); //测试步骤1：正常图表列表输入情况
r($chartTest->getChartsToViewTest(array()))                                                                 && p()        && e('0'); //测试步骤2：空图表列表输入情况
r($chartTest->getChartsToViewTest(array(array('chartID' => 999, 'groupID' => 1))))                        && p()        && e('0'); //测试步骤3：不存在的图表ID输入情况
r($chartTest->getChartsToViewTest(array(array('chartID' => 1, 'groupID' => 1), array('chartID' => 999, 'groupID' => 2)))) && p('0:id') && e('1'); //测试步骤4：混合存在和不存在图表ID的情况
r($chartTest->getChartsToViewTest(array(array('chartID' => 1, 'groupID' => 1), array('chartID' => 1, 'groupID' => 2))))   && p('1:currentGroup') && e('2'); //测试步骤5：包含重复chartID但groupID不同的情况