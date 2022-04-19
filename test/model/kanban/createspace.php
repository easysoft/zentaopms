#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->createSpace();
cid=1
pid=1

创建私人空间 >> 测试创建私人空间,private,私人空间的描述,admin,,admin,po15
创建协作空间 >> 测试创建协作空间,cooperation,协作空间的描述,po16,po15,admin,po16,
创建公共空间 >> 测试创建公共空间,public,公共空间的描述,user1,user2,user3,admin,user1,
创建不填写名称的空间 >> 『空间名称』不能为空。
创建不填写负责人的空间 >> 『负责人』不能为空。

*/

$space1 = new stdclass();
$space1->type            = 'private';
$space1->name            = '测试创建私人空间';
$space1->owner           = '';
$space1->contactListMenu = '';
$space1->desc            = '私人空间的描述';
$space1->whitelist       = array('po15');

$space2 = new stdclass();
$space2->type            = 'cooperation';
$space2->name            = '测试创建协作空间';
$space2->owner           = 'po16';
$space2->contactListMenu = '';
$space2->desc            = '协作空间的描述';
$space2->team            = array('po15');

$space3 = new stdclass();
$space3->type            = 'public';
$space3->name            = '测试创建公共空间';
$space3->owner           = 'user1';
$space3->contactListMenu = '';
$space3->desc            = '公共空间的描述';
$space3->team            = array('user2', 'user3');

$space4 = new stdclass();
$space4->type            = 'public';
$space4->name            = '';
$space4->owner           = 'user1';
$space4->contactListMenu = '';
$space4->desc            = '不填写名字的公共空间描述';
$space4->team            = array('user2', 'user3');

$space5 = new stdclass();
$space5->type            = 'public';
$space5->name            = '不填写负责人的公共空间';
$space5->owner           = '';
$space5->contactListMenu = '';
$space5->desc            = '不填写负责人的公共空间描述';
$space5->team            = array('user2', 'user3');

$kanban = new kanbanTest();

r($kanban->createSpaceTest($space1)) && p('name,type,desc,owner,team,whitelist') && e('测试创建私人空间,private,私人空间的描述,admin,,admin,po15');             // 创建私人空间
r($kanban->createSpaceTest($space2)) && p('name,type,desc,owner,team,whitelist') && e('测试创建协作空间,cooperation,协作空间的描述,po16,po15,admin,po16,');     // 创建协作空间
r($kanban->createSpaceTest($space3)) && p('name,type,desc,owner,team,whitelist') && e('测试创建公共空间,public,公共空间的描述,user1,user2,user3,admin,user1,'); // 创建公共空间
r($kanban->createSpaceTest($space4)) && p('name:0')                              && e('『空间名称』不能为空。');                                                // 创建不填写名称的空间
r($kanban->createSpaceTest($space5)) && p('owner:0')                             && e('『负责人』不能为空。');                                                  // 创建不填写负责人的空间