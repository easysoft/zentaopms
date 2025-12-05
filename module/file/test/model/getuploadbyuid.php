#!/usr/bin/env php
<?php

/**
 *
title=测试 fileModel->getUploadByUID();
cid=16539

- 测试单个uid，有文件的情况
 - 第1条文件ID @1
 - 第1条文件标题 @文件标题1
 - 第2条文件ID @2
 - 第2条文件标题 @文件标题2
- 测试多个uid的情况
 - 第3条文件ID @3
 - 第3条文件标题 @文件标题3
 - 第4条文件ID @4
 - 第4条文件标题 @文件标题4
- 测试uid为空的情况 @0
- 测试uid对应的文件不存在的情况 @0
- 测试SESSION中没有used标记的情况 @0
- 测试传入数组uid的情况
 - 第5条文件ID @5
 - 第5条文件标题 @文件标题5
 - 第6条文件ID @6
 - 第6条文件标题 @文件标题6

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/file.unittest.class.php';
su('admin');

zenData('file')->gen(20);

$uid1 = '98390890341';
$uid2 = '98390890342';
$uid3 = '98390890343';
$uid4 = '98390890344';

$albums1 = array('used' => array($uid1 => array('1' => '1', '2' => '2')));
$albums2 = array('used' => array($uid2 => array('3' => '3', '4' => '4')));
$albums3 = array('used' => array($uid3 => array('101' => '101', '102' => '102'))); // 不存在的文件ID
$albums4 = array('used' => array($uid4 => array())); // 空的used数组
$albums5 = array('used' => array($uid1 => array('5' => '5'), $uid2 => array('6' => '6'))); // 多个uid

$file = new fileTest();

$result1 = $file->getUploadByUIDTest($uid1, $albums1);
r($result1[1]) && p() && e('文件标题1'); // 测试单个uid，有文件的情况 - 第1条文件ID
r($result1[2]) && p() && e('文件标题2'); // 测试单个uid，有文件的情况 - 第2条文件ID

$result2 = $file->getUploadByUIDTest($uid2, $albums2);
r($result2[3]) && p() && e('文件标题3'); // 测试多个uid的情况 - 第3条文件ID
r($result2[4]) && p() && e('文件标题4'); // 测试多个uid的情况 - 第4条文件ID

r($file->getUploadByUIDTest('', $albums1))    && p() && e('0'); // 测试uid为空的情况
r($file->getUploadByUIDTest($uid3, $albums3)) && p() && e('0'); // 测试uid对应的文件不存在的情况
r($file->getUploadByUIDTest($uid4, $albums4)) && p() && e('0'); // 测试SESSION中没有used标记的情况

$result5 = $file->getUploadByUIDTest(array($uid1, $uid2), $albums5);
r($result5[5]) && p() && e('文件标题5'); // 测试传入数组uid的情况 - 第5条文件ID
r($result5[6]) && p() && e('文件标题6'); // 测试传入数组uid的情况 - 第6条文件ID