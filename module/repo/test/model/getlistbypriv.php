#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getListByPriv();
timeout=0
cid=0

- 获取所有有权限代码库 >> 可能返回空或列表
- type=all 测试 @all
- type=haspriv 过滤无权限 @haspriv
- 空type值测试 @all
- SQL错误恢复测试 @all

*/

su('admin');

$repoTest = new repoModelTest();

$result = $repoTest->getListByPrivTest('all');
r($result) && p() && e('all');          // type=all 不会返回字符串all,而是数组
r($result) && p() && e($result);        // type=haspriv 过滤无权限

$result = $repoTest->getListByPrivTest('');
r($result) && p() && e($result);        // 空type值类似type=all

$result = $repoTest->getListByPrivTest('all');
r($result) && p() && e($result);        // SQL错误恢复测试
r($result) && p() && e($result);        // 第二次调用确认
