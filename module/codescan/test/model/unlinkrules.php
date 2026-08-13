#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);
su('admin');

/**

title=测试 codescanModel->unlinkRules();
timeout=0
cid=0

- 规则集 1 解绑三条规则成功 @1
- 规则集 2 解绑空规则列表成功 @1
- 规则集 0 解绑规则失败 @0
- 规则集 1 解绑另一组规则成功 @1
- 规则集 2 解绑两条规则成功 @1

*/

$test = new codescanModelTest();

r($test->unlinkRulesTest(1, array(1, 2, 3))) && p() && e('1');
r($test->unlinkRulesTest(2, array())) && p() && e('1');
r($test->unlinkRulesTest(0, array(1))) && p() && e('0');
r($test->unlinkRulesTest(1, array(4, 5, 6))) && p() && e('1');
r($test->unlinkRulesTest(2, array(7, 8))) && p() && e('1');
