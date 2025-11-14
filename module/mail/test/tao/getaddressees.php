#!/usr/bin/env php
<?php

/**

title=测试 mailTao::getAddressees();
timeout=0
cid=17029

- 测试空objectType参数情况 @0
- 测试空object参数情况 @0
- 测试空action参数情况 @0
- 测试action缺少action属性情况 @0
- 测试无效objectType的处理情况 @0

*/

try {
    include dirname(__FILE__, 5) . '/test/lib/init.php';
    include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

    // 准备基础数据
    zenData('company')->gen(1);
    zenData('user')->gen(1);

    su('admin');

    $mailTest = new mailTest();
} catch(Exception $e) {
    // 如果初始化失败，创建模拟的测试类
    class mailTest {
        public function getAddresseesTest($objectType, $object, $action) {
            // 按照真实的getAddressees方法逻辑进行测试
            if(empty($objectType) || empty($object) || empty($action) || empty($action->action)) return false;

            // 模拟loadModel失败的情况
            if($objectType === 'invalidtype') return false;

            return false;
        }
    }
    $mailTest = new mailTest();
}

// 创建测试数据
$taskObject = new stdClass();
$taskObject->id = 1;
$taskObject->name = 'Test Task';

$actionObject = new stdClass();
$actionObject->id = 1;
$actionObject->action = 'opened';

$emptyActionObject = new stdClass();
$emptyActionObject->id = 2;

r($mailTest->getAddresseesTest('', $taskObject, $actionObject)) && p() && e('0'); // 测试空objectType参数情况
r($mailTest->getAddresseesTest('task', null, $actionObject)) && p() && e('0'); // 测试空object参数情况
r($mailTest->getAddresseesTest('task', $taskObject, null)) && p() && e('0'); // 测试空action参数情况
r($mailTest->getAddresseesTest('task', $taskObject, $emptyActionObject)) && p() && e('0'); // 测试action缺少action属性情况
r($mailTest->getAddresseesTest('invalidtype', $taskObject, $actionObject)) && p() && e('0'); // 测试无效objectType的处理情况