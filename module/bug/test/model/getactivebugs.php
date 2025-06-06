#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('user')->gen(1);
zenData('bug')->gen(20);
zenData('product')->gen(10);

su('admin');

/**

title=bugModel->getActiveBugs();
timeout=0
cid=1

- 测试获取产品 1 2 3 类型 空 开始日期 上月 结束日期 下月 的bug @2,5,6,9

- 测试获取产品 1 2 3 类型 空 开始日期 上周 结束日期 下周 的bug @0
- 测试获取产品 1 2 3 类型 resolved 开始日期 上月 结束日期 下月 的bug @5,6,9

- 测试获取产品 1 2 3 类型 resolved 开始日期 上周 结束日期 下周 的bug @0
- 测试获取产品 1 2 3 类型 opened 开始日期 上月 结束日期 下月 的bug @2,5,6,9

- 测试获取产品 1 2 3 类型 opened 开始日期 上周 结束日期 下周 的bug @0
- 测试获取产品 4 5 6 类型 空 开始日期 上月 结束日期 下月 的bug @10,13,14,17,18

- 测试获取产品 4 5 6 类型 空 开始日期 上周 结束日期 下周 的bug @0
- 测试获取产品 4 5 6 类型 resolved 开始日期 上月 结束日期 下月 的bug @10,13,14,17,18

- 测试获取产品 4 5 6 类型 resolved 开始日期 上周 结束日期 下周 的bug @0
- 测试获取产品 4 5 6 类型 opened 开始日期 上月 结束日期 下月 的bug @10,13,14,17,18

- 测试获取产品 4 5 6 类型 opened 开始日期 上周 结束日期 下周 的bug @0
- 测试获取产品 7 8 9 类型 空 开始日期 上月 结束日期 下月 的bug @21,22,25,26

- 测试获取产品 7 8 9 类型 空 开始日期 上周 结束日期 下周 的bug @25,26

- 测试获取产品 7 8 9 类型 resolved 开始日期 上月 结束日期 下月 的bug @21,22,25,26

- 测试获取产品 7 8 9 类型 resolved 开始日期 上周 结束日期 下周 的bug @25,26

- 测试获取产品 7 8 9 类型 opened 开始日期 上月 结束日期 下月 的bug @21,22,25,26

- 测试获取产品 7 8 9 类型 opened 开始日期 上周 结束日期 下周 的bug @25,26

- 测试获取产品 空 类型 空 开始日期 上月 结束日期 下月 的bug @0
- 测试获取产品 空 类型 空 开始日期 上周 结束日期 下周 的bug @0
- 测试获取产品 空 类型 resolved 开始日期 上月 结束日期 下月 的bug @0
- 测试获取产品 空 类型 resolved 开始日期 上周 结束日期 下周 的bug @0
- 测试获取产品 空 类型 opened 开始日期 上月 结束日期 下月 的bug @0
- 测试获取产品 空 类型 opened 开始日期 上周 结束日期 下周 的bug @0

*/

$productIDList = array(array(1, 2, 3, 1000001), array(1, 3), 1, 1000001);
$excludeBugs   = array(array(), array(2), array(3, 8));

$bug=new bugTest();

r($bug->getActiveBugsTest($productIDList[0], $excludeBugs[0])) && p() && e('BUG9,bug8,缺陷!()(){}|+=%^&*$#测试bug名称到底可以有多长！#￥%&*":.<>。?/（）;7,BUG6,BUG5,BUG4,BUG3,BUG2,BUG1'); // 查询产品1 2 3 不存在的产品1000001下 且不排除bug的bug
r($bug->getActiveBugsTest($productIDList[0], $excludeBugs[1])) && p() && e('BUG9,bug8,缺陷!()(){}|+=%^&*$#测试bug名称到底可以有多长！#￥%&*":.<>。?/（）;7,BUG6,BUG5,BUG4,BUG3,BUG1');      // 查询产品1 2 3 不存在的产品1000001下 且排除bug2的bug
r($bug->getActiveBugsTest($productIDList[0], $excludeBugs[2])) && p() && e('BUG9,缺陷!()(){}|+=%^&*$#测试bug名称到底可以有多长！#￥%&*":.<>。?/（）;7,BUG6,BUG5,BUG4,BUG2,BUG1');           // 查询产品1 2 3 不存在的产品1000001下 且排除bug3 8的bug
r($bug->getActiveBugsTest($productIDList[1], $excludeBugs[0])) && p() && e('BUG9,bug8,缺陷!()(){}|+=%^&*$#测试bug名称到底可以有多长！#￥%&*":.<>。?/（）;7,BUG3,BUG2,BUG1');                // 查询产品1 3下 且不排除bug的bug
r($bug->getActiveBugsTest($productIDList[1], $excludeBugs[1])) && p() && e('BUG9,bug8,缺陷!()(){}|+=%^&*$#测试bug名称到底可以有多长！#￥%&*":.<>。?/（）;7,BUG3,BUG1');                     // 查询产品1 3下 且排除bug2的bug
r($bug->getActiveBugsTest($productIDList[1], $excludeBugs[2])) && p() && e('BUG9,缺陷!()(){}|+=%^&*$#测试bug名称到底可以有多长！#￥%&*":.<>。?/（）;7,BUG2,BUG1');                          // 查询产品1 3下 且排除bug3 8的bug
r($bug->getActiveBugsTest($productIDList[2], $excludeBugs[0])) && p() && e('BUG3,BUG2,BUG1'); // 查询产品1下 且不排除bug的bug
r($bug->getActiveBugsTest($productIDList[2], $excludeBugs[1])) && p() && e('BUG3,BUG1');      // 查询产品1下 且排除bug2的bug
r($bug->getActiveBugsTest($productIDList[2], $excludeBugs[2])) && p() && e('BUG2,BUG1');      // 查询产品1下 且排除bug3 8的bug
r($bug->getActiveBugsTest($productIDList[3], $excludeBugs[0])) && p() && e('0');              // 查询不存在的产品1000001下 且不排除bug的bug
r($bug->getActiveBugsTest($productIDList[3], $excludeBugs[1])) && p() && e('0');              // 查询不存在的产品1000001下 且排除bug2的bug
r($bug->getActiveBugsTest($productIDList[3], $excludeBugs[2])) && p() && e('0');              // 查询不存在的产品1000001下 且排除bug3 8的bug
