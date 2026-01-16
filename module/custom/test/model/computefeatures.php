#!/usr/bin/env php
<?php

/**

title=测试 customModel->computeFeatures();
timeout=0
cid=0

- 计算开源版不启用的功能
 -  @program
 - 属性1 @productLine
- 计算开源版敏捷项目启用的功能 @0
- 计算开源版敏捷项目不启用的功能 @0
- 计算ipd版不启用的功能
 -  @program
 - 属性1 @productLine
- 计算ipd版敏捷项目启用的功能
 -  @问题
 - 属性1 @机会
- 计算ipd版敏捷项目不启用的功能
 -  @风险
 - 属性1 @QA
- 计算旗舰版不启用的功能
 -  @program
 - 属性1 @productLine
- 计算旗舰版敏捷项目启用的功能
 -  @问题
 - 属性1 @机会
- 计算旗舰版敏捷项目不启用的功能
 -  @风险
 - 属性1 @QA
- 计算无相关数据时，开源版不启用的功能
 -  @program
 - 属性1 @productLine
 - 属性2 @productER
 - 属性3 @productUR
 - 属性4 @projectWaterfall
- 计算无相关数据时，开源版敏捷项目启用的功能 @0
- 计算无相关数据时，开源版敏捷项目不启用的功能 @0
- 计算无相关数据时，ipd版不启用的功能
 -  @program
 - 属性1 @productLine
 - 属性2 @productER
 - 属性3 @productUR
 - 属性4 @projectWaterfall
- 计算无相关数据时，ipd版敏捷项目启用的功能 @0
- 计算无相关数据时，ipd版敏捷项目不启用的功能
 -  @问题
 - 属性1 @风险
- 计算无相关数据时，旗舰版不启用的功能
 -  @program
 - 属性1 @productLine
 - 属性2 @productER
 - 属性3 @productUR
 - 属性4 @projectWaterfall
- 计算无相关数据时，旗舰版敏捷项目启用的功能 @0
- 计算无相关数据时，旗舰版敏捷项目不启用的功能
 -  @问题
 - 属性1 @风险

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
su('admin');

$projectTable = zenData('project');
$projectTable->id->range('1-15');
$projectTable->model->range('scrum{5},waterfall{5},waterfallplus{5}');
$projectTable->deleted->range('0');
$projectTable->gen(15);

$storyTable = zenData('story');
$storyTable->type->range('requirement');
$storyTable->deleted->range('0');
$storyTable->gen(5);

$issueTable = zenData('issue');
$issueTable->deleted->range('0');
$issueTable->project->range('1-5');
$issueTable->gen(5);

$meetingTable = zenData('meeting');
$meetingTable->deleted->range('0');
$meetingTable->project->range('1-5');
$meetingTable->gen(5);

$opportunityTable = zenData('opportunity');
$opportunityTable->execution->range('1-5');
$opportunityTable->deleted->range('0');
$opportunityTable->gen(5);

$programactivityTable = zenData('programactivity');
$programactivityTable->execution->range('1-5');
$programactivityTable->deleted->range('0');
$programactivityTable->gen(5);

$assetlibTable = zenData('assetlib');
$assetlibTable->deleted->range('0');
$assetlibTable->gen(5);

$editionList = array('open', 'ipd', 'max');

$customTester = new customModelTest();
$openFeatures = $customTester->computeFeaturesTest($editionList[0]);
$ipdFeatures  = $customTester->computeFeaturesTest($editionList[1]);
$maxFeatures  = $customTester->computeFeaturesTest($editionList[2]);

r($openFeatures[0]) && p('0,1') && e('program,productLine'); // 计算开源版不启用的功能
r($openFeatures[1]) && p()      && e('0');                   // 计算开源版敏捷项目启用的功能
r($openFeatures[2]) && p()      && e('0');                   // 计算开源版敏捷项目不启用的功能
r($ipdFeatures[0])  && p('0,1') && e('program,productLine'); // 计算ipd版不启用的功能
r($ipdFeatures[1])  && p('0,1') && e('问题,机会');           // 计算ipd版敏捷项目启用的功能
r($ipdFeatures[2])  && p('0,1') && e('风险,QA');             // 计算ipd版敏捷项目不启用的功能
r($maxFeatures[0])  && p('0,1') && e('program,productLine'); // 计算旗舰版不启用的功能
r($maxFeatures[1])  && p('0,1') && e('问题,机会');           // 计算旗舰版敏捷项目启用的功能
r($maxFeatures[2])  && p('0,1') && e('风险,QA');             // 计算旗舰版敏捷项目不启用的功能

zenData('project')->gen(0);
zenData('story')->gen(0);
zenData('issue')->gen(0);
zenData('meeting')->gen(0);
zenData('opportunity')->gen(0);
zenData('programactivity')->gen(0);
zenData('assetlib')->gen(0);

$openFeatures = $customTester->computeFeaturesTest($editionList[0]);
$ipdFeatures  = $customTester->computeFeaturesTest($editionList[1]);
$maxFeatures  = $customTester->computeFeaturesTest($editionList[2]);

r($openFeatures[0]) && p('0,1,2,3,4') && e('program,productLine,productER,productUR,projectWaterfall'); // 计算无相关数据时，开源版不启用的功能
r($openFeatures[1]) && p()            && e('0');                                                        // 计算无相关数据时，开源版敏捷项目启用的功能
r($openFeatures[2]) && p()            && e('0');                                                        // 计算无相关数据时，开源版敏捷项目不启用的功能
r($ipdFeatures[0])  && p('0,1,2,3,4') && e('program,productLine,productER,productUR,projectWaterfall'); // 计算无相关数据时，ipd版不启用的功能
r($ipdFeatures[1])  && p()            && e('0');                                                        // 计算无相关数据时，ipd版敏捷项目启用的功能
r($ipdFeatures[2])  && p('0,1')       && e('问题,风险');                                                // 计算无相关数据时，ipd版敏捷项目不启用的功能
r($maxFeatures[0])  && p('0,1,2,3,4') && e('program,productLine,productER,productUR,projectWaterfall'); // 计算无相关数据时，旗舰版不启用的功能
r($maxFeatures[1])  && p()            && e('0');                                                        // 计算无相关数据时，旗舰版敏捷项目启用的功能
r($maxFeatures[2])  && p('0,1')       && e('问题,风险');                                                // 计算无相关数据时，旗舰版敏捷项目不启用的功能