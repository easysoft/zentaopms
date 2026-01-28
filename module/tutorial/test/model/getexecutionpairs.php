#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 tutorialModel->getExecutionPairs();
timeout=0
cid=19431

- 检查获取的数据信息属性3 @Test execution
- 检查获取的数据信息属性4 @~~
- 检查获取的数据信息属性5 @~~
- 检查获取的数据信息属性6 @~~
- 检查获取的数据信息属性7 @~~

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);

su('admin');

$tutorial = new tutorialModelTest();

r($tutorial->getExecutionPairsTest()) && p('3') && e('Test execution'); //检查获取的数据信息
r($tutorial->getExecutionPairsTest()) && p('4') && e('~~'); //检查获取的数据信息
r($tutorial->getExecutionPairsTest()) && p('5') && e('~~'); //检查获取的数据信息
r($tutorial->getExecutionPairsTest()) && p('6') && e('~~'); //检查获取的数据信息
r($tutorial->getExecutionPairsTest()) && p('7') && e('~~'); //检查获取的数据信息