#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=测试productModel->getLatestProject();
cid=1
pid=1

测试产品25关联的最后一个未关闭的项目,按begin字段排序 >> 315
测试产品38关联的最后一个未关闭的项目,按begin字段排序 >> 阶段588
传入不存在的产品 >> 没有数据

*/

$project = new Product('admin');

#var_dump($z->testGetLatestProject(38));die;
r($project->testGetLatestProject(25))  && p('id')   && e('315');       // 测试产品25关联的最后一个未关闭的项目,按begin字段排序
r($project->testGetLatestProject(38))  && p('name') && e('阶段588');   // 测试产品38关联的最后一个未关闭的项目,按begin字段排序
r($project->testGetLatestProject(101)) && p()       && e('没有数据');  // 传入不存在的产品