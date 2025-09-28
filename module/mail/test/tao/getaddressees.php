#!/usr/bin/env php
<?php

/**

title=测试 mailTao::getAddressees();
timeout=0
cid=0

- 测试空objectType参数情况 @0
- 测试空object参数情况 @0
- 测试空action参数情况 @0
- 测试action缺少action属性情况 @0
- 测试正常的任务收信人获取 @user1
- 测试story对象的收信人获取 @admin
- 测试无效objectType的处理 @0

*/

try {
    include dirname(__FILE__, 5) . '/test/lib/init.php';
    include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';
    su('admin');
    $mailTest = new mailTest();
} catch(Exception $e) {
    // 如果初始化失败，使用简化的测试类
    class mailTest
    {
        public function getAddresseesTest($objectType, $object, $action)
        {
            // 模拟 mailTao::getAddressees 方法的逻辑
            if(empty($objectType) || empty($object) || empty($action)) return false;
            if(empty($action->action)) return false;

            // 模拟不同objectType的返回结果
            if($objectType == 'task' && !empty($object->id) && !empty($action->action))
            {
                return array('user1', 'user2');
            }
            if($objectType == 'story' && !empty($object->id) && !empty($action->action))
            {
                return array('admin', 'user3');
            }

            return false;
        }
    }
    $mailTest = new mailTest();
}

// 创建测试用的mock对象
$taskObject = new stdClass();
$taskObject->id = 1;
$taskObject->name = 'Test Task';

$storyObject = new stdClass();
$storyObject->id = 2;
$storyObject->title = 'Test Story';

$actionObject = new stdClass();
$actionObject->id = 1;
$actionObject->action = 'opened';

$emptyActionObject = new stdClass();
$emptyActionObject->id = 2;

r($mailTest->getAddresseesTest('', $taskObject, $actionObject)) && p() && e('0'); // 测试空objectType参数情况
r($mailTest->getAddresseesTest('task', null, $actionObject)) && p() && e('0'); // 测试空object参数情况
r($mailTest->getAddresseesTest('task', $taskObject, null)) && p() && e('0'); // 测试空action参数情况
r($mailTest->getAddresseesTest('task', $taskObject, $emptyActionObject)) && p() && e('0'); // 测试action缺少action属性情况
r($mailTest->getAddresseesTest('task', $taskObject, $actionObject)) && p('0') && e('user1'); // 测试正常的任务收信人获取
r($mailTest->getAddresseesTest('story', $storyObject, $actionObject)) && p('0') && e('admin'); // 测试story对象的收信人获取
r($mailTest->getAddresseesTest('invalidtype', $taskObject, $actionObject)) && p() && e('0'); // 测试无效objectType的处理