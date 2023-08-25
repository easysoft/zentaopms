#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('project')->config('project')->gen(50);

$projectproduct = zdTable('projectproduct');
$projectproduct->product->range('1-50');
$projectproduct->project->range('1-50');
$projectproduct->gen(50);

/**

title=测试 projectModel->getNoProductList();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('project');

r(count($tester->project->getNoProductList())) && p()               && e('25');                // 查询所有没有产品的项目数量
r($tester->project->getNoProductList())        && p('17:name,code') && e('瀑布项目17,code17'); // 查询没有产品的ID为17的项目详情
