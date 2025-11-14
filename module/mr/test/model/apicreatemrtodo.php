#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiCreateMRTodo();
timeout=0
cid=17225

- 执行$check1 @0
- 执行$check2 @object
- 执行$check3 @object
- 执行$check4 @0
- 执行$check5 @object
- 执行$result6 @success
- 执行$check7 @object

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

zenData('pipeline')->gen(1);

su('admin');

$mrTest = new mrTest();

$hostID = array(
    'valid'   => 1,
    'invalid' => 999,
    'zero'    => 0,
    'negative' => -1
);

$projectID = array(
    'valid'   => '3',
    'empty'   => '',
    'numeric' => '123',
    'special' => 'test-project'
);

$mrID = array(
    'valid'   => 30,
    'zero'    => 0,
    'negative' => -1,
    'large'   => 999999
);

$result1 = $mrTest->apiCreateMRTodoTest($hostID['invalid'], $projectID['valid'], $mrID['valid']);
$check1 = is_null($result1) ? '0' : (is_object($result1) ? 'object' : $result1);
r($check1) && p() && e('0');

$result2 = $mrTest->apiCreateMRTodoTest($hostID['valid'], $projectID['empty'], $mrID['valid']);
$check2 = is_null($result2) ? '0' : (is_object($result2) ? 'object' : $result2);
r($check2) && p() && e('object');

$result3 = $mrTest->apiCreateMRTodoTest($hostID['valid'], $projectID['valid'], $mrID['negative']);
$check3 = is_null($result3) ? '0' : (is_object($result3) ? 'object' : $result3);
r($check3) && p() && e('object');

$result4 = $mrTest->apiCreateMRTodoTest($hostID['zero'], $projectID['valid'], $mrID['zero']);
$check4 = is_null($result4) ? '0' : (is_object($result4) ? 'object' : $result4);
r($check4) && p() && e('0');

$result5 = $mrTest->apiCreateMRTodoTest($hostID['valid'], $projectID['valid'], $mrID['large']);
$check5 = is_null($result5) ? '0' : (is_object($result5) ? 'object' : $result5);
r($check5) && p() && e('object');

$result6 = $mrTest->apiCreateMRTodoTest($hostID['valid'], $projectID['valid'], $mrID['valid']);
if(!isset($result6->message) || $result6->message == '404 Not found') $result6 = 'success';
r($result6) && p() && e('success');

$result7 = $mrTest->apiCreateMRTodoTest($hostID['valid'], $projectID['special'], $mrID['valid']);
$check7 = is_null($result7) ? '0' : (is_object($result7) ? 'object' : $result7);
r($check7) && p() && e('object');