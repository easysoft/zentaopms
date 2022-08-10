#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->getTrashObjectTypes();
cid=1
pid=1

查询type all 的对象类型列表 >> story
查询type all 的对象类型列表 >> testcase

*/

$typeList = array('all', 'hidden');

$action = new actionTest();

r($action->getTrashObjectTypesTest($typeList[0])) && p('story:objectType') && e('story');       // 查询type all 的对象类型列表
r($action->getTrashObjectTypesTest($typeList[1])) && p('testcase:objectType') && e('testcase'); // 查询type all 的对象类型列表