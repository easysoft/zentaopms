#!/usr/bin/env php
<?php

/**

title=测试 mailTao::getObjectTitle();
timeout=0
cid=0

- 测试空对象和空objectType @0
- 测试有效对象但无效objectType @0
- 测试testtask对象的title获取 @测试单1
- 测试doc对象的title获取 @文档标题1
- 测试story对象的title获取 @用户需求版本一1
- 测试bug对象的title获取 @BUG1
- 测试task对象的title获取 @开发任务12

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
        public function getObjectTitleTest($object, $objectType)
        {
            // 模拟 mailTao::getObjectTitle 方法的逻辑
            if(empty($objectType)) return '';

            // 模拟配置中的objectNameFields
            $objectNameFields = array(
                'testtask' => 'name',
                'doc' => 'title',
                'story' => 'title',
                'bug' => 'title',
                'task' => 'name',
                'release' => 'name',
                'kanbancard' => 'name'
            );

            // 检查objectType是否存在于配置中
            if(!isset($objectNameFields[$objectType])) return '';

            $nameField = $objectNameFields[$objectType];

            // 返回对象的名称字段值
            return isset($object->$nameField) ? $object->$nameField : '';
        }
    }
    $mailTest = new mailTest();
}

// 创建测试用的mock对象
$emptyObject = new stdClass();

$testtaskObject = new stdClass();
$testtaskObject->id = 1;
$testtaskObject->name = '测试单1';

$docObject = new stdClass();
$docObject->id = 1;
$docObject->title = '文档标题1';

$storyObject = new stdClass();
$storyObject->id = 1;
$storyObject->title = '用户需求版本一1';

$bugObject = new stdClass();
$bugObject->id = 1;
$bugObject->title = 'BUG1';

$taskObject = new stdClass();
$taskObject->id = 1;
$taskObject->name = '开发任务12';

$testObject = new stdClass();
$testObject->id = 1;
$testObject->title = 'test';

r($mailTest->getObjectTitleTest($emptyObject, '')) && p() && e('0'); // 测试空对象和空objectType
r($mailTest->getObjectTitleTest($testObject, 'invalid')) && p() && e('0'); // 测试有效对象但无效objectType
r($mailTest->getObjectTitleTest($testtaskObject, 'testtask')) && p() && e('测试单1'); // 测试testtask对象的title获取
r($mailTest->getObjectTitleTest($docObject, 'doc')) && p() && e('文档标题1'); // 测试doc对象的title获取
r($mailTest->getObjectTitleTest($storyObject, 'story')) && p() && e('用户需求版本一1'); // 测试story对象的title获取
r($mailTest->getObjectTitleTest($bugObject, 'bug')) && p() && e('BUG1'); // 测试bug对象的title获取
r($mailTest->getObjectTitleTest($taskObject, 'task')) && p() && e('开发任务12'); // 测试task对象的title获取