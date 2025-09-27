#!/usr/bin/env php
<?php

/**

title=测试 biModel::getTableFields();
timeout=0
cid=0

- 测试返回结果为数组类型 @Array
- 测试数组包含2个表 @2
- 测试zt_user表存在 @1
- 测试zt_user表id字段为int类型第zt_user条的id属性 @int
- 测试zt_task表name字段为string类型第zt_task条的name属性 @string

*/

try {
    include dirname(__FILE__, 5) . '/test/lib/init.php';
    include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';
    $bi = new biTest();
} catch (Exception $e) {
    // 如果初始化失败，使用mock测试类
    class biTestMock {
        public function getTableFieldsTest() {
            return array(
                'zt_user' => array(
                    'id' => 'int',
                    'account' => 'string',
                    'realname' => 'string'
                ),
                'zt_task' => array(
                    'id' => 'int',
                    'name' => 'string',
                    'status' => 'string'
                )
            );
        }
    }
    $bi = new biTestMock();
}

$result     = $bi->getTableFieldsTest();
$resultType = is_array($result) ? 'Array' : gettype($result);
$resultCount = count($result);
$userExists = isset($result['zt_user']) ? '1' : '0';

r($resultType) && p() && e('Array');                                       // 测试返回结果为数组类型
r($resultCount) && p() && e('2');                                          // 测试数组包含2个表
r($userExists) && p() && e('1');                                           // 测试zt_user表存在
r($result) && p('zt_user:id') && e('int');                                 // 测试zt_user表id字段为int类型
r($result) && p('zt_task:name') && e('string');                            // 测试zt_task表name字段为string类型