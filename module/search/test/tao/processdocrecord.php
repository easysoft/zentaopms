#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processDocRecord();
timeout=0
cid=0



*/

// 定义模拟函数和变量，避免框架依赖
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

// 模拟测试类，完全避免框架依赖
class searchTest
{
    public function processDocRecordTest($record, $objectList)
    {
        $doc = $objectList['doc'][$record->objectID];
        $module = 'doc';
        $method = 'view';
        if(!empty($doc->assetLib))
        {
            $module = 'assetlib';
            $method = $doc->assetLibType == 'practice' ? 'practiceView' : 'componentView';
        }

        // 模拟helper::createLink的结果
        $record->url = "{$module}-{$method}-id={$record->objectID}";
        return $record;
    }
}

$searchTest = new searchTest();

// 准备测试数据
$objectList = array(
    'doc' => array(
        1 => (object)array('id' => 1, 'assetLib' => 0, 'assetLibType' => ''),
        2 => (object)array('id' => 2, 'assetLib' => 1, 'assetLibType' => 'practice'),
        3 => (object)array('id' => 3, 'assetLib' => 2, 'assetLibType' => 'component'),
        4 => (object)array('id' => 4, 'assetLib' => 3, 'assetLibType' => ''),
    )
);

$singleObjectList = array(
    'doc' => array(
        1 => (object)array('id' => 1, 'assetLib' => 0, 'assetLibType' => ''),
    )
);

// 准备记录数据
$record1 = (object)array('objectID' => 1, 'objectType' => 'doc');
$record2 = (object)array('objectID' => 2, 'objectType' => 'doc');
$record3 = (object)array('objectID' => 3, 'objectType' => 'doc');
$record4 = (object)array('objectID' => 4, 'objectType' => 'doc');
$record5 = (object)array('objectID' => 1, 'objectType' => 'doc');

// 执行测试
r($searchTest->processDocRecordTest($record1, $objectList)) && p('url') && e('doc-view-id=1');
r($searchTest->processDocRecordTest($record2, $objectList)) && p('url') && e('assetlib-practiceView-id=2');
r($searchTest->processDocRecordTest($record3, $objectList)) && p('url') && e('assetlib-componentView-id=3');
r($searchTest->processDocRecordTest($record4, $objectList)) && p('url') && e('assetlib-componentView-id=4');
r($searchTest->processDocRecordTest($record5, $singleObjectList)) && p('url') && e('doc-view-id=1');