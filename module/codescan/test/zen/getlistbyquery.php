#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanZen->getListByQuery();
timeout=0
cid=0

- 测试ruleset查询返回数组 >> 1
- 测试solution查询返回数组 >> 1
- 测试plan查询返回数组 >> 1
- 测试task查询返回数组 >> 1
- 测试ruleset带status查询返回数组 >> 1

*/

$test = new codescanZenTest();

r(is_array($test->getListByQueryTest('ruleset', 0, 0, ''))) && p() && e('1');
r(is_array($test->getListByQueryTest('solution', 0, 0, ''))) && p() && e('1');
r(is_array($test->getListByQueryTest('plan', 1, 0, ''))) && p() && e('1');
r(is_array($test->getListByQueryTest('task', 1, 0, ''))) && p() && e('1');
r(is_array($test->getListByQueryTest('ruleset', 0, 0, 'enabled'))) && p() && e('1');
