#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');
zenData('system')->gen(10);

/**

title=测试 systemModel::getPairsByProducts();
timeout=0
cid=18738

- 传空数组返回全部应用取属性1 @应用1
- 传空数组返回全部应用的总数量 @5
- 查询产品ids包含一个成员，结果取属性1 @应用1
- 查询产品ids包含2个成员，结果取属性3 @应用3
- 查询传入错误的应用id0的数组取数量 @5

*/
global $tester;
$system = $tester->loadModel('system');
r($system->getPairsByProducts([]))         && p('1') && e('应用1');
r(count($system->getPairsByProducts([])))  && p()    && e('5');
r($system->getPairsByProducts([1]))        && p('1') && e('应用1');
r($system->getPairsByProducts([1, 3]))     && p('3') && e('应用3');
r(count($system->getPairsByProducts([0]))) && p()    && e('5');
