#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->create();
cid=1
pid=1

创建协同空间 >> 测试创建协作看板,1,,po15,
创建私人空间 >> 测试创建私人看板,2,,admin,,user4
创建公共空间 >> 测试创建公共看板,3,,po17,
创建没有名字的公共空间 >> 『看板名称』不能为空。
创建没有空间公共空间 >> 『所属空间』不能为空。
创建重名公共空间 >> 『看板名称』已经有『测试创建公共看板』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。

*/

$kanban1 = new stdclass();
$kanban1->type  = 'cooperation';
$kanban1->space = 1;
$kanban1->name  = '测试创建协作看板';
$kanban1->owner = 'po15';
$kanban1->team  = array('', 'user3');
$kanban1->desc  = '测试创建协作看板的描述';

$kanban2 = new stdclass();
$kanban2->type      = 'private';
$kanban2->space     = 2;
$kanban2->name      = '测试创建私人看板';
$kanban2->owner     = 'po16';
$kanban2->whitelist = array('', 'user4');
$kanban2->desc      = '测试创建私人看板的描述';

$kanban3 = new stdclass();
$kanban3->type  = 'open';
$kanban3->space = 3;
$kanban3->name  = '测试创建公共看板';
$kanban3->owner = 'po17';
$kanban3->team  = array('', 'user5');
$kanban3->desc  = '测试创建公共看板的描述';

$kanban4 = new stdclass();
$kanban4->type  = 'open';
$kanban4->space = 3;
$kanban4->name  = '';
$kanban4->owner = 'po17';
$kanban4->team  = array('', 'user5');
$kanban4->desc  = '测试创建没有名字看板的描述';

$kanban5 = new stdclass();
$kanban5->type  = 'open';
$kanban5->space = 0;
$kanban5->name  = '测试创建没有空间的公共看板';
$kanban5->owner = 'po17';
$kanban5->team  = array('', 'user5');
$kanban5->desc  = '测试创建没有空间的公共看板的描述';

$kanban6 = new stdclass();
$kanban6->type  = 'open';
$kanban6->space = 3;
$kanban6->name  = '测试创建公共看板';
$kanban6->owner = 'po17';
$kanban6->team  = array('', 'user5');
$kanban6->desc  = '测试创建重名的公共看板的描述';

$kanban = new kanbanTest();

r($kanban->createTest($kanban1)) && p('name,space,type,owner,whitelist') && e('测试创建协作看板,1,,po15,');        // 创建协同空间
r($kanban->createTest($kanban2)) && p('name,space,type,owner,whitelist') && e('测试创建私人看板,2,,admin,,user4'); // 创建私人空间
r($kanban->createTest($kanban3)) && p('name,space,type,owner,whitelist') && e('测试创建公共看板,3,,po17,');        // 创建公共空间
r($kanban->createTest($kanban4)) && p('name:0')                          && e('『看板名称』不能为空。');           // 创建没有名字的公共空间
r($kanban->createTest($kanban5)) && p('space:0')                         && e('『所属空间』不能为空。');           // 创建没有空间公共空间
r($kanban->createTest($kanban6)) && p('name:0')                          && e('『看板名称』已经有『测试创建公共看板』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。'); // 创建重名公共空间
