#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 tutorialModel->getExecutionStoryPairs();
cid=19435

- 测试是否能拿到数据属性3 @Test active story

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

zenData('user')->gen(5);

su('admin');

$tutorial = new tutorialTest();

r($tutorial->getExecutionStoryPairsTest()) && p('3') && e('Test active story'); //测试是否能拿到数据
r($tutorial->getExecutionStoryPairsTest()) && p('1') && e('~~'); //测试是否能拿到数据
r($tutorial->getExecutionStoryPairsTest()) && p('2') && e('~~'); //测试是否能拿到数据
r($tutorial->getExecutionStoryPairsTest()) && p('4') && e('~~'); //测试是否能拿到数据
r($tutorial->getExecutionStoryPairsTest()) && p('5') && e('~~'); //测试是否能拿到数据
