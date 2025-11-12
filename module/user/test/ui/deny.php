#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/deny.ui.class.php';

/**

title=开源版m=user&f=deny测试
timeout=0
cid=1

- 开源版m=user&f=deny测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @开源版m=user&f=deny测试成功
- 开源版m=user&f=deny(noview)测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @开源版m=user&f=deny(noview)测试成功
- 开源版m=user&f=deny(noview 路由)测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @开源版m=user&f=deny(noview 路由)测试成功

*/

$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin,user1,user2');
$user->realname->range('管理员,用户1,用户2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->role->range('admin,dev,qa');
$user->gender->range('f,m');
$user->gen(3);

$userGroup = zenData('usergroup');
$userGroup->account->range('1,2')->prefix('user');
$userGroup->group->range('2-3');
$userGroup->gen(2);

$groupPriv = zenData('grouppriv');
$groupPriv->group->range('2');
$groupPriv->module->range('my,qa');
$groupPriv->method->range('team,index');
$groupPriv->gen(2);

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->code->range('product1');
$product->type->range('normal');
$product->status->range('normal');
$product->PO->range('admin');
$product->deleted->range('0');
$product->gen(1);

$tester = new denyTester();

// 先运行 nopriv 测试：访问 my/team 触发拒绝
r($tester->verifyUserDenyNopriv())                     && p('status,message') && e('SUCCESS,开源版m=user&f=deny测试成功');              // 开源版m=user&f=deny测试
// 再运行 noview 测试：直接打开 deny(noview)
r($tester->verifyUserDenyNoview('my', 'team'))         && p('status,message') && e('SUCCESS,开源版m=user&f=deny(noview)测试成功');      // 开源版m=user&f=deny(noview)测试
// 运行基于真实路由触发的 noview 测试：访问 qa/index，预期 deny(noview)
r($tester->verifyUserDenyNoviewByRoute('qa', 'index')) && p('status,message') && e('SUCCESS,开源版m=user&f=deny(noview 路由)测试成功'); // 开源版m=user&f=deny(noview 路由)测试

$tester->closeBrowser();