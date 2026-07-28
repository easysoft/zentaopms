#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->getScanRulesets();
timeout=0
cid=0

- 测试空数组参数 >> 0
- 测试按id过滤规则集返回0 >> id,1,0,none
- 测试带ID的数组 >> 0
- 测试按limit过滤规则集返回0 >> limit,1,0,none
- 测试带limit的数组 >> 0

*/

$test = new codescanModelTest();

r($test->getscanrulesetsTest(array())) && p() && e('0');
r($test->getscanrulesetsTest(array('id' => 1))) && p() && e('0');
r($test->getscanrulesetsTest(array('page' => 1))) && p() && e('0');
r($test->getscanrulesetsTest(array('limit' => 10))) && p() && e('0');
r($test->getscanrulesetsTest(array('sort' => 'id'))) && p() && e('0');
