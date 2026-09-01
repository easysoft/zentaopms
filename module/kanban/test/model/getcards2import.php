#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
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
$zd_user->id->range('1-6');
$zd_user->account->range('admin,po15,po16,user1,user2,user3');
$zd_user->realname->range('admin,po15,po16,user1,user2,user3');
$zd_user->password->range('e10adc3949ba59abbe56e057f20f883e');
$zd_user->visions->range('rnd');
$zd_user->deleted->range('0');
$zd_user->gen(6);

su('admin');

zenData('kanbancard')->gen(100);
zenData('kanban')->gen(10);

/**

title=测试 kanbanModel->getCards2Import();
timeout=0
cid=16907

- 测试获取可以导入的卡片数量 @80
- 测试获取看板1可以导入的卡片数量 @8
- 测试获取看板1可以导入的卡片数量 排除看板1 @0
- 测试获取看板2可以导入的卡片数量 @8
- 测试获取看板2可以导入的卡片数量 排除看板1 @8
- 测试获取看板3可以导入的卡片数量 @8
- 测试获取看板3可以导入的卡片数量 排除看板1 @8
- 测试获取看板4可以导入的卡片数量 @8
- 测试获取看板4可以导入的卡片数量 排除看板1 @8
- 测试获取看板5可以导入的卡片数量 @8
- 测试获取看板5可以导入的卡片数量 @8
- 测试获取不存在看板可以导入的卡片数量 排除看板1 @0
- 测试获取不存在看板可以导入的卡片数量 排除看板1 @0

*/
$kanbanIDList = array('1', '2', '3', '4', '5', '1000001');
$excludedID   = 1;

$kanban = new kanbanModelTest();

r($kanban->getCards2ImportTest())                              && p() && e('80'); // 测试获取可以导入的卡片数量
r($kanban->getCards2ImportTest($kanbanIDList[0]))              && p() && e('8');  // 测试获取看板1可以导入的卡片数量
r($kanban->getCards2ImportTest($kanbanIDList[0], $excludedID)) && p() && e('0');  // 测试获取看板1可以导入的卡片数量 排除看板1
r($kanban->getCards2ImportTest($kanbanIDList[1]))              && p() && e('8');  // 测试获取看板2可以导入的卡片数量
r($kanban->getCards2ImportTest($kanbanIDList[1], $excludedID)) && p() && e('8');  // 测试获取看板2可以导入的卡片数量 排除看板1
r($kanban->getCards2ImportTest($kanbanIDList[2]))              && p() && e('8');  // 测试获取看板3可以导入的卡片数量
r($kanban->getCards2ImportTest($kanbanIDList[2], $excludedID)) && p() && e('8');  // 测试获取看板3可以导入的卡片数量 排除看板1
r($kanban->getCards2ImportTest($kanbanIDList[3]))              && p() && e('8');  // 测试获取看板4可以导入的卡片数量
r($kanban->getCards2ImportTest($kanbanIDList[3], $excludedID)) && p() && e('8');  // 测试获取看板4可以导入的卡片数量 排除看板1
r($kanban->getCards2ImportTest($kanbanIDList[4]))              && p() && e('8');  // 测试获取看板5可以导入的卡片数量
r($kanban->getCards2ImportTest($kanbanIDList[4]))              && p() && e('8');  // 测试获取看板5可以导入的卡片数量
r($kanban->getCards2ImportTest($kanbanIDList[5], $excludedID)) && p() && e('0');  // 测试获取不存在看板可以导入的卡片数量 排除看板1
r($kanban->getCards2ImportTest($kanbanIDList[5], $excludedID)) && p() && e('0');  // 测试获取不存在看板可以导入的卡片数量 排除看板1
