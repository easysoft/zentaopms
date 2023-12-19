#!/usr/bin/env php
<?php
/**
title=测试 userModel->create();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('user')->gen(1);
zdTable('company')->gen(1);
zdTable('usergroup')->gen(0);

su('admin');

global $app;

/* 安全配置相关功能在 checkPassword 单元测试用例中有详细测试，此处重置为默认值以减少对当前用例的影响。*/
unset($config->safe->mode);
unset($config->safe->weak);

$random   = updateSessionRandom();
$password = md5('123456');
$verify   = md5($app->user->password . $random);

$template = new stdclass();
$template->type             = 'inside';
$template->account          = 'user1';
$template->realname         = 'user1';
$template->password         = $password;
$template->password1        = $password;
$template->password2        = $password;
$template->visions          = 'rnd';
$template->company          = 0;
$template->dept             = 0;
$template->new              = 0;
$template->newCompany       = '';
$template->join             = null;
$template->role             = '';
$template->group            = array();
$template->email            = '';
$template->commiter         = '';
$template->gender           = 'm';
$template->verifyPassword   = $verify;
$template->passwordLength   = 6;
$template->passwordStrength = 0;

$userTest = new userTest();

/* 检测系统预留用户名。*/
$user1 = clone $template;
$user1->account = 'guest';
$result = $userTest->createUserTest($user1);
r($result) && p('result')           && e(0);                    // 使用系统预留用户名，返回 false。
r($result) && p('errors:account')   && e('用户名已被系统预留'); // 使用系统预留用户名，提示错误信息。

/* 检测密码是否为空。*/
$user2 = clone $template;
$user2->password1 = '';
$result = $userTest->createUserTest($user2);
r($result) && p('result')           && e(0);                    // 密码为空，返回 false。
r($result) && p('errors:password1') && e('『密码』不能为空。'); // 密码为空，提示错误信息。

/* 检测密码长度。*/
$user3 = clone $template;
$user3->password1      = $password;
$user3->passwordLength = 5;
$result = $userTest->createUserTest($user3);
r($result) && p('result')         && e(0);                     // 密码长度不够，返回 false。
r($result) && p('errors:password1') && e('密码须6位及以上。'); // 密码长度不够，提示错误信息。

/* 检测两次密码是否相同。*/
$user4 = clone $template;
$user4->password2      = '';
$user4->passwordLength = 6;
$result = $userTest->createUserTest($user4);
r($result) && p('result')           && e(0);                    // 两次密码不同，返回 false。
r($result) && p('errors:password1') && e('两次密码应该相同。'); // 两次密码不同，提示错误信息。

/* 检测当前用户登录密码。*/
$user4 = clone $template;
$user4->verifyPassword = '';
$result = $userTest->createUserTest($user4);
r($result) && p('result')                && e(0);                                          // 当前用户登录密码不正确，返回 false。
r($result) && p('errors:verifyPassword') && e('验证失败，请检查您的系统登录密码是否正确'); // 当前用户登录密码不正确，提示错误信息。

/* 检测创建外部公司。*/
$user5 = clone $template;
$user5->type = 'outside';
$user5->new  = 1;
$result = $userTest->createUserTest($user5);
r($result) && p('result')            && e(0);                        // 创建外部公司，公司名称为空，返回 false。
r($result) && p('errors:newCompany') && e('『所属公司』不能为空。'); // 创建外部公司，公司名称为空，提示错误信息。

$user5->newCompany = 'newCompany';
$result = $userTest->createUserTest($user5);
r($result) && p('result')            && e(2);    // 创建外部公司，公司名称不为空，返回创建的用户 id。
r($result) && p('errors:newCompany') && e('~~'); // 创建外部公司，公司名称不为空，没有错误信息。

/* 检测必填项。*/
$user6 = clone $template;
$user6->account  = '';
$user6->realname = '';
$user6->password = '';
$user6->visions  = '';
$result = $userTest->createUserTest($user6);
r($result) && p('result')          && e(0);                        // 必填项为空，返回 false。
r($result) && p('errors:account')  && e('『用户名』不能为空。');   // 用户名为空，提示错误信息。
r($result) && p('errors:realname') && e('『姓名』不能为空。');     // 姓名为空，提示错误信息。
r($result) && p('errors:password') && e('『密码』不能为空。');     // 密码为空，提示错误信息。
r($result) && p('errors:visions')  && e('『界面类型』不能为空。'); // 界面类型为空，提示错误信息。

