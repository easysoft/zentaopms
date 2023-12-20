#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbanspace')->gen(5);

/**

title=测试 kanbanModel->updateSpace();
timeout=0
cid=1

- 测试修改空间1
 - 属性name @测试修改名称1
 - 属性owner @user3
 - 属性whitelist @~~
 - 属性team @,user3,po15,
- 测试修改空间2
 - 属性name @测试修改名称2
 - 属性owner @po16
 - 属性whitelist @~~
 - 属性team @,user4,po16,
- 测试修改空间3
 - 属性name @测试修改名称3
 - 属性owner @po17
 - 属性whitelist @,user5,po17,
 - 属性team @,user5,po17,user3,
- 测试修改空间4
 - 属性name @测试修改名称4
 - 属性owner @po18
 - 属性whitelist @~~
 - 属性team @,user6,po18,
- 测试修改空间5 不填写名称第name条的0属性 @『空间名称』不能为空。
- 测试修改空间5 不填写负责人第owner条的0属性 @『负责人』不能为空。

*/

$spaceIDList = array('1', '2', '3', '4', '5');
$nameList    = array('测试修改名称1', '测试修改名称2', '测试修改名称3', '测试修改名称4', '');
$ownerList   = 'user3';
$whitelist   = ',user4,po16,user3,';
$team        = ',user5,po17,user3,';
$desc        = '修改备注';

$param1 = new stdclass();
$param1->name  = $nameList[0];
$param1->owner = $ownerList;
$param1->type  = 'public';

$param2 = new stdclass();
$param2->name      = $nameList[1];
$param2->whitelist = $whitelist;
$param2->type      = 'cooperation';

$param3 = new stdclass();
$param3->name = $nameList[2];
$param3->team = $team;
$param3->type = 'private';

$param4 = new stdclass();
$param4->name = $nameList[3];
$param4->desc = $desc;
$param4->type = 'public';

$param5 = new stdclass();
$param5->name = $nameList[4];
$param5->type = 'public';

$param6 = new stdclass();
$param6->type  = 'public';
$param6->owner = '';

$kanban = new kanbanTest();

r($kanban->updateSpaceTest($spaceIDList[0], $param1)) && p('name|owner|whitelist|team', '|') && e('测试修改名称1|user3|~~|,user3,po15,'); // 测试修改空间1
r($kanban->updateSpaceTest($spaceIDList[1], $param2)) && p('name|owner|whitelist|team', '|') && e('测试修改名称2|po16|~~|,user4,po16,');  // 测试修改空间2
r($kanban->updateSpaceTest($spaceIDList[2], $param3)) && p('name|owner|whitelist|team', '|') && e('测试修改名称3|po17|,user5,po17,|,user5,po17,user3,'); // 测试修改空间3
r($kanban->updateSpaceTest($spaceIDList[3], $param4)) && p('name|owner|whitelist|team', '|') && e('测试修改名称4|po18|~~|,user6,po18,'); // 测试修改空间4
r($kanban->updateSpaceTest($spaceIDList[4], $param5)) && p('name:0')                         && e('『空间名称』不能为空。'); // 测试修改空间5 不填写名称
r($kanban->updateSpaceTest($spaceIDList[4], $param6)) && p('owner:0')                        && e('『负责人』不能为空。'); // 测试修改空间5 不填写负责人