#!/usr/bin/env php
<?php

/**

title=测试 groupModel->getObjectForAdminGroup();
timeout=0
cid=1

- 验证项目集属性programs @项目集1
- 验证项目属性projects @项目1
- 验证产品属性products @正常产品1|项目集1/正常产品2
- 验证执行属性executions @迭代1|阶段1|看板1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

zdTable('product')->gen(2);
zdTable('project')->config('project')->gen(5);

$group = new groupTest();

r($group->getObjectForAdminGroupTest()) && p('programs')   && e('项目集1');                     // 验证项目集
r($group->getObjectForAdminGroupTest()) && p('projects')   && e('项目1');                       // 验证项目
r($group->getObjectForAdminGroupTest()) && p('products')   && e('正常产品1|项目集1/正常产品2'); // 验证产品
r($group->getObjectForAdminGroupTest()) && p('executions') && e('迭代1|阶段1|看板1');           // 验证执行