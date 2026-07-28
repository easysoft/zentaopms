#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->deleteRuleset();
timeout=0
cid=0

- 测试ID为1的调用 >> 0
- 测试删除规则集2返回0 >> 2,0,none
- 测试ID为0的调用 >> 0
- 测试删除规则集3返回0 >> 3,0,none
- 测试ID为2的调用 >> 0

*/

$test = new codescanModelTest();

r($test->deleterulesetTest(1)) && p() && e('0');
r($test->deleterulesetTest(2)) && p() && e('0');
r($test->deleterulesetTest(0)) && p() && e('0');
r($test->deleterulesetTest(3)) && p() && e('0');
r($test->deleterulesetTest(4)) && p() && e('0');
