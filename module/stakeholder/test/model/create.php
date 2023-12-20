#!/usr/bin/env php
<?php
/**

title=测试 stakeholderModel->create();
cid=1

- 测试创建团队干系人时，不填写用户名属性user @『用户』不能为空。
- 测试创建公司干系人时，不填写用户名属性user @『用户』不能为空。
- 测试创建外部干系人时，不填写用户名属性user @『用户』不能为空。
- 测试创建外部干系人时，不填写姓名属性name @『姓名』不能为空。
- 测试创建外部干系人时，不填写公司属性company @『所属公司』不能为空。
- 测试创建外部干系人时，不填写公司名称属性company @『所属公司』不能为空。
- 测试创建项目团队干系人
 - 属性objectID @11
 - 属性objectType @project
 - 属性type @inside
 - 属性key @0
 - 属性from @team
- 测试创建已存在的项目干系人第user条的0属性 @『用户』已经有『user1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 测试创建项目外部干系人
 - 属性objectID @11
 - 属性objectType @project
 - 属性type @outside
 - 属性key @0
 - 属性from @outside
- 测试创建项目外部干系人
 - 属性objectID @11
 - 属性objectType @project
 - 属性type @outside
 - 属性key @0
 - 属性from @outside
- 测试创建项目集团队干系人
 - 属性objectID @1
 - 属性objectType @program
 - 属性type @inside
 - 属性key @0
 - 属性from @team
- 测试创建已存在的项目集干系人第user条的0属性 @『用户』已经有『user1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 测试创建项目集外部干系人
 - 属性objectID @1
 - 属性objectType @program
 - 属性type @outside
 - 属性key @0
 - 属性from @outside
- 测试创建项目集外部干系人
 - 属性objectID @1
 - 属性objectType @program
 - 属性type @outside
 - 属性key @0
 - 属性from @outside

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stakeholder.class.php';

zdTable('company')->gen(1);
zdTable('product')->gen(0);
zdTable('projectproduct')->gen(0);
zdTable('stakeholder')->gen(0);
zdTable('group')->gen(0);
zdTable('acl')->gen(0);
zdTable('user')->gen(5);
zdTable('project')->config('project')->gen(15);

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
$projectTeamUser      = array('objectType' => 'project', 'objectID' => 11, 'from' => $from[0], 'user' => $account[1]);
$projectExistUser     = array('objectType' => 'project', 'objectID' => 11, 'from' => $from[1], 'user' => $account[1]);
$projectCreateUser    = array('objectType' => 'project', 'objectID' => 11, 'from' => $from[2], 'user' => $account[0], 'newUser' => $newUser[1], 'name' => $name[1], 'company' => $company[1]);
$projectCreateCompany = array('objectType' => 'project', 'objectID' => 11, 'from' => $from[2], 'user' => $account[0], 'newUser' => $newUser[1], 'name' => $name[1], 'newCompany' => $newCompany[1], 'companyName' => $companyName[1]);

$programTeamUser      = array('objectType' => 'program', 'objectID' => 1, 'from' => $from[0], 'user' => $account[1]);
$programExistUser     = array('objectType' => 'program', 'objectID' => 1, 'from' => $from[1], 'user' => $account[1]);
$programCreateUser    = array('objectType' => 'program', 'objectID' => 1, 'from' => $from[2], 'user' => $account[0], 'newUser' => $newUser[1], 'name' => $name[1], 'company' => $company[1]);
$programCreateCompany = array('objectType' => 'program', 'objectID' => 1, 'from' => $from[2], 'user' => $account[0], 'newUser' => $newUser[1], 'name' => $name[1], 'newCompany' => $newCompany[1], 'companyName' => $companyName[1]);

$stakeholderTester = new stakeholderTest();

/* Error condition. */
r($stakeholderTester->createTest($emptyTeamUser))    && p('user')    && e('『用户』不能为空。');     // 测试创建团队干系人时，不填写用户名
r($stakeholderTester->createTest($emptyCompanyUser)) && p('user')    && e('『用户』不能为空。');     // 测试创建公司干系人时，不填写用户名
r($stakeholderTester->createTest($emptyOutsideUser)) && p('user')    && e('『用户』不能为空。');     // 测试创建外部干系人时，不填写用户名
r($stakeholderTester->createTest($emptyUserName))    && p('name')    && e('『姓名』不能为空。');     // 测试创建外部干系人时，不填写姓名
r($stakeholderTester->createTest($emptyCompany))     && p('company') && e('『所属公司』不能为空。'); // 测试创建外部干系人时，不填写公司
r($stakeholderTester->createTest($emptyNewCompany))  && p('company') && e('『所属公司』不能为空。'); // 测试创建外部干系人时，不填写公司名称

/* Normal condition. */
r($stakeholderTester->createTest($projectTeamUser))      && p('objectID,objectType,type,key,from') && e('11,project,inside,0,team');                                                                  // 测试创建项目团队干系人
r($stakeholderTester->createTest($projectExistUser))     && p('user:0')                            && e('『用户』已经有『user1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 测试创建已存在的项目干系人
r($stakeholderTester->createTest($projectCreateUser))    && p('objectID,objectType,type,key,from') && e('11,project,outside,0,outside');                                                              // 测试创建项目外部干系人
r($stakeholderTester->createTest($projectCreateCompany)) && p('objectID,objectType,type,key,from') && e('11,project,outside,0,outside');                                                              // 测试创建项目外部干系人

r($stakeholderTester->createTest($programTeamUser))      && p('objectID,objectType,type,key,from') && e('1,program,inside,0,team');                                                                   // 测试创建项目集团队干系人
r($stakeholderTester->createTest($programExistUser))     && p('user:0')                            && e('『用户』已经有『user1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 测试创建已存在的项目集干系人
r($stakeholderTester->createTest($programCreateUser))    && p('objectID,objectType,type,key,from') && e('1,program,outside,0,outside');                                                               // 测试创建项目集外部干系人
r($stakeholderTester->createTest($programCreateCompany)) && p('objectID,objectType,type,key,from') && e('1,program,outside,0,outside');                                                               // 测试创建项目集外部干系人
