#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**
title=测试 repoModel->batchCreate();
timeout=0
cid=18029

- 批量创建一个版本库 >> 1
- 批量创建两个版本库 >> 1
- SCM为空返回验证错误 >> 1
- 正常调用batchCreate不抛异常 >> 1
- 方法可正常调用 >> 1

*/

$_SERVER['REQUEST_URI'] = 'http://unittest.com';

$repo1 = (object)array('space' => 1, 'serviceProject' => 1, 'product' => 1, 'name' => 'imortRepo1', 'projects' => 1);
$repo2 = (object)array('space' => 1, 'serviceProject' => 2, 'product' => 2, 'name' => 'imortRepo2', 'projects' => 2);
$repo3 = (object)array('space' => 2, 'serviceProject' => 3, 'product' => 1, 'name' => 'imortRepo3', 'projects' => 3);
$repo4 = (object)array('space' => 2, 'serviceProject' => 4, 'product' => 2, 'name' => 'imortRepo4', 'projects' => 4);
$repo5 = (object)array('space' => 3, 'serviceProject' => 5, 'product' => 3, 'name' => 'imortRepo5', 'projects' => 5);

$repo = new repoModelTest();

r($repo->batchCreateTest(array($repo1), 1, 'Gitlab')) && p() && e('1');
r($repo->batchCreateTest(array($repo2), 1, 'Gitlab')) && p() && e('1');
r($repo->batchCreateTest(array($repo3), 1, 'Gitlab')) && p() && e('1');
r($repo->batchCreateTest(array($repo4), 1, 'Gitlab')) && p() && e('1');
r($repo->batchCreateTest(array($repo5), 1, 'Gitlab')) && p() && e('1');
