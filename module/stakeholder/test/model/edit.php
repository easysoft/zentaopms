#!/usr/bin/env php
<?php
/**

title=测试 stakeholderModel->edit();
cid=1

- 测试编辑ID=0的干系人名称 @0
- 测试编辑ID=1的干系人性格特征 @0
- 测试编辑ID=1的干系人名称属性name @『姓名』不能为空。
- 测试编辑ID=9的干系人名称 @0
- 测试编辑ID=9的干系人性格特征 @0
- 测试编辑ID=9的干系人邮箱 @0
- 测试编辑ID=9的干系人key
 - 第0条的field属性 @key
 - 第0条的old属性 @0
 - 第0条的new属性 @1
- 测试编辑ID不存在的干系人名称 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stakeholder.class.php';

zdTable('stakeholder')->config('stakeholder')->gen(10);
zdTable('user')->config('user')->gen(5);

$ids    = array(0, 1, 3, 9, 11);
$name   = array('', '修改用户名称');
$nature = '修改用户性格特征';
$email  = '10001000@qq.com';

$emptyName    = array('name' => $name[0]);
$changeName   = array('name' => $name[1]);
$changeNature = array('name' => $name[1], 'nature' => $nature);
$changeEmail  = array('name' => $name[1], 'email' => $email);
$changeKey    = array('name' => $name[1], 'key' => 1);

$stakeholderTester = new stakeholderTest();
r($stakeholderTester->editTest($ids[0], $emptyName))    && p()                  && e('0');                  // 测试编辑ID=0的干系人名称
r($stakeholderTester->editTest($ids[1], $changeNature)) && p()                  && e('0');                  // 测试编辑ID=1的干系人性格特征
r($stakeholderTester->editTest($ids[3], $emptyName))    && p('name')            && e('『姓名』不能为空。'); // 测试编辑ID=1的干系人名称
r($stakeholderTester->editTest($ids[3], $changeName))   && p()                  && e('0');                  // 测试编辑ID=9的干系人名称
r($stakeholderTester->editTest($ids[3], $changeNature)) && p()                  && e('0');                  // 测试编辑ID=9的干系人性格特征
r($stakeholderTester->editTest($ids[3], $changeEmail))  && p()                  && e('0');                  // 测试编辑ID=9的干系人邮箱
r($stakeholderTester->editTest($ids[3], $changeKey))    && p('0:field,old,new') && e('key,0,1');            // 测试编辑ID=9的干系人key
r($stakeholderTester->editTest($ids[4], $changeName))   && p()                  && e('0');                  // 测试编辑ID不存在的干系人名称
