#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/custom.class.php';
su('admin');
zdTable('user')->gen(5);
su('admin');

$project = zdTable('project');
$project->id->range('1-3');
$project->name->range('项目集1,融合瀑布项目1,瀑布项目1');
$project->type->range('program,project{2}');
$project->parent->range('0,1{2}');
$project->status->range('wait');
$project->model->range('[],waterfallplus,waterfall');
$project->openedBy->range('admin,user1');
$project->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$project->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$project->gen(3);

/**

title=测试projectModel->hasWaterfallplusData();
cid=1
pid=1

检查获取所有未删除的融合瀑布项目的数量 >> 1
检查删除所有融合瀑布模型项目后，获取融合瀑布项目的数量 >> 0

*/

$customTester = new customTest();
r($customTester->hasWaterfallplusDataTest())                && p() && e('1'); // 检查获取所有未删除的融合瀑布项目的数量
r($customTester->hasWaterfallplusDataTest('deleteproject')) && p() && e('0'); // 检查删除所有融合瀑布模型项目后，获取融合瀑布项目的数量
