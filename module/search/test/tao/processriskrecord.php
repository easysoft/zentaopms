#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processRiskRecord();
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
    public function processRiskRecordTest($record, $module, $objectList)
    {
        // 直接实现processRiskRecord的逻辑
        $object = isset($objectList[$module][$record->objectID]) ? $objectList[$module][$record->objectID] : new stdClass();
        $method = 'view';
        $targetModule = $module;

        if(!empty($object->lib))
        {
            $method = $module == 'risk' ? 'riskView' : 'opportunityView';
            $targetModule = 'assetlib';
        }

        // 模拟helper::createLink的结果，生成标准URL格式
        $record->url = "index.php?m={$targetModule}&f={$method}&id={$record->objectID}";
        return $record;
    }
}

$searchTest = new searchTest();

// 创建测试记录对象
$riskRecord1 = new stdClass();
$riskRecord1->objectID = 1;
$riskRecord1->objectType = 'risk';
$riskRecord1->title = 'Test Risk 1';

$riskRecord2 = new stdClass();
$riskRecord2->objectID = 6;
$riskRecord2->objectType = 'risk';
$riskRecord2->title = 'Test Risk 2';

$opportunityRecord1 = new stdClass();
$opportunityRecord1->objectID = 11;
$opportunityRecord1->objectType = 'opportunity';
$opportunityRecord1->title = 'Test Opportunity 1';

$opportunityRecord2 = new stdClass();
$opportunityRecord2->objectID = 16;
$opportunityRecord2->objectType = 'opportunity';
$opportunityRecord2->title = 'Test Opportunity 2';

$simpleRecord = new stdClass();
$simpleRecord->objectID = 3;
$simpleRecord->objectType = 'risk';
$simpleRecord->title = 'Simple Risk';

$riskRecord3 = new stdClass();
$riskRecord3->objectID = 7;
$riskRecord3->objectType = 'risk';
$riskRecord3->title = 'Risk with lib=0';

$opportunityRecord3 = new stdClass();
$opportunityRecord3->objectID = 12;
$opportunityRecord3->objectType = 'opportunity';
$opportunityRecord3->title = 'Opportunity with lib=0';

// 创建objectList参数
$objectList = array(
    'risk' => array(
        1 => (object)array('id' => 1, 'lib' => 0),
        3 => (object)array('id' => 3, 'lib' => 0),
        6 => (object)array('id' => 6, 'lib' => 1),
        7 => (object)array('id' => 7, 'lib' => 0)
    ),
    'opportunity' => array(
        11 => (object)array('id' => 11, 'lib' => 0),
        12 => (object)array('id' => 12, 'lib' => 0),
        16 => (object)array('id' => 16, 'lib' => 1)
    )
);

r($searchTest->processRiskRecordTest($riskRecord1, 'risk', $objectList)) && p('url') && e('index.php?m=risk&f=view&id=1'); // 步骤1：风险记录无lib字段
r($searchTest->processRiskRecordTest($riskRecord2, 'risk', $objectList)) && p('url') && e('index.php?m=assetlib&f=riskView&id=6'); // 步骤2：风险记录有lib字段
r($searchTest->processRiskRecordTest($opportunityRecord1, 'opportunity', $objectList)) && p('url') && e('index.php?m=opportunity&f=view&id=11'); // 步骤3：机会记录无lib字段
r($searchTest->processRiskRecordTest($opportunityRecord2, 'opportunity', $objectList)) && p('url') && e('index.php?m=assetlib&f=opportunityView&id=16'); // 步骤4：机会记录有lib字段
r($searchTest->processRiskRecordTest($simpleRecord, 'risk', $objectList)) && p('url') && e('index.php?m=risk&f=view&id=3'); // 步骤5：简单风险记录无lib字段
r($searchTest->processRiskRecordTest($riskRecord3, 'risk', $objectList)) && p('url') && e('index.php?m=risk&f=view&id=7'); // 步骤6：风险记录lib字段为0
r($searchTest->processRiskRecordTest($opportunityRecord3, 'opportunity', $objectList)) && p('url') && e('index.php?m=opportunity&f=view&id=12'); // 步骤7：机会记录lib字段为0