#!/usr/bin/env php
<?php

/**

title=测试 groupModel::getGroupAccounts();
timeout=0
cid=16704

- 执行groupTest模块的getGroupAccountsTest方法，参数是array 属性user1 @user1
- 执行groupTest模块的getGroupAccountsTest方法，参数是array 属性user1 @user1
- 执行groupTest模块的getGroupAccountsTest方法，参数是array  @0
- 执行groupTest模块的getGroupAccountsTest方法，参数是array  @0
- 执行groupTest模块的getGroupAccountsTest方法，参数是array 属性user1 @user1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('usergroup');
$table->account->range('user1,user2,user3,user4,user5,user6,user7,user8,user9,user10');
$table->group->range('1{3},2{3},3{2},4{1},5{1}');
$table->project->range('');
$table->gen(10);

su('admin');

$groupTest = new groupModelTest();

r($groupTest->getGroupAccountsTest(array(1, 2))) && p('user1') && e('user1');
r($groupTest->getGroupAccountsTest(array(1))) && p('user1') && e('user1');
r($groupTest->getGroupAccountsTest(array())) && p() && e('0');
r($groupTest->getGroupAccountsTest(array(999))) && p() && e('0');
r($groupTest->getGroupAccountsTest(array(1, 999))) && p('user1') && e('user1');