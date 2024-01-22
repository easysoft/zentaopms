#!/usr/bin/env php
<?php

/**

title=getCalcInstanceList
timeout=0
cid=1

- 测试count_of_task_in_project的数据源和编号
 - 第count_of_task_in_project条的dataset属性 @getTasks
 - 第count_of_task_in_project条的id属性 @213
- 测试count_of_unclosed_story_in_project的数据源和编号
 - 第count_of_unclosed_story_in_project条的dataset属性 @getDevStoriesWithProject
 - 第count_of_unclosed_story_in_project条的id属性 @205
- 测试count_of_user_in_project的数据源和编号
 - 第count_of_user_in_project条的dataset属性 @~~
 - 第count_of_user_in_project条的id属性 @228
- 测试count_of_valid_story_in_project的数据源和编号
 - 第count_of_valid_story_in_project条的dataset属性 @getDevStoriesWithProject
 - 第count_of_valid_story_in_project条的id属性 @208
- 测试consume_of_all_in_project的数据源和编号
 - 第consume_of_all_in_project条的dataset属性 @~~
 - 第consume_of_all_in_project条的id属性 @229
- 测试ac_of_all_in_waterfall的数据源和编号
 - 第ac_of_all_in_waterfall条的dataset属性 @~~
 - 第ac_of_all_in_waterfall条的id属性 @231
- 测试count_of_pending_story_in_user的数据源和编号
 - 第count_of_pending_story_in_user条的dataset属性 @getDevStories
 - 第count_of_pending_story_in_user条的id属性 @251

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

$metric = new metricTest();

r($metric->getCalcInstanceList()) && p('count_of_task_in_project:dataset,id')           && e('getTasks,213');                 // 测试count_of_task_in_project的数据源和编号
r($metric->getCalcInstanceList()) && p('count_of_unclosed_story_in_project:dataset,id') && e('getDevStoriesWithProject,205'); // 测试count_of_unclosed_story_in_project的数据源和编号
r($metric->getCalcInstanceList()) && p('count_of_user_in_project:dataset,id')           && e('~~,228');                       // 测试count_of_user_in_project的数据源和编号
r($metric->getCalcInstanceList()) && p('count_of_valid_story_in_project:dataset,id')    && e('getDevStoriesWithProject,208'); // 测试count_of_valid_story_in_project的数据源和编号
r($metric->getCalcInstanceList()) && p('consume_of_all_in_project:dataset,id')          && e('~~,229');                       // 测试consume_of_all_in_project的数据源和编号
r($metric->getCalcInstanceList()) && p('ac_of_all_in_waterfall:dataset,id')             && e('~~,231');                       // 测试ac_of_all_in_waterfall的数据源和编号
r($metric->getCalcInstanceList()) && p('count_of_pending_story_in_user:dataset,id')     && e('getDevStories,251');            // 测试count_of_pending_story_in_user的数据源和编号