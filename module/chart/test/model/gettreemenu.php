#!/usr/bin/env php
<?php
/**

title=测试 chartModel::getTreeMenu();
timeout=0
cid=1

- 测试ID为0时是否返回空 @0
- 测试传入子分组时是否返回空 @0
- 测试获取的分组为空 @0
- 测试产品分组下的图表树
 - 第1条的id属性 @36_1102
 - 第1条的parent属性 @36
 - 第1条的name属性 @年度排行-产品-完成需求规模榜

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('module')->config('module')->gen(27);
zdTable('user')->gen(5);
su('admin');

global $tester;
$chart = $tester->loadModel('chart');

r($chart->getTreeMenu(0))  && p() && e('0'); //测试ID为0时是否返回空
r($chart->getTreeMenu(36)) && p() && e('0'); //测试传入子分组时是否返回空
r($chart->getTreeMenu(30)) && p() && e('0'); //测试获取的分组为空

r($chart->getTreeMenu(32)) && p("1:id,parent,name") && e("36_1102,36,年度排行-产品-完成需求规模榜"); //测试产品分组下的图表树
