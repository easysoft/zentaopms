#!/usr/bin/env php
<?php

/**

title=测试 projectZen::buildCreateForm();
timeout=0
cid=0

- 测试scrum模型、无项目集ID、无复制项目的创建表单 >> 创建项目
- 测试waterfall模型、有项目集ID的创建表单 >> waterfall,1
- 测试kanban模型的创建表单(验证ACL列表切换) >> kanban,1
- 测试带复制项目ID的创建表单 >> 1,1
- 测试带extra参数(productID和branchID)的创建表单 >> 1,2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

zenData('product')->gen(0);
zenData('program')->gen(0);
zenData('project')->gen(10);
zenData('user')->gen(10);

su('admin');

$projectTest = new projectzenTest();

r($projectTest->buildCreateFormTest('scrum', 0, 0, '')) && p('title') && e('创建项目');
r($projectTest->buildCreateFormTest('waterfall', 1, 0, '')) && p('model,programID') && e('waterfall,1');
r($projectTest->buildCreateFormTest('kanban', 1, 0, '')) && p('model,aclChanged') && e('kanban,1');
r($projectTest->buildCreateFormTest('scrum', 1, 1, '')) && p('copyProjectID,hasCopyProject') && e('1,1');
r($projectTest->buildCreateFormTest('scrum', 1, 0, 'productID=1,branchID=2')) && p('productID,branchID') && e('1,2');