#!/usr/bin/env php
<?php
/**

title=测试 stakeholderModel->replaceUserInfo();
cid=1

- 测试创建团队干系人时，不填写用户名属性user @『用户』不能为空。
- 测试创建公司干系人时，不填写用户名属性user @『用户』不能为空。
- 测试创建外部干系人时，不填写用户名属性user @『用户』不能为空。
- 测试创建外部干系人时，不填写姓名属性name @『姓名』不能为空。
- 测试创建外部干系人时，不填写公司属性company @『所属公司』不能为空。
- 测试创建外部干系人时，不填写公司名称属性company @『所属公司』不能为空。
- 测试创建团队干系人
 - 属性type @inside
 - 属性company @1
 - 属性realname @用户1
- 测试创建公司干系人
 - 属性type @inside
 - 属性company @1
 - 属性realname @用户1
- 测试创建外部干系人
 - 属性type @inside
 - 属性company @1
 - 属性realname @用户1
- 测试创建外部干系人
 - 属性type @outside
 - 属性company @1
 - 属性realname @新建外部人员
- 测试创建公司和外部干系人
 - 属性type @outside
 - 属性company @2
 - 属性realname @新建外部人员

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stakeholder.class.php';

zdTable('company')->gen(1);
zdTable('user')->config('user')->gen(2);

$from        = array('team', 'company', 'outside');
$account     = array('', 'user1');
$company     = array(0, 1);
$newUser     = array('', 'on');
$name        = array('', '新建外部人员');
$newCompany  = array('', 'on');
$companyName = array('', '新建公司');

/* Error condition. */
$emptyTeamUser    = array('from' => $from[0], 'user' => $account[0]);
$emptyCompanyUser = array('from' => $from[1], 'user' => $account[0]);
$emptyOutsideUser = array('from' => $from[2], 'user' => $account[0]);
$emptyUserName    = array('from' => $from[2], 'user' => $account[0], 'newUser' => $newUser[1]);
$emptyCompany     = array('from' => $from[2], 'user' => $account[0], 'newUser' => $newUser[1], 'name' => $name[1], 'company' => $company[0]);
$emptyNewCompany  = array('from' => $from[2], 'user' => $account[0], 'newUser' => $newUser[1], 'name' => $name[1], 'newCompany' => $newCompany[1], 'newCompany' => $newCompany[0]);

/* Normal condition. */
$teamUser      = array('from' => $from[0], 'user' => $account[1]);
$companyUser   = array('from' => $from[1], 'user' => $account[1]);
$outsideUser   = array('from' => $from[2], 'user' => $account[1]);
$createUser    = array('from' => $from[2], 'user' => $account[0], 'newUser' => $newUser[1], 'name' => $name[1], 'company' => $company[1]);
$createCompany = array('from' => $from[2], 'user' => $account[0], 'newUser' => $newUser[1], 'name' => $name[1], 'newCompany' => $newCompany[1], 'companyName' => $companyName[1]);

$stakeholderTester = new stakeholderTest();

/* Error condition. */
r($stakeholderTester->replaceUserInfoTest($emptyTeamUser))    && p('user')    && e('『用户』不能为空。');     // 测试创建团队干系人时，不填写用户名
r($stakeholderTester->replaceUserInfoTest($emptyCompanyUser)) && p('user')    && e('『用户』不能为空。');     // 测试创建公司干系人时，不填写用户名
r($stakeholderTester->replaceUserInfoTest($emptyOutsideUser)) && p('user')    && e('『用户』不能为空。');     // 测试创建外部干系人时，不填写用户名
r($stakeholderTester->replaceUserInfoTest($emptyUserName))    && p('name')    && e('『姓名』不能为空。');     // 测试创建外部干系人时，不填写姓名
r($stakeholderTester->replaceUserInfoTest($emptyCompany))     && p('company') && e('『所属公司』不能为空。'); // 测试创建外部干系人时，不填写公司
r($stakeholderTester->replaceUserInfoTest($emptyNewCompany))  && p('company') && e('『所属公司』不能为空。'); // 测试创建外部干系人时，不填写公司名称

/* Normal condition. */
r($stakeholderTester->replaceUserInfoTest($teamUser))      && p('type,company,realname') && e('inside,1,用户1');         // 测试创建团队干系人
r($stakeholderTester->replaceUserInfoTest($companyUser))   && p('type,company,realname') && e('inside,1,用户1');         // 测试创建公司干系人
r($stakeholderTester->replaceUserInfoTest($outsideUser))   && p('type,company,realname') && e('inside,1,用户1');         // 测试创建外部干系人
r($stakeholderTester->replaceUserInfoTest($createUser))    && p('type,company,realname') && e('outside,1,新建外部人员'); // 测试创建外部干系人
r($stakeholderTester->replaceUserInfoTest($createCompany)) && p('type,company,realname') && e('outside,2,新建外部人员'); // 测试创建公司和外部干系人
