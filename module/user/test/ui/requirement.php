#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/story.ui.class.php';

/**

title=开源版m=user&f=story&storyType=requirement测试
timeout=0
cid=1

- 开源版m=user&f=story&storyType=requirement测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @开源版m=user&f=story&storyType=requirement测试成功

*/

$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin,user1,user2');
$user->realname->range('管理员,用户1,用户2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->role->range('admin,dev,qa');
$user->gender->range('f,m');
$user->gen(3);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品1,产品2,产品3');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

$requirement = zenData('story');
$requirement->id->range('101-118');
$requirement->title->range('1-18')->prefix('UR ');
$requirement->product->range('1,2');
$requirement->type->range('requirement');
$requirement->openedBy->range('admin{6},user1{6},user2{6}');
$requirement->assignedTo->range('admin{6},user1{6},user2{6}');
$requirement->reviewedBy->range('admin{6},user1{6},user2{6}');
$requirement->status->range('closed{2},active{2},changing{2}');
$requirement->closedBy->range('admin{2},{4},user1{2},{4},user2{2},{4}');
$requirement->stage->range('wait{2},planned{2},projected{2}');
$requirement->estimate->range('1-3');
$requirement->vision->range('rnd');
$requirement->plan->range('1,2,3,4,5,6');
$requirement->gen(18);

global $uiTester;
$users = $uiTester->dao->select('*')->from('zt_user')->fetchAll();

// 补充 productTitle,openedByName 字段用于比对
$requirements = $uiTester->dao->select("s.*, p.name AS productTitle, u.realname AS openedByName")
    ->from("zt_story")->alias('s')
    ->leftJoin('zt_product')->alias('p')->on('s.product = p.id')
    ->leftJoin('zt_user')->alias('u')->on('s.openedBy = u.account')
    ->where('s.type')->eq('requirement')
    ->fetchAll();

$tester = new storyTester();

r($tester->verifyUserStoryMenus($users, $requirements, 'requirement', 5)) && p('status,message') && e('SUCCESS,开源版m=user&f=story&storyType=requirement测试成功'); //开源版m=user&f=story&storyType=requirement测试

$tester->closeBrowser();