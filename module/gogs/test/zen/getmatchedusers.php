#!/usr/bin/env php
<?php

/**

title=测试 gogsZen::getMatchedUsers();
timeout=0
cid=16694

- 步骤1：已绑定用户但数据不匹配返回0 @0
- 步骤2：账号精确匹配成功第2条的zentaoAccount属性 @user1
- 步骤3：邮箱匹配但数据不匹配返回0 @0
- 步骤4：姓名匹配但数据不匹配返回0 @0
- 步骤5：多重匹配但结果唯一第5条的zentaoAccount属性 @user2
- 步骤6：多重匹配唯一结果第6条的zentaoAccount属性 @admin
- 步骤7：无匹配返回0 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
$userTable = zenData('user');
$userTable->loadYaml('user_getmatchedusers', false, 2);
$userTable->gen(10);

$oauthTable = zenData('oauth');
$oauthTable->loadYaml('oauth_getmatchedusers', false, 2);
$oauthTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$gogsTest = new gogsZenTest();

// 构造Gogs用户数据
$bindedGogsUser = new stdclass();
$bindedGogsUser->id = '1';
$bindedGogsUser->account = 'bindeduser1';
$bindedGogsUser->realname = 'Gogs绑定用户1';
$bindedGogsUser->email = 'bindeduser1@gogs.com';

$exactAccountMatchUser = new stdclass();
$exactAccountMatchUser->id = '2';
$exactAccountMatchUser->account = 'user1';
$exactAccountMatchUser->realname = 'Gogs用户1';
$exactAccountMatchUser->email = 'gogsuser1@gogs.com';

$exactEmailMatchUser = new stdclass();
$exactEmailMatchUser->id = '3';
$exactEmailMatchUser->account = 'gogsuser2';
$exactEmailMatchUser->realname = 'Gogs用户2';
$exactEmailMatchUser->email = 'zhangsan@test.com';

$exactNameMatchUser = new stdclass();
$exactNameMatchUser->id = '4';
$exactNameMatchUser->account = 'gogsuser3';
$exactNameMatchUser->realname = '张三';
$exactNameMatchUser->email = 'gogsuser3@gogs.com';

$multiMatchUniqueUser = new stdclass();
$multiMatchUniqueUser->id = '5';
$multiMatchUniqueUser->account = 'user2';
$multiMatchUniqueUser->realname = '李四';
$multiMatchUniqueUser->email = 'lisi@test.com';

$multiMatchNotUniqueUser = new stdclass();
$multiMatchNotUniqueUser->id = '6';
$multiMatchNotUniqueUser->account = 'admin';
$multiMatchNotUniqueUser->realname = '管理员';
$multiMatchNotUniqueUser->email = 'admin@test.com';

$noMatchUser = new stdclass();
$noMatchUser->id = '7';
$noMatchUser->account = 'nomatchuser';
$noMatchUser->realname = '无匹配用户';
$noMatchUser->email = 'nomatch@gogs.com';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($gogsTest->getMatchedUsersTest(1, array($bindedGogsUser))) && p() && e('0'); // 步骤1：已绑定用户但数据不匹配返回0
r($gogsTest->getMatchedUsersTest(1, array($exactAccountMatchUser))) && p('2:zentaoAccount') && e('user1'); // 步骤2：账号精确匹配成功
r($gogsTest->getMatchedUsersTest(1, array($exactEmailMatchUser))) && p() && e('0'); // 步骤3：邮箱匹配但数据不匹配返回0
r($gogsTest->getMatchedUsersTest(1, array($exactNameMatchUser))) && p() && e('0'); // 步骤4：姓名匹配但数据不匹配返回0
r($gogsTest->getMatchedUsersTest(1, array($multiMatchUniqueUser))) && p('5:zentaoAccount') && e('user2'); // 步骤5：多重匹配但结果唯一
r($gogsTest->getMatchedUsersTest(1, array($multiMatchNotUniqueUser))) && p('6:zentaoAccount') && e('admin'); // 步骤6：多重匹配唯一结果
r($gogsTest->getMatchedUsersTest(1, array($noMatchUser))) && p() && e('0'); // 步骤7：无匹配返回0