#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/team.ui.class.php';

/**

title=开源版m=my&f=team测试
timeout=0
cid=1

- 开源版m=my&f=team测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @开源版m=my&f=team测试成功

*/

$today = time();

$user = zenData('user');
$user->id->range('1-7');
$user->account->range('admin,user1,user2,user3,user4,user5,user6');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6');
$user->role->range('admin,dev{3},qa{3}');
$user->last->range($today);
$user->deleted->range('0{7}');
$user->gen(7);

$userGroup = zenData('usergroup');
$userGroup->account->range('1-6')->prefix('user');
$userGroup->group->range('2,3');
$userGroup->gen(6);

// 除admin外，只给dev组(gid=2)用户分配查看team的权限
$groupPriv = zenData('grouppriv');
$groupPriv->group->range('2');
$groupPriv->module->range('index,my');
$groupPriv->method->range('index,team');
$groupPriv->gen(2);

$tester = new myTeamTester();

r($tester->verifyTeamPage()) && p('status,message') && e('SUCCESS,开源版m=my&f=team测试成功'); // 开源版m=my&f=team测试

$tester->closeBrowser();
