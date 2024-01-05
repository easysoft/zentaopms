#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

zdTable('action')->gen(5);
zdTable('bug')->gen(1);
zdTable('effort')->gen(1);
zdTable('case')->gen(1);
zdTable('casestep')->gen(1);

/**

title=测试 actionModel->saveIndex();
timeout=0
cid=1

- 测试保存空数据 @0
- 测试保存$objectId为0, action为空的数据 @0
- 测试保存$objectId为1, action为空的数据 @0
- 测试保存$objectId为100000, action为空的数据 @0
- 测试保存$objectId为1, action为created的数据 @1
- 测试保存$objectId为100000, action为created的数据 @0
- 测试保存$objectId为1, action为created的数据 @0
- 测试保存$objectId为1, action为efforta,评论不为空的数据 @1
- 测试保存$objectId为1, action为efforta,评论不为空的数据 @0

*/

$objectTypeList = array('', 'bug', 'task', 'effort', 'case');
$objectIdList   = array(0, 1, 100000);
$actionTypeList = array('', 'created', 'tested');
$commentList    = array('评论1');

$action = new actionTest();

r($action->saveIndexTest($objectTypeList[0], $objectIdList[0], $actionTypeList[0])) && p() && e('0');                    // 测试保存空数据
r($action->saveIndexTest($objectTypeList[1], $objectIdList[0], $actionTypeList[0])) && p() && e('0');                    // 测试保存$objectId为0, action为空的数据
r($action->saveIndexTest($objectTypeList[1], $objectIdList[1], $actionTypeList[0])) && p() && e('0');                    // 测试保存$objectId为1, action为空的数据
r($action->saveIndexTest($objectTypeList[1], $objectIdList[2], $actionTypeList[0])) && p() && e('0');                    // 测试保存$objectId为100000, action为空的数据
r($action->saveIndexTest($objectTypeList[1], $objectIdList[1], $actionTypeList[1])) && p() && e('1');                    // 测试保存$objectId为1, action为created的数据
r($action->saveIndexTest($objectTypeList[1], $objectIdList[2], $actionTypeList[1])) && p() && e('0');                    // 测试保存$objectId为100000, action为created的数据
r($action->saveIndexTest($objectTypeList[3], $objectIdList[1], $actionTypeList[1])) && p() && e('0');                    // 测试保存$objectId为1, action为created的数据
r($action->saveIndexTest($objectTypeList[4], $objectIdList[1], $actionTypeList[2], $commentList[0])) && p() && e('1');   // 测试保存$objectId为1, action为efforta,评论不为空的数据
r($action->saveIndexTest($objectTypeList[3], $objectIdList[1], $actionTypeList[2], $commentList[0])) && p() && e('0');   // 测试保存$objectId为1, action为efforta,评论不为空的数据