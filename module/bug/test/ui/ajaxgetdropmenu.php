#!/usr/bin/env php
<?php
chdir (__DIR__);
include '../lib/ui/ajaxgetdropmenu.ui.class.php';

/**

title=所属产品下拉菜单内容测试
timeout=0
cid=1

- Ajaxgetdropmenu在Bug创建页面测试
 - 最终测试状态 @SUCCESS
 - 测试结果 user1在Bug创建页面获取所属产品数据正确
- Ajaxgetdropmenu在Bug创建页面测试
 - 最终测试状态 @SUCCESS
 - 测试结果 admin在Bug创建页面获取所属产品数据正确
- Ajaxgetdropmenu在Bug编辑页面测试
 - 最终测试状态 @SUCCESS
 - 测试结果 user1在Bug编辑页面获取所属产品数据正确
- Ajaxgetdropmenu在Bug编辑页面测试
 - 最终测试状态 @SUCCESS
 - 测试结果 admin在Bug编辑页面获取所属产品数据正确

*/

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品1公开, 产品2公开, 产品3私有');
$product->shadow->range('0');
$product->PO->range('admin');
$product->program->range('0');
$product->QD->range('[]');
$product->RD->range('[]');
$product->acl->range('open{2}, private');
$product->vision->range('rnd');
$product->gen(3);

$projectproduct = zenData('projectproduct')->gen(0);

$bug = zenData('bug');
$bug->id->range('1');
$bug->product->range('1');
$bug->title->range('产品1公开的Bug');
$bug->project->range('0');
$bug->module->range('0');
$bug->execution->range('0');
$bug->plan->range('0');
$bug->story->range('0');
$bug->openedBuild->range('trunk');
$bug->gen(1);

$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin, user1, user2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->realname->range('admin, USER1, USER2');
$user->gen(3);

$usergroup = zenData('usergroup');
$usergroup->account->range('admin, user1, user2');
$usergroup->group->range('1');

$tester = new ajaxGetDropmenuTester();

$products = array('产品1公开', '产品2公开');
$productAll = array('产品1公开', '产品2公开', '产品3私有');


global $lang;
$bug = array('title' => 'bug' . time(), 'openedBuild' => array('multiPicker' => '主干'));
$user = array(
    'user1' => 'user1',
    'admin' => 'admin'
);

r($tester->ajaxGetDropmenuInBugCreate($user['user1'], $products, $bug))   && p('status,message') && e('SUCCESS,user1在Bug创建页面获取所属产品数据正确'); //Ajaxgetdropmenu在Bug创建页面测试
r($tester->ajaxGetDropmenuInBugCreate($user['admin'], $productAll, $bug)) && p('status,message') && e('SUCCESS,admin在Bug创建页面获取所属产品数据正确'); //Ajaxgetdropmenu在Bug创建页面测试
r($tester->ajaxGetDropmenuInBugEdit($user['user1'], $products, $bug))     && p('status,message') && e('SUCCESS,user1在Bug编辑页面获取所属产品数据正确'); //Ajaxgetdropmenu在Bug编辑页面测试
r($tester->ajaxGetDropmenuInBugEdit($user['admin'], $productAll, $bug))   && p('status,message') && e('SUCCESS,admin在Bug编辑页面获取所属产品数据正确'); //Ajaxgetdropmenu在Bug编辑页面测试

$tester->closeBrowser();
