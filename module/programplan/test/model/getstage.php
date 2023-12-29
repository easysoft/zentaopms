#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->getStage();
cid=0

- 测试获取阶段2 产品2 all的名称
 -  @新阶段a子1;新阶段a子2;新阶段a子3
 - 属性1 @2;2;2
- 测试获取阶段2 产品2 parent id倒叙的名称
 -  @新阶段a子3;新阶段a子2;新阶段a子1
 - 属性1 @2;2;2
- 测试获取阶段2 产品2 parent的名称
 -  @新阶段a子1;新阶段a子3
 - 属性1 @2;2
- 测试获取阶段3 产品2 all的名称
 -  @新阶段a子1子1
 - 属性1 @2
- 测试获取阶段3 产品2 parent的名称
 -  @新阶段a子1子2
 - 属性1 @2
- 测试获取阶段5 产品2 all的名称
 -  @新阶段b子1;新阶段b子2
 - 属性1 @2;2
- 测试获取阶段5 产品2 parent的名称
 -  @新阶段b子1
 - 属性1 @2
- 测试获取阶段2 产品2 all id倒叙的名称
 -  @新阶段b子2;新阶段b子1
 - 属性1 @2;2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

zdTable('project')->config('project')->gen(10);
zdTable('product')->gen(5);
zdTable('projectproduct')->config('projectproduct')->gen(10);
$executionIDList = array(2, 3, 5);
$productIDList   = array(2, 2, 2, 2);
$typeList        = array('all', 'parent');
$order           = 'id_desc';

$programplan = new programplanTest();
$programplan->objectModel->app->user->admin = true;

r($programplan->getStageTest($executionIDList[0], $productIDList[0], $typeList[0]))         && p('0,1') && e('新阶段a子1;新阶段a子2;新阶段a子3,2;2;2'); // 测试获取阶段2 产品2 all的名称
r($programplan->getStageTest($executionIDList[0], $productIDList[0], $typeList[0], $order)) && p('0,1') && e('新阶段a子3;新阶段a子2;新阶段a子1,2;2;2'); // 测试获取阶段2 产品2 parent id倒叙的名称
r($programplan->getStageTest($executionIDList[0], $productIDList[0], $typeList[1]))         && p('0,1') && e('新阶段a子1;新阶段a子3,2;2');              // 测试获取阶段2 产品2 parent的名称
r($programplan->getStageTest($executionIDList[1], $productIDList[1], $typeList[0]))         && p('0,1') && e('新阶段a子1子1,2');                        // 测试获取阶段3 产品2 all的名称
r($programplan->getStageTest($executionIDList[1], $productIDList[1], $typeList[1]))         && p('0,1') && e('新阶段a子1子2,2');                        // 测试获取阶段3 产品2 parent的名称
r($programplan->getStageTest($executionIDList[2], $productIDList[2], $typeList[0]))         && p('0,1') && e('新阶段b子1;新阶段b子2,2;2');              // 测试获取阶段5 产品2 all的名称
r($programplan->getStageTest($executionIDList[2], $productIDList[2], $typeList[1]))         && p('0,1') && e('新阶段b子1,2');                           // 测试获取阶段5 产品2 parent的名称
r($programplan->getStageTest($executionIDList[2], $productIDList[2], $typeList[0], $order)) && p('0,1') && e('新阶段b子2;新阶段b子1,2;2');              // 测试获取阶段2 产品2 all id倒叙的名称
