#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

zdTable('user')->gen(100);
zdTable('product')->gen(10);
zdTable('project')->gen(50);

$projectProduct = zdTable('projectproduct');
$projectProduct->product->range('1-10');
$projectProduct->gen(50);

zdTable('team')->gen(100);

su('admin');

/**

title=bugModel->getProductMemberPairs();
timeout=0
cid=1

- 测试获取productID为1的bug的团队成员 @A:admin,U:用户12,U:用户22,U:用户32


- 测试获取productID为2的bug的团队成员 @U:用户3,U:用户13,U:用户23,U:用户33


- 测试获取productID为3的bug的团队成员 @U:用户4,U:用户14,U:用户24,U:用户34


- 测试获取productID为4的bug的团队成员 @U:用户5,U:用户15,U:用户25,U:用户35


- 测试获取productID为5的bug的团队成员 @U:用户6,U:用户16,U:用户26,U:用户36


- 测试获取productID为6的bug的团队成员 @U:用户7,U:用户17,U:用户27,U:用户37


- 测试获取不存在的product的bug的团队成员 @0

*/

$productIDList = array(1, 2, 3, 4, 5, 6, 1000001);

$bug=new bugTest();
r($bug->getProductMemberPairsTest($productIDList[0])) && p() && e('A:admin,U:用户12,U:用户22,U:用户32'); // 测试获取productID为1的bug的团队成员
r($bug->getProductMemberPairsTest($productIDList[1])) && p() && e('U:用户3,U:用户13,U:用户23,U:用户33'); // 测试获取productID为2的bug的团队成员
r($bug->getProductMemberPairsTest($productIDList[2])) && p() && e('U:用户4,U:用户14,U:用户24,U:用户34'); // 测试获取productID为3的bug的团队成员
r($bug->getProductMemberPairsTest($productIDList[3])) && p() && e('U:用户5,U:用户15,U:用户25,U:用户35'); // 测试获取productID为4的bug的团队成员
r($bug->getProductMemberPairsTest($productIDList[4])) && p() && e('U:用户6,U:用户16,U:用户26,U:用户36'); // 测试获取productID为5的bug的团队成员
r($bug->getProductMemberPairsTest($productIDList[5])) && p() && e('U:用户7,U:用户17,U:用户27,U:用户37'); // 测试获取productID为6的bug的团队成员
r($bug->getProductMemberPairsTest($productIDList[6])) && p() && e('0');                                  // 测试获取不存在的product的bug的团队成员
