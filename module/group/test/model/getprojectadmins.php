#!/usr/bin/env php
<?php

/**

title=测试 groupModel->getProjectAdmins();
timeout=0
cid=1

- 获取admin的管理信息
 - 第admin,user9条的programs属性 @all
 - 第admin,user9条的projects属性 @all
 - 第admin,user9条的products属性 @all
 - 第admin,user9条的executions属性 @all
- 获取user1的管理信息
 - 第user1条的programs属性 @1
 - 第user1条的projects属性 @6
 - 第user1条的products属性 @1
 - 第user1条的executions属性 @16

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

zdTable('projectadmin')->gen(10);

$group = new groupTest();

r($group->getProjectAdminsTest()) && p('admin,user9:programs,projects,products,executions')  && e('all,all,all,all');   // 获取admin的管理信息
r($group->getProjectAdminsTest()) && p('user1:programs,projects,products,executions')        && e('1,6,1,16');          // 获取user1的管理信息