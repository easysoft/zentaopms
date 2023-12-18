#!/usr/bin/env php
<?php

/**

title=测试 groupModel->getByAccount();
timeout=0
cid=1

- 测试获取user2所在的所有分组
 - 第2条的id属性 @2
 - 第2条的name属性 @这是一个新的用户分组2
 - 第2条的role属性 @0
- 测试获取other所在的所有分组 @0
- 测试获取user4当前vision的分组 @0
- 测试获取user4所有vision的分组
 - 第4条的id属性 @4
 - 第4条的name属性 @这是一个新的用户分组4
 - 第4条的role属性 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

zdTable('user')->gen(100);
zdTable('group')->config('group')->gen(10);
zdTable('usergroup')->config('usergroup')->gen(10);

$group = new groupTest();
r($group->getByAccountTest('user2'))       && p('2:id,name,role') && e('2,这是一个新的用户分组2,0');  //测试获取user2所在的所有分组
r($group->getByAccountTest('other'))       && p()                 && e('0');                          //测试获取other所在的所有分组
r($group->getByAccountTest('user4'))       && p()                 && e('0');                          //测试获取user4当前vision的分组
r($group->getByAccountTest('user4', true)) && p('4:id,name,role') && e('4,这是一个新的用户分组4,0');  //测试获取user4所有vision的分组