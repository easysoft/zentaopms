#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 tutorialModel->getProjectPairs();
timeout=0
cid=19456

- 测试是否能拿到数据属性2 @Test Project
- 测试是否能拿到数据属性3 @~~
- 测试是否能拿到数据属性4 @~~
- 测试是否能拿到数据属性5 @~~
- 测试是否能拿到数据属性6 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

zenData('user')->gen(5);

su('admin');

$tutorial = new tutorialTest();

r($tutorial->getProjectPairsTest()) && p('2') && e('Test Project'); //测试是否能拿到数据
r($tutorial->getProjectPairsTest()) && p('3') && e('~~'); //测试是否能拿到数据
r($tutorial->getProjectPairsTest()) && p('4') && e('~~'); //测试是否能拿到数据
r($tutorial->getProjectPairsTest()) && p('5') && e('~~'); //测试是否能拿到数据
r($tutorial->getProjectPairsTest()) && p('6') && e('~~'); //测试是否能拿到数据