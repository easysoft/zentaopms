#!/usr/bin/env php
<?php

/**
 *
title=测试 fileModel->getUploadByUID();
cid=16539

- 测试单个uid，有文件的情况
 - 第1条文件标题 @文件标题1
- 测试多个uid的情况
 - 第1条文件标题 @文件标题3
- 测试uid为空的情况 @0
- 测试uid对应的文件不存在的情况 @0
- 测试传入数组uid的情况
 - 第1条文件标题 @文件标题5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/file.unittest.class.php';
su('admin');

zenData('file')->gen(20);

$uid1 = '98390890341';
$uid2 = '98390890342';
$uid3 = '98390890343';

$albums1 = array('used' => array($uid1 => array('1' => '1', '2' => '2')));
$albums2 = array('used' => array($uid2 => array('3' => '3', '4' => '4')));
$albums3 = array('used' => array($uid3 => array('101' => '101', '102' => '102')));         // 不存在的文件ID
$albums5 = array('used' => array($uid1 => array('5' => '5'), $uid2 => array('6' => '6'))); // 多个uid

$file = new fileTest();

r($file->getUploadByUIDTest($uid1, $albums1)) && p('1') && e('文件标题1');               // 测试单个uid，有文件的情况
r($file->getUploadByUIDTest($uid2, $albums2)) && p('3') && e('文件标题3');               // 测试多个uid的情况
r($file->getUploadByUIDTest('', $albums1))    && p() && e('0');                          // 测试uid为空的情况
r($file->getUploadByUIDTest($uid3, $albums3)) && p() && e('0');                          // 测试uid对应的文件不存在的情况
r($file->getUploadByUIDTest(array($uid1, $uid2), $albums5)) && p('5') && e('文件标题5'); // 测试传入数组uid的情况