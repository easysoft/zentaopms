#!/usr/bin/env php
<?php

/**

title=测试 storyTao::getProductReviewers();
timeout=0
cid=0

- 测试步骤1:获取有reviewer字段的open产品的评审人员列表 >> 返回包含admin和user1用户
- 测试步骤2:获取有reviewer字段的private产品的评审人员列表 >> 返回包含admin和user2用户
- 测试步骤3:获取无reviewer字段但ACL为private的产品评审人员列表 >> 返回有权限查看产品的用户列表
- 测试步骤4:获取无reviewer字段且ACL为open的产品评审人员列表 >> 返回所有未关闭未删除用户
- 测试步骤5:传入storyReviewers参数获取评审人员列表 >> 返回包含story评审人员的列表
- 测试步骤6:获取有reviewer字段但用户已删除的产品评审人员 >> 返回不包含已删除用户的列表
- 测试步骤7:获取ACL为custom的产品评审人员列表 >> 返回指定的评审人员

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$product = zenData('product');
$product->id->range('1-7');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7');
$product->acl->range('open,private,custom,open,private,open,private');
$product->reviewer->range('admin,user1,admin,user2,[]{2},admin,user3,user5');
$product->deleted->range('0');
$product->gen(7);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->password->range('123456{10}');
$user->role->range('admin,dev{9}');
$user->deleted->range('0{8},1,0');
$user->gen(10);

$usergroup = zenData('usergroup');
$usergroup->account->range('admin,user1,user2,user3');
$usergroup->group->range('1,2,2,2');
$usergroup->gen(4);

su('admin');

$storyTest = new storyTaoTest();

r($storyTest->getProductReviewersTest(1, array())) && p('admin,user1') && e('管理员,用户1');
r($storyTest->getProductReviewersTest(2, array())) && p('admin,user2') && e('管理员,用户2');
r($storyTest->getProductReviewersTest(5, array())) && p('admin;user1;user2;user3') && e('管理员;用户1;用户2;用户3');
r($storyTest->getProductReviewersTest(4, array())) && p('admin;user1;user2;user3;user4;user5;user6;user7;user9') && e('管理员;用户1;用户2;用户3;用户4;用户5;用户6;用户7;用户9');
r($storyTest->getProductReviewersTest(1, array('user4' => '用户4'))) && p('admin;user1;user4') && e('管理员;用户1;用户4');
r($storyTest->getProductReviewersTest(7, array())) && p('admin,user3,user5') && e('管理员,用户3,用户5');
r($storyTest->getProductReviewersTest(3, array())) && p('admin,user3') && e('管理员,用户3');
