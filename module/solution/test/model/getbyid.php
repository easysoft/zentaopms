#!/usr/bin/env php
<?php

/**

title=测试 solutionModel->getByID();
timeout=0
cid=1

- 解决方案ID为空 @0
- 解决方案ID存在
 - 属性name @禅道 DevOps 解决方案1
 - 属性status @notEnoughResource
- 解决方案ID存在，检查实例数量 @3
- 解决方案ID存在
 - 属性name @禅道 DevOps 解决方案2
 - 属性status @notEnoughResource
- 解决方案ID存在，检查实例数量 @1
- 解决方案ID不存在 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/solution.class.php';

zdTable('space')->config('space')->gen(2);
zdTable('solution')->config('solution')->gen(2);
zdTable('instance')->config('instance')->gen(4);

$solutionModel = new solutionTest();

r($solutionModel->getByIdTest(0)) && p() && e('0');  // 解决方案ID为空

$solution = $solutionModel->getByIdTest(1);
r($solution)                   && p('name,status') && e('禅道 DevOps 解决方案1,notEnoughResource');  // 解决方案ID存在
r(count($solution->instances)) && p()              && e('3');                                        // 解决方案ID存在，检查实例数量

$solution = $solutionModel->getByIdTest(2);
r($solution)                   && p('name,status') && e('禅道 DevOps 解决方案2,notEnoughResource');  // 解决方案ID存在
r(count($solution->instances)) && p()              && e('1');                                        // 解决方案ID存在，检查实例数量

r($solutionModel->getByIdTest(5)) && p() && e('0');  // 解决方案ID不存在