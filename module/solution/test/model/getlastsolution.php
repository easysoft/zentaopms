#!/usr/bin/env php
<?php

/**

title=测试 solutionModel->getLastSolution();
timeout=0
cid=1

- 获取最后一个解决方案
 - 属性id @2
 - 属性name @禅道 DevOps 解决方案2
- 没有数据时获取最后一个解决方案 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/solution.class.php';

zdTable('solution')->config('solution')->gen(2);

$solutionModel = new solutionTest();

r($solutionModel->getLastSolutionTest()) && p('id,name') && e('2,禅道 DevOps 解决方案2');  // 获取最后一个解决方案

zdTable('solution')->config('solution')->gen(0);
r($solutionModel->getLastSolutionTest()) && p() && e('0');  // 没有数据时获取最后一个解决方案