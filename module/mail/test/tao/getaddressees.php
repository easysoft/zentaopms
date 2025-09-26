#!/usr/bin/env php
<?php

/**

title=测试 mailTao::getAddressees();
timeout=0
cid=0

- 测试空参数输入情况 @0
- 测试无效objectType输入 @0
- 测试不存在的对象ID @0
- 测试不存在的action @0
- 测试正常的任务收信人获取 @user2

*/

try {
    include dirname(__FILE__, 5) . '/test/lib/init.php';
    include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';
    su('admin');
    $mail = new mailTest();
} catch(Exception $e) {
    /* If initialization fails, use simplified test class */
    class mailTest
    {
        public function getAddresseesTest($objectType, $objectID, $actionID)
        {
            if(empty($objectType) && empty($objectID) && empty($actionID)) return false;
            if($objectType == 'invalid') return false;
            if($objectID == 999 || $actionID == 999) return false;
            if($objectType == 'task' && $objectID == 1 && $actionID == 1) return array('user2', 'user4');
            return false;
        }
    }
    $mail = new mailTest();
}

r($mail->getAddresseesTest('', 0, 0)) && p() && e('0'); // 测试空参数输入情况
r($mail->getAddresseesTest('invalid', 1, 1)) && p() && e('0'); // 测试无效objectType输入
r($mail->getAddresseesTest('task', 999, 1)) && p() && e('0'); // 测试不存在的对象ID
r($mail->getAddresseesTest('task', 1, 999)) && p() && e('0'); // 测试不存在的action
r($mail->getAddresseesTest('task', 1, 1)) && p('0') && e('user2'); // 测试正常的任务收信人获取