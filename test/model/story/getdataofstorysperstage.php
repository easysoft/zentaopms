#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getDataOfStorysPerStage();
cid=1
pid=1

按照需求阶段分组，获取分组后的需求数量 >> 11
按照需求阶段分组，获取各个需求阶段的需求数量，查看wait下的数据 >> 未开始,25
按照需求阶段分组，获取各个需求阶段的需求数量，查看planned下的数据 >> 已计划,25
按照需求阶段分组，获取各个需求阶段的需求数量，查看released下的数据 >> 已发布,25

*/

global $tester;
$tester->loadModel('story');
$tester->loadModel('report');

$data = $tester->story->getDataOfStorysPerStage();

r(count($data)) && p()                      && e('11');        // 按照需求阶段分组，获取分组后的需求数量
r($data)        && p('wait:name,value')     && e('未开始,25'); // 按照需求阶段分组，获取各个需求阶段的需求数量，查看wait下的数据
r($data)        && p('planned:name,value')  && e('已计划,25'); // 按照需求阶段分组，获取各个需求阶段的需求数量，查看planned下的数据
r($data)        && p('released:name,value') && e('已发布,25'); // 按照需求阶段分组，获取各个需求阶段的需求数量，查看released下的数据