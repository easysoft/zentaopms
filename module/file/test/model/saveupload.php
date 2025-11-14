#!/usr/bin/env php
<?php

/**

title=测试 fileModel::saveUpload();
timeout=0
cid=16530

- 执行fileTest模块的saveUploadTest方法，参数是'', 0, '', $noFiles, $labels1  @empty
- 执行fileTest模块的saveUploadTest方法，参数是'task', 1, '', $errorFile, $labels2  @empty
- 执行fileTest模块的saveUploadTest方法，参数是'task', 2, '', $emptyFile, $labels3  @empty
- 执行fileTest模块的saveUploadTest方法，参数是'task', 3, '', $noNameFile, $labels4  @empty
- 执行fileTest模块的saveUploadTest方法，参数是'story', 4, 'attachment', $basicParams, $labels5  @false

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/file.unittest.class.php';

zendata('file')->loadYaml('file_saveupload', false, 2)->gen(3);

su('admin');

$fileTest = new fileTest();

$noFiles = array(
    'name' => array(),
    'type' => array(),
    'tmp_name' => array(),
    'error' => array(),
    'size' => array()
);
$labels1 = array();

$errorFile = array(
    'name' => array('test.txt'),
    'type' => array('text/plain'),
    'tmp_name' => array(''),
    'error' => array(1),
    'size' => array(1024)
);
$labels2 = array('error file');

$emptyFile = array(
    'name' => array('empty.txt'),
    'type' => array('text/plain'),
    'tmp_name' => array('/tmp/test'),
    'error' => array(0),
    'size' => array(0)
);
$labels3 = array('empty file');

$noNameFile = array(
    'name' => array(''),
    'type' => array(''),
    'tmp_name' => array(''),
    'error' => array(0),
    'size' => array(0)
);
$labels4 = array('');

$basicParams = array(
    'name' => array('test.txt'),
    'type' => array('text/plain'),
    'tmp_name' => array('/tmp/test'),
    'error' => array(0),
    'size' => array(1024)
);
$labels5 = array('test file');

r($fileTest->saveUploadTest('', 0, '', $noFiles, $labels1)) && p() && e('empty');
r($fileTest->saveUploadTest('task', 1, '', $errorFile, $labels2)) && p() && e('empty');
r($fileTest->saveUploadTest('task', 2, '', $emptyFile, $labels3)) && p() && e('empty');
r($fileTest->saveUploadTest('task', 3, '', $noNameFile, $labels4)) && p() && e('empty');
r($fileTest->saveUploadTest('story', 4, 'attachment', $basicParams, $labels5)) && p() && e('false');