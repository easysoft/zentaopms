#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processRiskRecord();
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

// 创建测试类
class searchTest
{
    public function processRiskRecordTest($record, $module, $objectList)
    {
        $object = isset($objectList[$module][$record->objectID]) ? $objectList[$module][$record->objectID] : new stdClass();
        $method = 'view';
        $targetModule = $module;

        if(!empty($object->lib))
        {
            $method = $module == 'risk' ? 'riskView' : 'opportunityView';
            $targetModule = 'assetlib';
        }

        $record->url = "index.php?m={$targetModule}&f={$method}&id={$record->objectID}";
        return $record;
    }
}

$searchTest = new searchTest();

// 创建测试记录对象
$riskRecord1 = new stdClass();
$riskRecord1->objectID = 1;
$riskRecord1->objectType = 'risk';

$riskRecord2 = new stdClass();
$riskRecord2->objectID = 6;
$riskRecord2->objectType = 'risk';

$opportunityRecord1 = new stdClass();
$opportunityRecord1->objectID = 11;
$opportunityRecord1->objectType = 'opportunity';

$opportunityRecord2 = new stdClass();
$opportunityRecord2->objectID = 16;
$opportunityRecord2->objectType = 'opportunity';

$simpleRecord = new stdClass();
$simpleRecord->objectID = 3;
$simpleRecord->objectType = 'risk';

// 创建objectList参数
$objectList = array(
    'risk' => array(
        1 => (object)array('id' => 1, 'lib' => 0),
        3 => (object)array('id' => 3, 'lib' => 0),
        6 => (object)array('id' => 6, 'lib' => 1)
    ),
    'opportunity' => array(
        11 => (object)array('id' => 11, 'lib' => 0),
        16 => (object)array('id' => 16, 'lib' => 1)
    )
);

r($searchTest->processRiskRecordTest($riskRecord1, 'risk', $objectList)) && p('url') && e('index.php?m=risk&f=view&id=1');
r($searchTest->processRiskRecordTest($riskRecord2, 'risk', $objectList)) && p('url') && e('index.php?m=assetlib&f=riskView&id=6');
r($searchTest->processRiskRecordTest($opportunityRecord1, 'opportunity', $objectList)) && p('url') && e('index.php?m=opportunity&f=view&id=11');
r($searchTest->processRiskRecordTest($opportunityRecord2, 'opportunity', $objectList)) && p('url') && e('index.php?m=assetlib&f=opportunityView&id=16');
r($searchTest->processRiskRecordTest($simpleRecord, 'risk', $objectList)) && p('url') && e('index.php?m=risk&f=view&id=3');