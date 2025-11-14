#!/usr/bin/env php
<?php

/**

title=测试 bugZen::processImageForBatchCreate();
timeout=0
cid=15468

- 步骤1:测试uploadImage为null的情况 @0
- 步骤2:测试uploadImage为空字符串的情况 @0
- 步骤3:测试bugImagesFiles为空数组的情况 @0
- 步骤4:测试uploadImage不存在于bugImagesFiles中的情况 @0
- 步骤5:测试文件realpath不存在的情况 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$bugTest = new bugZenTest();

$bug = new stdClass();
$bug->id = 1;
$bug->steps = 'Test steps';

$emptyFilesArray = array();

$nonExistingFile = array(
    'test.jpg' => array(
        'pathname'  => 'test.jpg',
        'realpath'  => '/tmp/nonexisting_file_' . time() . '.jpg',
        'extension' => 'jpg',
        'size'      => 1024,
        'addedBy'   => '',
        'addedDate' => ''
    )
);

$invalidKeyFile = array(
    'other.jpg' => array(
        'pathname'  => 'other.jpg',
        'realpath'  => '/tmp/other_' . time() . '.jpg',
        'extension' => 'jpg',
        'size'      => 1024,
        'addedBy'   => '',
        'addedDate' => ''
    )
);

r(count($bugTest->processImageForBatchCreateTest($bug, null, $emptyFilesArray))) && p() && e('0'); // 步骤1:测试uploadImage为null的情况
r(count($bugTest->processImageForBatchCreateTest($bug, '', $emptyFilesArray))) && p() && e('0'); // 步骤2:测试uploadImage为空字符串的情况
r(count($bugTest->processImageForBatchCreateTest($bug, 'image.jpg', $emptyFilesArray))) && p() && e('0'); // 步骤3:测试bugImagesFiles为空数组的情况
r(count($bugTest->processImageForBatchCreateTest($bug, 'test.jpg', $invalidKeyFile))) && p() && e('0'); // 步骤4:测试uploadImage不存在于bugImagesFiles中的情况
r(count($bugTest->processImageForBatchCreateTest($bug, 'test.jpg', $nonExistingFile))) && p() && e('0'); // 步骤5:测试文件realpath不存在的情况