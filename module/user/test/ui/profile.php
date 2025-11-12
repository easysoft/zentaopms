#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/profile.ui.class.php';

/**

title=开源版m=user&f=profile测试
timeout=0
cid=1

- 开源版m=user&f=profile测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @开源版m=user&f=profile测试成功

*/

$group = zenData('group');
$group->id->range('1-3');
$group->name->range('ADMIN,DEV,QA');
$group->role->range('admin,dev,qa');
$group->gen(3);

$userGroup = zenData('usergroup');
$userGroup->account->range('admin,user1,user2');
$userGroup->group->range('1-3');
$userGroup->gen(3);

$dept = zenData('dept');
$dept->id->range('1-3');
$dept->name->range('总办,开发部,测试部');
$dept->gen(3);

$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin,user1,user2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->realname->range('管理员,用户1,用户2');
$user->gender->range('f,m');
$user->email->range('admin,user1,user2')->postfix('@chandao.com');
$user->dept->range('1-3');
$user->role->range('admin,dev,qa');
$user->mobile->range('13988888888');
$user->weixin->range('admin,user1,user2')->postfix('@chandao.com');
$user->zipcode->range('610041');
$user->address->range('CD/SC/CHINA');
$user->commiter->range('admin,user1,user2')->postfix('@chandao.com');
$user->skype->range('admin,user1,user2')->postfix('@chandao.com');
$user->slack->range('admin,user1,user2')->postfix('@chandao.com');
$user->whatsapp->range('admin,user1,user2')->postfix('@chandao.com');
$user->dingding->range('admin,user1,user2')->postfix('@chandao.com');
$user->ip->range('10.0.0.1');
$user->gen(3);

$tester = new profileTester();

r($tester->verifyUserProfile()) && p('status,message') && e('SUCCESS,开源版m=user&f=profile测试成功'); //开源版m=user&f=profile测试

$tester->closeBrowser();