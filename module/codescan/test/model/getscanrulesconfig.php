#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->getScanRulesConfig();
timeout=0
cid=0

- 测试langs类型 >> 0
- 测试tags配置返回空数组 >> tags,array,0
- 测试tags类型 >> 0
- 测试默认空值 >> 0
- 测试types配置返回空数组 >> types,array,0

*/

$test = new codescanModelTest();

r($test->getscanrulesconfigTest('langs')) && p() && e('0');
r($test->getscanrulesconfigTest('tags')) && p() && e('0');
r($test->getscanrulesconfigTest('plugins')) && p() && e('0');
r($test->getscanrulesconfigTest('types')) && p() && e('0');
r($test->getscanrulesconfigTest('')) && p() && e('0');
