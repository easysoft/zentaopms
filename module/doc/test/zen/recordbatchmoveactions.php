#!/usr/bin/env php
<?php

/**

title=测试 docZen::recordBatchMoveActions();
timeout=0
cid=0

- 执行docTest模块的recordBatchMoveActionsTest方法，参数是createOldDocList  @1
- 执行docTest模块的recordBatchMoveActionsTest方法，参数是createOldDocList  @4
- 执行docTest模块的recordBatchMoveActionsTest方法，参数是createOldDocList  @4
- 执行docTest模块的recordBatchMoveActionsTest方法，参数是createOldDocList  @6
- 执行docTest模块的recordBatchMoveActionsTest方法，参数是createOldDocList  @11

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('doc')->gen(10);
zenData('action')->gen(0);

su('admin');

$docTest = new docZenTest();

function createOldDocList($docIDs, $libID) {
    $oldDocs = array();
    foreach($docIDs as $docID) {
        $doc = new stdClass();
        $doc->id = $docID;
        $doc->lib = $libID;
        $doc->title = "Doc {$docID}";
        $oldDocs[] = $doc;
    }
    return $oldDocs;
}

function createData($libID) {
    $data = new stdClass();
    $data->lib = $libID;
    return $data;
}

r($docTest->recordBatchMoveActionsTest(createOldDocList(array(1), 1), createData(2))) && p() && e('1');
r($docTest->recordBatchMoveActionsTest(createOldDocList(array(2, 3, 4), 1), createData(3))) && p() && e('4');
r($docTest->recordBatchMoveActionsTest(createOldDocList(array(), 1), createData(2))) && p() && e('4');
r($docTest->recordBatchMoveActionsTest(createOldDocList(array(5, 6), 2), createData(1))) && p() && e('6');
r($docTest->recordBatchMoveActionsTest(createOldDocList(array(7, 8, 9, 10, 11), 3), createData(4))) && p() && e('11');