/* 检测用户名唯一性。*/
$user7 = clone $template;
$result = $userTest->createUserTest($user7);
r($result) && p('result')         && e(0);                                                                                             // 用户名重复，返回 false。
r($result) && p('errors:account') && e('『用户名』已经有『user1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 用户名重复，提示错误信息。

/* 检测用户名是否符合格式要求。*/
$user8 = clone $template;
$user8->account = 'user 8';
$result = $userTest->createUserTest($user8);
r($result) && p('result')         && e(0);                                                    // 用户名不符合格式要求，返回 false。
r($result) && p('errors:account') && e('『用户名』只能是字母、数字或下划线的组合三位以上。'); // 用户名不符合格式要求，提示错误信息。

/* 检测邮箱是否符合格式要求。*/
$user9 = clone $template;
$user9->email = 'email@';
$result = $userTest->createUserTest($user9);
r($result) && p('result')       && e(0);                             // 邮箱不符合格式要求，返回 false。
r($result) && p('errors:email') && e('『邮箱』应当为合法的EMAIL。'); // 邮箱不符合格式要求，提示错误信息。

/* 检测字段是否符合数据库设置。*/
$user10 = clone $template;
$user10->type    = '这是一个很长的用户类型。到底有多长呢？长到超出了数据库设置的长度。';
$user10->company = 'company';
$user10->join    = 'join';
$user10->gender  = 'gender';
$result = $userTest->createUserTest($user10);
r($result) && p('result')         && e(0);                                                 // 字段不符合数据库设置，返回 false。
r($result) && p('errors:type')    && e('『用户类型』长度应当不超过『30』，且大于『0』。'); // 字符串字段长度超过数据库设置，提示错误信息。
r($result) && p('errors:company') && e('『所属公司』应当是数字。');                        // 数字字段类型不符合数据库设置，提示错误信息。
r($result) && p('errors:join')    && e('『入职日期』应当为合法的日期。');                  // 日期字段类型不符合数据库设置，提示错误信息。
r($result) && p('errors:gender')  && e('『性别』不符合格式，应当为:『/f|m/』。');          // 枚举字段类型不符合数据库设置，提示错误信息。

/* 检查事务回滚功能。*/
$user11 = clone $template;
$user11->type       = 'outside';
$user11->new        = 1;
$user11->newCompany = 'newCompany2';
$result = $userTest->createUserTest($user11);
r($result) && p('result')         && e(0);                                                                                             // 创建外部公司成功，用户名重复，返回 false。
r($result) && p('errors:account') && e('『用户名』已经有『user1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 创建外部公司成功，用户名重复，提示错误信息。

$company = $tester->dao->select('*')->from(TABLE_COMPANY)->where('name')->eq($user11->newCompany)->fetch();
r($company) && p() && e(0); // 事务回滚成功，没有创建公司。

/* 检查生成用户权限组。*/
$user12 = clone $template;
$user12->account = 'user2';
$user12->group   = array(0, 1, 2);
$result = $userTest->createUserTest($user12);
r($result) && p('result')         && e(3);    // 创建用户成功，返回创建的用户 id。
r($result) && p('errors:account') && e('~~'); // 创建用户成功，没有错误信息。

$groups = $tester->dao->select('*')->from(TABLE_USERGROUP)->fetchAll();
r(count($groups)) && p()                  && e(2);         // 生成用户权限组成功，返回 2 条记录。
r($groups)        && p('0:account,group') && e('user2,1'); // 第 1 条记录的用户名是 user2，权限组 id 是 1。
r($groups)        && p('1:account,group') && e('user2,2'); // 第 2 条记录的用户名是 user2，权限组 id 是 2。

/* 检查生成用户视图。*/
// TODO：computeUserView() 单元测试完成后，补充此处的测试代码。

/* 检查是否创建日志。*/
$action = $tester->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(1)->fetch();
r($action) && p('objectType,objectID,action') && e('user,3,created'); // 创建日志成功，最后一条日志的对象类型是 user，对象 id 是 3，操作是 created。

/* 检查事务提交功能。*/
$user = $userTest->getByIdTest('user2');
r($user) && p('id,account') && e('3,user2'); // 事务提交成功，能查询到创建的用户。
