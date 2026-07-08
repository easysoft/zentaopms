#!/usr/bin/env php
<?php

/**

title=测试 mrModel::convertApiError();
timeout=0
cid=17240

- 执行mrModel模块的convertApiError方法，参数是array  @存在另外一个同样的合并请求在源项目分支中: ID123
- 执行mrModel模块的convertApiError方法，参数是"You can't use same project/branch for source and target"  @源项目分支与目标项目分支不能相同
- 执行mrModel模块的convertApiError方法，参数是'Unknown error message'  @Unknown error message
- 执行mrModel模块的convertApiError方法，参数是''  @0
- 执行mrModel模块的convertApiError方法，参数是'403 Forbidden'  @权限不足
- 执行mrModel模块的convertApiError方法，参数是'401 Unauthorized'  @权限不足
- 执行mrModel模块的convertApiError方法，参数是array  @存在另外一个同样的合并请求在源项目分支中

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

$mrModel = $tester->loadModel('mr');

r($mrModel->convertApiError(array('Another open merge request already exists for this source branch: !123'))) && p() && e('存在另外一个同样的合并请求在源项目分支中: ID123');
r($mrModel->convertApiError("You can't use same project/branch for source and target")) && p() && e('源项目分支与目标项目分支不能相同');
r($mrModel->convertApiError('Unknown error message')) && p() && e('Unknown error message');
r($mrModel->convertApiError('')) && p() && e('0');
r($mrModel->convertApiError('403 Forbidden')) && p() && e('权限不足');
r($mrModel->convertApiError('401 Unauthorized')) && p() && e('权限不足');
r($mrModel->convertApiError(array('pull request already exists for these targets and source'))) && p() && e('存在另外一个同样的合并请求在源项目分支中');