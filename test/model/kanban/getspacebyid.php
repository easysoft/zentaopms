#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getSpaceById();
cid=1
pid=1

测试查询空间1信息 >> 协作空间1,cooperation,po15,active
测试查询空间2信息 >> 私有空间2,private,po16,active
测试查询空间3信息 >> 公共空间3,public,po17,active
测试查询空间4信息 >> 协作空间4,cooperation,po18,active
测试查询空间5信息 >> 私有空间5,private,po19,active
测试查询不存在的空间信息 >> 0

*/
$spaceIDList    = array('1', '2', '3', '4', '5', '10001');

$kanban = new kanbanTest();

r($kanban->getSpaceByIdTest($spaceIDList[0])) && p('name,type,owner,status') && e('协作空间1,cooperation,po15,active'); // 测试查询空间1信息
r($kanban->getSpaceByIdTest($spaceIDList[1])) && p('name,type,owner,status') && e('私有空间2,private,po16,active');     // 测试查询空间2信息
r($kanban->getSpaceByIdTest($spaceIDList[2])) && p('name,type,owner,status') && e('公共空间3,public,po17,active');      // 测试查询空间3信息
r($kanban->getSpaceByIdTest($spaceIDList[3])) && p('name,type,owner,status') && e('协作空间4,cooperation,po18,active'); // 测试查询空间4信息
r($kanban->getSpaceByIdTest($spaceIDList[4])) && p('name,type,owner,status') && e('私有空间5,private,po19,active');     // 测试查询空间5信息
r($kanban->getSpaceByIdTest($spaceIDList[5])) && p('name,type,owner,status') && e('0');                                 // 测试查询不存在的空间信息