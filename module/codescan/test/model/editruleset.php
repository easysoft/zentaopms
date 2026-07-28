#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->editRuleset();
timeout=0
cid=0

- 测试ID为1和带属性对象 >> 0
- 测试空规则集对象返回0 >> 2,empty,0,none
- 测试默认参数 >> 0
- 测试带名称规则集对象返回0 >> 3,edit2,0,none
- 测试不同参数组合 >> 0

*/

$test = new codescanModelTest();

$data1 = new stdclass(); $data1->name = 'edit1';
r($test->editrulesetTest(1, $data1)) && p() && e('0');
r($test->editrulesetTest(2, new stdclass())) && p() && e('0');
r($test->editrulesetTest(0)) && p() && e('0');
$data2 = new stdclass(); $data2->name = 'edit2';
r($test->editrulesetTest(3, $data2)) && p() && e('0');
r($test->editrulesetTest(4, new stdclass())) && p() && e('0');
