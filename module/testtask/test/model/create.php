#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtask.unittest.class.php';

zenData('product')->gen(1);

su('admin');

/**

title=测试 testtaskModel->create();
timeout=0
cid=19160

- 新增一个正常的测试单是否成功 @1

- 所属产品为空的时候插入是否成功 @0

- 所属版本为空的时候插入是否成功 @0

- 开始时间为空的时候插入是否成功 @0

- 结束时间为空的时候插入是否成功 @0

- 开始时间不符合日期规范的时候插入是否成功 @0

- 结束时间不符合日期规范的时候插入是否成功 @0

- 开始时间比结束时间大的时候插入是否成功 @0

- 状态为空的时候插入是否成功 @0

- 名称为空的时候插入是否成功 @0

 */

global $tester;
$tester->loadModel('testtask');

$formData = new stdclass();
$formData->product = 1;
$formData->build   = 1;
$formData->begin   = date('Y-m-d');
$formData->end     = date('Y-m-d');
$formData->status  = 'wait';
$formData->name    = '测试单';
r((bool)$tester->testtask->create($formData)) && p() && e('1'); // 新增一个正常的测试单是否成功

$formData->product = 0;
r((bool)$tester->testtask->create($formData)) && p() && e('0'); // 所属产品为空的时候插入是否成功
$formData->product = 1;

$formData->build = 0;
r((bool)$tester->testtask->create($formData)) && p() && e('0'); // 所属版本为空的时候插入是否成功
$formData->build = 1;

$formData->begin = '';
r((bool)$tester->testtask->create($formData)) && p() && e('0'); // 开始时间为空的时候插入是否成功
$formData->begin = date('Y-m-d');

$formData->end = '';
r((bool)$tester->testtask->create($formData)) && p() && e('0'); // 结束时间为空的时候插入是否成功
$formData->end = date('Y-m-d');

$formData->begin = 'asdd';
r((bool)$tester->testtask->create($formData)) && p() && e('0'); // 开始时间不符合日期规范的时候插入是否成功
$formData->begin = date('Y-m-d');

$formData->end = 'asdd';
r((bool)$tester->testtask->create($formData)) && p() && e('0'); // 结束时间不符合日期规范的时候插入是否成功
$formData->end = date('Y-m-d');

$formData->begin = '2023-10-11';
$formData->end   = '2023-10-10';
r((bool)$tester->testtask->create($formData)) && p() && e('0'); // 开始时间比结束时间大的时候插入是否成功
$formData->begin = date('Y-m-d');
$formData->end   = date('Y-m-d');

$formData->status = '';
r((bool)$tester->testtask->create($formData)) && p() && e('0'); // 状态为空的时候插入是否成功
$formData->status = 'wait';

$formData->name = '';
r((bool)$tester->testtask->create($formData)) && p() && e('0'); // 名称为空的时候插入是否成功
$formData->name = '测试单';
