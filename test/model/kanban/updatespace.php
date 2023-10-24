#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->updateSpace();
cid=1
pid=1

测试修改空间1 >> name,协作空间1,测试修改名称1;owner,po15,user3
测试修改空间2 >> name,私有空间2,测试修改名称2;whitelist,,user4,po16,,,user4,po16,user3,
测试修改空间3 >> name,公共空间3,测试修改名称3;team,,user5,po17,,,user5,po17,user3,
测试修改空间4 >> name,协作空间4,测试修改名称4;desc,,修改备注
测试重复修改空间4 >> 没有数据更新
测试修改空间5 不填写名称 >> 『空间名称』不能为空。
测试修改空间5 名称为空格 >> 『空间名称』不能为空。
测试修改空间5 不填写负责人 >> 『负责人』不能为空。

*/

$spaceIDList = array('1', '2', '3', '4', '5');
$typeList    = array('cooperation', 'private', 'public');
$nameList    = array('测试修改名称1', '测试修改名称2', '测试修改名称3', '测试修改名称4', '', '    ');
$ownerList   = array('user3', '');
$whitelist   = array(',user4,po16,user3,');
$team        = array(',user5,po17,user3,');
$desc        = '修改备注';

$param1 = array('name' => $nameList[0], 'owner' => $ownerList[0]);
$param2 = array('name' => $nameList[1], 'whitelist' => $whitelist);
$param3 = array('name' => $nameList[2], 'team' => $team);
$param4 = array('name' => $nameList[3], 'desc' => $desc);
$param5 = array('name' => $nameList[4]);
$param6 = array('name' => $nameList[5]);
$param7 = array('owner' =>  $ownerList[1]);

$kanban = new kanbanTest();

r($kanban->updateSpaceTest($spaceIDList[0], $typeList[0], $param1)) && p('0:field,old,new;1:field,old,new') && e('name,协作空间1,测试修改名称1;owner,po15,user3');                          // 测试修改空间1
r($kanban->updateSpaceTest($spaceIDList[1], $typeList[1], $param2)) && p('0:field,old,new;1:field,old,new') && e('name,私有空间2,测试修改名称2;whitelist,,user4,po16,,,user4,po16,user3,'); // 测试修改空间2
r($kanban->updateSpaceTest($spaceIDList[2], $typeList[2], $param3)) && p('0:field,old,new;1:field,old,new') && e('name,公共空间3,测试修改名称3;team,,user5,po17,,,user5,po17,user3,');      // 测试修改空间3
r($kanban->updateSpaceTest($spaceIDList[3], $typeList[0], $param4)) && p('0:field,old,new;1:field,old,new') && e('name,协作空间4,测试修改名称4;desc,,修改备注');                            // 测试修改空间4
r($kanban->updateSpaceTest($spaceIDList[3], $typeList[0], $param4)) && p()                                  && e('没有数据更新');                                                           // 测试重复修改空间4
r($kanban->updateSpaceTest($spaceIDList[4], $typeList[1], $param5)) && p('name:0')                          && e('『空间名称』不能为空。');                                                 // 测试修改空间5 不填写名称
r($kanban->updateSpaceTest($spaceIDList[4], $typeList[1], $param6)) && p('name:0')                          && e('『空间名称』不能为空。');                                                 // 测试修改空间5 名称为空格
r($kanban->updateSpaceTest($spaceIDList[4], $typeList[1], $param7)) && p('owner:0')                         && e('『负责人』不能为空。');                                                   // 测试修改空间5 不填写负责人
