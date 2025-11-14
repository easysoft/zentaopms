#!/usr/bin/env php
<?php

/**

title=getCalcInstanceList
timeout=0
cid=17080

- 测试count_of_task_in_project的数据源和编号
 - 第count_of_task_in_project条的dataset属性 @getTasks
 - 第count_of_task_in_project条的id属性 @222
- 测试count_of_unclosed_story_in_project的数据源和编号
 - 第count_of_unclosed_story_in_project条的dataset属性 @getAllStoriesWithProject
 - 第count_of_unclosed_story_in_project条的id属性 @214
- 测试count_of_user_in_project的数据源和编号
 - 第count_of_user_in_project条的dataset属性 @getTeamMembers
 - 第count_of_user_in_project条的id属性 @237
- 测试count_of_valid_story_in_project的数据源和编号
 - 第count_of_valid_story_in_project条的dataset属性 @getDevStoriesWithProject
 - 第count_of_valid_story_in_project条的id属性 @217
- 测试consume_of_all_in_project的数据源和编号
 - 第consume_of_all_in_project条的dataset属性 @getProjectEfforts
 - 第consume_of_all_in_project条的id属性 @238
- 测试ac_of_all_in_waterfall的数据源和编号
 - 第ac_of_weekly_all_in_waterfall条的dataset属性 @getWaterfallEfforts
 - 第ac_of_weekly_all_in_waterfall条的id属性 @284
- 测试count_of_pending_story_in_user的数据源和编号
 - 第count_of_pending_story_in_user条的dataset属性 @getAllDevStoriesWithParent
 - 第count_of_pending_story_in_user条的id属性 @260

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';
su('admin');

$metric = new metricTest();

r($metric->getCalcInstanceList()) && p('count_of_task_in_project:dataset,id')           && e('getTasks,222');                   // 测试count_of_task_in_project的数据源和编号
r($metric->getCalcInstanceList()) && p('count_of_unclosed_story_in_project:dataset,id') && e('getAllStoriesWithProject,214');   // 测试count_of_unclosed_story_in_project的数据源和编号
r($metric->getCalcInstanceList()) && p('count_of_user_in_project:dataset,id')           && e('getTeamMembers,237');             // 测试count_of_user_in_project的数据源和编号
r($metric->getCalcInstanceList()) && p('count_of_valid_story_in_project:dataset,id')    && e('getDevStoriesWithProject,217');   // 测试count_of_valid_story_in_project的数据源和编号
r($metric->getCalcInstanceList()) && p('consume_of_all_in_project:dataset,id')          && e('getProjectEfforts,238');          // 测试consume_of_all_in_project的数据源和编号
r($metric->getCalcInstanceList()) && p('ac_of_weekly_all_in_waterfall:dataset,id')      && e('getWaterfallEfforts,284');        // 测试ac_of_all_in_waterfall的数据源和编号
r($metric->getCalcInstanceList()) && p('count_of_pending_story_in_user:dataset,id')     && e('getAllDevStoriesWithParent,260'); // 测试count_of_pending_story_in_user的数据源和编号
