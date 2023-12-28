#!/usr/bin/env php
<?php

/**

title=测试 zahostModel->isClickable();
timeout=0
cid=1

- 测试不是正常地址得到的结果 @1
- 测试不是正常地址得到的结果 @0
- 测试不是正常地址得到的结果 @0
- 测试不是正常地址得到的结果 @1
- 测试不是正常地址得到的结果 @1
- 测试不是正常地址得到的结果 @1
- 测试不是正常地址得到的结果 @0
- 测试不是正常地址得到的结果 @1
- 测试不是正常地址得到的结果 @0
- 测试不是正常地址得到的结果 @1
- 测试不是正常地址得到的结果 @1
- 测试不是正常地址得到的结果 @1
- 测试不是正常地址得到的结果 @0
- 测试不是正常地址得到的结果 @1
- 测试不是正常地址得到的结果 @0
- 测试不是正常地址得到的结果 @0
- 测试不是正常地址得到的结果 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zahost.class.php';
su('admin');

$action = array('delete', 'browseImage', 'cancelDownload', 'downloadImage');

$object = new stdclass();
$object->canDelete = true;

$zahost = new zahostTest();
r($zahost->isClickable($object, $action[0])) && p() && e('1');  //测试不是正常地址得到的结果

$object->status = 'wait';
r($zahost->isClickable($object, $action[1])) && p() && e('0');  //测试不是正常地址得到的结果
r($zahost->isClickable($object, $action[2])) && p() && e('0');  //测试不是正常地址得到的结果
r($zahost->isClickable($object, $action[3])) && p() && e('1');  //测试不是正常地址得到的结果

$object->status = 'created';
r($zahost->isClickable($object, $action[1])) && p() && e('1');  //测试不是正常地址得到的结果
r($zahost->isClickable($object, $action[2])) && p() && e('1');  //测试不是正常地址得到的结果
r($zahost->isClickable($object, $action[3])) && p() && e('0');  //测试不是正常地址得到的结果

$object->status = 'notDownloaded';
r($zahost->isClickable($object, $action[1])) && p() && e('1');  //测试不是正常地址得到的结果
r($zahost->isClickable($object, $action[2])) && p() && e('0');  //测试不是正常地址得到的结果
r($zahost->isClickable($object, $action[3])) && p() && e('1');  //测试不是正常地址得到的结果

$object->status = 'inprogress';
r($zahost->isClickable($object, $action[1])) && p() && e('1');  //测试不是正常地址得到的结果
r($zahost->isClickable($object, $action[2])) && p() && e('1');  //测试不是正常地址得到的结果
r($zahost->isClickable($object, $action[3])) && p() && e('0');  //测试不是正常地址得到的结果

$object->status = 'completed';
r($zahost->isClickable($object, $action[1])) && p() && e('1');  //测试不是正常地址得到的结果
r($zahost->isClickable($object, $action[2])) && p() && e('0');  //测试不是正常地址得到的结果
r($zahost->isClickable($object, $action[3])) && p() && e('0');  //测试不是正常地址得到的结果

$object->from = 'user';
r($zahost->isClickable($object, $action[3])) && p() && e('0');  //测试不是正常地址得到的结果
