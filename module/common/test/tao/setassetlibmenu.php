#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$zd_company = zenData('company');
$zd_company->id->range('1');
$zd_company->name->range('禅道软件');
$zd_company->admins->range('admin');
$zd_company->guest->range('0');
$zd_company->gen(1);
$dbh->exec("UPDATE zt_company SET admins = ',admin,' WHERE id = 1");
global $app;
$app->company = $dbh->query('SELECT * FROM zt_company WHERE id = 1')->fetch(PDO::FETCH_OBJ);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->password->range('e10adc3949ba59abbe56e057f20f883e');
$user->visions->range('rnd');
$user->deleted->range('0');
$user->gen(10);

su('admin');

/**

title=测试 commonTao->setAssetLibMenu();
timeout=0
cid=0

- 查看admin是否可以打印资产库的菜单
 -  @1
 - 属性1 @caselib
- 查看user1是否可以打印资产库的菜单
 -  @~~
 - 属性1 @browse
- 查看user1是否可以打印资产库的菜单
 -  @~~
 - 属性1 @create

*/
$result1 = commonTao::setAssetLibMenu(false, 'assetlib', 'browse');

su('user1');
$result2 = commonTao::setAssetLibMenu(false, 'assetlib', 'browse');
$result3 = commonTao::setAssetLibMenu(false, 'assetlib', 'create');

r($result1) && p('0,1') && e('1,caselib'); // 查看admin是否可以打印资产库的菜单
r($result2) && p('0,1') && e('~~,browse'); // 查看user1是否可以打印资产库的菜单
r($result3) && p('0,1') && e('~~,create'); // 查看user1是否可以打印资产库的菜单
