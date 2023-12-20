#!/usr/bin/env php
<?php
/**
title=测试 userModel->update();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('user')->gen(3);
$companyTable = zdTable('company');
$companyTable->admins->range('`,admin,user1,`');
$companyTable->gen(1);

$viewTable = zdTable('userview');
$viewTable->account->range('user1');
$viewTable->gen(1);

$groupTable = zdTable('usergroup');
$groupTable->account->range('user1');
$groupTable->group->range('1,2');
$groupTable->gen(2);

su('admin');

global $app;

$app->company->admins = ',admin,user1,';

/* 安全配置相关功能在 checkPassword 单元测试用例中有详细测试，此处重置为默认值以减少对当前用例的影响。*/
unset($config->safe->mode);
unset($config->safe->weak);

$random   = updateSessionRandom();
$password = md5('123456');
$verify   = md5($app->user->password . $random);

$template = new stdclass();
$template->id               = 2;
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
$template->group            = array(1, 2);
$template->email            = '';
$template->phone            = '';
$template->mobile           = '';
$template->qq               = '';
$template->dingding         = '';
$template->weixin           = '';
$template->skype            = '';
$template->whatsapp         = '';
$template->slack            = '';
$template->commiter         = '';
$template->gender           = 'm';
$template->verifyPassword   = $verify;
$template->passwordLength   = 6;
$template->passwordStrength = 0;

$userTest = new userTest();

/**
 * 检测系统预留用户名。
 */
$user1 = clone $template;
$user1->account = 'guest';
$result = $userTest->updateTest($user1);
r($result) && p('result')         && e(0);                    // 使用系统预留用户名，返回 false。
r($result) && p('errors:account') && e('用户名已被系统预留'); // 使用系统预留用户名，提示错误信息。

/**
 *检测密码长度。
 */
$user2 = clone $template;
$user2->password1      = $password;
$user2->passwordLength = 5;
$result = $userTest->updateTest($user2);
r($result) && p('result')           && e(0);                   // 密码长度不够，返回 false。
r($result) && p('errors:password1') && e('密码须6位及以上。'); // 密码长度不够，提示错误信息。

/**
 * 检测两次密码是否相同。
 */
$user3 = clone $template;
$user3->password2      = '';
$user3->passwordLength = 6;
$result = $userTest->updateTest($user3);
r($result) && p('result')           && e(0);                    // 两次密码不同，返回 false。
r($result) && p('errors:password1') && e('两次密码应该相同。'); // 两次密码不同，提示错误信息。

/**
 * 检测当前用户登录密码。
 */
$user4 = clone $template;
$user4->verifyPassword = '';
$result = $userTest->updateTest($user4);
r($result) && p('result')                && e(0);                                          // 当前用户登录密码不正确，返回 false。
r($result) && p('errors:verifyPassword') && e('验证失败，请检查您的系统登录密码是否正确'); // 当前用户登录密码不正确，提示错误信息。

/**
 * 检测创建外部公司。
 */
$user5 = clone $template;
$user5->type = 'outside';
$user5->new  = 1;
$result = $userTest->updateTest($user5);
r($result) && p('result')            && e(0);                        // 公司名称为空，创建外部公司失败，返回 false。
r($result) && p('errors:newCompany') && e('『所属公司』不能为空。'); // 公司名称为空，创建外部公司失败，提示错误信息。

$user5->newCompany = 'newCompany';
$result = $userTest->updateTest($user5);
r($result) && p('result') && e(1); // 公司名称不为空，创建外部公司成功，返回 true。

/**
 * 检测必填项。
 * TODO：后台自定义必填项不可用，待可用后补充此处的测试代码。
 */
$user6 = clone $template;
$user6->account  = '';
$user6->realname = '';
$user6->visions  = '';
$result = $userTest->updateTest($user6);
r($result) && p('result')          && e(0);                        // 必填项为空，返回 false。
r($result) && p('errors:account')  && e('『用户名』不能为空。');   // 用户名为空，提示错误信息。
r($result) && p('errors:realname') && e('『姓名』不能为空。');     // 姓名为空，提示错误信息。
r($result) && p('errors:visions')  && e('『界面类型』不能为空。'); // 界面类型为空，提示错误信息。

/**
 * 检测用户名唯一性。
 */
$user7 = clone $template;
$result = $userTest->updateTest($user7);
r($result) && p('result') && e(1); // 更新 id 为 2 的用户成功，返回 true。

