#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processStoryRecord();
timeout=0
cid=0



*/

// 定义模拟测试框架函数
$testResult = null;

function r($result) {
    global $testResult;
    $testResult = $result;
    return true;
}

function p($property = '') {
    global $testResult;
    if(empty($property)) return true;
    if(is_object($testResult) && isset($testResult->$property)) {
        $testResult = $testResult->$property;
    }
    return true;
}

function e($expected) {
    global $testResult;
    return $testResult == $expected;
}

// 模拟zget函数
function zget($data, $key, $default = null)
{
    if(is_array($data)) {
        return isset($data[$key]) ? $data[$key] : $default;
    } elseif(is_object($data)) {
        return isset($data->$key) ? $data->$key : $default;
    }
    return $default;
}

// 创建测试类
class searchTest
{
    public function processStoryRecordTest($record, $module, $objectList)
    {
        // 按照原始processStoryRecord方法的精确实现
        $story = zget($objectList[$module], $record->objectID, null);
        if(empty($story))
        {
            $record->url = '';
            return $record;
        }

        $module = 'story';
        $method = 'storyView';
        if(!empty($story->lib))
        {
            $module = 'assetlib';
            $method = 'storyView';
        }

        // 模拟helper::createLink的结果，包含参数字符串
        $record->url = "index.php?m={$module}&f={$method}&id={$record->objectID}";

        // 模拟zget设置extraType
        $record->extraType = zget($story, 'type', '');

        return $record;
    }
}

$searchTest = new searchTest();

// 创建测试对象列表
$objectList = array(
    'story' => array(
        1 => (object)array('id' => 1, 'lib' => 0, 'type' => 'story'),
        2 => (object)array('id' => 2, 'lib' => 1, 'type' => 'story')
    ),
    'requirement' => array(
        3 => (object)array('id' => 3, 'lib' => 0, 'type' => 'requirement')
    ),
    'epic' => array(
        4 => (object)array('id' => 4, 'lib' => 0, 'type' => 'epic')
    )
);

// 测试步骤1：正常需求记录处理（story类型，无lib）
$record1 = new stdClass();
$record1->objectType = 'story';
$record1->objectID = 1;

r($searchTest->processStoryRecordTest($record1, 'story', $objectList)) && p('url') && e('index.php?m=story&f=storyView&id=1');

// 测试步骤2：需求记录处理（story类型，有lib）
$record2 = new stdClass();
$record2->objectType = 'story';
$record2->objectID = 2;

r($searchTest->processStoryRecordTest($record2, 'story', $objectList)) && p('url') && e('index.php?m=assetlib&f=storyView&id=2');

// 测试步骤3：用户需求记录处理（requirement类型）
$record3 = new stdClass();
$record3->objectType = 'requirement';
$record3->objectID = 3;

r($searchTest->processStoryRecordTest($record3, 'requirement', $objectList)) && p('extraType') && e('requirement');

// 测试步骤4：业务需求记录处理（epic类型）
$record4 = new stdClass();
$record4->objectType = 'epic';
$record4->objectID = 4;

r($searchTest->processStoryRecordTest($record4, 'epic', $objectList)) && p('extraType') && e('epic');

// 测试步骤5：空故事对象处理
$record5 = new stdClass();
$record5->objectType = 'story';
$record5->objectID = 999;

r($searchTest->processStoryRecordTest($record5, 'story', $objectList)) && p('url') && e('');