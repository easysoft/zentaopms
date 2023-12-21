#!/usr/bin/env php
<?php
/**

title=测试 stakeholderModel->getListByType();
cid=1

- 获取项目ID=0按照类型分组的干系人列表 @0
- 获取项目ID=11按照类型分组的干系人列表
 - 第0条的name属性 @用户10
 - 第0条的account属性 @user10
 - 第0条的type属性 @inside
 - 第0条的role属性 @qa
- 获取项目ID=11按照类型分组的干系人列表
 - 第0条的name属性 @用户15
 - 第0条的account属性 @user15
 - 第0条的type属性 @outside
 - 第0条的role属性 @qa
- 获取项目ID不存在按照类型分组的干系人列表 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stakeholder.class.php';

zdTable('stakeholder')->config('stakeholder')->gen(20);
zdTable('user')->gen(20);

$objectIds = array(0 , 11, 12);

$stakeholderTester = new stakeholderTest();
r($stakeholderTester->getListByTypeTest($objectIds[0]))            && p()                           && e('0');                        // 获取项目ID=0按照类型分组的干系人列表
r($stakeholderTester->getListByTypeTest($objectIds[1])['inside'])  && p('0:name,account,type,role') && e('用户10,user10,inside,qa');  // 获取项目ID=11按照类型分组的干系人列表
r($stakeholderTester->getListByTypeTest($objectIds[1])['outside']) && p('0:name,account,type,role') && e('用户15,user15,outside,qa'); // 获取项目ID=11按照类型分组的干系人列表
r($stakeholderTester->getListByTypeTest($objectIds[2]))            && p()                           && e('0');                        // 获取项目ID不存在按照类型分组的干系人列表
