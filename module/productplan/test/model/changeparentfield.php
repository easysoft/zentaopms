#!/usr/bin/env php
<?php
/**

title=productplanModel->changeParentField();
timeout=0
cid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/productplan.class.php';

zdTable('user')->gen(5);
zdTable('productplan')->config('productplan')->gen(5);

$planIdList = array(1, 2, 3);

$planTester = new productplan('admin');
r($planTester->changeParentFieldTest($planIdList[0])) && p('parent') && e('-1'); // 测试更新父计划的parent字段
r($planTester->changeParentFieldTest($planIdList[1])) && p('parent') && e('0');  // 测试更新子计划的parent字段
r($planTester->changeParentFieldTest($planIdList[2])) && p('parent') && e('0');  // 测试更新普通计划的parent字段
