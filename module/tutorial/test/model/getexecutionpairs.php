#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 tutorialModel->getExecutionPairs();
cid=1

- 检查获取的数据信息属性3 @Test execution

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';

zdTable('user')->gen(5);

su('admin');

$tutorial = new tutorialTest();

r($tutorial->getExecutionPairsTest()) && p('3') && e('Test execution'); //检查获取的数据信息
