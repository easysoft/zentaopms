#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->saveIndex();
cid=1
pid=1

测试保存空数据 >> 1
测试保存stroyId为0, action为空的数据 >> 1
测试保存stroyId为1, action为空的数据 >> 1
测试保存stroyId为100000, action为空的数据 >> 1
测试保存stroyId为0, action为created的数据 >> 1
测试保存stroyId为1, action为created的数据 >> 1
测试保存stroyId为100000, action为created的数据 >> 1
测试保存不存在类型的数据 >> 1

*/

$objectTypeList = array('', 'stroy', 'test');
$objectIdList   = array(0, 1, 100000);
$actionTypeList = array('', 'created', 'tested');

$action = new actionTest();

r($action->saveIndexTest($objectTypeList[0], $objectIdList[0], $actionTypeList[0])) && p() && e('1'); // 测试保存空数据
r($action->saveIndexTest($objectTypeList[1], $objectIdList[0], $actionTypeList[0])) && p() && e('1'); // 测试保存stroyId为0, action为空的数据
r($action->saveIndexTest($objectTypeList[1], $objectIdList[1], $actionTypeList[0])) && p() && e('1'); // 测试保存stroyId为1, action为空的数据
r($action->saveIndexTest($objectTypeList[1], $objectIdList[2], $actionTypeList[0])) && p() && e('1'); // 测试保存stroyId为100000, action为空的数据
r($action->saveIndexTest($objectTypeList[1], $objectIdList[0], $actionTypeList[1])) && p() && e('1'); // 测试保存stroyId为0, action为created的数据
r($action->saveIndexTest($objectTypeList[1], $objectIdList[1], $actionTypeList[1])) && p() && e('1'); // 测试保存stroyId为1, action为created的数据
r($action->saveIndexTest($objectTypeList[1], $objectIdList[2], $actionTypeList[1])) && p() && e('1'); // 测试保存stroyId为100000, action为created的数据
r($action->saveIndexTest($objectTypeList[2], $objectIdList[2], $actionTypeList[2])) && p() && e('1'); // 测试保存不存在类型的数据
