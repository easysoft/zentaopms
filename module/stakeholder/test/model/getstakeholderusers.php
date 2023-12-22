#!/usr/bin/env php
<?php
/**

title=测试 stakeholderModel->getStakeholderUsers();
cid=1

- 获取项目ID=0的干系人id=>realname的键值对 @0
- 获取项目ID=11的干系人id=>realname的键值对属性1 @易软天创网络科技有限公司/admin
- 获取项目ID不存在的干系人id=>realname的键值对 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stakeholder.class.php';

zdTable('user')->gen(20);
zdTable('stakeholder')->gen(20);

$objectIds = array(0 , 11, 100);

$stakeholderTester = new stakeholderTest();
r($stakeholderTester->getStakeholderUsersTest($objectIds[0])) && p()    && e('0');                              // 获取项目ID=0的干系人id=>realname的键值对
r($stakeholderTester->getStakeholderUsersTest($objectIds[1])) && p('1') && e('易软天创网络科技有限公司/admin'); // 获取项目ID=11的干系人id=>realname的键值对
r($stakeholderTester->getStakeholderUsersTest($objectIds[2])) && p()    && e('0');                              // 获取项目ID不存在的干系人id=>realname的键值对
