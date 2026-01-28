#!/usr/bin/env php
<?php
/**

title=productplanModel->changeParentField();
timeout=0
cid=17623

- 测试更新父计划的parent字段属性parent @-1
- 测试更新子计划的parent字段属性parent @0
- 测试更新普通计划的parent字段属性parent @0
- 测试更新普通计划的parent字段属性parent @0
- 测试更新普通计划的parent字段属性parent @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
zenData('productplan')->loadYaml('productplan')->gen(5);

$planIdList = range(1, 5);

$planTester = new productplan('admin');
r($planTester->changeParentFieldTest($planIdList[0])) && p('parent') && e('-1'); // 测试更新父计划的parent字段
r($planTester->changeParentFieldTest($planIdList[1])) && p('parent') && e('0');  // 测试更新子计划的parent字段
r($planTester->changeParentFieldTest($planIdList[2])) && p('parent') && e('0');  // 测试更新普通计划的parent字段
r($planTester->changeParentFieldTest($planIdList[3])) && p('parent') && e('0');  // 测试更新普通计划的parent字段
r($planTester->changeParentFieldTest($planIdList[4])) && p('parent') && e('0');  // 测试更新普通计划的parent字段
