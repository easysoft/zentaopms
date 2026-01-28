#!/usr/bin/env php
<?php

/**

title=测试 customModel->processMeasrecordCron();
timeout=0
cid=15921

- 检查没有瀑布项目时，定时任务的状态 @normal
- 检查没有融合项目时，定时任务的状态 @normal
- 检查不启用敏捷模型时，定时任务的状态 @normal
- 检查不启用融合敏捷模型时，定时任务的状态 @normal
- 检查不启用瀑布模型时，定时任务的状态 @normal
- 检查不启用融合瀑布模型时，定时任务的状态 @normal
- 检查不启用任何项目模型功能时，定时任务的状态 @stop

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
zenData('cron')->loadYaml('cron')->gen(1);
su('admin');

$disabledFeatures[0] = 'waterfall';
$disabledFeatures[1] = 'waterfallplus';
$disabledFeatures[2] = 'scrumMeasrecord';
$disabledFeatures[3] = 'agileMeasrecord';
$disabledFeatures[4] = 'waterfallMeasrecord';
$disabledFeatures[5] = 'waterfallplusMeasrecord';
$disabledFeatures[6] = 'waterfall,waterfallplus,projectMeasrecord';

$customTester = new customModelTest();
r($customTester->processMeasrecordCronTest($disabledFeatures[0])) && p() && e('normal'); // 检查没有瀑布项目时，定时任务的状态
r($customTester->processMeasrecordCronTest($disabledFeatures[1])) && p() && e('normal'); // 检查没有融合项目时，定时任务的状态
r($customTester->processMeasrecordCronTest($disabledFeatures[2])) && p() && e('normal'); // 检查不启用敏捷模型时，定时任务的状态
r($customTester->processMeasrecordCronTest($disabledFeatures[3])) && p() && e('normal'); // 检查不启用融合敏捷模型时，定时任务的状态
r($customTester->processMeasrecordCronTest($disabledFeatures[4])) && p() && e('normal'); // 检查不启用瀑布模型时，定时任务的状态
r($customTester->processMeasrecordCronTest($disabledFeatures[5])) && p() && e('normal'); // 检查不启用融合瀑布模型时，定时任务的状态
r($customTester->processMeasrecordCronTest($disabledFeatures[6])) && p() && e('stop');   // 检查不启用任何项目模型功能时，定时任务的状态