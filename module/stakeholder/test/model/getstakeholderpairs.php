#!/usr/bin/env php
<?php
/**

title=测试 stakeholderModel->getStakeHolderPairs();
cid=1

- 获取objectID=0的干系人键值对 @0
- 获取objectID=1的干系人键值对属性user1 @用户1
- 获取objectID=11的干系人键值对属性user11 @用户11
- 获取objectID不存在的干系人键值对 @0
- 获取objectID=0的干系人键值对数量 @0
- 获取objectID=1的干系人键值对数量 @10
- 获取objectID=11的干系人键值对数量 @10
- 获取objectID不存在的干系人键值对数量 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stakeholder.class.php';

zdTable('user')->gen(20);
zdTable('stakeholder')->config('stakeholder')->gen(20);

$objectIds = array(0, 1, 11, 100);

$stakeholderTester = new stakeholderTest();
r($stakeholderTester->getStakeHolderPairsTest($objectIds[0])) && p()         && e('0');      // 获取objectID=0的干系人键值对
r($stakeholderTester->getStakeHolderPairsTest($objectIds[1])) && p('user1')  && e('用户1');  // 获取objectID=1的干系人键值对
r($stakeholderTester->getStakeHolderPairsTest($objectIds[2])) && p('user11') && e('用户11'); // 获取objectID=11的干系人键值对
r($stakeholderTester->getStakeHolderPairsTest($objectIds[3])) && p()         && e('0');      // 获取objectID不存在的干系人键值对

r(count($stakeholderTester->getStakeHolderPairsTest($objectIds[0]))) && p() && e('0');  // 获取objectID=0的干系人键值对数量
r(count($stakeholderTester->getStakeHolderPairsTest($objectIds[1]))) && p() && e('10'); // 获取objectID=1的干系人键值对数量
r(count($stakeholderTester->getStakeHolderPairsTest($objectIds[2]))) && p() && e('10'); // 获取objectID=11的干系人键值对数量
r(count($stakeholderTester->getStakeHolderPairsTest($objectIds[3]))) && p() && e('0');  // 获取objectID不存在的干系人键值对数量
