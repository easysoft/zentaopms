#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

$result = zenData('projectproduct')->gen(0);

$result = zenData('project');
$result->charter->range('1-30');
$result->gen(40);

$result = zenData('roadmap');
$result->product->range('1-10');
$result->branch->range('0');
$result->gen(10);

$result = zenData('charter');
$result->roadmap->range('1-10');
$result->gen(30);

/**

title=upgradeModel->processProjectRoadmapsByCharter();
cid=1
pid=1

*/

global $tester;
$upgrade = $tester->loadModel('upgrade');
$upgrade->processProjectRoadmapsByCharter();
$linkProducts = $upgrade->dao->select('*')->from(TABLE_PROJECTPRODUCT)->fetchAll('project');
r($linkProducts) && p('1:project|product|branch|plan|roadmap', '|')  && e('1 |1|0|~~|,1,');   // 获取project为1的项目产品关系数据。
r($linkProducts) && p('11:project|product|branch|plan|roadmap', '|') && e('11|1|0|~~|,1,');   // 获取project为11的项目产品关系数据。
r($linkProducts) && p('22:project|product|branch|plan|roadmap', '|') && e('22|2|0|~~|,2,');   // 获取project为22的项目产品关系数据。
r($linkProducts) && p('33:project|product|branch|plan|roadmap', '|') && e('33|3|0|~~|,3,');   // 获取project为33的项目产品关系数据。
r($linkProducts) && p('40:project|product|branch|plan|roadmap', '|') && e('40|10|0|~~|,10,'); // 获取project为49的项目产品关系数据。
