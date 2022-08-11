#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/file.class.php';

/**

title=测试 fileModel->getSaveName();
cid=1
pid=1



*/

global $tester;
$tester->loadModel('file');
$file1  = $tester->file->getByID(1);
$file10 = $tester->file->getByID(10);
$file45 = $tester->file->getByID(45);

su('user10');
r($tester->file->checkPriv($file1))  && p() && e(1); // 查看用户user10是否有下载附件1的权限
r($tester->file->checkPriv($file10)) && p() && e(1); // 查看用户user10是否有下载附件10的权限
r($tester->file->checkPriv($file45)) && p() && e(0); // 查看用户user10是否有下载附件45的权限

su('dev38');
r($tester->file->checkPriv($file1))  && p() && e(1); // 查看用户dev38是否有下载附件1的权限
r($tester->file->checkPriv($file10)) && p() && e(1); // 查看用户dev38是否有下载附件10的权限
r($tester->file->checkPriv($file45)) && p() && e(0); // 查看用户dev38是否有下载附件45的权限
