#!/usr/bin/env php
<?php

/**

title=bugModel->getProductBugPairs();
cid=15388

- 测试获取productID为1的bug @3:BUG3,2:BUG2,1:BUG1

- 测试获取productID为2的bug @6:BUG6,5:BUG5,4:BUG4

- 测试获取productID为3的bug @9:BUG9,8:bug8,7:缺陷!()(){}|+=^&*#测试bug名称到底可以有多长！#￥&*":.<>。?/（）;7

- 测试获取productID为4的bug @12:BUG12,11:BUG11,10:BUG10

- 测试获取productID为5的bug @15:缺陷!()(){}|+=^&*#测试bug名称到底可以有多长！#￥&*":.<>。?/（）;15,14:BUG14,13:BUG13

- 测试获取productID为6的bug @18:BUG18,17:BUG17,16:bug16

- 测试获取不存在的product的bug @0
- 测试获取productID为45,主干分支的bug @133:BUG133
- 测试获取productID为45,分支为9的bug @134:BUG134
- 测试获取productID为45,标题匹配 BUG1 的bug @134:BUG134,133:BUG133

- 测试获取所有标题匹配 BUG1 的3条bug，productID为45的bug排序前面。 @134:BUG134,133:BUG133,产品35;164;1:BUG1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('bug')->gen(140);
zenData('user')->gen(1);

su('admin');

$productIDList = array('1', '2', '3', '4','5', '6', '1000001', '45');
$branchIDList  = array('9');

$bug=new bugTest();
$bug->objectModel->app->user->admin = true;
r($bug->getProductBugPairsTest($productIDList[0]))                   && p() && e('3:BUG3,2:BUG2,1:BUG1');       // 测试获取productID为1的bug
r($bug->getProductBugPairsTest($productIDList[1]))                   && p() && e('6:BUG6,5:BUG5,4:BUG4');       // 测试获取productID为2的bug
r($bug->getProductBugPairsTest($productIDList[2]))                   && p() && e('9:BUG9,8:bug8,7:缺陷!()(){}|+=^&*#测试bug名称到底可以有多长！#￥&*":.<>。?/（）;7'); // 测试获取productID为3的bug
r($bug->getProductBugPairsTest($productIDList[3]))                   && p() && e('12:BUG12,11:BUG11,10:BUG10'); // 测试获取productID为4的bug
r($bug->getProductBugPairsTest($productIDList[4]))                   && p() && e('15:缺陷!()(){}|+=^&*#测试bug名称到底可以有多长！#￥&*":.<>。?/（）;15,14:BUG14,13:BUG13'); // 测试获取productID为5的bug
r($bug->getProductBugPairsTest($productIDList[5]))                   && p() && e('18:BUG18,17:BUG17,16:bug16'); // 测试获取productID为6的bug
r($bug->getProductBugPairsTest($productIDList[6]))                   && p() && e('0');                          // 测试获取不存在的product的bug
r($bug->getProductBugPairsTest($productIDList[7], 0))                && p() && e('133:BUG133');                 // 测试获取productID为45,主干分支的bug
r($bug->getProductBugPairsTest($productIDList[7], $branchIDList[0])) && p() && e('134:BUG134');                 // 测试获取productID为45,分支为9的bug

r(implode(',', $bug->objectModel->getProductBugPairs($productIDList[7], '', 'BUG1'))) && p() && e('134:BUG134,133:BUG133'); // 测试获取productID为45,标题匹配 BUG1 的bug
r(str_replace(array('@', '#'), array('64;', '35;'), implode(',', $bug->objectModel->getProductBugPairs($productIDList[7], '', 'BUG1', 3, 'all')))) && p() && e('134:BUG134,133:BUG133,产品35;164;1:BUG1'); // 测试获取所有标题匹配 BUG1 的3条bug，productID为45的bug排序前面。
