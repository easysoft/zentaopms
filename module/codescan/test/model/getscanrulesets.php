#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);
su('admin');

/**

title=测试 codescanModel->getScanRulesets();
timeout=0
cid=0

- 空参数查询规则集失败 @0
- 按 ID 查询 1 号规则集 @edit1,enabled;1,1,30
- 按页码查询规则集列表 @edit1,enabled;test2,enabled
- 按 limit 查询规则集分页信息 @2,1,30
- 按排序查询规则集列表 @edit1,enabled;test2,enabled

*/

$test = new codescanModelTest();

r($test->getscanrulesetsTest(array())) && p() && e('0');
r($test->getScanRulesetsTest(array('id' => 1))) && p('data[0]:name,status;pager:total,page,pageSize') && e('edit1,enabled;1,1,30');
r($test->getScanRulesetsTest(array('page' => 1))) && p('data[0]:name,status;data[1]:name,status') && e('edit1,enabled;test2,enabled');
r($test->getScanRulesetsTest(array('limit' => 10))) && p('pager:total,page,pageSize') && e('2,1,30');
r($test->getScanRulesetsTest(array('sort' => 'id'))) && p('data[0]:name,status;data[1]:name,status') && e('edit1,enabled;test2,enabled');
