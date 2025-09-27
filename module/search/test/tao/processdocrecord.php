#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processDocRecord();
timeout=0
cid=0

- 执行searchTest模块的processDocRecordTest方法，参数是$record1, $objectList 属性url @doc-view-id=1
- 执行searchTest模块的processDocRecordTest方法，参数是$record2, $objectList 属性url @assetlib-practiceView-id=2
- 执行searchTest模块的processDocRecordTest方法，参数是$record3, $objectList 属性url @assetlib-componentView-id=3
- 执行searchTest模块的processDocRecordTest方法，参数是$record4, $objectList 属性url @assetlib-componentView-id=4
- 执行searchTest模块的processDocRecordTest方法，参数是$record5, $singleObjectList 属性url @doc-view-id=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

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