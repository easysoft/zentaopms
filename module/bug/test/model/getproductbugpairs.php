#!/usr/bin/env php
<?php

/**

title=bugModel->getProductBugPairs();
cid=15388

- 测试获取productID为1的bug @3:BUG3,2:BUG2,1:BUG1
- 测试获取productID为2的bug @5:BUG5,4:BUG4
- 测试获取不存在的product的bug @0
- 测试获取productID为45,主干分支的bug @133:BUG133
- 测试获取productID为45,分支为9的bug @134:BUG134
- 测试获取productID为45,标题匹配 BUG1 的bug @134:BUG134,133:BUG133
- 测试获取所有标题匹配 BUG1 的3条bug，productID为45的bug排序前面。 @134:BUG134,133:BUG133,1:BUG1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('bug')->gen(0);
$bugData = zenData('bug');
$bugData->id->range('1-5,133,134');
$bugData->product->range('1{3},2{2},45{2}');
$bugData->branch->range('0{6},9');
$bugData->title->range('BUG1,BUG2,BUG3,BUG4,BUG5,BUG133,BUG134');
$bugData->gen(7);
zenData('user')->gen(1);

su('admin');

$productIDList = array('1', '2', '1000001', '45');
$branchIDList  = array('9');

$bugTest = new bugModelTest();
$bugTest->instance->app->user->admin = true;
r($bugTest->getProductBugPairsTest($productIDList[0]))                   && p() && e('3:BUG3,2:BUG2,1:BUG1');       // 测试获取productID为1的bug
r($bugTest->getProductBugPairsTest($productIDList[1]))                   && p() && e('5:BUG5,4:BUG4');              // 测试获取productID为2的bug
r($bugTest->getProductBugPairsTest($productIDList[2]))                   && p() && e('0');                          // 测试获取不存在的product的bug
r($bugTest->getProductBugPairsTest($productIDList[3], 0))                && p() && e('133:BUG133');                 // 测试获取productID为45,主干分支的bug
r($bugTest->getProductBugPairsTest($productIDList[3], $branchIDList[0])) && p() && e('134:BUG134');                 // 测试获取productID为45,分支为9的bug

r(implode(',', $bugTest->instance->getProductBugPairs($productIDList[3], '', 'BUG1'))) && p() && e('134:BUG134,133:BUG133'); // 测试获取productID为45,标题匹配 BUG1 的bug
r(implode(',', $bugTest->instance->getProductBugPairs($productIDList[3], '', 'BUG1', 3, 'all'))) && p() && e('134:BUG134,133:BUG133,1:BUG1'); // 测试获取所有标题匹配 BUG1 的3条bug，productID为45的bug排序前面。
