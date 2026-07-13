#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::convertApiError();
timeout=0
cid=0

- 执行ppmModel模块的convertApiErrorTest方法，参数是'401 Unauthorized'  @权限不足
- 执行ppmModel模块的convertApiErrorTest方法，参数是array  @权限不足
- 执行ppmModel模块的convertApiErrorTest方法，参数是'Another open merge request already exists for this source branch: !12'  @存在另外一个同样的合并请求在源项目分支中: ID12
- 执行ppmModel模块的convertApiErrorTest方法，参数是array  @存在另外一个同样的合并请求在源项目分支中: ID34
- 执行ppmModel模块的convertApiErrorTest方法，参数是'custom error'  @custom error

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$ppmModel = new ppmModelTest();

r($ppmModel->convertApiErrorTest('401 Unauthorized')) && p() && e('权限不足');
r($ppmModel->convertApiErrorTest(array('401 Unauthorized'))) && p() && e('权限不足');
r($ppmModel->convertApiErrorTest('Another open merge request already exists for this source branch: !12')) && p() && e('存在另外一个同样的合并请求在源项目分支中: ID12');
r($ppmModel->convertApiErrorTest(array('Another open merge request already exists for this source branch: !34'))) && p() && e('存在另外一个同样的合并请求在源项目分支中: ID34');
r($ppmModel->convertApiErrorTest('custom error')) && p() && e('custom error');