#!/usr/bin/env php
<?php

/**

title=测试 giteaZen::getMatchedUsers();
timeout=0
cid=16571

- 执行$result1第1条的zentaoAccount属性 @admin
- 执行$result2第2条的zentaoAccount属性 @user1
- 执行$result3第3条的zentaoAccount属性 @user2
- 执行$result4第4条的zentaoAccount属性 @user3
- 执行$result5第5条的zentaoAccount属性 @testuser
- 执行$result6第6条的zentaoAccount属性 @duplicateuser
- 执行$result7 @0
- 执行$result8 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitea.unittest.class.php';

// 手动准备用户数据
$userTable = zenData('user');
$userTable->id->range('1-10');
$userTable->company->range('1{10}');
$userTable->type->range('inside{8},outside{2}');
$userTable->dept->range('1-3');
$userTable->account->range('admin,user1,user2,user3,testuser,gituser,emailuser,nameuser,duplicateuser,outsideuser');
$userTable->password->range('123456{10}');
$userTable->role->range('admin,dev{6},qa{2},pm{1}');
$userTable->realname->range('管理员,用户一,用户二,用户三,测试用户,Git用户,邮箱用户,姓名用户,重复用户,外部用户');
$userTable->email->range('admin@test.com,user1@test.com,user2@test.com,user3@test.com,test@example.com,git@example.com,email@example.com,name@example.com,duplicate@example.com,outside@external.com');
$userTable->deleted->range('0{10}');
$userTable->gen(10);

// 准备oauth数据
$oauthTable = zenData('oauth');
$oauthTable->account->range('admin,user1,user2');
$oauthTable->openID->range('admin,gitea_user1,gitea_user2');
$oauthTable->providerType->range('gitea{3}');
$oauthTable->providerID->range('1{3}');
$oauthTable->gen(3);

su('admin');

$giteaTest = new giteaTest();

// 准备测试数据：Gitea用户数组
$giteaUsers = array();

// 已绑定用户（admin -> admin）
$giteaUser1 = new stdClass();
$giteaUser1->id = 1;
$giteaUser1->account = 'admin';
$giteaUser1->realname = '管理员';
$giteaUser1->email = 'admin@test.com';
$giteaUsers[] = $giteaUser1;

// 账号完全匹配用户（user1 -> user1）
$giteaUser2 = new stdClass();
$giteaUser2->id = 2;
$giteaUser2->account = 'user1';
$giteaUser2->realname = 'Different Name';
$giteaUser2->email = 'different@test.com';
$giteaUsers[] = $giteaUser2;

// 邮箱匹配用户（git_user -> user2 by email）
$giteaUser3 = new stdClass();
$giteaUser3->id = 3;
$giteaUser3->account = 'git_user';
$giteaUser3->realname = 'Git User';
$giteaUser3->email = 'user2@test.com';
$giteaUsers[] = $giteaUser3;

// 姓名匹配用户（name_user -> user3 by realname）
$giteaUser4 = new stdClass();
$giteaUser4->id = 4;
$giteaUser4->account = 'name_user';
$giteaUser4->realname = '用户三';
$giteaUser4->email = 'nameuser@gitea.com';
$giteaUsers[] = $giteaUser4;

// 精确匹配的用户（通过email匹配到testuser）
$giteaUser5 = new stdClass();
$giteaUser5->id = 5;
$giteaUser5->account = 'multi_match';
$giteaUser5->realname = '测试用户';
$giteaUser5->email = 'test@example.com';
$giteaUsers[] = $giteaUser5;

// 多重匹配但非唯一的用户（同时匹配多个禅道用户的姓名）
$giteaUser6 = new stdClass();
$giteaUser6->id = 6;
$giteaUser6->account = 'conflict_user';
$giteaUser6->realname = '重复用户'; // 会同时匹配到duplicateuser的realname和其他相同realname的用户
$giteaUser6->email = 'duplicate@example.com';
$giteaUsers[] = $giteaUser6;

// 无匹配用户
$giteaUser7 = new stdClass();
$giteaUser7->id = 7;
$giteaUser7->account = 'nomatch';
$giteaUser7->realname = 'No Match User';
$giteaUser7->email = 'nomatch@gitea.com';
$giteaUsers[] = $giteaUser7;

// 测试步骤1：正常情况下匹配已绑定用户
$result1 = $giteaTest->getMatchedUsersTest(1, array($giteaUser1));
r($result1) && p('1:zentaoAccount') && e('admin');

// 测试步骤2：账号完全匹配的情况
$result2 = $giteaTest->getMatchedUsersTest(1, array($giteaUser2));
r($result2) && p('2:zentaoAccount') && e('user1');

// 测试步骤3：邮箱匹配的情况
$result3 = $giteaTest->getMatchedUsersTest(1, array($giteaUser3));
r($result3) && p('3:zentaoAccount') && e('user2');

// 测试步骤4：姓名匹配的情况
$result4 = $giteaTest->getMatchedUsersTest(1, array($giteaUser4));
r($result4) && p('4:zentaoAccount') && e('user3');

// 测试步骤5：通过email匹配的情况
$result5 = $giteaTest->getMatchedUsersTest(1, array($giteaUser5));
r($result5) && p('5:zentaoAccount') && e('testuser');

// 测试步骤6：多重匹配但能找到唯一匹配的情况
$result6 = $giteaTest->getMatchedUsersTest(1, array($giteaUser6));
r($result6) && p('6:zentaoAccount') && e('duplicateuser');

// 测试步骤7：无匹配的情况
$result7 = $giteaTest->getMatchedUsersTest(1, array($giteaUser7));
r($result7) && p() && e('0');

// 测试步骤8：边界值测试：空的gitea用户数组
$result8 = $giteaTest->getMatchedUsersTest(1, array());
r($result8) && p() && e('0');