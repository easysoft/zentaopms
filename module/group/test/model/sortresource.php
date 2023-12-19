#!/usr/bin/env php
<?php

/**

title=测试 groupModel->sortResource();
timeout=0
cid=1

- 检查program和personnel模块的排序
 - 属性2 @program
 - 属性3 @personnel
- 检查my模块方法的排序
 - 属性4 @project
 - 属性12 @audit

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

$group = new groupTest();
$resource = $group->sortResourceTest();

r(array_keys((array)$resource)) && p('2,3') && e('program,personnel');   // 检查program和personnel模块的排序
r(array_keys((array)$resource->my)) && p('4,12') && e('project,audit');  // 检查my模块方法的排序
