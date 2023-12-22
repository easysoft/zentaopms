#!/usr/bin/env php
<?php

/**

title=mrModel->isClickable();
timeout=0
cid=1

- 未同步合并请求检查编辑权限 @0
- 已同步合并请求检查编辑权限 @1
- 编辑被禁用的合并请求检查编辑权限 @0
- 编辑未被禁用的合并请求检查删除权限 @1
- 删除被禁用的合并请求检查删除权限 @0
- 删除未被禁用的合并请求检查合并权限 @1
- 检查其他权限 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');
global $tester;
$mrModel = $tester->loadModel('mr');

$mr = new stdclass();
$mr->synced    = 0;
$mr->canEdit   = '';
$mr->canDelete = '';

r($mrModel->isClickable($mr, 'edit')) && p() && e('0'); // 未同步合并请求检查编辑权限

$mr->synced = 1;
r($mrModel->isClickable($mr, 'edit')) && p() && e('1'); // 已同步合并请求检查编辑权限

$mr->canEdit = 'disabled';
r($mrModel->isClickable($mr, 'edit')) && p() && e('0'); // 编辑被禁用的合并请求检查编辑权限

$mr->canEdit = '';
r($mrModel->isClickable($mr, 'delete')) && p() && e('1'); // 编辑未被禁用的合并请求检查删除权限

$mr->canDelete = 'disabled';
r($mrModel->isClickable($mr, 'delete')) && p() && e('0'); // 删除被禁用的合并请求检查删除权限

$mr->canDelete = '';
r($mrModel->isClickable($mr, 'merge')) && p() && e('1'); // 删除未被禁用的合并请求检查合并权限

r($mrModel->isClickable($mr, 'other')) && p() && e('1'); // 检查其他权限