#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->update();
cid=1
pid=1

测试修改看板名称 >> name,通用看板1,修改名字
测试修改看板空间 >> space,1,2
测试修改看板备注 >> desc,看板详情,修改注释
测试修改看板负责人 >> owner,po16,user4
测试修改看板团队成员 >> team,,user5,po17,,user5,po17,user3
测试重复修改看板名称 >> 没有数据更新
测试修改看板名称为空 >> 『看板名称』不能为空。
测试修改看板名称为空格 >> 『看板名称』不能为空。
测试修改看板空间为空 >> 『所属空间』不能为空。

*/

$kanbanIDList = array('1', '2', '3', '4', '5');

$names = array('修改名字', '', '   ');
$space = array('2', '');
$desc  = '修改注释';
$owner = 'user4';
$team  = array('user5', 'po17', 'user3');

$kanban = new kanbanTest();

r($kanban->updateTest($kanbanIDList[0], array('name' => $names[0])))  && p('0:field,old,new') && e('name,通用看板1,修改名字');            // 测试修改看板名称
r($kanban->updateTest($kanbanIDList[1], array('space' => $space[0]))) && p('0:field,old,new') && e('space,1,2');                          // 测试修改看板空间
r($kanban->updateTest($kanbanIDList[2], array('desc' => $desc)))      && p('0:field,old,new') && e('desc,看板详情,修改注释');             // 测试修改看板备注
r($kanban->updateTest($kanbanIDList[3], array('owner' => $owner)))    && p('0:field,old,new') && e('owner,po16,user4');                   // 测试修改看板负责人
r($kanban->updateTest($kanbanIDList[4], array('team' => $team)))      && p('0:field,old,new') && e('team,,user5,po17,,user5,po17,user3'); // 测试修改看板团队成员
r($kanban->updateTest($kanbanIDList[0], array('name' => $names[0])))  && p()                  && e('没有数据更新');                       // 测试重复修改看板名称
r($kanban->updateTest($kanbanIDList[0], array('name' => $names[1])))  && p('name:0')          && e('『看板名称』不能为空。');             // 测试修改看板名称为空
r($kanban->updateTest($kanbanIDList[0], array('name' => $names[2])))  && p('name:0')          && e('『看板名称』不能为空。');             // 测试修改看板名称为空格
r($kanban->updateTest($kanbanIDList[0], array('space' => $space[1]))) && p('space:0')         && e('『所属空间』不能为空。');             // 测试修改看板空间为空
