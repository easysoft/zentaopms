#!/usr/bin/env php
<?php
declare(strict_types=1);

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

zdTable('project')->config('project')->gen(10);
zdTable('projectproduct')->config('projectproduct')->gen(10);

/**

title=测试 programplanModel->getStage();
cid=1
pid=1

*/
$IDList        = array(2, 3, 5);
$productIDList = array(2, 2, 2, 2);
$typeList      = array('all', 'parent');
$order         = 'id_desc';

$programplan = new programplanTest();

r($programplan->getStageTest($IDList[0], $productIDList[0], $typeList[0]))         && p() && e(',新阶段a子1,新阶段a子2,新阶段a子3'); // 测试获取阶段2 产品2 all的名称
r($programplan->getStageTest($IDList[0], $productIDList[0], $typeList[1]))         && p() && e(',新阶段a子1,新阶段a子3'); // 测试获取阶段2 产品2 parent的名称
r($programplan->getStageTest($IDList[0], $productIDList[0], $typeList[0], $order)) && p() && e(',新阶段a子3,新阶段a子2,新阶段a子1'); // 测试获取阶段2 产品2 parent id倒叙的名称
r($programplan->getStageTest($IDList[1], $productIDList[1], $typeList[0]))         && p() && e(',新阶段a子1子1'); // 测试获取阶段3 产品2 all的名称
r($programplan->getStageTest($IDList[1], $productIDList[1], $typeList[1]))         && p() && e(',新阶段a子1子2'); // 测试获取阶段3 产品2 parent的名称
r($programplan->getStageTest($IDList[2], $productIDList[2], $typeList[0]))         && p() && e(',新阶段b子1,新阶段b子2'); // 测试获取阶段5 产品2 all的名称
r($programplan->getStageTest($IDList[2], $productIDList[2], $typeList[1]))         && p() && e(',新阶段b子1'); // 测试获取阶段5 产品2 parent的名称
r($programplan->getStageTest($IDList[2], $productIDList[2], $typeList[0], $order)) && p() && e(',新阶段b子2,新阶段b子1'); // 测试获取阶段2 产品2 all id倒叙的名称
