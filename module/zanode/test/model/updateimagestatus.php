#!/usr/bin/env php
<?php

/**

title=测试 zanodeModel::updateImageStatus();
timeout=0
cid=19809

- 执行zanode模块的updateImageStatusTest方法，参数是1, $postData1
 - 属性status @failed
 - 属性path @/home/devops/zagent/download/ubuntu20.04.qcow2
- 执行zanode模块的updateImageStatusTest方法，参数是2, $postData2 属性status @success
- 执行zanode模块的updateImageStatusTest方法，参数是3, $postData3 属性path @/new/path/to/image.qcow2
- 执行zanode模块的updateImageStatusTest方法，参数是4, $postData4
 - 属性status @ready
 - 属性path @/complete/path/image.qcow2
- 执行zanode模块的updateImageStatusTest方法，参数是999, $postData5  @0
- 执行zanode模块的updateImageStatusTest方法，参数是5, $postData6 属性status @pending

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanode.unittest.class.php';

$table = zenData('image');
$table->id->range('1-10');
$table->host->range('1{2},2{3},3{5}');
$table->name->range('test-image-1,test-image-2,test-image-3,test-image-4,test-image-5');
$table->status->range('creating,ready,failed,success,pending');
$table->path->range('[]{3},/path/to/image1,/path/to/image2');
$table->osName->range('Ubuntu20.04{5},CentOS7{3},Windows10{2}');
$table->from->range('zentao{5},snapshot{3},backup{2}');
$table->createdBy->range('admin{5},user1{3},user2{2}');
$table->gen(10);

zenData('user')->gen(5);
su('admin');

$zanode = new zanodeTest();

$postData1 = new stdclass();
$postData1->status = 'failed';
$postData1->path = '/home/devops/zagent/download/ubuntu20.04.qcow2';
r($zanode->updateImageStatusTest(1, $postData1)) && p('status,path') && e('failed,/home/devops/zagent/download/ubuntu20.04.qcow2');

$postData2 = new stdclass();
$postData2->status = 'success';
r($zanode->updateImageStatusTest(2, $postData2)) && p('status') && e('success');

$postData3 = new stdclass();
$postData3->path = '/new/path/to/image.qcow2';
r($zanode->updateImageStatusTest(3, $postData3)) && p('path') && e('/new/path/to/image.qcow2');

$postData4 = new stdclass();
$postData4->status = 'ready';
$postData4->path = '/complete/path/image.qcow2';
$postData4->fileSize = 1024.5;
r($zanode->updateImageStatusTest(4, $postData4)) && p('status,path') && e('ready,/complete/path/image.qcow2');

$postData5 = new stdclass();
$postData5->status = 'deleted';
r($zanode->updateImageStatusTest(999, $postData5)) && p() && e('0');

$postData6 = new stdclass();
$postData6->status = 'pending';
r($zanode->updateImageStatusTest(5, $postData6)) && p('status') && e('pending');