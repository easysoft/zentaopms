#!/usr/bin/env php
<?php
/**

title=测试 docModel->getSpaces();
cid=1

- 测试获取不存在类型的空间 @0
- 测试获取我的空间数据第0条的type属性 @mine
- 测试获取自定义空间数据第0条的type属性 @custom
- 测试获取产品空间数据第0条的type属性 @product
- 测试获取项目空间数据第0条的type属性 @project
- 测试获取执行空间数据第0条的name属性 @迭代5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

$docLibData = zenData('doclib')->loadYaml('doclib');
$docLibData->type->range('mine,custom,product,project,execution');
$docLibData->parent->range('0');
$docLibData->gen(10);

zenData('product')->loadYaml('product')->gen(10);
zenData('project')->loadYaml('execution')->gen(10);
zenData('user')->gen(5);
su('admin');

$typeList    = array('all', 'mine', 'custom', 'product', 'project', 'execution');
$spaceIdList = array(0, 1, 2, 3, 4, 101);
