#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 tutorialModel->getExecutionStoryPairs();
cid=1

- 测试是否能拿到数据属性1 @Test story

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';

zdTable('user')->gen(5);

su('admin');

$tutorial = new tutorialTest();

r($tutorial->getExecutionStoryPairsTest()) && p('1') && e('Test story'); //测试是否能拿到数据
