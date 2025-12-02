#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/story.ui.class.php';

/**

title=开源版m=user&f=story&storyType=story测试
timeout=0
cid=1

- 开源版m=user&f=story&storyType=story测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @开源版m=user&f=story&storyType=story测试成功

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

$plan = zenData('productplan');
$plan->id->range('1-6');
$plan->product->range('1{2},2{2},3{2}');
$plan->title->range('计划A1,计划A2,计划B1,计划B2,计划C1,计划C2');
$plan->status->range('wait{2},doing{2},done{2}');
$plan->gen(6);

$story = zenData('story');
$story->id->range('1-18');
$story->title->range('1-18')->prefix('需求');
$story->product->range('1,2,3');
$story->type->range('story');
$story->openedBy->range('admin{6},user1{6},user2{6}');
$story->assignedTo->range('admin{6},user1{6},user2{6}');
$story->reviewedBy->range('admin{6},user1{6},user2{6}');
$story->status->range('closed{2},active{2},changing{2}');
$story->closedBy->range('admin{2},{4},user1{2},{4},user2{2},{4}');
$story->stage->range('wait{2},planned{2},projected{2}');
$story->estimate->range('1-3');
$story->vision->range('rnd');
$story->plan->range('1,2,3,4,5,6');
$story->gen(18);

global $uiTester;
$users = $uiTester->dao->select('*')->from('zt_user')->fetchAll();

// 补充 productTitle,planTitle,openedByName 字段用于比对
$stories = $uiTester->dao->select("s.*, p.name AS productTitle, pp.title AS planTitle, u.realname AS openedByName")
    ->from('zt_story')->alias('s')
    ->leftJoin('zt_product')->alias('p')->on('s.product = p.id')
    ->leftJoin('zt_productplan')->alias('pp')->on('s.plan = pp.id')
    ->leftJoin('zt_user')->alias('u')->on('s.openedBy = u.account')
    ->where('s.type')->eq('story')
    ->fetchAll();

$tester = new storyTester();

r($tester->verifyUserStoryMenus($users, $stories, 'story', 5)) && p('status,message') && e('SUCCESS,开源版m=user&f=story&storyType=story测试成功'); //开源版m=user&f=story&storyType=story测试

$tester->closeBrowser();