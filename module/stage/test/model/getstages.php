#!/usr/bin/env php
<?php
/**

title=测试 stageModel->getStages();
cid=1

- 获取敏捷模型下按照id倒序排列的所有阶段 @0
- 获取瀑布模型下按照id正序排列的所有阶段 @0
- 获取敏捷项目下按照id倒序排列的所有阶段
 - 第101条的type属性 @sprint
 - 第101条的name属性 @迭代5
- 获取敏捷项目下按照id正序排列的所有阶段
 - 第105条的type属性 @sprint
 - 第105条的name属性 @迭代9
- 获取瀑布项目下按照id倒序排列的所有阶段
 - 第106条的type属性 @stage
 - 第106条的name属性 @阶段10
- 获取瀑布项目下按照id正序排列的所有阶段
 - 第106条的type属性 @stage
 - 第106条的name属性 @阶段10
- 获取看板项目下按照id倒序排列的所有阶段 @0
- 获取看板项目下按照id正序排列的所有阶段 @0
- 获取敏捷模型下按照id倒序排列的所有阶段 @0
- 获取瀑布模型下按照id正序排列的所有阶段 @0
- 获取瀑布模型下按照id倒序排列的所有阶段
 - 第1条的projectType属性 @waterfall
 - 第1条的type属性 @request
 - 第1条的name属性 @需求1
- 获取瀑布模型下按照id正序排列的所有阶段
 - 第1条的projectType属性 @waterfall
 - 第1条的type属性 @request
 - 第1条的name属性 @需求1
- 获取融合瀑布模型下按照id倒序排列的所有阶段
 - 第7条的projectType属性 @waterfallplus
 - 第7条的type属性 @request
 - 第7条的name属性 @需求2
- 获取融合瀑布模型下按照id正序排列的所有阶段
 - 第7条的projectType属性 @waterfallplus
 - 第7条的type属性 @request
 - 第7条的name属性 @需求2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stage.class.php';

zdTable('user')->gen(5);
zdTable('project')->config('project')->gen(10);
zdTable('stage')->config('stage')->gen(12);

$sorts      = array('id_desc', 'id_asc');
$projectIds = array(0, 11, 60, 100);
$types      = array('scrum', 'waterfall', 'waterfallplus');

$stageTester = new stageTest();
r($stageTester->getStagesTest($sorts[0], $projectIds[0], $types[0])) && p()                && e('0');                                     // 获取敏捷模型下按照id倒序排列的所有阶段
r($stageTester->getStagesTest($sorts[1], $projectIds[0], $types[0])) && p()                && e('0');                                     // 获取瀑布模型下按照id正序排列的所有阶段
r($stageTester->getStagesTest($sorts[0], $projectIds[1], $types[0])) && p('101:type,name') && e('sprint,迭代5');                          // 获取敏捷项目下按照id倒序排列的所有阶段
r($stageTester->getStagesTest($sorts[1], $projectIds[1], $types[0])) && p('105:type,name') && e('sprint,迭代9');                          // 获取敏捷项目下按照id正序排列的所有阶段
r($stageTester->getStagesTest($sorts[0], $projectIds[2], $types[0])) && p('106:type,name') && e('stage,阶段10');                          // 获取瀑布项目下按照id倒序排列的所有阶段
r($stageTester->getStagesTest($sorts[1], $projectIds[2], $types[0])) && p('106:type,name') && e('stage,阶段10');                          // 获取瀑布项目下按照id正序排列的所有阶段
r($stageTester->getStagesTest($sorts[0], $projectIds[3], $types[0])) && p()                && e('0');                                     // 获取看板项目下按照id倒序排列的所有阶段
r($stageTester->getStagesTest($sorts[1], $projectIds[3], $types[0])) && p()                && e('0');                                     // 获取看板项目下按照id正序排列的所有阶段
r($stageTester->getStagesTest($sorts[0], $projectIds[0], $types[0])) && p()                && e('0');                                     // 获取敏捷模型下按照id倒序排列的所有阶段
r($stageTester->getStagesTest($sorts[1], $projectIds[0], $types[0])) && p()                && e('0');                                     // 获取瀑布模型下按照id正序排列的所有阶段
r($stageTester->getStagesTest($sorts[0], $projectIds[0], $types[1])) && p('1:projectType,type,name') && e('waterfall,request,需求1');     // 获取瀑布模型下按照id倒序排列的所有阶段
r($stageTester->getStagesTest($sorts[1], $projectIds[0], $types[1])) && p('1:projectType,type,name') && e('waterfall,request,需求1');     // 获取瀑布模型下按照id正序排列的所有阶段
r($stageTester->getStagesTest($sorts[0], $projectIds[0], $types[2])) && p('7:projectType,type,name') && e('waterfallplus,request,需求2'); // 获取融合瀑布模型下按照id倒序排列的所有阶段
r($stageTester->getStagesTest($sorts[1], $projectIds[0], $types[2])) && p('7:projectType,type,name') && e('waterfallplus,request,需求2'); // 获取融合瀑布模型下按照id正序排列的所有阶段
