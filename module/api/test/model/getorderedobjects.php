#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('doclib')->loadYaml('doclib')->gen(300, true, false);
zenData('project')->gen(100, true, false);
zenData('product')->gen(100, true, false);

$zd_company = zenData('company');
$zd_company->id->range('1');
$zd_company->name->range('禅道软件');
$zd_company->admins->range('admin');
$zd_company->guest->range('0');
$zd_company->gen(1);
$dbh->exec("UPDATE zt_company SET admins = ',admin,' WHERE id = 1");
global $app;
$app->company = $dbh->query('SELECT * FROM zt_company WHERE id = 1')->fetch(PDO::FETCH_OBJ);

$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('admin');
$zd_user->realname->range('admin');
$zd_user->password->range('e10adc3949ba59abbe56e057f20f883e');
$zd_user->visions->range('rnd');
$zd_user->deleted->range('0');
$zd_user->gen(1);
su('admin');

/**

title=测试 apiModel->getOrderedObjects();
timeout=0
cid=15110

- 测试获取正常的产品和项目列表。
 - 第product条的1属性 @正常产品1
 - 第product条的2属性 @正常产品2
 - 第product条的44属性 @多分支产品44
 - 第project条的3属性 @项目集3
 - 第project条的4属性 @项目集4
 - 第project条的45属性 @项目45
- 测试获取已关闭的产品和项目列表。
 - 第product条的21属性 @已关闭的正常产品21
 - 第product条的22属性 @已关闭的正常产品22
 - 第product条的66属性 @已关闭的多分支产品66
 - 第project条的8属性 @项目集8
 - 第project条的16属性 @项目16
 - 第project条的96属性 @项目96

*/

global $tester;
$tester->loadModel('api');
$result = $tester->api->getOrderedObjects();
$normalObjects = $result[0];
$closedObjects = $result[1];

r($normalObjects) && p('product:1,2,44;project:3,4,45')    && e('正常产品1,正常产品2,多分支产品44,项目集3,项目集4,项目45');                          // 测试获取正常的产品和项目列表。
r($closedObjects) && p('product:21,22,66;project:8,16,96') && e('已关闭的正常产品21,已关闭的正常产品22,已关闭的多分支产品66,项目集8,项目16,项目96'); // 测试获取已关闭的产品和项目列表。