$user7->id = 3;
$result = $userTest->updateTest($user7);
r($result) && p('result')         && e(0);                                                                                             // 更新 id 为 3 的用户失败，用户名重复，返回 false。
r($result) && p('errors:account') && e('『用户名』已经有『user1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 更新 id 为 3 的用户失败，用户名重复，提示错误信息。

/**
 * 检测用户名是否符合格式要求。
 */
$user8 = clone $template;
$user8->account = 'user 8';
$result = $userTest->updateTest($user8);
r($result) && p('result')         && e(0);                                                    // 用户名不符合格式要求，返回 false。
r($result) && p('errors:account') && e('『用户名』只能是字母、数字或下划线的组合三位以上。'); // 用户名不符合格式要求，提示错误信息。

/**
 * 检测邮箱、电话和手机是否符合格式要求。
 */
$user9 = clone $template;
$user9->email  = 'email@';
$user9->phone  = '868930';
$user9->mobile = '1388888888';
$result = $userTest->updateTest($user9);
r($result) && p('result')        && e(0);                                // 邮箱不符合格式要求，返回 false。
r($result) && p('errors:email')  && e('『邮箱』应当为合法的EMAIL。');    // 邮箱不符合格式要求，提示错误信息。
r($result) && p('errors:phone')  && e('『电话』应当为合法的电话号码。'); // 电话不符合格式要求，提示错误信息。
r($result) && p('errors:mobile') && e('『手机』应当为合法的手机号码。'); // 手机不符合格式要求，提示错误信息。

/**
 * 检测字段是否符合数据库设置。
 */
$user10 = clone $template;
$user10->type    = '这是一个很长的用户类型。到底有多长呢？长到超出了数据库设置的长度。';
$user10->company = 'company';
$user10->join    = 'join';
$user10->gender  = 'gender';
$result = $userTest->updateTest($user10);
r($result) && p('result')         && e(0);                                                 // 字段不符合数据库设置，返回 false。
r($result) && p('errors:type')    && e('『用户类型』长度应当不超过『30』，且大于『0』。'); // 字符串字段长度超过数据库设置，提示错误信息。
r($result) && p('errors:company') && e('『所属公司』应当是数字。');                        // 数字字段类型不符合数据库设置，提示错误信息。
r($result) && p('errors:join')    && e('『入职日期』应当为合法的日期。');                  // 日期字段类型不符合数据库设置，提示错误信息。
r($result) && p('errors:gender')  && e('『性别』不符合格式，应当为:『/f|m/』。');          // 枚举字段类型不符合数据库设置，提示错误信息。

/**
 * 检测事务回滚功能。
 */
$user11 = clone $template;
$user11->id         = 3;
$user11->type       = 'outside';
$user11->new        = 1;
$user11->newCompany = 'newCompany2';
$result = $userTest->updateTest($user11);
r($result) && p('result')         && e(0);                                                                                             // 创建外部公司成功，更新 id 为 3 的用户失败，用户名重复，返回 false。
r($result) && p('errors:account') && e('『用户名』已经有『user1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 创建外部公司成功，更新 id 为 3 的用户失败，用户名重复，提示错误信息。

$company = $tester->dao->select('*')->from(TABLE_COMPANY)->where('name')->eq($user11->newCompany)->fetch();
r($company) && p() && e(0); // 事务回滚成功，没有创建公司。

/**
 * 检测修改用户名成功后的用户权限组、用户视图和超级管理员的用户名是否更新。
 */
$groups = $tester->dao->select('*')->from(TABLE_USERGROUP)->fetchAll();
r(count($groups)) && p()                  && e(2);         // 查看用户权限组，返回 2 条记录。
r($groups)        && p('0:account,group') && e('user1,1'); // 第 1 条记录的用户名是 user1，权限组 id 是 1。
r($groups)        && p('1:account,group') && e('user1,2'); // 第 2 条记录的用户名是 user1，权限组 id 是 2。

$views = $tester->dao->select('*')->from(TABLE_USERVIEW)->fetchAll();
r(count($views)) && p()            && e(2);       // 查看用户视图，返回 2 条记录。
r($views)        && p('0:account') && e('admin'); // 第 1 条记录的用户名是 admin。
r($views)        && p('1:account') && e('user1'); // 第 2 条记录的用户名是 user1。

$admins = $tester->dao->select('admins')->from(TABLE_COMPANY)->where('id')->eq(1)->fetch('admins');
r($admins) && p() && e(',admin,user1,'); // 数据库中超级管理员是 admin,user1。

r($app->company->admins) && p() && e(',admin,user1,'); // $app 对象中超级管理员是 admin,user1。

$user12 = clone $template;
$user12->account = 'user12';
$result = $userTest->updateTest($user12);
r($result) && p('result') && e(1); // 修改用户名成功，返回 true。

$groups = $tester->dao->select('*')->from(TABLE_USERGROUP)->fetchAll();
r(count($groups)) && p()                  && e(2);          // 查看用户权限组，返回 2 条记录。
r($groups)        && p('0:account,group') && e('user12,1'); // 第 1 条记录的用户名是 user12，权限组 id 是 1。
r($groups)        && p('1:account,group') && e('user12,2'); // 第 2 条记录的用户名是 user12，权限组 id 是 2。

$views = $tester->dao->select('*')->from(TABLE_USERVIEW)->fetchAll();
r(count($views)) && p()            && e(2);        // 查看用户视图，返回 2 条记录。
r($views)        && p('0:account') && e('admin');  // 第 1 条记录的用户名是 admin。
r($views)        && p('1:account') && e('user12'); // 第 2 条记录的用户名是 user12。

$admins = $tester->dao->select('admins')->from(TABLE_COMPANY)->where('id')->eq(1)->fetch('admins');
r($admins) && p() && e(',admin,user12,'); // 数据库中超级管理员是 admin,user12。

r($app->company->admins) && p() && e(',admin,user12,'); // $app 对象中超级管理员是 admin,user12。

$view = $tester->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq($user12->account)->fetch();
r($view) && p('programs', '|') && e(',1'); // user12 用户项目集权限是 1。

$user12->group = array(0, 2, 3);
$result = $userTest->updateTest($user12);
r($result) && p('result') && e(1); // 修改用户权限组成功，返回 true。

$groups = $tester->dao->select('*')->from(TABLE_USERGROUP)->fetchAll();
r(count($groups)) && p()                  && e(2);          // 查看用户权限组，返回 2 条记录。
r($groups)        && p('0:account,group') && e('user12,2'); // 第 1 条记录的用户名是 user12，权限组 id 是 1。
r($groups)        && p('1:account,group') && e('user12,3'); // 第 2 条记录的用户名是 user12，权限组 id 是 2。

$view = $tester->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq($user12->account)->fetch();
r($view) && p('programs', '|') && e('2,3,5,6,8,9'); // user12 用户项目集权限是 2,3,5,6,8,9。

/**
 * 检测是否创建日志和历史记录。
 */
$action = $tester->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(1)->fetch();
r($action) && p('objectType,objectID,action') && e('user,2,edited'); // 创建日志成功，最后一条日志的对象类型是 user，对象 id 是 2，操作是 edited。

$histories = $tester->dao->select('*')->from(TABLE_HISTORY)->where('action')->eq($action->id)->fetchAll();
r(count($histories)) && p()                  && e(1);                      // 创建历史记录成功，最后一条日志的历史记录是 1 条。
r($histories)        && p('0:field,old,new') && e('account,user1,user12'); // 创建历史记录成功，最后一条日志的历史记录的字段是 account，旧值是 user1，新值是 user12。

/**
 * 检测事务提交功能。
 */
$user = $userTest->getByIdTest('user12');
r($user) && p('id,account') && e('2,user12'); // 事务提交成功，能查询到修改的用户。

/**
 * 检测更新当前登录用户。
 */
r($app->user) && p('account,realname,role') && e('admin,admin,qa'); // 当前登录用户的用户名是 admin，真实姓名是 admin，角色是 qa。

r($app->user->password == $password) && p() && e(0); // 当前登录用户的密码不是 123456。

$user13 = clone $template;
$user13->id       = 1;
$user13->account  = 'admin';
$user13->realname = 'user13';
$user13->role     = 'role13';
$user13->password = $password;
$result = $userTest->updateTest($user13);
r($result)    && p('result')                && e(1);                      // 更新当前登录用户成功，返回 true。
r($app->user) && p('account,realname,role') && e('admin,user13,role13'); // 当前登录用户的用户名是 user13，真实姓名是 user13，角色是 role13。

r($app->user->password == $password) && p() && e(1); // 当前登录用户的密码是 123456。
